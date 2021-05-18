<?php

namespace App\Form;

use App\Entity\Slide;
use App\Form\FileUpload\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title',null,[
                'label' => 'Título',
                'required' => false,
            ])
            ->add('shortDescription',null,[
                'label' => 'Descripción',
                'required' => false
            ])
            ->add('image',ImageType::class,[
                'label' => 'Diapositiva',
                'required' => !$options['edit']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
        ]);
        $resolver->setRequired('edit');
    }
}
