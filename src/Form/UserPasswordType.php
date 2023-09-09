<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('current_password', PasswordType::class, [
                'label' => 'Enter Current password',
                'mapped' => false,
            ])
            ->add('new_password', PasswordType::class, [
                'label' => 'Enter new password',
            ])
            ->add('new_password_confirmation', PasswordType::class, [
                'label' => 'Confirm new password',
            ])
            ->add('Update', SubmitType::class,[
                'attr' =>[
                    'class' => 'btn btn-primary mt-4'
                ]
            ])
//            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
