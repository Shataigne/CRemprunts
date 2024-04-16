<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Centre;
use App\Entity\Marque;
use App\Entity\Materiel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateMaterielFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $marques = $options['marques'];
        $builder
            ->add('libelle')
            ->add('modele')
            ->add('noSerie')
            ->add('centre', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'libelle',
            ])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choices' => $marques,
                'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
            'marques' => []
        ]);
    }
}
