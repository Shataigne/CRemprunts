<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Salle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateSallesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', NumberType::class, [
                'attr' => [
                    'min' => '0'
                ],
                'html5' => true,
            ])
            ->add('batiment')
            ->add('etage')
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Salle de cours' => 'CRS',
                    'Salle de réunion' => 'REU'
                    ]
            ])
            ->add('centre', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
