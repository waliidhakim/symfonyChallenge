<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('roles')
            ->add('password')
            ->add('email')
            ->add('isVerified')
            ->add('firstname')
            ->add('lastname')
            ->add('image')
            ->add('createdAt')
            ->add('updatedAt')*/
            ->add('email', EmailType::class) // champ e-mail avec validation appropriée
            ->add('firstname', TextType::class, [
                'required' => false // ou true si vous voulez qu'il soit obligatoire
            ])
            ->add('lastname', TextType::class, [
                'required' => false // ou true si vous voulez qu'il soit obligatoire
            ])
           /* ->add('image', TextType::class, [
                'required' => false // adaptez en fonction de vos besoins
            ])*/
           ->add('image', FileType::class, [
               'label' => 'Add an image (only image files)',

               // unmapped means that this field is not associated to any entity property
               'mapped' => false,

               // make it optional so you don't have to re-upload the PDF file
               // every time you edit the Product details
               'required' => false,

               // unmapped fields can't define their validation using annotations
               // in the associated entity, so you can use the PHP constraint classes
               'constraints' => [
                   new File([
                       'maxSize' => '1024k',
                       'mimeTypes' => [
                           'image/gif',
                           'image/jpg',
                           'image/jpeg',
                       ],
                       'mimeTypesMessage' => 'Please upload a valid image file',
                   ])
               ],
           ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    // Ajoutez d'autres rôles si nécessaire
                ],
                'multiple' => true, // Permettre la sélection de plusieurs rôles
                'expanded' => true, // Rendre comme des cases à cocher
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
