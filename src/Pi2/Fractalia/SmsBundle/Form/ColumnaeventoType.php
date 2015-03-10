<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColumnaeventoType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('numeroCaso', 'text', array('required' => true))
                ->add('cliente', 'text', array('required' => true) )
                ->add('tipo', 'text', array('required' => true))
                ->add('tecnico', 'text', array('required' => true))
                ->add('tsol', 'text', array('required' => true))
                ->add('fecha', 'datetime', array('required' => true, 'widget' => 'single_text','format'=>'dd/MM/yy H:mm:ss'))
//                ->add('modo', 'text', array('required' => true))
                ->add('detalle', 'text', array('required' => true))
//            ->add('incidencia')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\Columnaevento'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'pi2_fractalia_smsbundle_columnaevento';
    }

}
