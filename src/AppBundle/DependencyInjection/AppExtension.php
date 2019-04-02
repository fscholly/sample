<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // The next 2 lines are pretty common to all Extension templates.
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration( $configuration, $configs );

        // Mailer
        $container->setParameter( 'app.contact.customer', $processedConfig[ 'contact' ][ 'customer' ] );
        $container->setParameter( 'app.contact.prod', $processedConfig[ 'contact' ][ 'prod' ] );
        $container->setParameter( 'app.contact.admin', $processedConfig[ 'contact' ][ 'admin' ] );
    }
}
