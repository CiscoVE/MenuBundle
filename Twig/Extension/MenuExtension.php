<?php

namespace CiscoSystems\MenuBundle\Twig\Extension;

use Twig_Extension;
use Twig_Function_Method;
use Twig_Environment;
use CiscoSystems\MenuBundle\Authorisation\AuthorisationInterface;
use CiscoSystems\MenuBundle\Model\Node;

class MenuExtension extends Twig_Extension
{
    protected $accessVerifier;
    protected $twig;
    protected $template;
    protected $configuration;

    /**
     * Constructor
     *
     * @param \CiscoSystems\MenuBundle\Authorisation\AuthorisationInterface $accessVerifier
     * @param \Twig_Environment $twig
     * @param array $configuration
     */
    public function __construct(
        AuthorisationInterface $accessVerifier,
        Twig_Environment $twig,
        $configuration
    ){
        $this->accessVerifier = $accessVerifier;
        $this->twig = $twig;
        $this->configuration = $configuration;
    }

    /**
     * Get functions offered by extension
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'render_menu' => new Twig_Function_Method( $this, 'renderMenu', array( 'is_safe' => array( 'html' ), )),
        );
    }

    /**
     * Get name of extension
     *
     * @return string
     */
    public function getName()
    {
        return 'menu_extension';
    }

    /**
     * Render a menu
     *
     * @param string $menu
     * @param string $type
     */
    public function renderMenu( $key = "", $type = "" )
    {
        if ( !array_key_exists( $key, $this->configuration ))
        {
            $keys = array();
            foreach ( array_keys( $this->configuration ) as $k ) $keys[] = '`' . $k . '`';
            $msg = 'Specified key `' . $key . '` does not exist in menu configuration: ' . join( ', ', $keys );
            throw new \InvalidArgumentException( $msg );
        }
        $rootNode = $this->buildNode( $this->configuration[$key] );
        if ( null !== $rootNode )
        {
            $globals = $this->twig->getGlobals();
            $block = $this->twig
                          ->loadTemplate( 'CiscoSystemsMenuBundle::menu.html.twig' )
                          ->renderBlock( $type, array_merge( $globals, array( 'root' => $rootNode )));
            if ( $block )
            {
                return $block;
            }
        }
        return 'No menu could be displayed';
    }

    /**
     * Build markup object tree
     *
     * @param array $config
     * @return null|\CiscoSystems\MenuBundle\Model\Node
     */
    protected function buildNode( array $config )
    {
        // Check the config vars exist
        $label =   ( array_key_exists( 'label',   $config )) ? $config['label']   : null;
        $route =   ( array_key_exists( 'route',   $config )) ? $config['route']   : null;
        $classes = ( array_key_exists( 'classes', $config )) ? $config['classes'] : null;
        $icon =    ( array_key_exists( 'icon',    $config )) ? $config['icon']    : null;
        $title =   ( array_key_exists( 'title',   $config )) ? $config['title']   : null;
        // Do some route access checking
        if ( $route )
        {
            if ( !$this->accessVerifier->canAccessRoute( $route )) return null;
        }
        // Do the object building
        $node = new Node( $label, $route, $classes, $icon, $title );
        if( array_key_exists( 'items', $config ))
        {
            foreach( $config['items'] as $key => $item )
            {
                $node->addChild( $this->buildNode( $item ), $key );
            }
        }
        return $node;
    }
}
