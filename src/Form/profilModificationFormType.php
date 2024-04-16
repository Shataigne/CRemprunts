<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class profilModificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,[
            'label' => 'Email : ',
                    ]
            )
            ->add('nom',TextType::class,[
                    'label' => 'Nom : ',
                ]
            )
            ->add('prenom',TextType::class,[
                    'label' => 'Prenom : ',
                ]
            )
            ->add('poste',TextType::class,[
                    'label' => 'Poste : ',
                ]
            )
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles : ',
                'choices' => [

                    'Emprunteur' => 'ROLE_LOUEUR',
                    'Observateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
