<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Expression;

class PasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel: ',
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe : ',
            ])
            ->add('verification', PasswordType::class, [
                'label' => 'Verification du mot de passe : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

    }
}
