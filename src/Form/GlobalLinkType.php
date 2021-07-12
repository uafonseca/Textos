<?php

namespace App\Form;

use App\Entity\GlobalLink;
use App\Form\FileUpload\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GlobalLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label'=>'Nombre del enlace'])
            ->add('url', UrlType::class, ['label'=>'Enlace'])
            ->add('destination', ChoiceType::class, [
                'label'=>'Dirigido a:',
                'choices' =>[
                    'Estudiantes' => 'Estudiantes',
                    'Docentes' => 'Docentes',
                    'Ambos' => 'Ambos',
                ]
                ])
            ->add('imagen', ImageType::class, [
                'label' => 'Imágen del enlace',
                'required' => !$options['edit'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GlobalLink::class,
        ]);
        $resolver->setRequired('edit');
    }
}
