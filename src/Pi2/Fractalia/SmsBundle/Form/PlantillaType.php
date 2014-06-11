<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlantillaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('cliente')
            ->add('tipo')
            ->add('tecnico')
            ->add('tsol')
            ->add('fecha')
            ->add('modo')
            ->add('detalle')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Pi2\Fractalia\SmsBundle\Util\PlantillaGenerica'
//        ));
//    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'plantilla_generica';
    }
}
