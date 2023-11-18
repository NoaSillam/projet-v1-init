<?php

namespace App\Form;

use App\Entity\Personne;
use App\Entity\Regions;
use App\Entity\TrancheFiscal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrancheFiscalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Debut', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('Fin', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('nbPersonne', EntityType::class,
                ['class'=>Personne::class,
                    'choice_label' => 'nbPersonne',
                    'label' => 'Personne' ,
                    'attr' =>
                        ['class' => 'form-control',
                            'style' => 'color:black; margin-bottom: 20px;',
                        ],
                    'label_attr' => [
                        'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                        'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                    ],
                ])
            ->add('Region', EntityType::class,
                ['class'=>Regions::class,
                    'choice_label' => 'Nom',
                    'label' => 'Régions' ,
                    'attr' =>
                        ['class' => 'form-control',
                            'style' => 'color:black; margin-bottom: 20px;',
                        ],
                    'label_attr' => [
                        'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                        'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrancheFiscal::class,
        ]);
    }
}
