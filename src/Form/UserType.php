<?php

namespace App\Form;

use App\Entity\Canton;
use App\Entity\Country;
use App\Entity\Provincia;
use App\Entity\Role;
use App\Entity\State;
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
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => 'País',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('country');
                },
                'choice_label' => 'name'
            ])
            ->add('city', TextType::class,[
                'disabled' => true,
                'label' => 'Ciudad'
            ])
            ->add('roles', EntityType::class, [
                'class' => Role::class,
                'query_builder' => function (EntityRepository $rp) {
                    return $rp->createQueryBuilder('r')
                        ->where('r.rolename = :name OR r.rolename = :name2')
                        ->setParameters(array(
                            'name' => Role::ROLE_USER,
                            'name2' => Role::ROLE_DOCENTE
                        ));
                },
                'choice_value' => function ($choice) {
                    if (is_object($choice))
                        return $choice->getId();
                },
                'choice_label' => function ($choice) {
                    if (Role::ROLE_USER === $choice->getRolename()) {
                        return 'Estudiante';
                    } elseif (Role::ROLE_DOCENTE === $choice->getRolename()) {
                        return 'Docente';
                    }
                },
                'choice_attr' => function ($choice, $key, $value) {
                    $arrayClass = ['class' => 'custom-control-input'];
                    if (is_object($choice)) {
                        if ($choice->getRolename() === Role::ROLE_USER)
                            $arrayClass['data'] = 'student pull-left';
                        elseif ($choice->getRolename() === Role::ROLE_DOCENTE)
                            $arrayClass['data'] = 'teacher pull-right';
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
                'second_options' => ['label' => 'Repita su contraseña'],
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
            // ->add('canton', TextType::class, [
            //     'label' => 'Cantón',
            // ])
            ->add('scoholName', TextType::class, [
                'label' => 'Institución'
            ])
//            ->add('student', EstudianteType::class)
            ->add('profesor', ProfesorType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT,[$this, 'onPreSetData']);

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
