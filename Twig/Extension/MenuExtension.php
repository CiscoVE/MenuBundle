<?php

namespace CiscoSystems\MenuBundle\Twig\Extension;

use Twig_Extension;
use Twig_Function_Method;
use Twig_Environment;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use CiscoSystems\MenuBundle\Model\Node;

class MenuExtension extends Twig_Extension
{
    protected $context;
    protected $accessDecisionManager;
    protected $map;
    protected $router;
    protected $currentRoute;
    protected $twig;
    protected $template;
    protected $configuration;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $context
     * @param \Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface $accessDecisionManager
     * @param \Symfony\Component\Security\Http\AccessMap $map
     * @param \Symfony\Component\Routing\Router $router
     * @param \Twig_Environment $twig
     * @param array $configuration
     */
    public function __construct(
        SecurityContextInterface $context,
        AccessDecisionManagerInterface $accessDecisionManager,
        AccessMap $map,
        Router $router,
        Twig_Environment $twig,
        $configuration
    ){
        $this->context = $context;
        $this->accessDecisionManager = $accessDecisionManager;
        $this->map = $map;
        $this->router = $router;
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
        $token = $this->attainToken( $this->context );
        $rootNode = $this->buildNode( $this->configuration[$key], $token );
        if ( $rootNode )
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
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return null|\CiscoSystems\MenuBundle\Model\Node
     */
    protected function buildNode( array $config, TokenInterface $token )
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
            // This may not yet work properly, general idea taken from
            // https://groups.google.com/forum/#!topic/symfony2/HbyMIPqPdvs
            $path = $this->router->generate( $route );
            $baseUrl = $this->router->getContext()->getBaseUrl();
            $path = substr( $path, strlen( $baseUrl ));
            $request = Request::create( $path );
            list( $attributes, $channel ) = $this->map->getPatterns( $request );
            if ( is_array( $attributes )) // this is a bit fishy, what about anonymous users?
            {
                $access = $this->accessDecisionManager->decide( $token, $attributes, $request );
                if ( !$access ) return null;
            }
        }
        // Do the object building
        $children = array();
        if( array_key_exists( 'items', $config ))
        {
            foreach( $config['items'] as $key => $item )
            {
                $children[$key] = $this->buildNode( $item, $token );
            }
        }
        return new Node( $label, $route, $classes, $children, $icon, $title );
    }

    protected function attainToken( SecurityContextInterface $context )
    {
        $token = $this->context->getToken();
        if ( null !== $token ) return $token;
        return new AnonymousToken( "anonymous", "anony-mouse" );
    }
}
