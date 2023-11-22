<?php
namespace App\Form;
use App\Entity\InfosDevis;

use App\Entity\Personne;

use App\Entity\Regions;


use App\Entity\TrancheFiscal;

use App\Repository\TrancheFiscalRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class InfosDevisType extends AbstractType{
    private $trancheFiscalRepository;

    public function __construct(TrancheFiscalRepository $trancheFiscalRepository)
    {
        $this->trancheFiscalRepository = $trancheFiscalRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px; test-align:center;'],
                'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;'],
            ])
            ->add('Prenom', TextType::class, [
                'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 43%;'],
            ])
            ->add('Mail', TextType::class, [
                'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 45%;'],
            ])


            ->add('telephone', null, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label',
                    'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 43%;'],
                ])

         /*   ->add('nbPersonne', EntityType::class, [
                'class' => Personne::class,
                'choice_label' => 'nbPersonne',
                'label' => 'Nombre de Personne dans le Foyer',
                'placeholder' => 'Choisir le nombre de personne à charge dans votre foyer',
                'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 30%;'],
            ])*/
         ->add('nbPersonne', EntityType::class, [
             'class' => Personne::class,
             'choice_label' => 'nbPersonne',
             'label' => 'Nombre de Personne dans le Foyer',
             'placeholder' => 'Choisir le nombre de personne à charge dans votre foyer',
             'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
             'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 30%;'],
         ])

            ->add('Regions', EntityType::class, [
                'class' => Regions::class,
                'choice_label' => 'Nom',
                'label' => 'Régions',
                'placeholder' => 'Choisir une région',
                'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 43%;'],
            ])
            ->add('TrancheFiscal', EntityType::class, [
                'class' => TrancheFiscal::class,
                'choices' => $options['tranchesFiscales'],
                'choice_label' => 'label',
                'label' => 'Tranche Fiscale (€)',
                'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight: bold; text-align: center; margin-left: 40%;'],
            ])
            ->add('Num_fiscal', TextType::class, [
                'label' => 'Numéro Fiscale (€)',
                'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight : bold; text-align : center; margin-left: 40%;'],
            ])
            ->add('proprieter',  ChoiceType::class, [
                'choices' => ['Locataire' => 'Locataire',
                    'Propriétaire' => 'Proprietaire',
                    ],
                'label' => 'Propriétaire / Locataire',
                'placeholder' => 'Choisir Propriétaire / Locataire',
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label',
                    'style' => 'color: black; 
                    font-weight : bold; 
                    text-align : center; 
                    margin-left: 40%;'],
                ])
            ->add('typeChauffage',  ChoiceType::class, [
                'choices' => ['Életricité' => 'electricite',
                    'Fioul' => 'fioul',
                    'Gaz' => 'gaz',
                    'Bois' => 'bois',
                    ],
                'label' => 'Type de chauffage',
                'placeholder' => 'Choisir le type de chauffage',
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label',
                    'style' => 'color: black; 
                    font-weight : bold; 
                    text-align : center; margin-left: 40%;'],
                ])
            ->add('surfaceHabitable', null, [
                'label' => 'Surface habitable (m2)',
                'attr' => ['class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px;'],
                'label_attr' => ['class' => 'custom-label',
                    'style' => 'color: black; 
                    font-weight : bold; 
                    text-align : center; 
                    margin-left: 40%;'],
                ])
            ->add('residencePrincipale', ChoiceType::class, [
                'label' => 'Résidence Principale',
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non',
                ],
                'expanded' => true,
                'multiple' => false,
                'choice_attr' => [
                    'Oui' => ['style' => 'margin-left: 50px; text-align:center;'],
                    'Non' => ['style' => 'margin-left: 150px; text-align:center; margin-bottom:100px;'],
                ],
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'color:black; margin-bottom: 20px; height:40px;',
                ],
                'label_attr' => [
                    'class' => 'custom-label',
                    'style' => 'color: black; font-weight: bold; text-align: center; margin-left: 40%;',
                ],
            ])

        ;


        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data instanceof InfosDevis) {
                    $this->addTrancheFiscalField($form, $data->getNbPersonne());
                }
            }
        );

        $builder->get('nbPersonne')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm()->getParent();
                $data = $event->getData();

                $this->addTrancheFiscalField($form, $data);
            }
        );
    }


    private function addTrancheFiscalField(FormInterface $form, $personne): void
    {
        if (!$personne instanceof Personne) {
            return;
        }

        $tranchesFiscales = $this->trancheFiscalRepository->findTrancheFiscalChoicesByPersonne($personne);

        $form->remove('TrancheFiscal');
        $form->add('TrancheFiscal', EntityType::class, [
            'class' => TrancheFiscal::class,
            'choices' => $tranchesFiscales,
            'choice_label' => 'label',
            'label' => 'Tranche fiscale',
            'attr' => ['class' => 'form-control', 'style' => 'color:black; margin-bottom: 20px;'],
            'label_attr' => ['class' => 'custom-label', 'style' => 'color: black; font-weight: bold; text-align: center; margin-left: 45%;'],
            'placeholder' => 'Choisir une Tranche Fiscale', // Ajoutez une option de placeholder
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfosDevis::class,
            'tranchesFiscales' => [], // Ajoutez cette ligne pour définir l'option tranchesFiscales
        ]);
    }
}