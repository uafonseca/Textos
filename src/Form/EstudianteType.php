<?php

namespace App\Form;

use App\Entity\Estudiante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class EstudianteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brithday',TextType::class,[
            	'label' => 'Fecha de nacimiento',
	            
	            'attr' => ['class'=>'student'],
            ])
            ->add('mentorName',null,[
	            'label' => 'Nombre del representante',
	
	            'attr' => ['class'=>'student'],
            ])
            ->add('mentorFirstName',null,[
	            'label' => 'Primer apellido representante',
	
	            'attr' => ['class'=>'student'],
            ])
            ->add('mentorLastName',null,[
	            'label' => 'Segundo apellido representante',
	
	            'attr' => ['class'=>'student'],
            ])
            
            ->add('mentorPhone',NumberType::class,[
	            'label' => 'Celular',
	
	            'attr' => ['class'=>'student'],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Estudiante::class,
        ]);
    }
}
