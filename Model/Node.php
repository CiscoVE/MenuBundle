<?php

namespace CiscoSystems\MenuBundle\Model;

class Node
{
    protected $label;
    protected $route;
    protected $classes;
    protected $icon;
    protected $title;
    protected $children;

    /**
     * Constructor
     *
     * @param string $label
     * @param string $route
     * @param array $classes
     * @param string $icon
     * @param string $title
     */
    public function __construct( $label = "", $route = "", $classes = array(), $icon = "", $title = "" )
    {
        $this->label = $label;
        $this->route = $route;
        $this->classes = $classes;
        $this->icon = $icon;
        $this->title = $title;
        $this->children = array();
    }

    /**
     * Get HTML node text
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set HTML node text
     *
     * @param string $label
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function setLabel( $label )
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get HTML title attribute
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set HTML title attribute
     *
     * @param string $title
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function setTitle( $title )
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get route name
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route name
     *
     * @param string $route
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function setRoute( $route )
    {
        $this->route = $route;
        return $this;
    }

    /**
     * Get all CSS classes
     *
     * @return array $classes
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Set all CSS classes
     *
     * @param array $classes
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function setClasses( $classes = array() )
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * Add one CSS class
     *
     * @param string $class
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function addClass( $class )
    {
        if ( !in_array( $class, $this->classes ))
        {
            $this->classes[] = $class;
        }
        return $this;
    }

    /**
     * Remove one CSS class
     *
     * @param string $class
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function removeClass( $class )
    {
        if ( in_array( $class, $this->classes ) )
        {
            unset( $this->classes[array_search( $class, $this->classes )]);
        }
        return $this;
    }

    /**
     * Get the icon URI
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the icon URI
     *
     * @param string $icon
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function setIcon( $icon )
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Get all child nodes
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set all child nodes
     *
     * @param array $children
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function setChildren( $children )
    {
        $this->children = $children;
        return $this;
    }

    /**
     * Add one child node
     *
     * @param \CiscoSystems\MenuBundle\Model\Node $node
     * @param string $key
     *
     * @return \CiscoSystems\MenuBundle\Model\Node
     */
    public function addChild( Node $node = null, $key = "" )
    {
        if ( null !== $node && !array_key_exists( $key, $this->children ))
        {
            $this->children[$key] = $node;
        }
        return $this;
    }
}