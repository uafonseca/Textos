<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Unit;
use App\Form\FileUpload\PdfType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nombre'
            ])
        
            ->add('book', EntityType::class,[
                'class'=> Book::class,
                'label' => 'Texto'
            ])

            ->add('pdf',PdfType::class)

            ->add('activities', CollectionType::class,[
                'entry_type' => ActivityFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_options' => [
                    'label' => false,
                ],
                'label' => false,
                'attr' => array(
                    'class' => 'activity-collection row',
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'unitType';
    }
}
