<?php

namespace App\Form;

use App\Entity\Intitution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null,[ 
                'label' => 'Institución',
                'attr' => ['placeholder' => 'Nombre de la institución']
                 ])
            ->add('web', null,[ 
                'label' => 'Página web',
                'attr' => ['placeholder' => 'Url de la página web']
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intitution::class,
        ]);
    }
}
