<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimePickerType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => 'dd-mm-yyyy',
            'format' => 'dd-MM-yyyy',
            'widget' => 'single_text',
        ]);
    }

    public function getParent()
    {
        return DateTimeType::class;
    }

    public function getBlockPrefix()
    {
        return 'datetimepicker';
    }
}
