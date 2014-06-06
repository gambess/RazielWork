<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FakeMessageType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destinatario', 'text', array('label' => 'GRUPO DESTINO SMS: ',))
            ->add('ticketId', 'text', array('label' => 'RESUELTO ID: ',))
            ->add('cliente', 'text', array('label' => 'CLIENTE: ',))
            ->add('tipo', 'text', array('label' => 'TIPO: ',))
            ->add('tecnico', 'text', array('label' => 'TECNICO: ',))
            ->add('tsol', 'text', array('label' => 'TSOL: ',))
            ->add('fecha', 'date', array(
                'label' => 'FECHA: ',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy H:m:s'
            ))
            ->add('modo', 'text', array('label' => 'MODO RECEPCIÓN: ',))
            ->add('resolucion', 'textarea', array('label' => 'RESOLUCION: '))
            ->add('fechaEnvio')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\FakeMessage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pi2_fractalia_smsbundle_fakemessage';
    }

}
