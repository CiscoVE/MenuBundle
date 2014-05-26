<?php

namespace CiscoSystems\MenuBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
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
        $definition = $container->getDefinition( 'cisco.twig.menu_extension' );
        // Check if app wants to override access verification service
        $taggedServices = $container->findTaggedServiceIds( 'cisco.menu.access_verifier' );
        foreach ( $taggedServices as $serviceId => $attributes )
        {
            if ( count( $attributes ) > 0 && array_key_exists( 'alias', $attributes[0] ))
            {
                if ( 'default_menu_access_verifier' == $attributes[0]['alias'] ) continue;
            }
            // grab the first non-default access verifier defined and replace the default one with it:
            $definition->replaceArgument( 0, new Reference( $serviceId ));
            break;
        }
        // Get menu configurations from registered bundles
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
//         ladybug_dump_die( $config );
    }
}
