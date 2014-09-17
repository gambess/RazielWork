<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SmsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destinatario', 'text', array(
                'label' => 'DESTINARIO: ',
                'required'=> true,
            ))
            ->add('remitente', 'text', array(
                'label' => 'REMITENTE: ',
                'required'=> true,
            ))
            ->add('estadoEnvio', 'text', array(
                'label' => 'ESTADO: ',
                'required'=> true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\Sms'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sms';
    }
}
