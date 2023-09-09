<?php

namespace App\Form;

use App\Entity\Gift;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GiftTypeExtended extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [

                ],
                'label' => 'Gift name',
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Please enter a name',
                    ]),
                ]

            ])
            ->add('price', NumberType ::class, [
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Please enter a price',
                    ]),
                    new Length([
                        'min' => 0,
                        'minMessage' => 'This field should be at least {{ limit }}',
                        // max length allowed by Symfony for security reasons

                    ]),
                ]
            ])
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
            ->add('purchaseLink', UrlType::class,[
                'constraints' =>[
                        new NotBlank([
                            'message' => 'Please enter the purchase link',
                        ]),


                ]
            ])
//            ->add('giftList')
//            ->add('reservedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gift::class,
        ]);
    }
}
