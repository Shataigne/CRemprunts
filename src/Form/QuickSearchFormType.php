<?php

namespace App\Form;

use App\Entity\Centre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuickSearchFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('action', ChoiceType::class, [
                'label'=> "J'emprunte un(e) :",
                'choices' => [
                    'Véhicule' => 'vehicule',
                    'Ordinateur' => 'pc',
                    'Kit' => 'Kit',
                    'Flotte de PC' => 'flotte',
                    'Salle de cours' => 'cours',
                    'Salle de reunion' => 'reunion',
                ],
            ])
            ->add('q', TextType::class, [
                'label' => 'Rechercher : ',
                'required' => false,
            ])
            ->add('centres', EntityType::class,[
                'label' => 'Centres : ',
                'required' => false,
                'class' => Centre::class,
                'expanded' =>false,
                'multiple' =>false
            ])
            ->add('dispoNow', CheckboxType::class, [
                'label' => 'Disponible maintenant ',
                'required' => false
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}