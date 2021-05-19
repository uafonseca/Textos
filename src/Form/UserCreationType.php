<?php

namespace App\Form;

use App\Entity\Canton;
use App\Entity\Country;
use App\Entity\Provincia;
use App\Entity\Role;
use App\Entity\State;
use App\Entity\User;
use App\Form\FileUpload\ImageType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	    
	        ->add ('email', EmailType::class,[
                'label' => 'Dirección de correo',
            ])

	        ->add ('name', TextType::class, [
		        'label' => 'Nombre(s)'
	        ])
	        ->add ('firstName', TextType::class, [
		        'label' => 'Primer Apellido'
	        ])
            ->add ('username', TextType::class, [
		        'label' => 'Usuario'
	        ])
            ->add ('plainPassword', PasswordType::class, [
		        'label' => 'Contraseña'
	        ])
            ->add('rolesObject', null,[
				'label' => 'Listado de roles'
			])
            
        ;
    }

  

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
