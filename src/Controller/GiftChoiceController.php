<?php
namespace App\Controller;

use App\Entity\Gift;
use App\Repository\GiftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\GiftListRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GiftChoiceController extends AbstractController {

//    /**
//     * @Route("/gift-choice/{listGiftId}", name="gift_choice", methods={"GET", "POST"})
//     */

    #[Route('/gift-choice/{listGiftId}', name: 'gift_choice', methods: ['GET', 'POST'])]
    public function giftChoice(int $listGiftId, Request $request, GiftListRepository $giftListRepo,  MailerInterface $mailer, EntityManagerInterface $manager) {

        $giftList = $giftListRepo->find($listGiftId);
        if (!$giftList) {
            $this->addFlash('error', 'No gift list was found.');
            return $this->redirectToRoute('gift_list_not_found');
        }

        $session = $request->getSession();

        // Check if password is required and not yet verified
        if ($giftList->isStatus() == "private" && !$session->get('password_verified')) {
            $passwordForm = $this->createFormBuilder()
                ->add('password', PasswordType::class)
                ->add('submit', SubmitType::class, ['label' => 'Validate'])
                ->getForm();

            $passwordForm->handleRequest($request);

            if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
                $data = $passwordForm->getData();

                if ($data['password'] == $giftList->getPassword()) {
                    $session->set('password_verified', true);
                } else {
                    $this->addFlash('error', 'Incorrect password.');
                    return $this->render('gift_choice/password.html.twig', [
                        'passwordForm' => $passwordForm->createView(),
                        'giftList' => $giftList
                    ]);
                }
            } else {
                return $this->render('gift_choice/password.html.twig', [
                    'passwordForm' => $passwordForm->createView(),
                    'giftList' => $giftList
                ]);
            }
        }

        $gifts = $giftList->getGifts();
        $gifts = $gifts->filter(function($gift) {
            return !$gift->isIsChosen();
        });

        $defaultData = ['gift' => null, 'email' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add('gift', ChoiceType::class, [
                'choices' => $gifts,
                'choice_label' => function(Gift $gift) {
                    $label = '';

                    if (is_null($gift->getImage()) || $gift->getImage() === '') {
                        $label = sprintf(
                            '<i class="fas fa-gift"></i> %s - %s$ <a href="%s">Link</a>',
                            $gift->getName(),
                            $gift->getPrice(),
                            $gift->getPurchaseLink()
                        );
                    } elseif (0 === strpos($gift->getImage(), 'http')) {
                        $label = sprintf(
                            '<img src="%s" alt="Gift Image" class="profile-image"> %s - %s$ <a href="%s">Link</a>',
                            $gift->getImage(),
                            $gift->getName(),
                            $gift->getPrice(),
                            $gift->getPurchaseLink()
                        );
                    } else {
                        $label = sprintf(
                            '<img src="/uploads/gifts/%s" alt="Gift Image" class="profile-image"> %s - %s$ <a href="%s">Link</a>',
                            $gift->getImage(),
                            $gift->getName(),
                            $gift->getPrice(),
                            $gift->getPurchaseLink()
                        );
                    }

                    return $label;},
                'expanded' => true,
                'multiple' => false,
                'label_html' => true,
            ])
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $gift = $data['gift'];
            $email = $data['email'];

            dump($gift);

            $gift->setIsChosen(true);
            $gift->setChosenByEmail($email); // $email étant l'adresse e-mail fournie par l'utilisateur.

            $manager->persist($gift);
            $manager->flush();


            $message = (new TemplatedEmail())
                ->from(new Address('idirwalidhakim31@gmail.com', 'IDIR Walid Challenge Symfony6'))
                ->to($giftList->getUser()->getEmail())
                ->subject('Gift Chosen !')
                ->htmlTemplate('email/gift-chosen.html.twig')
                ->context(['gift' => $gift, 'mail' => $email ]);

            $mailer->send($message);


            $reminderMessage = (new TemplatedEmail())
                ->from(new Address('idirwalidhakim31@gmail.com', 'IDIR Walid Challenge Symfony6'))
                ->to($email)  // L'adresse e-mail de l'utilisateur qui a choisi le cadeau
                ->subject('Gift Reminder!')
                ->htmlTemplate('gift_choice/gift_reminder.html.twig')
                ->context(['gift' => $gift, 'cancelLink' => $this->generateUrl('cancel_gift_choice', ['giftId' => $gift->getId()], UrlGeneratorInterface::ABSOLUTE_URL)]);

            $mailer->send($reminderMessage);

            $session->set('password_verified', false);
            $this->addFlash('success', 'Your choise has been saved. Thank you for your participation .');
            return $this->redirectToRoute('thank_you', ['listGiftId' => $listGiftId]);
        }

        return $this->render('gift_choice/gift_choice.html.twig', [
            'form' => $form->createView(),
            'giftList' => $giftList
        ]);
    }



    #[Route('/thank-you', name: 'thank_you')]
    public function thankYou(): Response
    {
        return $this->render('thank_you.html.twig');
    }

    #[Route('/gift-list-not-found', name: 'gift_list_not_found')]
    public function giftListNotFound() {
        return $this->render('error/gift_list_not_found.html.twig');
    }

    #[Route('/gift-canceled', name: 'gift_canceled')]
    public function giftCancelReservation() {
        return $this->render('gift_choice/gift_cancel.html.twig');
    }

    #[Route('/error/gift-not-found', name: 'gift_error')]
    public function giftError(): Response
    {
        return $this->render('error/gift_error.html.twig');
    }


    #[Route('/cancel-gift-choice/{giftId}', name: 'cancel_gift_choice', methods: ['GET'])]
    public function cancelGiftChoice(int $giftId, GiftRepository $giftRepo, EntityManagerInterface $manager, MailerInterface $mailer): Response {
        $gift = $giftRepo->find($giftId);

        if (!$gift || !$gift->isIsChosen()) {
            $this->addFlash('error', 'The gift is either not found or not chosen by you.');
            return $this->redirectToRoute('gift_error');
        }

        $gift->setIsChosen(false);
        $chosenByEmail = $gift->getChosenByEmail();
        $gift->setChosenByEmail(null);

        //dd($gift);

        $manager->persist($gift);
        $manager->flush();

        // Envoyer un mail au créateur pour l'informer
        $infoMessage = (new TemplatedEmail())
            ->from(new Address('idirwalidhakim31@gmail.com', 'IDIR Walid Challenge Symfony6'))
            ->to($gift->getGiftList()->getUser()->getEmail())
            ->subject('Reservation Cancelled!')
            ->htmlTemplate('email/gift-reservation-canceled.html.twig')
            ->context(['gift' => $gift, 'userEmail' => $chosenByEmail]);

        $mailer->send($infoMessage);

        $this->addFlash('success', 'You have successfully cancelled your gift reservation.');
        return $this->redirectToRoute('gift_canceled');
    }
}
