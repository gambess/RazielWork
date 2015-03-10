<?php

namespace Pi2\Fractalia\SmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NombrecortoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
//            ->add('fechaCreacion')
//            ->add('fechaModificacion')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pi2\Fractalia\SmsBundle\Entity\Nombrecorto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pi2_fractalia_smsbundle_nombrecorto';
    }
}
