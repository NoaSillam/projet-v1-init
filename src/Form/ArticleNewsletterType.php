<?php

namespace App\Form;

use App\Entity\ArticleNewsletter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Form\DataTransformer\FileToStringTransformer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\DataTransformerInterface;




class ArticleNewsletterType extends AbstractType
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

            ->add('image', FileType::class, [
                'attr' => ['class' => 'form-control-file',
                'style' => 'margin-bottom: 20px;',
            ],
                'constraints' => [
                    new File([
                       // 'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Mettez un type fichier valide',
                       // 'maxSizeMessage' => 'L\'image doit faire 10 pixels de large au maximum',
                    ]),
                    new Assert\Image([
                        'maxWidth' => 1024, // Définissez la largeur maximale souhaitée en pixels
                        'maxWidthMessage' => 'La largeur de l\'image ne doit pas dépasser {{ max_width }} pixels de large.',
                    ]),
                ],
            'label_attr' => [
                'class' => 'custom-label', // Ajoutez votre classe personnalisée pour les labels
                'style' => 'color: black; font-weight : bold; text-align : center; margin-right: 3%; display: block;', // Ajoutez des styles CSS personnalisés pour les labels
            ],
            ])

            ->add('annonce', null,[
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
        $builder->get('image')->addModelTransformer(new FileToStringTransformer());

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleNewsletter::class,
        ]);
    }
}
