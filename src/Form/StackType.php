<?php

namespace App\Form;

use App\Entity\Stack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                    'label' => 'Numéro',
                ]
            )
            ->add('title', TextType::class, [
                    'label' => 'Titre',
                ]
            )
            ->add('icon', FileType::class, [
                    'label' => 'Icône',
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add('color', ColorType::class, [
                    'label' => 'Couleur',
                ]
            )
            ->add('badgeVisible', CheckBoxType::class, [
                    'label' => 'Afficher le badge ?',
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stack::class,
        ]);
    }
}
