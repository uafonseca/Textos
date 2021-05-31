<?php

namespace App\Form;

use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Valid;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Título'])
            ->add('objective', null, ['label' => 'Objetivo'])
            ->add('attempts', null, ['label' => 'Intentos'])
            ->add('email', null, ['label' => 'Correo para recibir las evaluaciones'])
            ->add('requirements', ChoiceType::class, [
                'label' => 'Requisitos para aprobar',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'expanded' => true,
                'choices' => [
                    'Si' => true,
                    'No' => false
                ]
            ])
            ->add('percentage', null, ['label' => 'Porcentaje para aprobar'])
            ->add('time', null, ['label' => 'Tiempo para responder'])
            ->add('points', null, ['label' => 'Puntaje cuestionario'])
            ->add('certificate', ChoiceType::class, [
                    'label' => 'Generar certificado',
                    'label_attr' => [
                        'class' => 'radio-inline'
                    ],
                    'expanded' => true,
                    'choices' => [
                        'Si' => true,
                        'No' => false
                    ]
                ])
//            ->add('questions', CollectionType::class, [
//                'entry_type' => QuestionType::class,
//                'constraints' => [new Valid()],
//                'prototype_name' => '__questions_name__',
//                'block_name' => 'questions',
//                'block_prefix' => 'questions',
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//                'entry_options' => [
//                    'label' => false,
//                ],
//                'label' => false,
//                'attr' => array(
//                    'class' => 'question-collection row',
//                ),
//            ])
        ;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'evaluationForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
