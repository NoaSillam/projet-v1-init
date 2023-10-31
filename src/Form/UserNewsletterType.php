<?php

namespace App\Form;

use App\Entity\UserNewsletter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserNewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['class' => 'form-control',
                'style' => 'color:black; margin-bottom: 20px;',
            ],
            'label_attr' => [
                'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
            ], // Add the Bootstrap form-control class
            ])

            ->add('prenom', null, [
                'attr' => ['class' => 'form-control',
                'style' => 'color:black; margin-bottom: 20px;',
            ],
            'label_attr' => [
                'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
            ], // Add the Bootstrap form-control class
            ])
            ->add('adresseMail', null, [
                'attr' => ['class' => 'form-control',
                'style' => 'color:black; margin-bottom: 20px;',
            ],
            'label_attr' => [
                'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
            ], // Add the Bootstrap form-control class
            ])
            ->add('numTelephone', null, [
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
            'data_class' => UserNewsletter::class,
        ]);
    }
}
