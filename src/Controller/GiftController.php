<?php

namespace App\Controller;

use App\Entity\Gift;
use App\Entity\User;
use App\Form\GiftType;
use App\Form\GiftTypeExtended;
use App\Repository\GiftListRepository;
use App\Repository\GiftRepository;
use App\Services\ScrappingService;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/gifts')]

class GiftController extends AbstractController
{
    public function __construct( private Security $security)
    {
    }
    #[Route('/', name: 'app_gift_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(GiftRepository $giftRepository): Response
    {
        return $this->render('gift/index.html.twig', [
            'gifts' => $giftRepository->findAll(),
        ]);
    }

    #[Route('/new/{listGift_id?0}', name: 'app_gift_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        $listGift_id,
        GiftListRepository $giftListRepository,
        UploaderService $uploaderService,
        ScrappingService $scrappingService

    ) : Response
    {
        $giftList = $giftListRepository->findOneBy(['id' => $listGift_id]);

        $creatorId = $giftList->getUser()->getId();
        if ($this->security->getUser()->getId() !== $creatorId) {
            //dd("je ne suis pas le créator");
            return $this->redirectToRoute('app_forbidden');
        }

        //dd($giftList);
        $gift = new Gift();
        $form = $this->createForm(GiftType::class, $gift);
        $form->remove('name');
        $form->remove('price');
        $form->remove('image');
        $form->handleRequest($request);





        if ($form->isSubmitted() && $form->isValid()) {

            /*$photo = $form->get('image')->getData();
            if ($photo) {
                $directory =  $this->getParameter('gifts_directory');
                $newFilename = $uploaderService->uploadFile($photo, $directory);

                $gift->setImage($newFilename);
            }*/

            $url = $form->getData('purchaseLink')->getPurchaseLink();
            try{

                $productDetails = $scrappingService->scrapeProductDetails($url);
            }catch(\Exception $exc)
            {
                $form = $this->createForm(GiftTypeExtended::class, $gift);

                $this->addFlash('error' ,"Couldn't fetch data automatically, please enter them manually");

                return $this->redirectToRoute('app_gift_new_extended', ['listGift_id' => $listGift_id]);
//                return $this->renderForm('gift/newExtended.html.twig', [
//                    'gift' => $gift,
//                    'form' => $form,
//                    'listGift_id' => $listGift_id,
//                    'giftList' => $giftList
//                ]);
            }
//            dd($productDetails);

            $gift->setName($productDetails["name"]);
            $gift->setPrice($productDetails["price"]);
            $gift->setImage($productDetails["image"]);

            $giftList->addGift($gift);

            $entityManager->persist($gift);
            $entityManager->flush();
            $this->addFlash('success' ,'The gift has been successfully added to the gift list');
            return $this->redirectToRoute('app_gift_list_show', ['id' => $giftList->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gift/new.html.twig', [
            'gift' => $gift,
            'form' => $form,
            'listGift_id' => $listGift_id,
            'giftList' => $giftList
        ]);
    }



    #[Route('/new/extended/{listGift_id?0}', name: 'app_gift_new_extended', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function newExtended(
        Request $request,
        EntityManagerInterface $entityManager,
        $listGift_id,
        GiftListRepository $giftListRepository,
        UploaderService $uploaderService,
        ScrappingService $scrappingService

    ) : Response
    {
        $giftList = $giftListRepository->findOneBy(['id' => $listGift_id]);
        $creatorId = $giftList->getUser()->getId();
        if ($this->security->getUser()->getId() !== $creatorId) {
            //dd("je ne suis pas le créator");
            return $this->redirectToRoute('app_forbidden');
        }


        $gift = new Gift();
        $form = $this->createForm(GiftTypeExtended::class, $gift);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();
            if ($photo) {
//                $uploadsDirectory =  $this->getParameter('uploads_directory');
//                $directory =  $uploadsDirectory . "/gifts";
//
//                $newFilename = $uploaderService->uploadFile($photo, $directory);
                $newFilename = $uploaderService->uploadFile($photo, "gifts");

                $gift->setImage($newFilename);
            }


//            dd($productDetails);

            $gift->setName($form->get('name')->getData());
            $gift->setPrice($form->get('price')->getData());

            $giftList->addGift($gift);

            $entityManager->persist($gift);
            $entityManager->flush();
            $this->addFlash('success' ,'The gift has been successfully added to the gift list');
            return $this->redirectToRoute('app_gift_list_show', ['id' => $giftList->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gift/newExtended.html.twig', [
            'gift' => $gift,
            'form' => $form,
            'listGift_id' => $listGift_id,
            'giftList' => $giftList
        ]);
    }

    #[Route('/{id}', name: 'app_gift_show', methods: ['GET'])]
    public function show(Gift $gift /*, $id, GiftRepository $repository*/): Response
    {

//        $gift = $repository->findBy(['id'=>$id]);
        dd($gift);
        return $this->render('gift/show.html.twig', [
            'gift' => $gift,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gift_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Gift $gift, EntityManagerInterface $entityManager,UploaderService $uploaderService): Response
    {
        $form = $this->createForm(GiftType::class, $gift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();
            if ($photo) {
//                $directory =  $this->getParameter('gifts_directory');
//                $newFilename = $uploaderService->uploadFile($photo, $directory);

                $newFilename = $uploaderService->uploadFile($photo, "gifts");
                $gift->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', "The Gift has been successfully updated");
            return $this->redirectToRoute('app_gift_list_show', ['id' => $gift->getGiftList()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gift/edit.html.twig', [
            'gift' => $gift,
            'form' => $form,
            'giftListId' => $gift->getGiftList()->getId()
        ]);
    }

    #[Route('/{id}', name: 'app_gift_delete', methods: ['POST'])]
    public function delete(Request $request, Gift $gift, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gift->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gift);
            $entityManager->flush();
        }
        $this->addFlash('success', "The Gift has been successfully removed from the gift list");
        return $this->redirectToRoute('app_gift_list_show', ['id' => $gift->getGiftList()->getId()], Response::HTTP_SEE_OTHER);
    }
}
