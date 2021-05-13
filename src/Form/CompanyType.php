<?php

namespace App\Form;

use App\Entity\Company;
use App\Form\FileUpload\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
            	'label'=>'Nombre de la empresa'
            ])
            ->add('country',TextType::class,[
	            'label'=>'País'
            ])
            ->add('city',TextType::class,[
	            'label'=>'Cuidad'
            ])
            ->add('address',TextType::class,[
	            'label'=>'Dirección'
            ])
            ->add('phone',NumberType::class,[
	            'label'=>'Teléfono de contacto'
            ])
            ->add('email',EmailType::class,[
	            'label'=>'Correo eletrónico'
            ])
            ->add('visible',CheckboxType::class,[
	            'label'=>'Mostrar datos de contacto en la página de inicio',
                'required' => false
            ])
            ->add('logo',ImageType::class,[
	            'label'=>'Logo',
	            'required' => false
            ])
            ->add('identity',IdentityType::class,[
                'label' => 'Identidad'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
