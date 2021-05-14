<?php

namespace App\Form;

use App\Entity\Identity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdentityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('colorPrimary', ColorType::class, [
                'label' => 'Color primario',
                'help' => 'Recomendación: #3b7ddd ',
                'attr' => ['data-tippy-content' => 'Se utiliza en la barra superior y en los botones de acción primaria']
            ])
            ->add('colorSecondary', ColorType::class, [
                'label' => 'Color secundario',
                'help' => 'Recomendación: #6c757d',
                'attr' => ['data-tippy-content' => 'Se utiliza en los botones de acción secundaria']
            ])
            ->add('colorSuccess', ColorType::class, [
                'label' => 'Color de éxito',
                'help' => 'Recomendación: #28a745',
                'attr' => ['data-tippy-content' => 'Se utiliza para mostrar información de éxito']
            ])
            ->add('colorWarning', ColorType::class, [
                'label' => 'Color de alerta',
                'help' => 'Recomendación: #ffc107',
                'attr' => ['data-tippy-content' => 'Se utiliza para mostrar información de alerta']
            ])
            ->add('colorInfo', ColorType::class, [
                'label' => 'Color de información',
                'help' => 'Recomendación: #17a2b8',
                'attr' => ['data-tippy-content' => 'Se utiliza para mostrar información de estandar']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Identity::class,
        ]);
    }
}
