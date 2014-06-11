<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MensajeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('templatename')
            ->add('texto', new PlantillaType())
//            ->add('estadoConstruccion')
//            ->add('bitacora')
//            ->add('fechaCreacion')
//            ->add('fechaActualizacion')
//            ->add('fechaInsercionSms')
//            ->add('incidencia')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\Mensaje'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pi2_fractalia_smsbundle_mensaje';
    }
}
