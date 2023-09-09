<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
#[IsGranted("ROLE_ADMIN")]
class UserController extends AbstractController
{
    #[Route('/{page<\d+>?1}/{nbre?}', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, $page, $nbre): Response
    {
        //dd($page);
        $nbPersonne = $userRepository->count([]);
        $nbPage = ceil($nbPersonne / 10);

        $users = $userRepository->findBy([], [], 10, ($page-1)*10);

        return $this->render('user/index.html.twig', [
//            'users' => $userRepository->findAll(),
            'users' => $users,
            'isPaginated' => true,
            'nbPersonne' => $nbPersonne,
            'nbPage' => $nbPage,
            'page' => $page,
            'nbre' => 10
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]

    public function new(Request $request, EntityManagerInterface $entityManager, UploaderService $uploaderService, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();
            if ($photo) {
                $directory =  $this->getParameter('personnes_directory');
                $newFilename = $uploaderService->uploadFile($photo, $directory);

                $user->setImage($newFilename);
            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        //dd($user);
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        //dd("delete");
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
