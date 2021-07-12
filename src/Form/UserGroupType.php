<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\UserGroup;
use App\Form\FileUpload\ImageType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserGroupType extends AbstractType
{

    private TokenStorageInterface $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupName', TextType::class, [
                'label' => 'Nombre del grupo'
            ])
            ->add('startDate', DateTimePickerType::class, [
                'label' => 'Fecha de inicio',
                'html5' => false,
                'required' => true
            ])
            ->add('modality', null, [
                'label' => 'Modalidad',
                'required' => true
            ])
            ->add('course', EntityType::class, [
                'label' => 'Curso',
                'required' => true,
                'class' => Book::class,
                'query_builder' => function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('e')
                        ->where('e.createdBy=:loggedUser')
                        ->setParameter('loggedUser', $this->tokenStorageInterface->getToken()->getUser())
                    ;
                },
            ])
            ->add('details', null, [
                'label' => 'Detalles del curso',
                'required' => false
            ])
            ->add('chatDate', null, [
                'label' => 'Fecha',
                'html5' => false,
                'placeholder' => 'dd-mm-yyyy',
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('hour', null, [
                'label' => 'Hora',
                'required' => false
            ])
            ->add('videoLink', TextType::class, [
                'label' => 'Incluir invitación',
                'required' => false
            ])
            ->add('file', ImageType::class, [
                'label' => 'Excel',
                'mapped' => false,
                'required' => !$options['edit']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserGroup::class,
        ]);
        $resolver->setRequired('edit');
    }
}
