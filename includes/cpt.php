<?php
namespace AXi_System;

class CPT
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
        add_action( 'init', [ $this, 'register' ], 5 );
        // add_filter( 'post_row_actions', [ $this, 'post_list_row_actions' ], 10, 2 );
        // add_filter( 'axi_discipline_row_actions', [ $this, 'term_list_row_actions'], 10, 2 );
        // add_filter( 'axi_course_type_row_actions', [ $this, 'term_list_row_actions'], 10, 2 );
        add_filter( 'axi_delivery_mode_row_actions', [ $this, 'term_list_row_actions'], 10, 2 );
        add_filter( 'axi_location_row_actions', [ $this, 'term_list_row_actions'], 10, 2 );
        // add_filter( 'bulk_actions-edit-axi_course', [ $this, 'post_edit_bulk_actions'] );
        // add_filter( 'bulk_actions-edit-axi_discipline', [ $this, 'term_edit_bulk_actions'] );
        // add_filter( 'bulk_actions-edit-axi_course_type', [ $this, 'term_edit_bulk_actions'] );
        add_filter( 'bulk_actions-edit-axi_delivery_mode', [ $this, 'term_edit_bulk_actions'] );
        add_filter( 'bulk_actions-edit-axi_location', [ $this, 'term_edit_bulk_actions'] );
    }

    /**
     * Register post types and taxonomies
     *
     * @return void
     */
    function register()
    {
        $this->register_post_type_course();
        $this->register_post_type_feedback();
        $this->register_post_type_instructor();
        $this->register_post_type_promotion();

        $this->register_taxonomy_discipline();
        $this->register_taxonomy_discipline_guide();
        $this->register_taxonomy_discipline_link();
        $this->register_taxonomy_delivery_mode();
        $this->register_taxonomy_location();
        $this->register_taxonomy_course_type();
        $this->register_taxonomy_organisation();
        $this->register_taxonomy_tag();
        $this->register_taxonomy_discount_code();
    }

    /**
     * Register axi_course post type
     *
     * @return void
     */
    protected function register_post_type_course()
    {
        $args = [
            'label'  => esc_html__( 'Courses', 'axi-system' ),
            'labels' => [
                'name'          => esc_html__( 'Courses', 'axi-system' ),
                'singular_name' => esc_html__( 'Course', 'axi-system' )
            ],
            'description'           => '',
            'public'                => true,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_rest'          => false,
            'rest_base'             => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive'           => false,
            'show_in_menu'          => true,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'delete_with_user'      => false,
            'exclude_from_search'   => true,
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
            'hierarchical'          => false,
            'rewrite'               => false, // [ 'slug' => 'course', 'with_front' => false ],
            'query_var'             => false,
            'menu_icon'             => 'dashicons-book-alt',
            'supports'              => [ 'title', 'editor' ],
            'taxonomies'            => [ 'axi_discipline', 'axi_location', 'axi_delivery_mode', 'axi_course_type' ],
        ];
        register_post_type( 'axi_course', $args );
    }

    /**
     * Register axi_feedback post type
     *
     * @return void
     */
    protected function register_post_type_feedback()
    {
        $args = [
            'label'  => esc_html__( 'Feedbacks', 'axi-system' ),
            'labels' => [
                'name'          => esc_html__( 'Feedbacks', 'axi-system' ),
                'singular_name' => esc_html__( 'Feedback', 'axi-system' ),
            ],
            'description'           => '',
            'public'                => true,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_rest'          => false,
            'rest_base'             => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive'           => false,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => true,
            'delete_with_user'      => false,
            'exclude_from_search'   => true,
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
            'hierarchical'          => false,
            'rewrite'               => [ 'slug' => 'feedback', 'with_front' => false ],
            'query_var'             => true,
            'menu_icon'             => 'dashicons-testimonial',
            'supports'              => [ 'title', 'editor', 'thumbnail' ],
            'taxonomies'            => [ 'axi_organisation' ],
        ];
        register_post_type( 'axi_feedback', $args );
    }

    /**
     * Register axi_instructor post type
     *
     * @return void
     */
    protected function register_post_type_instructor()
    {
        $args = [
            'label'  => esc_html__( 'Instructors', 'axi-system' ),
            'labels' => [
                'name'          => esc_html__( 'Instructors', 'axi-system' ),
                'singular_name' => esc_html__( 'Instructor', 'axi-system' ),
            ],
            'description'           => '',
            'public'                => true,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_rest'          => false,
            'rest_base'             => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive'           => false,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => true,
            'delete_with_user'      => false,
            'exclude_from_search'   => true,
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
            'hierarchical'          => false,
            'rewrite'               => [ 'slug' => 'instructor', 'with_front' => false ],
            'query_var'             => true,
            'menu_icon'             => 'dashicons-businessman',
            'supports'              => [ 'title', 'editor', 'thumbnail' ],
        ];
    
        register_post_type( 'axi_instructor', $args );
    }

    /**
     * Register axi_promotion post type
     *
     * @return void
     */
    protected function register_post_type_promotion()
    {
        $args = [
            'label'  => esc_html__( 'Promotions', 'axi-system' ),
            'labels' => [
                'name'          => esc_html__( 'Promotions', 'axi-system' ),
                'singular_name' => esc_html__( 'Promotion', 'axi-system' ),
            ],
            'description'           => '',
            'public'                => true,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_rest'          => true,
            'rest_base'             => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive'           => false,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'delete_with_user'      => false,
            'exclude_from_search'   => true,
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
            'hierarchical'          => false,
            'rewrite'               => [ 'slug' => 'promotion', 'with_front' => false ],
            'query_var'             => false,
            'menu_icon'             => 'dashicons-tickets-alt',
            'supports'              => [ 'title', 'editor', 'thumbnail' ],
            'taxonomies'            => [ 'axi_discipline', 'axi_delivery_mode', 'axi_location' ],
        ];
    
        register_post_type( 'axi_promotion', $args );
    }

    /**
     * Register axi_discipline taxonomy
     *
     * @return void
     */
    protected function register_taxonomy_discipline()
    {
        $args = [
            'label' => esc_html__( 'Disciplines', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Disciplines', 'axi-system' ),
                'singular_name'      => esc_html__( 'Discipline', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Discipline', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Discipline', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Discipline', 'axi-system' ),
                'new_item'           => esc_html__( 'New Discipline', 'axi-system' ),
                'view_item'          => esc_html__( 'View Discipline', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Disciplines', 'axi-system' ),
                'not_found'          => esc_html__( 'No Disciplines found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Disciplines found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => false,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => false,
            'rewrite'               => [ 'slug' => 'discipline', 'with_front' => false, ],
            'show_admin_column'     => true,
            'show_in_rest'          => true,
            'rest_base'             => 'discipline',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_discipline', [ 'page', 'axi_course', 'axi_feedback', 'axi_promotion' ], $args );
    }

    /**
     * Register axi_discipline_guide taxonomy
     *
     * @return void
     */
    protected function register_taxonomy_discipline_guide()
    {
        $args = [
            'label'  => esc_html__( 'Discipline Guides', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Discipline Guides', 'axi-system' ),
                'singular_name'      => esc_html__( 'Discipline Guide', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Discipline Guide', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Discipline Guide', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Discipline Guide', 'axi-system' ),
                'new_item'           => esc_html__( 'New Discipline Guide', 'axi-system' ),
                'view_item'          => esc_html__( 'View Discipline Guide', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Discipline Guides', 'axi-system' ),
                'not_found'          => esc_html__( 'No Discipline Guides found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Discipline Guides found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => false,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => true,
            'rewrite'               => [ 'slug' => 'discipline_guide', 'with_front' => false, ],
            'show_admin_column'     => false,
            'show_in_rest'          => true,
            'rest_base'             => 'discipline_guide',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_discipline_guide', [ 'axi_course' ], $args );
    }
    
    /**
     * Register axi_discipline_link taxonomy
     *
     * @return void
     */
    protected function register_taxonomy_discipline_link()
    {
        $args = [
            'label'  => esc_html__( 'Discipline Links', 'axi-system' ),
            'labels' => [
                'name'          => esc_html__( 'Discipline Links', 'axi-system' ),
                'singular_name' => esc_html__( 'Discipline Link', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Discipline Link', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Discipline Link', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Discipline Link', 'axi-system' ),
                'new_item'           => esc_html__( 'New Discipline Link', 'axi-system' ),
                'view_item'          => esc_html__( 'View Discipline Link', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Discipline Links', 'axi-system' ),
                'not_found'          => esc_html__( 'No Discipline Links found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Discipline Links found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => true,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => true,
            'rewrite'               => [ 'slug' => 'discipline_link', 'with_front' => false, ],
            'show_admin_column'     => false,
            'show_in_rest'          => false,
            'rest_base'             => 'discipline_link',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_discipline_link', [ 'axi_course' ], $args );
    }

    /**
     * Register axi_delivery_mode taxonomy
     *
     * @return void
     */
    protected function register_taxonomy_delivery_mode()
    {
        $args = [
            'label'  => esc_html__( 'Delivery Modes', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Delivery Modes', 'axi-system' ),
                'singular_name'      => esc_html__( 'Delivery Mode', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Delivery Mode', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Delivery Mode', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Delivery Mode', 'axi-system' ),
                'new_item'           => esc_html__( 'New Delivery Mode', 'axi-system' ),
                'view_item'          => esc_html__( 'View Delivery Mode', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Delivery Modes', 'axi-system' ),
                'not_found'          => esc_html__( 'No Delivery Modes found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Delivery Modes found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => true,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => false,
            'rewrite'               => [ 'slug' => 'delivery_mode', 'with_front' => false, ],
            'show_admin_column'     => true,
            'show_in_rest'          => true,
            'rest_base'             => 'delivery_mode',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_delivery_mode', [ 'page', 'axi_course', 'axi_promotion' ], $args );
    }

    /**
     * Register axi_location taxonomy
     *
     * @return void
     */
    protected function register_taxonomy_location()
    {
        $args = [
            'label'  => esc_html__( 'Locations', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Locations', 'axi-system' ),
                'singular_name'      => esc_html__( 'Location', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Location', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Location', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Location', 'axi-system' ),
                'new_item'           => esc_html__( 'New Location', 'axi-system' ),
                'view_item'          => esc_html__( 'View Location', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Locations', 'axi-system' ),
                'not_found'          => esc_html__( 'No Locations found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Locations found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => false,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => true,
            'rewrite'               => [ 'slug' => 'location', 'with_front' => false, ],
            'show_admin_column'     => true,
            'show_in_rest'          => true,
            'rest_base'             => 'location',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_location', [ 'page', 'axi_course', 'axi_promotion' ], $args );
    }

    /**
     * Register axi_course_type taxonomy
     *
     * @return void
     */
    protected function register_taxonomy_course_type()
    {
        $args = [
            'label'  => esc_html__( 'Course Types', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Course Types', 'axi-system' ),
                'singular_name'      => esc_html__( 'Course Type', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Course Type', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Course Type', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Course Type', 'axi-system' ),
                'new_item'           => esc_html__( 'New Course Type', 'axi-system' ),
                'view_item'          => esc_html__( 'View Course Type', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Course Types', 'axi-system' ),
                'not_found'          => esc_html__( 'No Course Types found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Course Types found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => false,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => false,
            'rewrite'               => [ 'slug' => 'course_type', 'with_front' => false, ],
            'show_admin_column'     => true,
            'show_in_rest'          => true,
            'rest_base'             => 'course_type',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_course_type', [ 'axi_course' ], $args );
    }

    /**
     * Register axi_organisation taxonomy
     *
     * @return void
     */
    protected function register_taxonomy_organisation()
    {
        $args = [
            'label'  => esc_html__( 'Organisations', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Organisations', 'axi-system' ),
                'singular_name'      => esc_html__( 'Organisation', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Organisation', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Organisation', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Organisation', 'axi-system' ),
                'new_item'           => esc_html__( 'New Organisation', 'axi-system' ),
                'view_item'          => esc_html__( 'View Organisation', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Organisations', 'axi-system' ),
                'not_found'          => esc_html__( 'No Organisations found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Organisations found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => false,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => true,
            'rewrite'               => [ 'slug' => 'organisation', 'with_front' => false, ],
            'show_admin_column'     => true,
            'show_in_rest'          => false,
            'rest_base'             => 'organisation',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_organisation', [ 'axi_feedback' ], $args );
    }

    function register_taxonomy_tag()
    {
        $args = [
            'label'  => esc_html__( 'Tags', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Tags', 'axi-system' ),
                'singular_name'      => esc_html__( 'Tag', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Tag', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Tag', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Tag', 'axi-system' ),
                'new_item'           => esc_html__( 'New Tag', 'axi-system' ),
                'view_item'          => esc_html__( 'View Tag', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Tags', 'axi-system' ),
                'not_found'          => esc_html__( 'No Tags found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Tags found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => false,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => true,
            'rewrite'               => [ 'slug' => 'axitag', 'with_front' => false, ],
            'show_admin_column'     => true,
            'show_in_rest'          => false,
            'rest_base'             => 'axitag',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_tag', [], $args );
    }

    function register_taxonomy_discount_code()
    {
        $args = [
            'label'  => esc_html__( 'Discount Codes', 'axi-system' ),
            'labels' => [
                'name'               => esc_html__( 'Discount Codes', 'axi-system' ),
                'singular_name'      => esc_html__( 'Discount Code', 'axi-system' ),
                'add_new'            => esc_html_x( 'Add New Discount Code', 'axi-system', 'axi-system' ),
                'add_new_item'       => esc_html__( 'Add New Discount Code', 'axi-system' ),
                'edit_item'          => esc_html__( 'Edit Discount Code', 'axi-system' ),
                'new_item'           => esc_html__( 'New Discount Code', 'axi-system' ),
                'view_item'          => esc_html__( 'View Discount Code', 'axi-system' ),
                'search_items'       => esc_html__( 'Search Discount Codes', 'axi-system' ),
                'not_found'          => esc_html__( 'No Discount Codes found', 'axi-system' ),
                'not_found_in_trash' => esc_html__( 'No Discount Codes found in Trash', 'axi-system' )
            ],
            'public'                => true,
            'publicly_queryable'    => false,
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'query_var'             => true,
            'rewrite'               => [ 'slug' => 'discount-code', 'with_front' => false, ],
            'show_admin_column'     => true,
            'show_in_rest'          => false,
            'rest_base'             => 'discount-code',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'show_in_quick_edit'    => false,
            'meta_box_cb'           => false,
        ];
        register_taxonomy( 'axi_discount_code', [], $args );
    }

    /**
     * Remove delete action for posts.
     * 
     * @see WP_Posts_List_Table
     * @param array $actions
     * @param WP_Post $post
     */
    function post_list_row_actions( $actions, $post )
    {
        if ( $post->post_type == 'axi_course' && isset( $actions['trash'] ) )
        {
            unset( $actions['trash'] );
        }
        return $actions;
    }

    /**
     * Remove bulk delete action for posts.
     * 
     * @see WP_Posts_List_Table
     * @param array $actions
     * @return array
     */
    function post_edit_bulk_actions( $actions )
    {
        if ( isset( $actions['trash'] ) )
        {
            unset( $actions['trash'] );
        }
        return $actions;
    }

    /**
     * Remove delete action for temms.
     * 
     * @see WP_Terms_List_Table
     * @param array $actions
     * @return array
     */
    function term_list_row_actions( $actions, $tag )
    {
        if ( isset( $actions['delete'] ) )
        {
            unset( $actions['delete'] );
        }
        return $actions;
    }

    /**
     * Remove bulk delete action for temms.
     * 
     * @see WP_Terms_List_Table
     * @param array $actions
     * @return array
     */
    function term_edit_bulk_actions( $actions )
    {
        if ( isset( $actions['delete'] ) )
        {
            unset( $actions['delete'] );
        }
        return $actions;
    }
}