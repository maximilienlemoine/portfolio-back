<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Stack;
use App\Repository\StackRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('icon', FileType::class, [
                'label' => 'Icône',
                'mapped' => false,
                'required' => false,
            ])
            ->add('stacks', EntityType::class, [
                'class' => Stack::class,
                'query_builder' => function (StackRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.number', 'ASC');
                },
                'choice_label' => function (Stack $stack) {
                    return $stack;
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
