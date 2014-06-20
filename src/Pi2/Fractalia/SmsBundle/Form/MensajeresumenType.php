<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Pi2\Fractalia\SmsBundle\Form\MensajeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Pi2\Fractalia\SmsBundle\Entity\Columnaresumen;

class MensajeresumenType extends MensajeType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('columnaResumen', 'collection', array('type' => new ColumnaresumenType()));
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
