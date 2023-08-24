<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonneEvent;
use App\Form\PersonneType;
//use App\Services\MailerService;
use App\Services\MailService;
use App\Services\PdfService;
//use App\Services\SendinblueMailer;
use App\Services\UploaderService;
use Doctrine\Persistence\Event\ManagerEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;





#[Route('/personne')]
#[IsGranted('ROLE_USER')]
class PersonneController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $dispatcher){

    }

    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig', ['personnes' => $personnes]);
    }

    #[Route('/pdf/{id}', name: 'personne.pdf')]
    public function generatePdfPersonne($id, ManagerRegistry $doctrine, PdfService $pdf )
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);

        $html = $this->render('personne/detail.html.twig', [
            'personne' => $personne
        ]);

        $pdf->showPdfFile($html);

    }

    #[Route('/alls/{page?1}/{nbre?12}', name: 'personne.list.alls')]
    #[IsGranted('ROLE_USER')]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        $nbPage = ceil($nbPersonne / $nbre);

        $personnes = $repository->findBy([], [], $nbre, ($page-1)*$nbre);
        $listAllPersonneEvent = new ListAllPersonneEvent(count($personnes));
        $this->dispatcher->dispatch($listAllPersonneEvent, ListAllPersonneEvent::LIST_ALL_PERSONNE_EVENT);


        return $this->render('personne/index.html.twig',
            [
                'personnes' => $personnes,
                'isPaginated' => true,
                'nbPersonne' => $nbPersonne,
                'nbPage' => $nbPage,
                'page' => $page,
                'nbre' => $nbre

            ]);
    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);

        if(!$personne)
        {
            //dd($personne);
            $this->addFlash('error', "La personne d'id $id n'existe pas");
            return $this->redirectToRoute('personne.list');
        }

        return $this->render('personne/detail.html.twig', ['personne' => $personne]);
    }

    #[Route('/delete/{id<\d+>}', name: 'personne.delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function deletePersonne(ManagerRegistry $doctrine, $id): RedirectResponse
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);

        if($personne)
        {
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            //dd($personne);
            $this->addFlash('success', "La personne a été supprimé avec succès");
        }
        else
        {
            //dd($personne);
            $this->addFlash('error', "La personne n'existe pas");
        }

        return $this->redirectToRoute('personne.list.alls');
    }

    #[Route('/update/{id<\d+>}/{name}/{firstname}/{age}', name: 'personne.update')]
    public function updatePersonne(ManagerRegistry $doctrine, $id, $name,$firstname,$age): RedirectResponse
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);
        if($personne) {
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);

            $doctrine->getManager()->persist($personne);
            $doctrine->getManager()->flush();
            $this->addFlash('success', "La personne a été mise à jour avec succès");
        }
        else {
            $this->addFlash('error', "La personne n'existe pas");
        }

        return $this->redirectToRoute('personne.list.alls');
    }

    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function addPersonne(
        ManagerRegistry $doctrine,
        Request $request,
        $id,
        SluggerInterface $slugger,
        UploaderService $uploaderService,
        MailService $mailer
        /*MailerService $mailer*/
        /*SendinblueMailer $mailer*/
    ): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);

        $new = false;
        if(!$personne)
        {
            $new = true;
            $personne = new Personne();
        }
//        dd($personne);
        $form = $this->createForm(PersonneType::class,$personne );
        $form->remove('createdAt');
        $form->remove('updatedAt');

        $form->handleRequest($request);

//        dd($this->getUser());
        if($form->isSubmitted() && $form->isValid() ) {
            //dd($personne);
            $manager = $doctrine->getManager();


            //
            $photo = $form->get('photo')->getData();



            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $directory =  $this->getParameter('personnes_directory');
                $newFilename = $uploaderService->uploadFile($photo, $directory);

                $personne->setImage($newFilename);
                //

                if ($new) {
                    $message = " a été ajouté avec succès";

                    $personne->setCreatedBy($request->getUser());
                } else {
                    $message = " a été mis à jour avec succès";
                }
                $manager->persist($personne);
                $manager->flush();

                if($new)
                {
                    $addPersonneEvent = new AddPersonneEvent($personne);
                    $this->dispatcher->dispatch($addPersonneEvent, AddPersonneEvent::ADD_PERSONNE_EVENT);
                }

                $this->addFlash('success', $personne->getName() . $message);
                //$mailer->sendEmail($this->renderView('email.html.twig', ['subject' => 'Some Subject', 'name' => $personne->getName()]));

                $email = (new TemplatedEmail())
                    ->from('idirwalidhakim31@gmail.com')
                    ->to('idirwalidhakim32@gmail.com')
                    ->subject('Subject')
                    ->htmlTemplate('email/sample_email.html.twig')
                    ->context(['name' => 'John Doe']);

                $mailer->sendEmail($email);

                return $this->redirectToRoute('personne.list');
            }

        }


        return $this->render('personne/add-personne.html.twig', [
            'form' => $form->createView(),
        ]);


    }


    #[Route('/alls/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function personnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonneByAgeInterval($ageMin, $ageMax);




        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }


    #[Route('/stats/age/{ageMin}/{ageMax}', name: 'personne.list.age.stats')]
    public function statsPersonnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonneByAgeInterval($ageMin, $ageMax);


        return $this->render('personne/stats.html.twig', [
            'stats' => $stats[0],
            'ageMin' => $ageMin,
            'ageMax' => $ageMax,
        ]);
    }
}
