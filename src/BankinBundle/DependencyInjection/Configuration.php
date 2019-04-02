<?php

namespace BankinBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bankin');

        $rootNode
             ->children()
                ->scalarNode('base_uri')->end()
                ->scalarNode('version')->end()
                ->arrayNode('client')
                    ->children()
                        ->scalarNode('id')->end()
                        ->scalarNode('secret')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
