<?php
namespace AXi_System;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
{
    exit;
}

/**
 * AXi System plugin.
 *
 * @since 1.0.0
 */
class Plugin
{
    /**
     * Plugin instance.
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
            do_action( 'axi-system/loaded' );
        }
        return self::$_instance;
    }

    /**
     * Register autoloader.
     *
     * @since 1.0.0
     * @access private
     */
    private function register_autoloader()
    {
        require AXISYS_PATH . 'includes/autoloader.php';
        Autoloader::run();
    }

    /**
     * Load components
     *
     * @return void
     */
    private function load_components()
    {
        Admin::instance();
        CPT::instance();
        Elementor_Addons::instance();
        Formidable_Addons::instance();
        Shortcodes::instance();
        Location::instance();
        API_Request::instance();
    }
    
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @access private
     */
    private function __construct()
    {
        require AXISYS_PATH . 'includes/helpers.php';
        $this->register_autoloader();
        $this->load_components();

        add_action( 'init', [ $this, 'init' ], 0 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'widgets_init', [ $this, 'widgets_init' ] );

        add_action( 'wp', [ $this, 'custom_404_page_redirect' ] );

        add_filter( 'theme_page_templates', [ $this, 'supported_page_templates' ], 10, 4 );
    }

    /**
     * Initialize plugin functionality
     *
     * @since 1.0.0
     * @access public
     */
    public function init()
    {
        if ( get_option( 'axisys_version' ) != AXISYS_VERSION )
        {
            update_option( 'axisys_version', AXISYS_VERSION );
        }
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    function enqueue_scripts() {}

    /**
     * Register widgets and siderbars
     *
     * @return void
     */
    function widgets_init()
    {
        register_widget( '\AXi_System\Image_Slider_Widget' );
        register_widget( '\AXi_System\Promotion_Widget' );
        register_sidebar( [
            'name'          => esc_html__( 'Mobile Header Promotion', 'academyxi' ),
            'id'            => 'mobile-nav-promotion',
            'description'   => esc_html__( 'Add widgets here.', 'academyxi' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ] );
    }

    /**
     * Set 404 status if user visit our custom 404 page which is used for real 404.
     *
     * @return void
     */
    function custom_404_page_redirect()
    {
        if ( ! is_page() )
        {
            return;
        }

        $page_id = get_the_ID();
        $custom_404 = (int)axisys_get_opt( 'custom_404_page_id' );
        
        if ( $page_id == $custom_404 )
        {
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            nocache_headers();
        }
    }

    /**
     * Additional supported template.
     * 
     * @param string[] $templates
     * @param \WP_Theme $wp_theme
     * @param \WP_Post|null $post
     * @param string $post_type
     * @return array
     */
    function supported_page_templates( $templates, $wp_theme, $post, $post_type )
    {
        $post = get_post( $post );

        $templates = [
            AXISYS_TMPL_HOME       => esc_html__( 'AXi System - Home', 'axi-system' ),
            AXISYS_TMPL_LANDING    => esc_html__( 'AXi System - Landing', 'axi-system' ),
            AXISYS_TMPL_MODALITY   => esc_html__( 'AXi System - Modality', 'axi-system' ),
            AXISYS_TMPL_COURSE     => esc_html__( 'AXi System - Course', 'axi-system' ),
            AXISYS_TMPL_DISCIPLINE => esc_html__( 'AXi System - Discipline', 'axi-system' )
        ] + $templates;

        return $templates;
    }
}

Plugin::instance();