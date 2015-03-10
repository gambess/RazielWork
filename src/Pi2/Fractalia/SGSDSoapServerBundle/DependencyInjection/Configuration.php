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
        
        
        /*
         * TREE NODE for mailings
         */

        $rootNode
            ->children()
                ->arrayNode('mails')
                        ->children()
                            ->scalarNode('clean_mail')->end()
                            ->scalarNode('cc_clean_mail')->end()
                            ->scalarNode('backup_mail')->end()
                            ->scalarNode('cc_backup_mail')->end()
                            ->scalarNode('sms_mail')->end()
                            ->scalarNode('cc_sms_mail')->end()
                        ->end()
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
