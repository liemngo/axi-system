<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Accordion Elementor Widget for AcademyXi.
 * 
 * @since 1.0.0
 */
class Widget_Accordion extends \Elementor\Widget_Base
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
        return 'axi-accordion';
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
        return esc_html__( 'AXi Accordion', 'axi-system' );
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
        return 'eicon-accordion';
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
        # Accordion Panels
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_panels',
            [
                'label' => esc_html__( 'Accordion', 'axi-system' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'panel_title',
            [
                'label'   => esc_html__( 'Title & Description', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Accordion Title', 'axi-system' ),
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'panel_content',
            [
                'label'      => esc_html__( 'Content', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::WYSIWYG,
                'default'    => esc_html__( 'Accordion Content', 'axi-system' ),
                'show_label' => false,
            ]
        );

        $this->add_control(
            'panels',
            [
                'label'   => esc_html__( 'Accordion Items', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => [
                    [
                        'panel_title'   => esc_html__( 'Accordion #1', 'axi-system' ),
                        'panel_content' => '<p>' . esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'axi-system' ) . '</p>',
                    ],
                    [
                        'panel_title'   => esc_html__( 'Accordion #2', 'axi-system' ),
                        'panel_content' => '<p>' . esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'axi-system' ) . '</p>',
                    ],
                ],
                'title_field' => '{{{ panel_title }}}',
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
        /* /Accordion Panels */

        /*--------------------------------------------------------------
        # Panel Icon
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_panel_icon',
            [
                'label' => esc_html__( 'Icons', 'axi-system' ),
            ]
        );

        $this->add_control(
            'panel_icon',
            [
                'label'     => esc_html__( 'Icon', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::ICONS,
                'separator' => 'before',
                'default'   => [
                    'value'   => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'chevron-down',
                        'angle-down',
                        'angle-double-down',
                        'caret-down',
                        'caret-square-down',
                    ],
                    'fa-regular' => [
                        'caret-square-down',
                    ],
                ],
                'skin'        => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'panel_active_icon',
            [
                'label'   => esc_html__( 'Active Icon', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'chevron-up',
                        'angle-up',
                        'angle-double-up',
                        'caret-up',
                        'caret-square-up',
                    ],
                    'fa-regular' => [
                        'caret-square-up',
                    ],
                ],
                'skin'        => 'inline',
                'label_block' => false,
                'condition'   => [
                    'panel_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Panel Icon */

        /*--------------------------------------------------------------
        # General Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_panel_general_style',
            [
                'label' => esc_html__( 'General', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'panels_spacing',
            [
                'label'              => esc_html__( 'Items Spacing', 'axi-system' ),
                'type'               => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units'         => [ 'px' ],
                'allowed_dimensions' => 'vertical',
                'placeholder'        => [
                    'top'    => '',
                    'right'  => '0',
                    'bottom' => '',
                    'left'   => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /General Style */

        /*--------------------------------------------------------------
        # Title Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_panel_title_style',
            [
                'label' => esc_html__( 'Item Title', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-title, {{WRAPPER}} .panel-icon' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'title_background',
            [
                'label'     => esc_html__( 'Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_title_color',
            [
                'label'     => esc_html__( 'Active Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-header.active .panel-title, {{WRAPPER}} .panel-header.active .panel-icon' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'active_title_background',
            [
                'label'     => esc_html__( 'Active Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-header.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .panel-header'
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Title Style */

        /*--------------------------------------------------------------
        # Content Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_panel_icon_style',
            [
                'label' => esc_html__( 'Item Icon', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label'   => esc_html__( 'Alignment', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Start', 'axi-system' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'End', 'axi-system' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle'  => false,
            ]
        );

        $this->add_responsive_control(
            'icon_space',
            [
                'label' => esc_html__( 'Spacing', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .panel-header.panel-icon-left .panel-title'  => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .panel-header.panel-icon-right .panel-title' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Title Style */

        /*--------------------------------------------------------------
        # Content Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_panel_content_style',
            [
                'label' => esc_html__( 'Item Content', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__( 'Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-content' => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control(
            'content_background',
            [
                'label'     => esc_html__( 'Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .panel-content'
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Title Style */
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
        $id_int   = substr( $this->get_id_int(), 0, 3 );
        ?>
        <div class="axi-accordion" role="tablist" data-axiel="accordion">
            <?php
            foreach ( $settings['panels'] as $index => $item ) :

                $panel_count = $index + 1;
                $panel_header_setting_key   = $this->get_repeater_setting_key( 'panel_title', 'panels', $index );
                $panel_content_setting_key = $this->get_repeater_setting_key( 'panel_content', 'panels', $index );

                $this->add_render_attribute( $panel_header_setting_key, [
                    'id'            => 'panel-header-' . $id_int . $panel_count,
                    'class'         => [ 'panel-header', 'panel-icon-' . $settings['icon_align'] ],
                    'data-tab'      => $panel_count,
                    'role'          => 'tab',
                    'aria-controls' => 'panel-content-' . $id_int . $panel_count,
                    'data-axirole'  => 'trigger'
                ] );

                $this->add_render_attribute( $panel_content_setting_key, [
                    'id'              => 'panel-content-' . $id_int . $panel_count,
                    'class'           => [ 'panel-content' ],
                    'data-tab'        => $panel_count,
                    'role'            => 'tabpanel',
                    'aria-labelledby' => 'panel-header-' . $id_int . $panel_count,
                    'data-axirole'    => 'content'
                ] );

                $this->add_inline_editing_attributes( $panel_content_setting_key, 'advanced' );
                ?>
                <div class="accordion-panel">
                    <div <?php echo $this->get_render_attribute_string( $panel_header_setting_key ); ?>>
                        <?php if ( $settings['panel_icon'] && $settings['panel_active_icon'] ) : ?>
                            <span class="panel-icon" aria-hidden="true">
                                <span class="panel-icon-closed"><?php \Elementor\Icons_Manager::render_icon( $settings['panel_icon'] ); ?></span>
                                <span class="panel-icon-opened"><?php \Elementor\Icons_Manager::render_icon( $settings['panel_active_icon'] ); ?></span>
                            </span>
                        <?php endif; ?>
                        <a class="panel-title" href="javascript:void(0)"><?php echo esc_html( $item['panel_title'] ); ?></a>
                    </div>
                    <div <?php echo $this->get_render_attribute_string( $panel_content_setting_key ); ?>><?php echo $this->parse_text_editor( $item['panel_content'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}