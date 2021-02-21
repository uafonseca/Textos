<?php

namespace App\Form;

use App\Entity\Profesor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ProfesorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni',null,[
	            'label' => 'Número de identificación',
	
	            'attr' => ['class'=>'profesor'],
	            
	            'constraints' => [
		            new Regex('/^\d{10}/')
	            ],
            ])
            ->add('phone',NumberType::class,[
	            'label' => 'Celular',
	
	            'attr' => ['class'=>'profesor'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profesor::class,
        ]);
    }
}
