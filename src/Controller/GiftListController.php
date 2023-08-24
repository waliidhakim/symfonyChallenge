<?php

namespace App\Controller;

use App\Entity\GiftList;
use App\Form\GiftListType;
use App\Repository\GiftListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gift/list')]
class GiftListController extends AbstractController
{
    #[Route('/', name: 'app_gift_list_index', methods: ['GET'])]
    public function index(GiftListRepository $giftListRepository): Response
    {
        return $this->render('gift_list/index.html.twig', [
            'gift_lists' => $giftListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gift_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $giftList = new GiftList();
        $form = $this->createForm(GiftListType::class, $giftList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($giftList);
            $entityManager->flush();

            return $this->redirectToRoute('app_gift_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gift_list/new.html.twig', [
            'gift_list' => $giftList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gift_list_show', methods: ['GET'])]
    public function show(GiftList $giftList): Response
    {
        return $this->render('gift_list/show.html.twig', [
            'gift_list' => $giftList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gift_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GiftList $giftList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GiftListType::class, $giftList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gift_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gift_list/edit.html.twig', [
            'gift_list' => $giftList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gift_list_delete', methods: ['POST'])]
    public function delete(Request $request, GiftList $giftList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$giftList->getId(), $request->request->get('_token'))) {
            $entityManager->remove($giftList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gift_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
