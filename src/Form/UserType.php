<?php

namespace App\Form;

use App\Entity\Canton;
use App\Entity\Provincia;
use App\Entity\Role;
use App\Entity\User;
use App\Form\EventListener\AddCantonFieldListener;
use App\Form\EventListener\AddProvinciaFieldListener;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', EmailType::class)
			->add('country', ChoiceType::class, [
				'label' => 'País',
				'choices' => [
					'Ecuador' => 'Ecuador',
				],
			])
			->add('roles', EntityType::class, [
				'class' => Role::class,
				'query_builder' => function (EntityRepository $rp) {
					return $rp->createQueryBuilder('r')
						->where('r.rolename = :name OR r.rolename = :name2')
						->setParameters(array(
							'name' => Role::ROLE_USER,
							'name2' => Role::ROLE_ADMIN
						));
				},
				'choice_value' => function ($choice) {
					if (is_object($choice))
						return $choice->getId();
				},
				'choice_label' => function ($choice) {
					if (Role::ROLE_USER === $choice->getRolename()) {
						return 'Estudiante';
					} elseif (Role::ROLE_ADMIN === $choice->getRolename()) {
						return 'Docente';
					} else {
						return '';
					}
				},
				'choice_attr' => function ($choice, $key, $value) {
					$arrayClass = ['class' => 'custom-control-input'];
					if (is_object($choice)) {
						if ($choice->getRolename() === Role::ROLE_USER)
							$arrayClass['data'] = 'student';
						elseif ($choice->getRolename() === Role::ROLE_ADMIN)
							$arrayClass['data'] = 'teacher';
						return $arrayClass;
					}
				},
				'label_attr' => ['class' => 'custom-control-label mr-5'],
				'multiple' => false,
				'expanded' => true,
				'label' => 'Registrarse como',
			
			])

			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'first_options' => ['label' => 'Contraseña'],
				'second_options' => ['label' => 'Repita su contraseña']
			])
			->add('name', TextType::class, [
				'label' => 'Nombre(s)'
			])
			->add('firstName', TextType::class, [
				'label' => 'Apellidos'
			])
			->add('username', TextType::class, [
				'label' => 'Nombre de usuario'
			])
			->add('canton', ChoiceType::class, [
				'label' => 'Ciudad',
				'choices' => []
			])
			->add('country', ChoiceType::class, [
				'label' => 'Pais',
				'choices' => []
			])
			->add('provincia', ChoiceType::class, [
				'label' => 'Provincia',
				'choices' => []
			])
			->add('scoholName', TextType::class, [
				'label' => 'Nombre de la institución'
			])
			->add('student', EstudianteType::class)

			->add('profesor', ProfesorType::class);
		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) {

				$form = $event->getForm();

				$data = $event->getData()['provincia'];

				$choices = array();

				if (is_array($data)) {
					foreach ($data as $choice) {
						$choices[$choice] = $choice;
					}
				} else {
					$choices[$data] = $data;
				}
				$form->add('provincia', ChoiceType::class, array('choices' => $choices));
			}
		);
		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) {

				$form = $event->getForm();

				$data = $event->getData()['canton'];

				$choices = array();

				if (is_array($data)) {
					foreach ($data as $choice) {
						$choices[$choice] = $choice;
					}
				} else {
					$choices[$data] = $data;
				}
				$form->add('canton', ChoiceType::class, array('choices' => $choices));
			}
		);

		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) {

				$form = $event->getForm();

				$data = $event->getData()['country'];

				$choices = array();

				if (is_array($data)) {
					foreach ($data as $choice) {
						$choices[$choice] = $choice;
					}
				} else {
					$choices[$data] = $data;
				}
				$form->add('country', ChoiceType::class, array('choices' => $choices));
			}
		);

		$builder->get('roles')
			->addModelTransformer(new CallbackTransformer(
				function ($rolesArray) {
					return count($rolesArray) ? $rolesArray[0] : null;
				},
				function ($rolesString) {
					return [$rolesString];
				}
			));
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
