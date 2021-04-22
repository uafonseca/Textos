<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Valid;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                'label' => 'Título'
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'Tipo',
                'choices' => [
                    Question::QUESTION_TYPE_OPEN_TEXT => Question::QUESTION_TYPE_OPEN_TEXT,
                    Question::QUESTION_TYPE_TRUE_OR_FALSE => Question::QUESTION_TYPE_TRUE_OR_FALSE,
                ],
                'placeholder' => '--Seleccione--',
                'attr' => [
                    'class' => 'selector'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT,[$this, 'onPreSetData'])
        ;
    }
    public function onPreSetData(FormEvent $event): void
    {
        /** @var Question $question */
        $question = $event->getData();
        $form = $event->getForm();

        if (!$question) {
            return;
        }
        if ($question['type'] == Question::QUESTION_TYPE_TRUE_OR_FALSE) {
            $form->add('choices',CollectionType::class,[
                'entry_type' => ChoicesType::class,
                'constraints' => [new Valid()],
                'prototype_name' => '__choice_name__',
                'block_name' => 'choice',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => false,
                'attr' => array(
                    'class' => 'choice-collection row',
                ),
            ]);
        } else if ($question['type'] == Question::QUESTION_TYPE_OPEN_TEXT){
            $form->add('singleQuestions',CollectionType::class,[
                'entry_type' => SingleQuestionType::class,
                'constraints' => [new Valid()],
                'prototype_name' => '__choice_name__',
                'block_name' => 'singleQuestion',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => false,
                'attr' => array(
                    'class' => 'singleQuestion-collection row',
                ),
            ]);
        }
        else{

            $event->setData($question);
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ChoiceForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
