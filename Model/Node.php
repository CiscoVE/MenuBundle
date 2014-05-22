<?php

namespace CiscoSystems\MenuBundle\Model;

class Node
{
    protected $label;
    protected $title;
    protected $route;
    protected $classes;
    protected $children;
    protected $icon;

    public function __construct( $label, $route, $classes = array(), $children = array(), $icon = null, $title = null )
    {
        $this->label = $label;
        $this->route = $route;
        $this->classes = $classes;
        $this->children = $children;
        $this->icon = $icon;
        $this->title = $title;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel( $label )
    {
        $this->label = $label;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle( $title )
    {
        $this->title = $title;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute( $route )
    {
        $this->route = $route;

        return $this;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function setClasses( $classes = array() )
    {
        $this->classes = $classes;

        return $this;
    }

    public function addClass( $class )
    {
        if ( !in_array( $class, $this->classes ))
        {
            $this->classes[] = $class;
        }
    }

    public function removeClass( $class )
    {
        if ( in_array( $class, $this->classes ) )
        {
            unset( $this->classes[array_search( $class, $this->classes )]);
        }
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon( $icon )
    {
        $this->icon = $icon;

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren( $children )
    {
        $this->children = $children;

        return $this;
    }

    public function addChild( Node $node = null )
    {
        if ( null !== $node && !array_key_exists( $node->getKey(), $this->children ))
        {
            $this->children[] = $node ;
            return $this;
        }
        return false;
    }
}