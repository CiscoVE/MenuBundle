<?php

namespace CiscoSystems\MenuBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use CiscoSystems\MenuBundle\DependencyInjection\MenuCompilerPass;

class CiscoSystemsMenuBundle extends Bundle
{
    protected $kernel;

    public function __construct( KernelInterface $kernel )
    {
        $this->kernel = $kernel;
    }

    public function build( ContainerBuilder $container )
    {
        parent::build( $container );
        $container->addCompilerPass( new MenuCompilerPass( $this->kernel ));
    }
}
