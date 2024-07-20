<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Stack;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('icon', TextType::class, [
                'label' => 'Icône',
            ])
            ->add('stacks', EntityType::class, [
                'class' => Stack::class,
                'choice_label' => function (Stack $stack) {
                    return sprintf('%s - %s', $stack->getId(), $stack->getTitle());
                },
                'multiple' => true,
                'attr' => [
                    'class' => 'select-multi',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
