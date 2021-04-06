<?php

namespace App\Form;

use App\Entity\FinancialDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinancialDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('acountName',null,[
                'label' => 'Nombre de la cuenta',
                'required' => true,
            ])
            ->add('dni',null,[
                'label' => 'Documento de identidad',
                'required' => true,
            ])
            ->add('acountNumber',null,[
                'label' => 'Número de cuenta',
                'required' => true,
            ])
            ->add('intitution',null,[
                'label' => 'Institución financiera',
                'required' => true,
            ])
            ->add('acountType',null,[
                'label' => 'Tipo de cuenta',
                'required' => true,
            ])
            ->add('contact',null,[
                'label' => 'Contacto',
                'required' => true,
            ])
            ->add('paypalUrlComplete',TextType::class,[
                'label' => 'Url de pago finalizado',
                'required' => true,
            ])
            ->add('PaypalUrlCancel',TextType::class,[
                'label' => 'Url de pago cancelado',
                'required' => true,
            ])
            ->add('paypalHtmlCode',null,[
                'label' => 'Código del botón de PayPal',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinancialDetails::class,
        ]);
    }
}
