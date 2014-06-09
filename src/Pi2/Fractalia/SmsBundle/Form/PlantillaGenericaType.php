<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlantillaGenericaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('casoId', 'text', array('label'=> 'ID') )
            ->add('cliente', 'text', array('label'=> 'CLIENTE'))
            ->add('tipo', 'text', array('label'=> 'TIPO'))
            ->add('tecnico', 'text', array('label'=> 'TECNICO'))
            ->add('tsol', 'text', array('label'=> 'TSOL'))
            ->add('fechaIncidencia', 'text', array('label'=> 'FECHA'))
            ->add('modo', 'text', array('label'=> 'MODO RECEPCION'))
            ->add('detalle', 'text', array('label'=> 'DETALLE'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\Plantilla'
//        ));
        $resolver->setDefaults(array(
            'virtual' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'generic';
    }
}
