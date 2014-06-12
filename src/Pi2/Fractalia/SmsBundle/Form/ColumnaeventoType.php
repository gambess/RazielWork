<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColumnaeventoType extends AbstractType
{
    private $extraLabels = array();

    public function __construct($config = array())
    {
        if (count($config) == 8)
        {
            $this->extraLabels = $config;
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if (count($options['label_attr']) == 0)
        {
            
            $options['label_attr'] = $this->extraLabels;
        }

        $builder
            ->add('numeroCaso', 'text', array(
                'label' => $options['label_attr']['id']
            ))
            ->add('cliente', 'text', array(
                'label' => $options['label_attr']['cliente']
            ))
            ->add('tipo', 'text', array(
                'label' => $options['label_attr']['tipo']
            ))
            ->add('tecnico', 'text', array(
                'label' => $options['label_attr']['tecnico']
            ))
            ->add('tsol', 'text', array(
                'label' => $options['label_attr']['tsol']
            ))
            ->add('fecha', 'datetime', array(
                'label' => $options['label_attr']['fecha'],
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yy hh:mm'
            ))
            ->add('modo', 'text', array(
                'label' => $options['label_attr']['modo']
            ))
            ->add('detalle', 'textarea', array(
                'label' => $options['label_attr']['detalle']
            ))
//            ->add('mensaje')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\Columnaevento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pi2_fractalia_smsbundle_columnaevento';
    }

}
