<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,[
                'label' => 'Nombre de la actividad'
            ])
            ->add('page',null,[
                'label' => 'Página para la actividad',
                'help' => 'El conteo de paginas comienza desde 0',
            ])
            ->add('url',null,[
                'label' => 'Url de la actividad Genially'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
    public function getBlockPrefix()
    {
        return 'activity';
    }
}
