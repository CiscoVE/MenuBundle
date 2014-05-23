<?php

namespace CiscoSystems\MenuBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class MenuCompilerPass implements CompilerPassInterface
{
    protected $kernel;
    protected $parameter;

    public function __construct( $kernel )
    {
        $this->kernel = $kernel;
        $this->parameter = 'cisco.menu.config';
    }

    public function process( ContainerBuilder $container )
    {
        // Menu configurations from registered bundles
        $config = array();
        $yaml = new Parser();
        $bundles = $this->kernel->getBundles();
        foreach ( $bundles as $bundle )
        {
            $file = $bundle->getPath()
                  . DIRECTORY_SEPARATOR . 'Resources'
                  . DIRECTORY_SEPARATOR . 'config'
                  . DIRECTORY_SEPARATOR . 'menu.yml';
            if ( !file_exists( $file )) continue;
            $filterConfig = $yaml->parse( file_get_contents( $file ));
            if ( !is_array( $filterConfig )) continue;
            $config = array_merge( $config, $filterConfig['parameters'][$this->parameter] );
        }
        $container->setParameter( $this->parameter, $config );
//         echo '<pre>';
//         print_r( $config );
//         echo '</pre>';
//         die(); exit;
        // TODO:
        // Check if we want to override the access verifier with an app level service
        // If so, replace first injected parameter of this bundle's Twig extension with it
    }
}
