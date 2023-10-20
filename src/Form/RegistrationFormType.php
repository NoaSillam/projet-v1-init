<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'attr' =>['class' => 'form-control'],
                'label' => 'Prénom',
                
            ] 
            )
            ->add('nom', TextType::class, [
                'attr' =>['class' => 'form-control'],
            ] )
            ->add('email', EmailType::class, [
                'attr' =>['class' => 'form-control'],
                'label' => 'E-mail',
            ] )
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Users' => 'ROLE_USERS',
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Product Admin' => 'ROLE_PRODUCT_ADMIN',
                   // 'Role admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN'
                    // Ajoutez d'autres rôles selon vos besoins
                ],
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control'],
            ])



            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Role users' => 'ROLE_USERS',
            //         'Role user' => 'ROLE_USER',
            //         'Role product admin' => 'ROLE_PRODUCT_ADMIN',
            //         'Role admin' => 'ROLE_ADMIN',
            //         'Role super admin' => 'ROLE_SUPER_ADMIN',
            //     ],
            //     'multiple' => false, // Un seul choix autorisé
            //     'expanded' => false, // Liste déroulante
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            // ->add('created_at', DateType::class, [
            //     'attr' => ['class' => 'form-control'],
            //      'mapped' => false,
            //     'data' => new \DateTime(), // Set the default value to the current date and time
            // ] )

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
