<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pi2_frac_sgsd_soap_server');

        $rootNode
            ->children()
                ->arrayNode('envio_sms')
                        ->children()
                            ->arrayNode('api')
                                ->children()
                                    ->scalarNode('url')->end()
                                    ->scalarNode('apiuser')->end()
                                    ->scalarNode('apipass')->end()
                                    ->scalarNode('remitente')->end()
                                ->end()
                            ->end()
                            ->arrayNode('servicio')
                                    ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('grupo_destino')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('destinatario')->end()
                                        ->scalarNode('dias')->end()
                                        ->scalarNode('desde')->end()
                                        ->scalarNode('hasta')->end()
                                    ->end()
                                ->end()    
                            ->end()
                            ->arrayNode('numero_destino')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('destinatario')->end()
                                        ->scalarNode('dias')->end()
                                        ->scalarNode('desde')->end()
                                        ->scalarNode('hasta')->end()
                                    ->end()
                                ->end()    
                            ->end()
                            ->arrayNode('prioridad')
                                    ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('estado')
                                    ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('nombres_cortos')
                                    ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('traduccion_tipo_caso')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('tsol_guardia')
                                    ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->arrayNode('plantillas')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')->end()
                            ->scalarNode('cliente')->end()
                            ->scalarNode('tipo')->end()
                            ->scalarNode('tecnico')->end()
                            ->scalarNode('tsol')->end()
                            ->scalarNode('fecha')->end()
                            ->scalarNode('modo')->end()
                            ->scalarNode('detalle')->end()
                        ->end()
                    ->end()
                ->end()
            
                ->arrayNode('resumenes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('titulo')->end()
                            ->arrayNode('estados')
                                ->prototype('scalar')->end()
                            ->end()
//                            ->scalarNode('estados')->end()
                        ->end()
                    ->end()
                ->end()
            
            ->end()
        ;

        return $treeBuilder;
    }
}
