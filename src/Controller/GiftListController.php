<?php

namespace App\Controller;

use App\Entity\GiftList;
use App\Entity\User;
use App\Form\GiftListType;
use App\Repository\GiftListRepository;
use App\Repository\UserRepository;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secur;


#[Route('/gift/list')]
class GiftListController extends AbstractController
{
    public function __construct( private Security $security)
    {
    }

    #[Route('/', name: 'app_gift_list_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserGiftLists(GiftListRepository $giftListRepository, UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $userGiftLists =  $user->getGiftLists();
        //dd($userGiftLists::class);

        return $this->render('gift_list/index.html.twig', [
            'gift_lists' => $userGiftLists,
        ]);
    }

    #[Route('/new', name: 'app_gift_list_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $giftList = new GiftList();
        $form = $this->createForm(GiftListType::class, $giftList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();
            if ($photo) {
//                $directory =  $this->getParameter('giftlists_directory');
//                $newFilename = $uploaderService->uploadFile($photo, $directory);
                $newFilename = $uploaderService->uploadFile($photo, "giftlists");

                $giftList->setImage($newFilename);
            }
            /** @var User $user */
            $user = $this->getUser();
            $user->addGiftList($giftList);
            $entityManager->persist($giftList);
            $entityManager->flush();

            $this->addFlash('success', "Gift list successfully created" );
            return $this->redirectToRoute('app_gift_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gift_list/new.html.twig', [
            'gift_list' => $giftList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gift_list_show', methods: ['GET'])]
    #[Secur("is_granted('ROLE_USER') and user == giftList.getUser()")]
//    #[IsGranted('ROLE_USER')]
    public function show(GiftList $giftList): Response
    {
//        /** @var User $user */
//        $user = $this->security->getUser();
//        $creator = $giftList->getUser();
//
//        if ($user !== $creator) {
//            return $this->redirectToRoute('app_forbidden');
//        }

        return $this->render('gift_list/show.html.twig', [
            'gift_list' => $giftList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gift_list_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, GiftList $giftList, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $form = $this->createForm(GiftListType::class, $giftList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();
            if ($photo) {

                $newFilename = $uploaderService->uploadFile($photo, "giftlists");

                $giftList->setImage($newFilename);
            }
            $entityManager->flush();

            $this->addFlash('success', "Gift list successfully updated" );
            return $this->redirectToRoute('app_gift_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gift_list/edit.html.twig', [
            'gift_list' => $giftList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gift_list_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, GiftList $giftList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$giftList->getId(), $request->request->get('_token'))) {
            $entityManager->remove($giftList);
            $entityManager->flush();
        }

        $this->addFlash('success', "Gift list successfully deleted" );
        return $this->redirectToRoute('app_gift_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
