<?php

namespace Pi2\Fractalia\SGSDWebMonitorBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('pi2_frac_sgsd_web_monitor');
        
        $rootNode
                ->children()
                     ->arrayNode('servicios')
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->arrayNode('buzones')
                                    ->prototype('scalar')->end()
                                ->end()              
                                ->arrayNode('categorias')
                                ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->arrayNode('alarma')
                                                ->children()
                                                    ->scalarNode('max_time')->end()
                                                    ->arrayNode('exclude_buzones')
                                                        ->prototype('scalar')->end()        
                                                    ->end()
                                                ->end()
                                            ->end()
                                            ->arrayNode('filter_buzones')
                                                ->prototype('scalar')->end()        
                                            ->end()
                                            ->arrayNode('prioridad')
                                                ->prototype('scalar')->end()
                                            ->end() 
                                            ->arrayNode('campos')
                                                ->prototype('scalar')->end()                            
                                            ->end()
                                            ->arrayNode('condiciones')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('campo')->end()
                                                        ->scalarNode('valor')->end()
                                                        ->scalarNode('operacion')->end()
                                                    ->end()
                                                ->end()                            
                                            ->end()
                                            ->arrayNode('clientes_criticos')
                                                ->prototype('scalar')->end()
                                            ->end()
                                            ->arrayNode('intervalo_horario')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('dia')->end()
                                                        ->scalarNode('desde')->end()
                                                        ->scalarNode('hasta')->end()
                                                    ->end()
                                                ->end()    
                                            ->end() 
                                        ->end()
                                    ->end()
                                ->end()                                                
                            ->end()                        
                        ->end()
                     ->end()
                     ->scalarNode('actualiza_web_segundos')->end()
                ->end()
                               
        ;
                    

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
