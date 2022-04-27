<?php
namespace AXi_System;

/**
 * Class Elementor_Widgets
 *
 * Main Elementor addons class
 * @since 1.0.0
 */
class Elementor_Addons
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
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        // Register categories
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );
        // Register widget scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
        // Register controls
        add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

        // Register additional display condditions for Elementor Pro
        add_action( 'elementor/theme/register_conditions', [ $this, 'register_theme_conditions' ] );

        // Get filtered items for AXi Discipline List
        add_action( 'wp_ajax_nopriv_axi_discipline_list_get_filtered_items', [ $this, 'get_filtered_disciplines' ] );
        add_action( 'wp_ajax_axi_discipline_list_get_filtered_items', [ $this, 'get_filtered_disciplines' ] );
    }

    /**
     * Register our custom categories
     * 
     * @param \Elementor\Elements_Manager $elements_manager Elements manager instance.
     */
    function register_categories( $elements_manager )
    {
        $elements_manager->add_category(
            'academyxi',
            [
                'title' => esc_html__( 'AcademyXi', 'axi-system' ),
                'icon'  => 'eicon-font',
            ]
        );
    }
    
    /**
     * widget_scripts
     *
     * Load required plugin core files.
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_scripts()
    {
        wp_register_script( 'axi-elementor', AXISYS_URL . 'assets/js/axi-elementor.js', [ 'jquery' ], AXISYS_VERSION, true );
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.0.0
     * @access public
     */
    public function register_widgets()
    {
        // Register Widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Accordion() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Banner() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Banner_Carousel() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SBanner() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_CDLButton() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Comment_Text() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Comparison_Table() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Course_List() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Discipline_List() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Feedbacks() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Icon() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_ImageCarousel() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Instructors() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_MCourses() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Media_Cards() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_NavMenu() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_NavMenu_Primary() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_NavMenu_Aside() );
        // \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_NavMenu_Side() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Organisations() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Stars() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_StudyModes() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_CourseAttributes() );
        // \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_FormidableForm() );
    }

    /**
     * Register our custom control
     *
     * @param \Elementor\Controls_Manager $control_manager
     * @return void
     */
    function register_controls( $control_manager )
    {
        $control_manager->register_control( 'axi_sortable_select', new Elementor\Control_Sortable_Select() );
    }

    /**
     * Undocumented function
     *
     * @param \ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Manager $conditions_manager
     * @return void
     */
    function register_theme_conditions( $conditions_manager )
    {
        $conditions_manager->get_condition( 'page' )->register_sub_condition( new Elementor\Conditions\Page_Template_Course() );
        $conditions_manager->get_condition( 'page' )->register_sub_condition( new Elementor\Conditions\Page_Template_Discipline() );
        $conditions_manager->get_condition( 'page' )->register_sub_condition( new Elementor\Conditions\Page_Template_Home() );
        $conditions_manager->get_condition( 'page' )->register_sub_condition( new Elementor\Conditions\Page_Template_Landing() );
        $conditions_manager->get_condition( 'page' )->register_sub_condition( new Elementor\Conditions\Page_Template_Modality() );
    }

    /**
     * Get filtered disciplines based on tags
     *
     * @return void
     */
    function get_filtered_disciplines()
    {
        $nonce = $_REQUEST['nonce'];
        $result = [
            'status' => 'failed',
            'data'   => ''
        ];
        if ( ! wp_verify_nonce( $nonce, 'axi_discipline_list_noncea' ) )
        {
            echo json_encode( $result );
            exit;
        }

        $settings = wp_parse_args( $_REQUEST['settings'], [
            'tags_label' => '',
            'item_count' => 1,
            'pagination_limit' => 1,
            'columns' => 1,
            'item_btn_text' => '',
            'more_btn_text' => ''
        ]);

        $post_id = absint( $_REQUEST['post_id'] );
        $tag_id = absint( $_REQUEST['tag'] );

        if ( $post_id <= 0 )
        {
            echo json_encode( $result );
            exit;
        }

        $data = Elementor\Widget_Discipline_List::get_filtered_discipline_list( $post_id, $settings, $tag_id );
        $result['status'] = 'success';
        $result['data'] = '<div>' . $data . '</div>';

        echo json_encode( $result );
        exit;
    }
}