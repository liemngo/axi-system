<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom NavMenu Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_NavMenu extends \Elementor\Widget_Base
{
    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'axi-navmenu';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__( 'AXi NavMenu', 'axi-system' );
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-nav-menu';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return [ 'academyxi' ];
    }
    
    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return [ 'axi-elementor' ];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        $menus = wp_get_nav_menus();
        $menu_options = [
            0 => esc_html__( '&mdash; Select &mdash;', 'axi-system' )
        ];
        foreach( $menus as $menu )
        {
            $menu_options[ $menu->term_id ] = $menu->name;
        }

        /*--------------------------------------------------------------
        # General
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'axi-system' ),
            ]
        );

        $this->add_control(
            'menu',
            [
                'label'   => esc_html__( 'Select Menu', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $menu_options,
                'default' => 0
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Custom Menu Title', 'axi-system' ),
                'description' => esc_html__( 'Leave blank to use defined menu title instead.', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => esc_html__( 'View', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->end_controls_section();
        /* /General */

        /*--------------------------------------------------------------
        # Title Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title Style', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-navmenu-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .axi-navmenu-title'
            ]
        );

        $this->end_controls_section();
        /* /Title Style */

        /*--------------------------------------------------------------
        # Menu Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_menu_style',
            [
                'label' => esc_html__( 'Menu Style', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'menu_color',
            [
                'label' => esc_html__( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .menu-item > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typo',
                'selector' => '{{WRAPPER}} .menu-item > a'
            ]
        );

        $this->end_controls_section();
        /* /Menu Style */
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', [
            'class' => 'axi-navmenu-wrapper',
        ]);
        $this->add_render_attribute( 'title', 'class', 'axi-navmenu-title' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php
            $menu_id = absint( $settings['menu'] );
            if ( $menu_id > 0 ) :
                $menu = wp_get_nav_menu_object( $menu_id );
            endif;
            if ( ! empty( $menu ) ) :
                $title = $settings['title'];
                if ( ! $title ) :
                    $title = $menu->name;
                endif;
                $random_id = 'axi-navmenu-' . axisys_guid();
                printf(
                    '<input class="axi-navmenu-title-checkbox" type="checkbox" style="display:none" id="%2$s" />' .
                    '<h3 %1$s>' .
                        '<label for="%2$s">' .
                            '%3$s' .
                            '<svg class="icon" viewBox="0 0 512 512">' .
                                ' <use href="#axi-icon-chevron" xlink:href="#axi-icon-chevron"></use>' .
                            '</svg>' .
                        '</label>' .
                    '</h3>',
                    $this->get_render_attribute_string( 'title' ),
                    esc_attr( $random_id ),
                    esc_html( $title )
                );
                wp_nav_menu( [
                    'menu'       => $menu_id,
                    'menu_class' => 'axi-navmenu',
                    'container'  =>  '',
                    'depth'      => 1
                ] );
            endif;
            ?>
        </div>
        <?php
    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _content_template() {}
}