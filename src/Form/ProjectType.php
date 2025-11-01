<?php

namespace App\Form;

use App\Entity\Goal;
use App\Entity\Project;
use App\Entity\Stack;
use App\Repository\StackRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('projectLink', TextType::class, [
                'label' => 'Lien du projet',
                'required' => false,
            ])
            ->add('sourceCodeLink', TextType::class, [
                'label' => 'Lien du code source',
                'required' => false,
            ])
            ->add('goals', EntityType::class, [
                'class' => Goal::class,
                'choice_label' => function (Goal $goal) {
                    return sprintf('%s - %s', $goal->getId(), $goal->getTitle());
                },
                'multiple' => true,
                'attr' => [
                    'class' => 'select-multi',
                ],
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
            ->add('visible', CheckBoxType::class, [
                'label' => 'Visible',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
