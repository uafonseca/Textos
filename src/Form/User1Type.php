<?php

namespace App\Form;

use App\Entity\Canton;
use App\Entity\Country;
use App\Entity\Provincia;
use App\Entity\State;
use App\Entity\User;
use App\Form\FileUpload\ImageType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => 'País',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('country');
                },
                'choice_label' => 'name'
            ])
	        ->add ('name', TextType::class, [
		        'label' => 'Nombre(s)'
	        ])
	        ->add ('firstName', TextType::class, [
		        'label' => 'Primer Apellido'
	        ])
	        // ->add ('canton', null, [
		    //     'label' => 'Ciudad',
            //     'required' => false
	        // ])
            ->add('city', TextType::class,[
                // 'disabled' => true,
                'label' => 'Provincia',
            ])
	        ->add ('scoholName', TextType::class, [
		        'label' => 'Nombre de la institución'
	        ])
	        ->add ('Guardar', SubmitType::class)
        ;
        $builder->addEventListener(FormEvents::PRE_SUBMIT,[$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $user = $event->getData();
        $form = $event->getForm();

        if (!$user) {
            return;
        }

        $form->add('city',EntityType::class,[
            'class' => State::class,
            'label' => 'Provincia',
            'query_builder' => function(EntityRepository $repository) use ($user){
                return $repository->createQueryBuilder('entity')
                    ->where('entity.contry =:c')
                    ->setParameter('c',$user['country']);
            },
            'choice_label' => 'name'
        ]);

        $event->setData($user);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
