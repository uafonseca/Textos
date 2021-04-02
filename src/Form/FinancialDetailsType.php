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
                'label' => 'Nombre de la cuenta'
            ])
            ->add('dni',null,[
                'label' => 'Documento de identidad'
            ])
            ->add('acountNumber',null,[
                'label' => 'Número de cuenta'
            ])
            ->add('intitution',null,[
                'label' => 'Institución financiera'
            ])
            ->add('acountType',null,[
                'label' => 'Tipo de cuenta'
            ])
            ->add('contact',null,[
                'label' => 'Contacto'
            ])
            ->add('paypalUrlComplete',TextType::class,[
                'label' => 'Url de pago finalizado'
            ])
            ->add('PaypalUrlCancel',TextType::class,[
                'label' => 'Url de pago cancelado'
            ])
            ->add('paypalHtmlCode',null,[
                'label' => 'Código del botón de PayPal'
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
