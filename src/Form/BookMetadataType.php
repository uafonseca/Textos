<?php

namespace App\Form;

use App\Entity\BookMetadata;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookMetadataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', null, ['label' => 'Autor'])
            ->add('introduction', CKEditorType::class, ['label' => 'Introducción'])
            ->add('dedication', null, [
                'label' => 'Dedicación',
                'attr' => ['placeholder' => 'Ejm: 4 a 5 horas semanales'],
            ])
            ->add('language', ChoiceType::class, [
                'label' => 'Idioma',
                'choices' => [
                    'Inglés' => 'Inglés',
                    'Español' => 'Español',
                    'Francés' => 'Francés',
                    'Alemán' => 'Alemán',
                    'Mandarín' => 'Mandarín'
                ]
            ])
            ->add('transcription', ChoiceType::class, [
                'label' => 'Transcripción de video',
                'choices' => [
                    'Inglés' => 'Inglés',
                    'Español' => 'Español',
                    'Francés' => 'Francés',
                    'Alemán' => 'Alemán',
                    'Mandarín' => 'Mandarín'
                ]

            ])
            ->add('learning', ChoiceType::class, [
                'label' => 'Aprendizaje',
                'choices' => [
                    'A tu ritmo' => 'A tu ritmo',
                    'Guiado por un instructor' => 'Guiado por un instructor',
                    'Con orientación profesional' => 'Con orientación profesional',
                    'Según el docente' => 'Según el docente',
                ]

            ])
            ->add('intitution', IntitutionType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BookMetadata::class,
        ]);
    }
}
