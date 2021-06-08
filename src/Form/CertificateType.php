<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Certificate;
use App\Entity\Company;
use App\Entity\Level;
use App\Form\FileUpload\ImageType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
                'label' => 'Tipo de certificado',
                'placeholder' => '--Seleccione--',
                'choices' => [
                    Certificate::TYPE_DEFAULT => Certificate::TYPE_DEFAULT,
                    Certificate::TYPE_PARTICIPATION => Certificate::TYPE_PARTICIPATION,
                    Certificate::TYPE_CAPACITATION => Certificate::TYPE_CAPACITATION,
                    Certificate::TYPE_APPROBATION => Certificate::TYPE_APPROBATION,
                    Certificate::TYPE_DIPLOMA => Certificate::TYPE_DIPLOMA,
                ],
            ])
            ->add('modality', EntityType::class,[
                'label' => 'Modalidad',
                'class' => Level::class,
                'placeholder' => '--Seleccione--',
                'query_builder' => function(EntityRepository $repository){
                    return $repository->createQueryBuilder('m');
                }
            ])
            ->add('hours', null,[
                'label' => 'Horas de duración'
            ])
            ->add('startDate',null,[
                'label' => 'Fecha inicial',
                'html5' => false,
                'placeholder' => 'dd-mm-yyyy',
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                // 'required' => false
            ])
            ->add('endDate',null,[
                'label' => 'Fecha final',
                'html5' => false,
                'placeholder' => 'dd-mm-yyyy',
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                // 'required' => false
            ])
            ->add('representative',null,[
                'label' => 'Representante empresa'
            ])
            ->add('representativePosition',null, [
                'label' => 'Cargo'
            ])
            ->add('trainerName',null, [
                'label' => 'Nombre del capacitador'
            ])
            ->add('trainerPosition',null, [
                'label' => 'Cargo'
            ])
            ->add('containsResolution',null, [
                'label' => 'Contiene resolucion',
                'required' => false
            ])
            ->add('company',null, [
                'label' => 'Empresa que certifica',
                'class' => Company::class,
                'placeholder' => '--Seleccione--',
                'query_builder' => function(EntityRepository $repository){
                    return $repository->createQueryBuilder('m');
                }
            ])
            ->add('course',null, [
                'label' => 'Curso',
                'class' => Book::class,
                'placeholder' => '--Seleccione--',
                'query_builder' => function(EntityRepository $repository){
                    return $repository->createQueryBuilder('m');
                }
            ])
            ->add('logo',ImageType::class,[
                'label' => 'Logo',
                'required' => false
            ])
            ->add('firm',ImageType::class,[
	            'label'=>'Firma del capacitador',
	            'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Certificate::class,
        ]);
    }
}
