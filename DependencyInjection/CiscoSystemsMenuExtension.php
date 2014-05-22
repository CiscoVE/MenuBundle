<?php

namespace CiscoSystems\MenuBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CiscoSystemsMenuExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load( array $configs, ContainerBuilder $container )
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration( $configuration, $configs );
        $loader = new Loader\YamlFileLoader( $container, new FileLocator( __DIR__ . '/../Resources/config' ));
        $loader->load( 'services.yml' );
    }
}
