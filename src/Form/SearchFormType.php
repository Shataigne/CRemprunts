<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Centre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
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
        ->add('dispoLe', DateTimeType::class, [
            'label' => 'Disponible le :',
            'required' => false,
            'widget' => 'single_text',

        ])
        ->add('dispoMin', DateTimeType::class, [
            'label' => 'Disponible du :',
            'required' => false,
            'widget' => 'single_text',

        ])
        ->add('dispoMax', DateTimeType::class, [
            'label' => ' au ',
            'required' => false,
            'widget' => 'single_text',

        ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}