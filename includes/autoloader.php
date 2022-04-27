<?php
namespace AXi_System;

/**
 * Autoloader class
 * 
 * @since 1.0.0
 */
class AutoLoader
{
    /**
     * Maps classes to file names.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var array
     */
    private static $_classmap;

    /**
     * Run autoloader.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function run()
    {
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    /**
     * For a given class, check if it exist and load it.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @param string $class_name
     */
    private static function autoload( $class_name )
    {
        if ( 0 !== strpos( $class_name, __NAMESPACE__ . '\\' ) )
        {
            return;
        }
        if ( ! class_exists( $class_name ) )
        {
            $relative_class_name = preg_replace( '/^' . __NAMESPACE__ . '\\\/', '', $class_name );
            self::load_class( $relative_class_name );
        }
    }

    /**
     * For a given class name, require the class file.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @param string $class_name
     */
    private static function load_class( $class_name )
    {
        $class_map = self::get_classmap();
        $filename  = '';
        if ( isset( $class_map[ $class_name ] ) )
        {
            $filename = AXISYS_PATH . $class_map[ $class_name ];
        }
        else {
            $filename = strtolower(
                preg_replace(
                    [ '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                    [ '$1-$2', '-', DIRECTORY_SEPARATOR ],
                    $class_name
                )
            );
            $filename = AXISYS_PATH . $filename . '.php';
        }
        if ( is_readable( $filename ) )
        {
            require $filename;
        }
    }

    /**
     * Get class to file mapped array
     *
     * @since 1.0.0
     * @access public
     * @static
     * 
     * @return array
     */
    public static function get_classmap()
    {
        if ( ! self::$_classmap )
        {
            self::generate_classmap();
        }
        return self::$_classmap;
    }
    
    /**
     * Generate class-to-file mapped
     * 
     * @since 1.0.0
     * @access private
     * @static
     * 
     * @return void
     */
    private static function generate_classmap()
    {
        self::$_classmap = [
            'Admin'           => 'admin/admin.php',
            'FrM_Extra'       => 'includes/frm-extra.php',
            'Post_Meta_Boxes' => 'admin/post-meta-boxes.php',
            'Term_Meta_Boxes' => 'admin/term-meta-boxes.php',

            'CPT'                 => 'includes/cpt.php',
            'Image_Slider_Widget' => 'includes/wp-widgets/image-slider.php',
            'Promotion_Widget'    => 'includes/wp-widgets/promotion-slider.php',

            'Elementor_Addons'                  => 'includes/elementor-addons.php',
            'Elementor\Widget_Accordion'        => 'includes/elementor-widgets/accordion.php',
            'Elementor\Widget_Banner'           => 'includes/elementor-widgets/banner.php',
            'Elementor\Widget_Banner_Carousel'  => 'includes/elementor-widgets/banner-carousel.php',
            'Elementor\Widget_SBanner'          => 'includes/elementor-widgets/sbanner.php',
            'Elementor\Widget_CDLButton'        => 'includes/elementor-widgets/cdlbutton.php',
            'Elementor\Widget_Comment_Text'     => 'includes/elementor-widgets/comment-text.php',
            'Elementor\Widget_Comparison_Table' => 'includes/elementor-widgets/comparison-table.php',
            'Elementor\Widget_Course_List'      => 'includes/elementor-widgets/course-list.php',
            'Elementor\Widget_Discipline_List'  => 'includes/elementor-widgets/discipline-list.php',
            'Elementor\Widget_Feedbacks'        => 'includes/elementor-widgets/feedbacks.php',
            'Elementor\Widget_Icon'             => 'includes/elementor-widgets/icon.php',
            'Elementor\Widget_ImageCarousel'    => 'includes/elementor-widgets/imagecarousel.php',
            'Elementor\Widget_Instructors'      => 'includes/elementor-widgets/instructors.php',
            'Elementor\Widget_MCourses'         => 'includes/elementor-widgets/mcourses.php',
            'Elementor\Widget_Media_Cards'      => 'includes/elementor-widgets/media-cards.php',
            'Elementor\Widget_NavMenu'          => 'includes/elementor-widgets/navmenu.php',
            'Elementor\Widget_NavMenu_Primary'  => 'includes/elementor-widgets/navmenu-primary.php',
            'Elementor\Widget_NavMenu_Aside'    => 'includes/elementor-widgets/navmenu-aside.php',
            'Elementor\Widget_NavMenu_Side'     => 'includes/elementor-widgets/navmenu-side.php',
            'Elementor\Widget_Organisations'    => 'includes/elementor-widgets/organisations.php',
            'Elementor\Widget_Stars'            => 'includes/elementor-widgets/stars.php',
            'Elementor\Widget_StudyModes'       => 'includes/elementor-widgets/studymodes.php',
            'Elementor\Widget_CourseAttributes' => 'includes/elementor-widgets/course-attributes.php',
            'Elementor\Widget_FormidableForm'   => 'includes/elementor-widgets/formidable-form.php',

            'Elementor\Conditions\Page_Template_Course'     => 'includes/elementor-conditions/page-template-course.php',
            'Elementor\Conditions\Page_Template_Discipline' => 'includes/elementor-conditions/page-template-discipline.php',
            'Elementor\Conditions\Page_Template_Home'       => 'includes/elementor-conditions/page-template-home.php',
            'Elementor\Conditions\Page_Template_Landing'    => 'includes/elementor-conditions/page-template-landing.php',
            'Elementor\Conditions\Page_Template_Modality'   => 'includes/elementor-conditions/page-template-modality.php',
			
            'Elementor\Control_Sortable_Select' => 'includes/elementor-controls/sortable-select.php',

            'Formidable_Addons'                  => 'includes/formidable-addons.php',
            'Formidable\Field_Discipline'        => 'includes/formidable-fields/discipline.php',
            'Formidable\Field_Linked_Discipline' => 'includes/formidable-fields/linked-discipline.php',
            'Formidable\Field_Discipline_Link'   => 'includes/formidable-fields/discipline-link.php',
            'Formidable\Field_Course_Type'       => 'includes/formidable-fields/course-type.php',
            'Formidable\Field_Delivery_Mode'     => 'includes/formidable-fields/delivery-mode.php',

            'Shortcodes'  => 'includes/shortcodes.php',
            'Location'    => 'includes/location.php',
            'API_Request' => 'includes/api-request.php'
        ];
    }
}