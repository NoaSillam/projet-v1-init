<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('mail', TextType::class, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
            ->add('telephone', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label',
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;',
                ],
                'html5' => true, // Active la validation HTML5
            ])
//            ->add('dateDemande', HiddenType::class, [
//                'data' => $options['data']->getDateDemande() ?? (new \DateTime())->format('Y-m-d H:i:s'),
//            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
                ], // Add the Bootstrap form-control class
            ])
//            ->add('dateDemande', null, [
//                'attr' => ['class' => 'form-control',
//                    'style' => 'color:black; margin-bottom: 20px;',
//                ],
//                'label_attr' => [
//                    'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
//                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
//                ], // Add the Bootstrap form-control class
//            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
          //  'timezone' => 'UTC', // Fuseau horaire par défaut
        ]);
    }
}
