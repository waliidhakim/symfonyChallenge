<?php

namespace App\Form;

use App\Entity\GiftList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GiftListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('image')
            ->add('status', CheckboxType::class, [
                'required' => false,
                'label'    => 'Privée',
                'attr'     => ['id' => 'gift_list_status']
            ])
            ->add('password', null, [
                'attr' => ['id' => 'gift_list_password', 'class' => 'password-field']
            ])
            ->add('beginDate')
            ->add('endDate')
            ->add('theme', ChoiceType::class, [
                'choices'  => [
                    'Anniversaire' => 'Anniversaire',
                    'Mariage' => 'Mariage',
                    'Naissance' => 'Naissance',
                    'Baptême' => 'Baptême',
                    'Pot de départ' => 'Pot de départ',
                    'Crémaillère' => 'Crémaillère',
                    'Diplôme' => 'Diplôme',
                    'Retraite' => 'Retraite',
                    'Promotion' => 'Promotion',
                    'Fêtes des mères/pères' => 'Fêtes des mères/pères',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'CHoisir un thème'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GiftList::class,
        ]);
    }
}
