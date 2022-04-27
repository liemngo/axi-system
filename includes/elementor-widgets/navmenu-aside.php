<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom NavMenu Aside Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_NavMenu_Aside extends \Elementor\Widget_Base
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
        return 'axi-navmenu-aside';
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
        return esc_html__( 'AXi NavMenu Aside', 'axi-system' );
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
        /*--------------------------------------------------------------
        # Items
        --------------------------------------------------------------*/

        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__( 'Items', 'axi-system' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'item_title',
            [
                'label'       => esc_html__( 'Title', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'item_link',
            [
                'label'   => esc_html__( 'Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ]
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label'     => esc_html__( 'Menu Items', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::REPEATER,
                'fields'    => $repeater->get_controls(),
                'title_field' => '{{{ item_title }}}',
                'separator' => 'after'
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
        /* /Items */

        /*--------------------------------------------------------------
        # Buttons2
        --------------------------------------------------------------*/

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Button', 'axi-system' ),
            ]
        );

        $this->add_control(
            'show_btn',
            [
                'label' => esc_html__( 'Show Additional Button', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Get your course guide', 'axi-system' ),
                'label_block' => true,
                'condition'   => [
                    'show_btn' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'btn_link',
            [
                'label'   => esc_html__( 'Button Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'show_btn' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        /* /Button */

        /*--------------------------------------------------------------
        # Sticky
        --------------------------------------------------------------*/

        $this->start_controls_section(
            'section_sticky',
            [
                'label' => esc_html__( 'Sticky', 'axi-system' ),
            ]
        );

        $this->add_control(
            'start_sticky_on',
            [
                'label'       => esc_html__( 'Start to stick on', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. This ID is taken from section ID where you want the menu starts to stick on. If blank, menu will start to stick on Header.', 'axi-system' ),
                'label_block' => true,
                'separator'   => 'before',
                'default'     => ''
            ]
        );

        $this->add_control(
            'stop_sticky_on',
            [
                'label'       => esc_html__( 'Stop to stick on', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. This ID is taken from section ID where you want the menu stops to stick on. If blank, menu will start to stick on Footer.', 'axi-system' ),
                'label_block' => true,
                'default'     => ''
            ]
        );

        $this->add_control(
            'hide_on_ids',
            [
                'label'       => esc_html__( 'Autohide on section ids', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'title'       => esc_html__( 'Add your custom ids WITHOUT the Pound key, separated by commas. e.g: my-id-1, my-id-2, my-id-3. These IDs are taken from section ID where you want the menu to hide.', 'axi-system' ),
                'label_block' => true,
                'separator'   => 'before',
                'default'     => ''
            ]
        );

        $this->end_controls_section();
        /* /Sticky */

        /*--------------------------------------------------------------
        # Menu Style
        --------------------------------------------------------------*/

        $this->start_controls_section(
            'section_menu_style',
            [
                'label' => esc_html__( 'Menu', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'container_width',
            [
                'label'  => esc_html__( 'Container Width', 'axi-system' ),
                'type'   => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'  => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100
                    ],
                ],
                'selectors' => [
                    'div.axi-navmenu-aside-global' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_width',
            [
                'label'  => esc_html__( 'Menu Width', 'axi-system' ),
                'type'   => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'  => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100
                    ],
                ],
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .navmenu-aside-inner' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_alignment',
            [
                'label' => __( 'Menu Alignment', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __( 'Left', 'axi-system' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'axi-system' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'axi-system' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'default' => 'right',
            ]
        );

        $this->end_controls_section();
        /* /Menu Style */

        /*--------------------------------------------------------------
        # Item Style
        --------------------------------------------------------------*/

        $this->start_controls_section(
            'section_menu_item_style',
            [
                'label' => esc_html__( 'Menu Item', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_item_typo',
                'selector' => 'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-items',
            ]
        );

        $this->add_responsive_control(
            'menu_item_alignment',
            [
                'label' => __( 'Alignment', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __( 'Left', 'axi-system' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'axi-system' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'axi-system' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'default' => 'left',
            ]
        );

        $this->start_controls_tabs( 'tabs_menu_item_style' );

        $this->start_controls_tab(
            'tab_menu_item_normal',
            [
                'label' => esc_html__( 'Normal', 'axi-system' ),
            ]
        );
        $this->add_control(
            'mitem_color',
            [
                'label' => esc_html__( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-item > a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_item_hover',
            [
                'label' => esc_html__( 'Hover', 'axi-system' ),
            ]
        );
        $this->add_control(
            'mitem_color_hover',
            [
                'label' => esc_html__( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-item.active > a, .axi-navmenu-aside-global .axi-navmenu-aside .menu-item > a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        /* /Item Style */

        /*--------------------------------------------------------------
        # Button Style
        --------------------------------------------------------------*/

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'show_btn' => 'yes'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_menu_btn_style' );

        $this->start_controls_tab(
            'tab_menu_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'axi-system' ),
            ]
        );
        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_border_color',
            [
                'label' => esc_html__( 'Border Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'axi-system' ),
            ]
        );
        $this->add_control(
            'btn_color_hover',
            [
                'label' => esc_html__( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn:hover, .axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn:focus' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_bgcolor_hover',
            [
                'label' => esc_html__( 'Background Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn:hover, .axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn:hover, .axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'selector' => 'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    'div.axi-navmenu-aside-global .axi-navmenu-aside .menu-extras .menu-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Button Style */
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

        if ( empty( $settings['menu_alignment'] ) )
        {
            $settings['menu_alignment'] = 'none';
        }

        $this->add_render_attribute(
            'wrapper',
            [
                'class' => 'axi-navmenu-aside align-' . esc_attr( $settings['menu_alignment'] ),
                'data-axiel' => 'navmenu-aside',
                'data-start-el' => $settings['start_sticky_on'],
                'data-stop-el' => $settings['stop_sticky_on'],
                'data-hide-on' => $settings['hide_on_ids']
            ]
        );

        if ( empty( $settings['menu_item_alignment'] ) )
        {
            $settings['menu_item_alignment'] = 'none';
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="navmenu-aside-inner align-<?php echo esc_attr( $settings['menu_item_alignment'] ); ?>">
                <?php
                if ( $settings['menu_items'] ) : ?>
                    <ul class="menu-items">
                        <?php
                        foreach( $settings['menu_items'] as $mik => $item ) :

                            if ( ! $item['item_title'] || empty( $item['item_link']['url'] ) ) :
                                continue;
                            endif;

                            printf(
                                '<li class="menu-item"><a href="%1$s" target="%2$s"%3$s>%4$s</a></li>',
                                esc_url( $item['item_link']['url'] ),
                                $item['item_link']['is_external'] ? '_blank' : '_self',
                                $item['item_link']['nofollow'] ? ' rel="nofollow"' : '',
                                wp_kses( $item['item_title'], axisys_kses() )
                            );
                        endforeach;
                        ?>
                    </ul>
                    <?php
                endif; ?>
                <?php
                if ( $settings['show_btn'] && ! empty( $settings['btn_link']['url'] ) ) :
                    echo '<div class="menu-extras">';
                    printf(
                        '<a class="menu-btn" href="%1$s" target="%2$s"%3$s>%4$s</a>',
                        esc_url( $settings['btn_link']['url'] ),
                        $settings['btn_link']['is_external'] ? '_blank' : '_self',
                        $settings['btn_link']['nofollow'] ? ' rel="nofollow"' : '',
                        wp_kses( $settings['btn_text'], axisys_kses() )
                    );
                    echo '</div>';
                endif;
                ?>
            </div>
        </div>
        <?php
    }
}