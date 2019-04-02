<?php

namespace BankinBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BankinExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // The next 2 lines are pretty common to all Extension templates.
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration( $configuration, $configs );

        $container->setParameter( 'bankin.base_uri', $processedConfig[ 'base_uri' ] );
        $container->setParameter( 'bankin.version', $processedConfig[ 'version' ] );
        $container->setParameter( 'bankin.client.id', $processedConfig[ 'client' ][ 'id' ] );
        $container->setParameter( 'bankin.client.secret', $processedConfig[ 'client' ][ 'secret' ] );
    }
}
