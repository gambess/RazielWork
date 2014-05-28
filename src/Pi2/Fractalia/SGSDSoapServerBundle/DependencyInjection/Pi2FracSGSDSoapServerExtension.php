<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Pi2FracSGSDSoapServerExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.api', $config['envio_sms']['api']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.servicio', $config['envio_sms']['servicio']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.grupo_destino', $config['envio_sms']['grupo_destino']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.numero_destino', $config['envio_sms']['numero_destino']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.prioridad', $config['envio_sms']['prioridad']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.estado', $config['envio_sms']['estado']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.nombres_cortos', $config['envio_sms']['nombres_cortos']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.traduccion_tipo_caso', $config['envio_sms']['traduccion_tipo_caso']);
        $container->setParameter('pi2_frac_sgsd_soap_server.envio_sms.tsol_guardia', $config['envio_sms']['tsol_guardia']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

}
