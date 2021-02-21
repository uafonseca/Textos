<?php

namespace App\Form;

use App\Entity\Canton;
use App\Entity\Provincia;
use App\Entity\User;
use App\Form\FileUpload\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add ('avatar',ImageType::class,[
	        	'label' => 'Ávatar',
		        'required' => false
	        ])
	        ->add ('email', EmailType::class)
	        ->add ('country', ChoiceType::class, [
		        'label' => 'País',
		        'choices' => [
			        'Ecuador' => 'Ecuador',
		        ],
	        ])
	        ->add ('name', TextType::class, [
		        'label' => 'Nombre(s)'
	        ])
	        ->add ('firstName', TextType::class, [
		        'label' => 'Primer Apellido'
	        ])
	        ->add ('lastName', TextType::class, [
		        'label' => 'Segundo apellido'
	        ])
	        ->add ('canton', EntityType::class, [
		        'label' => 'Ciudad',
		        'class' => Canton::class
	        ])
	        ->add ('provincia', EntityType::class, [
		        'label' => 'Provincia',
		        'class' => Provincia::class
	        ])
	        ->add ('scoholName', TextType::class, [
		        'label' => 'Nombre de la institución'
	        ])
	        ->add ('Guardar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
