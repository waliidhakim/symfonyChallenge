<?php

namespace App\Form;

use App\Entity\GiftList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class GiftListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Add an image to your gift list (only image files)',

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
            ->add('status', CheckboxType::class, [
                'required' => false,
                'label'    => 'Private',
                'attr'     => ['id' => 'gift_list_status']
            ])
            ->add('password', null, [
                'attr' => ['id' => 'gift_list_password', 'class' => 'password-field']
            ])
            ->add('beginDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
            ])
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
                'placeholder' => 'Choose a theme'
            ])
//            ->add('Gifts', TextType::class , [
//                'required'=> false
//            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GiftList::class,
        ]);
    }
}
