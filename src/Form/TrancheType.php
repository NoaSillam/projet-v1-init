<?php

namespace App\Form;

use App\Entity\Menage;
use App\Entity\Personne;
use App\Entity\Prime;
use App\Entity\Regions;
use App\Entity\Tranche;
use App\Entity\TrancheFiscal;
use App\Entity\TypeDevis;
use App\Repository\TrancheFiscalRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrancheType extends AbstractType
{
    private $trancheFiscalRepository;

    public function __construct(TrancheFiscalRepository $trancheFiscalRepository)
    {
        $this->trancheFiscalRepository = $trancheFiscalRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
         /*   ->add('debut', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('Fin', null, [
                'required' => false,
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])*/

            ->add('TrancheFiscal', EntityType::class, [
                'class' => TrancheFiscal::class,
                'choices' => $this->trancheFiscalRepository->findTrancheFiscalChoices(),
                'choice_value' => 'id',
                'choice_label' => function ($trancheFiscal) {
                    return sprintf('%s - %s', $trancheFiscal->getDebut(), $trancheFiscal->getFin() ?: '<');
                },
                'label' => 'TrancheFiscal',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label',
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 40%;',
                ],
            ])


            ->add('Menage', EntityType::class,
                ['class'=>Menage::class,
                    'choice_label' => 'Nom',
                    'label' => 'Ménage' ,
                    'attr' =>
                        ['class' => 'form-control',
                            'style' => 'color:black; margin-bottom: 20px;',
                        ],
                    'label_attr' => [
                        'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                        'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                    ],
                ])
  /*          ->add('Region', EntityType::class,
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
                ])*/
            ->add('Prime', EntityType::class,
                ['class'=>Prime::class,
                    'choice_label' => 'Nom',
                    'label' => 'Prime' ,
                    'attr' =>
                        ['class' => 'form-control',
                            'style' => 'color:black; margin-bottom: 20px;',
                        ],
                    'label_attr' => [
                        'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                        'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                    ],
                ])
            ->add('TypeDevis', EntityType::class,
                ['class'=>TypeDevis::class,
                    'choice_label' => 'Nom',
                    'label' => 'Type de chauffage' ,
                    'attr' =>
                        ['class' => 'form-control',
                            'style' => 'color:black; margin-bottom: 20px;',
                        ],
                    'label_attr' => [
                        'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                        'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 40%;', // Ajoutez des styles CSS personnalisés pour les labels
                    ],
                ])
            ->add('aide', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tranche::class,
        ]);
    }
}
