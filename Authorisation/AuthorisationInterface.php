<?php

namespace CiscoSystems\MenuBundle\Authorisation;

/**
 * Interface to be implemented to show users only
 * those navigation options they have access to
 */
interface AuthorisationInterface
{
    /**
     * Check whether the current user can access a given route
     *
     * @param string $routeName
     *
     * @return boolean
     */
    public function canAccessRoute( $routeName );
}
