<?php
namespace AXi_System;

/**
 * Formidable additional stuffs.
 * 
 * @since 1.0.0
 */
class Formidable_Addons
{
    /**
     * Class instance.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var self
     */
    private static $_instance = null;
    
    /**
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return self An instance of the class.
     */
    public static function instance()
    {
        if ( is_null( self::$_instance ) )
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        Formidable\Field_Course_Type::instance();
        Formidable\Field_Delivery_Mode::instance();
        Formidable\Field_Discipline::instance();
        Formidable\Field_Discipline_Link::instance();
        Formidable\Field_Linked_Discipline::instance();
    }
}