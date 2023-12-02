<?php

namespace App\Form;

use App\Entity\Menage;
use App\Entity\Prime;
use App\Entity\TypeDevis;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])

//            ->add('Type_chauffage', EntityType::class,
//                        ['class'=>TypeDevis::class,
//                            'choice_label' => 'Nom',
//                            'label' => 'Type de chauffage' ,
//                            'attr' =>
//                                ['class' => 'form-control',
//                                    'style' => 'color:black; margin-bottom: 20px;',
//                                    ],
//                            'label_attr' => [
//                                        'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
//                                        'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 40%;', // Ajoutez des styles CSS personnalisés pour les labels
//            ],
//        ])
//            ->add('Menage', EntityType::class,
//                ['class'=>Menage::class,
//                    'choice_label' => 'Nom',
//                    'label' => 'Ménage' ,
//                    'attr' =>
//                        ['class' => 'form-control',
//                            'style' => 'color:black; margin-bottom: 20px;',
//                        ],
//                    'label_attr' => [
//                        'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
//                        'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
//                    ],
//                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prime::class,
        ]);
    }
}
