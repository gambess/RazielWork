<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SmseventoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destinatario')
            ->add('remitente')
//            ->add('mensajeTexto', new ColumnaeventoType($options['label_attr']) )
            ->add('mensajeTexto')
//            ->add('estado')
//            ->add('respuestaEnvio')
            ->add('log')
//            ->add('fechaCreacion')
//            ->add('fechaActualizacion')
//            ->add('fechaEnvio')
//            ->add('mensaje')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\Smsevento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pi2_fractalia_smsbundle_smsevento';
    }
}
