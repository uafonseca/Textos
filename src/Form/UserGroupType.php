<?php

namespace App\Form;

use App\Entity\UserGroup;
use App\Form\FileUpload\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupName',TextType::class,[
                'label' => 'Nombre del grupo'
            ])
            ->add('startDate',DateTimePickerType::class,[
                'label' => 'Fecha de inicio',
                'html5' => false,
                'required' => true
            ])
            ->add('modality',null,[
                'label' => 'Modalidad',
                'required' => true
            ])
            ->add('course',null,[
                'label' => 'Curso',
                'required' => true
            ])
            ->add('file',ImageType::class,[
                'label' => 'Excel',
                'mapped' => false,
                'required' => !$options['edit']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserGroup::class,
        ]);
        $resolver->setRequired('edit');
    }
}
