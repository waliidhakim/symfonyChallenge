<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface ;

#[IsGranted("IS_AUTHENTICATED")]
class UserUpdateController extends AbstractController
{
    #[Route('/me/update-profile/{id}', name: 'app.user.update-profile')]
    public function index(User $user, Request $request, EntityManagerInterface $manager, UploaderService $uploaderService): Response
    {
//        if($user === null)
//        {
//            $this->addFlash('error','Something went wrong, Please Log in');
//            return $this->redirectToRoute('app_login');
//        }
        if(!$this->getUser()){
            $this->addFlash('error','Should Be logged in to update profile');
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() !== $user)
        {
            $this->addFlash('error',"Should not update someone else's profile");
            return $this->redirectToRoute('personne.list.alls');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $photo = $form->get('image')->getData();
            if ($photo) {
                $directory =  $this->getParameter('personnes_directory');
                $newFilename = $uploaderService->uploadFile($photo, $directory);

                $user->setImage($newFilename);
            }

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',"Profile updated successfully");
            return $this->redirectToRoute('personne.list.alls');
        }

        return $this->render('user-update/update-profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/me/update-password/{id}', name: 'app.user.update-password')]
    public function changePassword(Request $request, UserPasswordHasherInterface  $passwordEncoder, EntityManagerInterface $manager)
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            // Vérifier le mot de passe actuel
            if (!$passwordEncoder->isPasswordValid($user, $form->get('current_password')->getData())) {

                $this->addFlash('error', 'Current Password incorrect, please try again.');
                $form->addError(new FormError('Le mot de passe actuel est incorrect.'));
            } elseif ($form->get('new_password')->getData() !== $form->get('new_password_confirmation')->getData()) {
                $this->addFlash('error', "New Password and its confirmation don't match");
            } else {
                $newPassword = $passwordEncoder->hashPassword($user, $form->get('new_password')->getData());
                $user->setPassword($newPassword);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Password updated successfully');
                return $this->redirectToRoute('personne.list.alls');
            }
        }

        return $this->render('user-update/update-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
