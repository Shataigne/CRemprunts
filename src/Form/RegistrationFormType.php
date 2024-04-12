<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,[
            'label' => 'Email : ',
                    ]
            )
            ->add('password',PasswordType::class,[
                    'label' => 'Mot de passe : ',
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
            ->add('centre', EntityType::class,[
                'label' => 'Centres : ',
                'required' => false,
                'class' => Centre::class,
                'expanded' =>true,
                'multiple' =>false
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles : ',
                'choices' => [
                    'Observateur' => 'ROLE_USER',
                    'Emprunteur' => 'ROLE_LOUEUR',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'multiple' => true,
                'expanded'=> true,
                'data' => ['ROLE_USER'],
                'choice_value' => function ($value) {
                    return $value;
                }
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
