MenuBundle
==========

Symfony 2 bundle for dynamic menus

## Override the access verification

Usually not every user should see all navigation options, i.e. routes that they
have no access to should not show up in menus. Per default this bundle comes
with a fairly standard way of achieving that which will "just work" if your
Symfony project uses the standard means of securing URL patterns by hardcoding
roles for them in your security.yml.

If you have a custom access control mechanism you can easily override this
behaviour. Simply create a new class implementing the following interface:

    CiscoSystems\MenuBundle\Authorisation\AuthorisationInterface

It has one public method that you need to implement, which takes the name of a
route as its parameter and returns TRUE or FALSE, depending on whether the
currently logged on user has access to this route.

To make the menu bundle aware of your custom access verification, declare it as
a service with the tag `cisco.menu.access_verifier` and inject whatever you need
to inject into it in order to enable it to make its decision, like so:


    my_custom_access_verifier_service:
        class: MySecurityBundle\Authorisation
        arguments:
            - '@some.service.i.want.to.inject'
            - '%some.parameter.i.also.want.to.inject%'
        tags:
            - { name: cisco.menu.access_verifier }

TODO:
- remove project references from CSS file
- make CSS look better
