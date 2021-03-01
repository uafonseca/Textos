<?php

namespace App\Form;

use App\Entity\HtmlCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',null,[
                'label' => 'Código',
                'required' => false,
            ])
            ->add('active', null,[
                'label' => 'Activar',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HtmlCode::class,
        ]);
    }
}
