<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $rootNode
             ->children()
                ->arrayNode('contact')
                    ->children()
                        ->scalarNode('customer')->end()
                        ->scalarNode('prod')->end()
                        ->scalarNode('admin')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
