<?php

namespace App\Form;

use App\Entity\InfosDevis;
use App\Entity\Tranche;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfosDevisType extends AbstractType
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
            ->add('Prenom', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('Mail', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('Num_fiscal', null, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('Tranche')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfosDevis::class,
        ]);
    }
}
