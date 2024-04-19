<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Marque;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $marques = $options['marques'];
        $equipements = [];
        if($builder->getData()->getEquipements() !== null) {
            $equipements = $builder->getData()->getEquipements();
        }

        $builder
            ->add('libelle', TextType::class, [
                'attr' => [
                    'placeholder' => '"Marque modèle plaque"',
                    'null' =>true,

                    ],
                'label' => 'Libelle : ',
            ])
            ->add('modele')
            ->add('plaque')
            ->add('centre', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'libelle',
            ])

            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choices' => $marques,
                'choice_label' => 'libelle',
            ])
            ->add('places', NumberType::class, [
                'attr' => [
                    'min' => '0'
                ],
                'html5' => true,
            ])
            ->add('equipements', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'data' => implode(", ",$equipements),
                'attr' => [
                    'placeholder' => 'Séparez vos équipements par une virgule: Premier équipement,Deuxième équipement, etc..',

                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
            'marques' => []
        ]);
    }
}
