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

class GestionRolesFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = $options['roles'];
        $builder

            ->add('centres', CheckboxType::class,[
                'label' => 'All Centres ',
                'required' => false,
                'data' => in_array('ROLE_CENTRES', $roles),
                'attr' => [
                    'id' => 'centres-checkbox',
                ],
            ])
            ->add('salles', CheckboxType::class, [
                'label' => 'Salles',
                'required' => false,
                'data' => in_array('ROLE_SALLES',$roles),
                'attr' => [
                    'id' => 'salles-checkbox',
                ],
            ])
            ->add('vehicules', CheckboxType::class, [
                'label' => 'Véhicules',
                'required' => false,
                'data' => in_array('ROLE_VEHICULES',$roles),
                'attr' => [
                    'id' => 'vehicules-checkbox',
                ],

            ])
            ->add('kits', CheckboxType::class, [
                'label' => 'Kits',
                'required' => false,
                'data' => in_array('ROLE_KITS',$roles),
                'attr' => [
                    'id' => 'kits-checkbox',
                ],

            ])
            ->add('pc', CheckboxType::class, [
                'label' => 'PC',
                'required' => false,
                'data' => in_array('ROLE_PC', $roles),
                'attr' => [
                    'id' => 'pc-checkbox',
                ],

            ])
            ->add('flottes', CheckboxType::class, [
                'label' => 'Flottes de PC',
                'required' => false,
                'data' => in_array('ROLE_FLOTTES', $roles),
                'attr' => [
                    'id' => 'flottes-checkbox',
                ],

            ])
            ->add('emprunteur', CheckboxType::class, [
                'label' => 'Tous',
                'required' => false,
                'data' => in_array('ROLE_LOUEUR', $roles),
                'attr' => [
                    'id' => 'emprunteur-checkbox',
                ],

            ])
            ->add('administrateur', CheckboxType::class, [
                'label' => 'Administrateur',
                'required' => false,
                'data' => in_array('ROLE_ADMIN', $roles),
                'attr' => [
                    'id' => 'administrateur-checkbox',
                ],

            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'roles' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}