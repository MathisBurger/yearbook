<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\CourseMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Course member form type
 */
class CourseMemberFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['required' => true])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Student' => CourseMember::ROLE_STUDENT,
                    'Professor' => CourseMember::ROLE_PROFESSOR,
                ],
                'required' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Create']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CourseMember::class,
        ]);
    }
}