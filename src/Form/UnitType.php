<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Unit;
use App\Form\FileUpload\PdfType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'class'=> Book::class
            ])

            ->add('pdf',PdfType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
        ]);
    }
}
