<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Salle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateSallesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $equipements = [];
        if($builder->getData()->getEquipements() !== null) {
            $equipements = $builder->getData()->getEquipements();
        }

        $builder
            ->add('numero', NumberType::class, [
                'attr' => [
                    'min' => '0'
                ],
                'html5' => true,
            ])
            ->add('batiment')
            ->add('etage',NumberType::class, [
                'required' => False,
                'constraints' => [
                    new NotBlank([
                        'message' => "Il faut renseigner l'étage. Pour le rez-de-chaussée, mettez 0, pour un sous-sol mettez -1, -2, ..",
                    ]),
                ],
                'html5' => true,
            ])
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
            ->add('places')
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
            'data_class' => Salle::class,
        ]);
    }
}
