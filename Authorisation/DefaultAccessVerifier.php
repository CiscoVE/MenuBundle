<?php

namespace CiscoSystems\MenuBundle\Authorisation;

use CiscoSystems\MenuBundle\Authorisation\AuthorisationInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class DefaultAccessVerifier implements AuthorisationInterface
{
    protected $context;
    protected $accessDecisionManager;
    protected $map;
    protected $router;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $context
     * @param \Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface $accessDecisionManager
     * @param \Symfony\Component\Security\Http\AccessMap $map
     * @param \Symfony\Component\Routing\Router $router
     */
    public function __construct(
        TokenStorage $context,
        AccessDecisionManagerInterface $accessDecisionManager,
        AccessMap $map,
        Router $router
    ){
        $this->context = $context;
        $this->accessDecisionManager = $accessDecisionManager;
        $this->map = $map;
        $this->router = $router;
    }

    /**
     * @see \CiscoSystems\MenuBundle\Authorisation\AuthorisationInterface::canAccessRoute()
     */
    public function canAccessRoute( $routeName )
    {
        if ( null === $token = $this->context->getToken() ) return true;
        $path = $this->router->generate( $routeName );
        $baseUrl = $this->router->getContext()->getBaseUrl();
        $path = substr( $path, strlen( $baseUrl ));
        $request = Request::create( $path );
        list( $attributes, $channel ) = $this->map->getPatterns( $request );
        if ( null === $attributes ) return true;
        if ( !$token->isAuthenticated() ) return false;
        return $this->accessDecisionManager->decide( $token, $attributes, $request );
    }
}
