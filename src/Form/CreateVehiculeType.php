<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Marque;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
