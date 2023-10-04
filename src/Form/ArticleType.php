<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\DataTransformerInterface;
use App\Form\DataTransformer\FileToStringTransformer;

class ArticleType extends AbstractType
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
        ->add('Image', FileType::class, [
            'attr' => ['class' => 'form-control-file',
            'style' => 'margin-bottom: 20px;',
        ],
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file',
                ]),
            ],
        'label_attr' => [
            'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
            'style' => 'color: black; font-weight : bold; text-align : center; margin-right: 3%; display: block;', // Ajoutez des styles CSS personnalisés pour les labels
        ],
        ])
        ->add('Annonce', null,[
            'attr' => ['class' => 'form-control tinymce',
            'style' => 'margin-bottom: 20px;',
            'cols' => '50',
            'rows' => '10',
        ],
        'label_attr' => [
            'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
            'style' => 'color: black; font-weight : bold; text-align : center;  margin-left: 45%;', // Ajoutez des styles CSS personnalisés pour les labels
        ], // Add the Bootstrap form-control class
        ])
        ;
        $builder->get('Image')->addModelTransformer(new FileToStringTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
