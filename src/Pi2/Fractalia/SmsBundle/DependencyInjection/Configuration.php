<?php

namespace Pi2\Fractalia\SmsBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('fractalia_sms');
   /*
    * TREE NODE para el archivo de configuración sms_manager del fractalia_sms bundle
    * 
    */     
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
                                    ->scalarNode('ip')->end()
                                    ->scalarNode('port')->end()
                                ->end()
                            ->end()
                            ->arrayNode('numero_destino')
                                ->prototype('array')
                                    ->prototype('scalar')->end()
                                ->end()    
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
                            ->arrayNode('servicios_soc')
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
                            ->arrayNode('traduccion_matricula_tecnico')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->arrayNode('eventos')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('tipo_accion')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('actual')->end()
                                        ->scalarNode('previa')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('prioridad')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('actual')->end()
                                        ->scalarNode('previas')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('estado')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                            ->prototype('scalar')->end()
                                ->end()
                            ->end()
                            ->arrayNode('grupo_origen_IN')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('actual')->end()
                                        ->scalarNode('previos')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('grupo_origen_NOT')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('grupo_destino_IN')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('grupo_destino_NOT')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('filtro_titulo')
                                    ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('filtro_titulo_NOT')
                                    ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('tecnico_inicial')
                                    ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('tecnico_final')
                                    ->prototype('scalar')->end()
                            ->end()
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
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
