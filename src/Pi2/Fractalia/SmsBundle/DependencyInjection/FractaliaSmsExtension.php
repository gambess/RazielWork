<?php

namespace Pi2\Fractalia\SmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FractaliaSmsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        /*
         * Setter de los parametros de configuracion del fichero sms_manager.yml
         */
        $container->setParameter('fractalia_sms.envio_sms.api', $config['envio_sms']['api']);
        $container->setParameter('fractalia_sms.envio_sms.grupo_destino', $config['envio_sms']['grupo_destino']);
        $container->setParameter('fractalia_sms.envio_sms.numero_destino', $config['envio_sms']['numero_destino']);
        $container->setParameter('fractalia_sms.envio_sms.servicios_soc', $config['envio_sms']['servicios_soc']);
        $container->setParameter('fractalia_sms.envio_sms.nombres_cortos', $config['envio_sms']['nombres_cortos']);
        $container->setParameter('fractalia_sms.envio_sms.traduccion_tipo_caso', $config['envio_sms']['traduccion_tipo_caso']);
        $container->setParameter('fractalia_sms.envio_sms.tsol_guardia', $config['envio_sms']['tsol_guardia']);
        $container->setParameter('fractalia_sms.eventos', $config['eventos']);
        $container->setParameter('fractalia_sms.plantillas', $config['plantillas']);
        $container->setParameter('fractalia_sms.resumenes.resumen', $config['resumenes']['RESUMEN']);
     
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
