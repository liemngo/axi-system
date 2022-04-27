<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Course List Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_Course_List extends \Elementor\Widget_Base
{
    /**
     * Maximum number of course container
     *
     * @var integer
     * @access protected
     */
    protected $max_col_count;

    /**
     * Maximum number of items per container
     *
     * @var integer
     * @access protected
     */
    protected $max_item_count;

    /**
     * Widget base constructor.
     *
     * Initializing the widget base class.
     *
     * @since 1.0.0
     * @access public
     *
     * @throws \Exception If arguments are missing when initializing a full widget
     *                   instance.
     *
     * @param array      $data Widget data. Default is an empty array.
     * @param array|null $args Optional. Widget default arguments. Default is null.
     */
    public function __construct( $data = [], $args = null )
    {
        parent::__construct( $data, $args );
        $this->max_col_count = 6;
        $this->max_item_count = 10;
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'axi-course-list';
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
        return esc_html__( 'AXi Course List', 'axi-system' );
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
        return 'eicon-post-list';
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
        # General
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'axi-system' ),
            ]
        );

        $this->add_control(
            'col_count',
            [
                'label'   => esc_html__( 'Column Count', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => $this->max_col_count,
                'default' => 2
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
        # Items
        --------------------------------------------------------------*/
        $citem = new \Elementor\Repeater();

        $citem->add_control(
            'group_label',
            [
                'label'       => esc_html__( 'Label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true
            ]
        );

        $citem->add_control(
            'group_image',
            [
                'label'     => esc_html__( 'Image', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::MEDIA,
                'separator' => 'after',
                'default'   => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $pages = get_pages();
        $page_chooses = [
            0 => esc_html__( '-- Custom Link --', 'axi-system' )
        ];
        if ( $pages )
        {
            foreach ( $pages as $page )
            {
                $page_chooses[ $page->ID ] = get_the_title( $page );
            }
        }

        for ( $i = 0; $i < $this->max_item_count; $i++ )
        { 
            $index = $i + 1;

            $citem->add_control(
                'group_item_' . $index . '_label',
                [
                    'label'       => sprintf( esc_html__( 'Item #%s Label', 'axi-system' ), $index ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true
                ]
            );

            $citem->add_control(
                'group_item_' . $index . '_page',
                [
                    'label'       => sprintf( esc_html__( 'Item #%s Page', 'axi-system' ), $index ),
                    'type'        => \Elementor\Controls_Manager::SELECT,
                    'options'     => $page_chooses,
                    'label_block' => true,
                    'description' => esc_html__( 'This will ignore custom link', 'axi-system' ),
                    'default'     => '0'
                ]
            );
    
            $citem->add_control(
                'group_item_' . $index . '_link',
                [
                    'label'   => sprintf( esc_html__( 'Item #%s Custom Link', 'axi-system' ), $index ),
                    'type'    => \Elementor\Controls_Manager::URL,
                    
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'group_item_' . $index . '_page' => '0'
                    ],
                    'default' => [
                        'url' => '#',
                    ]
                ]
            );

            $citem->add_control(
                'group_item_' . $index . '_highlight_on',
                [
                    'label'     => sprintf( esc_html__( 'Highlight Item #%s', 'axi-system' ), $index ),
                    'type'      => \Elementor\Controls_Manager::SWITCHER,
                    'separator' => 'after'
                ]
            );
        }
        
        for ( $i = 0; $i < $this->max_col_count; $i++ )
        {
            $index = $i + 1;

            $this->start_controls_section(
                'section_col_' . $index,
                [
                    'label'     => esc_html__( 'Column #' . $index, 'axi-system' ),
                    'condition' => [
                        'col_count' => $this->col_conddition( $index )
                    ]
                ]
            );

            $this->add_control(
                'col_' . $index . '_title',
                [
                    'label'       => esc_html__( 'Label', 'axi-system' ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true
                ]
            );

            $this->add_control(
                'col_' . $index . '_image',
                [
                    'label'     => esc_html__( 'Image', 'axi-system' ),
                    'type'      => \Elementor\Controls_Manager::MEDIA,
                    'default'   => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ]
                ]
            );

            $this->add_control(
                'col_' . $index . '_separator_on',
                [
                    'label'     => esc_html__( 'Show separator between items', 'axi-system' ),
                    'type'      => \Elementor\Controls_Manager::SWITCHER,
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'col_' . $index . '_item_groups',
                [
                    'label'       => esc_html__( 'Item Groups', 'axi-system' ),
                    'type'        => \Elementor\Controls_Manager::REPEATER,
                    'fields'      => $citem->get_controls(),
                    'separator'   => 'before',
                    'title_field' => '{{group_label}}'
                ]
            );

            $this->end_controls_section();
        }
        /* / Items */

        /*--------------------------------------------------------------
        # General Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__( 'General', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'col_padding',
            [
                'label'      => esc_html__( 'Column Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .course-col' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /General Style */

        /*--------------------------------------------------------------
        # Column Header Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_style_col_header',
            [
                'label' => esc_html__( 'Column Header', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'col_header_height',
            [
                'label'      => esc_html__( 'Custom Height', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-header' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'col_header_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'col_header_background',
            [
                'label'     => esc_html__( 'Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'col_header_img_width',
            [
                'label' => esc_html__( 'Image Width', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-header-image img'  => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'col_header_img_padding',
            [
                'label'      => esc_html__( 'Image Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator'  => 'after',
                'selectors'  => [
                    '{{WRAPPER}} .course-box .box-header-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__( 'Typography', 'axi-system' ),
                'name'     => 'col_header_typography',
                'selector' => '{{WRAPPER}} .course-box .box-header-title'
            ]
        );

        $this->add_control(
            'col_header_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-header-title' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Column Header Style */

        /*--------------------------------------------------------------
        # Column Content Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_style_col_content',
            [
                'label' => esc_html__( 'Column Content', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'item_groups_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .box-item-groups' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_groups_background',
            [
                'label'     => esc_html__( 'Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-item-groups' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Column Content Style */

        /*--------------------------------------------------------------
        # Column Item Group Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_style_col_item_group',
            [
                'label' => esc_html__( 'Item Group', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'item_group_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .box-item-group' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_group_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .box-item-group' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_group_img_width',
            [
                'label' => esc_html__( 'Image Width', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .group-image img'  => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_group_img_padding',
            [
                'label'      => esc_html__( 'Image Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator'  => 'after',
                'selectors'  => [
                    '{{WRAPPER}} .course-box .group-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_group_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .course-box .group-label' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label'     => esc_html__( 'Label Typography', 'axi-system' ),
                'name'      => 'item_group_label_typography',
                'separator' => 'after',
                'selector'  => '{{WRAPPER}} .course-box .group-label'
            ]
        );

        $this->add_responsive_control(
            'item_group_label_margin',
            [
                'label'      => esc_html__( 'Label Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .group-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_group_items_margin',
            [
                'label'      => esc_html__( 'Item Group Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .group-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_group_separator_type',
            [
                'label'   => esc_html__( 'Separator Type', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'None', 'axi-system' ),
                    'solid'  => esc_html__( 'Solid', 'axi-system' ),
                    'double' => esc_html__( 'Double', 'axi-system' ),
                    'dotted' => esc_html__( 'Dotted', 'axi-system' ),
                    'dashed' => esc_html__( 'Dashed', 'axi-system' ),
                    'groove' => esc_html__( 'Groove', 'axi-system' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-item-group' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_group_separator_width',
            [
                'label' => esc_html__( 'Separator Thickness', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'condition' => [
                    'item_group_separator_type!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-item-group'  => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_group_separator_color',
            [
                'label'     => esc_html__( 'Separator Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'item_group_separator_type!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .box-item-group' => 'border-bottom-color: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Column Item Group Style */

        /*--------------------------------------------------------------
        # Column Item Group Item Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_style_col_item_group_item',
            [
                'label' => esc_html__( 'Item', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label'     => esc_html__( 'Typography', 'axi-system' ),
                'name'      => 'item_group_item_typography',
                'separator' => 'after',
                'selector'  => '{{WRAPPER}} .course-box .group-item-link'
            ]
        );

        $this->add_responsive_control(
            'item_group_item_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'separator'  => 'before',
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .group-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_group_item_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .group-item-link' => 'Padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'item_group_item_border',
                'selector'  => '{{WRAPPER}} .course-box .group-item-link',
                'separator' => 'before',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid'
                    ],
                    'width' => [
                        'default' => [
                            'top'    => 2,
                            'right'  => 2,
                            'bottom' => 2,
                            'left'   => 2
                        ]
                    ],
                    'color' => [
                        'default' => '#3673FC'
                    ]
                ]
            ]
        );

        $this->add_control(
            'item_group_item_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .course-box .group-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_group_item_box_shadow',
                'selector' => '{{WRAPPER}} .course-box .group-item-link',
                'fields_options' => [
                    'box_shadow' => [
                        'default' => [
                                'horizontal' => 0,
                                'vertical' => 3,
                                'blur' => 6,
                                'spread' => 0,
                                'color' => 'rgba(0,0,0,0.16)',
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'divider_tab_item_group_item',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER
            ]
        );

        /**
         * Item normal and hover
         */

        $this->start_controls_tabs( 'tabs_item_group_item' );

        $this->start_controls_tab(
            'tab_item_group_item_normal',
            [
                'label' => esc_html__( 'Normal', 'axi-system' ),
            ]
        );

        $this->add_control(
            'item_group_item_color',
            [
                'label'     => esc_html__( 'Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3673FC',
                'selectors' => [
                    '{{WRAPPER}} .course-box .group-item-link' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'item_group_item_background',
            [
                'label'     => esc_html__( 'Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .course-box .group-item-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        /** Hover Item Link */

        $this->start_controls_tab(
            'tab_item_group_item_hover',
            [
                'label' => esc_html__( 'Selected', 'axi-system' ),
            ]
        );

        $this->add_control(
            'item_group_item_selected_color',
            [
                'label'     => esc_html__( 'Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .course-box .group-item-link.highlighted, {{WRAPPER}} .course-box .group-item-link:hover, {{WRAPPER}} .course-box .group-item-link:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_group_item_selected_background',
            [
                'label'     => esc_html__( 'Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3673FC',
                'selectors' => [
                    '{{WRAPPER}} .course-box .group-item-link.highlighted, {{WRAPPER}} .course-box .group-item-link:hover, {{WRAPPER}} .course-box .group-item-link:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_group_item_selected_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'item_group_item_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .course-box .group-item-link.highlighted, {{WRAPPER}} .course-box .group-item-link:hover, {{WRAPPER}} .course-box .group-item-link:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();
        /* /Column Item Group Item Style */
    }

    /**
     * Add custom condition for each Column
     *
     * @param  integer $index
     * @return array
     */
    private function col_conddition( $index )
    {
        $values = [];
        for ( $i = $index; $i <= $this->max_col_count; $i++ )
        {
            $values[] = $i;
        }
        return $values;
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
        $col_count = absint( $settings['col_count'] );

        $carousel_options = [
            'slidesToShow'   => 1,
            'slidesToScroll' => 1,
            'arrows'         => false,
            'dots'           => true,
            'speed'          => 350,
            'autoplay'       => false,
            'infinite'       => false
        ];

        $this->add_render_attribute(
            'wrapper',
            'class',
            [
                'axi-carousel-wrapper',
                'axi-course-list-wrapper'
            ]
        );

        $this->add_render_attribute(
            'course-list',
            [
                'class' => [
                    'axi-carousel',
                    'axi-course-list',
                    'axi-course-list-col-' . $col_count
                ],
                'data-axiel' => 'mobile-carousel-only',
                'data-options' => json_encode( $carousel_options )
            ]
        );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'course-list' ); ?>>
                <?php
                for ( $i = 0; $i < $col_count ; $i++ ) :
                    $index = $i + 1;
                    $col_title  = $settings['col_' . $index . '_title'];
                    $col_image  = absint( $settings['col_' . $index . '_image']['id'] );
                    $col_sep_on = $settings['col_' . $index . '_separator_on'];
                    $col_groups = $settings['col_' . $index . '_item_groups'];
                    ?>
                    <div class="course-col">
                        <div class="course-box<?php echo ( $col_sep_on ? ' course-box-wsep' : '' ); ?>">
                            <div class="box-header">
                                <?php
                                if ( $col_image ) :
                                    printf(
                                        '<div class="box-header-image">%s</div>',
                                        wp_get_attachment_image( $col_image, 'full' )
                                    );
                                endif;
                                if ( $col_title ) :
                                    printf(
                                        '<h3 class="box-header-title">%s</h3>',
                                        esc_html( $col_title )
                                    );
                                endif;
                                ?>
                            </div>
                            <div class="box-item-groups">
                                <?php
                                foreach ( $col_groups as $col_group ) :
                                    ?>
                                    <div class="box-item-group">
                                        <?php
                                        $group_image = '';
                                        if ( ! empty( $col_group['group_image']['id'] ) ) :
                                            $group_image = wp_get_attachment_image( $col_group['group_image']['id'], 'full' );
                                            printf(
                                                '<div class="group-image">%s</div>',
                                                $group_image
                                            );
                                        endif;
                                        ?>
                                        <div class="group-body">
                                            <?php
                                            if ( ! empty( $col_group['group_label'] ) ) :
                                                printf(
                                                    '<h4 class="group-label">%1$s<span class="group-label-text">%2$s</span></h4>',
                                                    $group_image ? '<span class="group-label-image">' . $group_image . '</span>' : '',
                                                    esc_html( $col_group['group_label'] )
                                                );
                                            endif;
                                            ?>
                                            <ul class="group-items">
                                                <?php
                                                for ( $j = 0; $j < $this->max_item_count ; $j++ ) :
                                                    $jndex = $j + 1;
                                                    $jtem_label = $col_group['group_item_' . $jndex . '_label'];
                                                    $jtem_pid   = absint( $col_group['group_item_' . $jndex . '_page'] );
                                                    $jtem_link  = $col_group['group_item_' . $jndex . '_link'];

                                                    if ( empty( $jtem_label ) ) :
                                                        continue;
                                                    endif;

                                                    $jitem_link_html = '';
                                                    $jtem_highlight_on = $col_group['group_item_' . $jndex . '_highlight_on'];

                                                    if ( $jtem_pid > 0 )
                                                    {
                                                        $jitem_link_html = sprintf(
                                                            '<a class="group-item-link%1$s" href="%2$s">%3$s</a>',
                                                            ( $jtem_highlight_on ) ? ' highlighted' : '',
                                                            esc_url( get_permalink( $jtem_pid ) ),
                                                            esc_html( $jtem_label )
                                                        );
                                                    }
                                                    elseif ( ! empty( $jtem_link['url'] ) )
                                                    {
                                                        $jitem_link_html = sprintf(
                                                            '<a class="group-item-link%1$s" href="%2$s" target="%3$s"%4$s>%5$s</a>',
                                                            ( $jtem_highlight_on ) ? ' highlighted' : '',
                                                            esc_url( $jtem_link['url'] ),
                                                            $jtem_link['is_external'] ? '_blank' : '_self',
                                                            $jtem_link['nofollow'] ? ' rel="nofollow"' : '',
                                                            esc_html( $jtem_label )
                                                        );
                                                    }

                                                    if ( $jitem_link_html )
                                                    {
                                                        printf(
                                                            '<li class="group-item">%s</li>',
                                                            $jitem_link_html
                                                        );
                                                    }
                                                endfor;
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endfor;
                ?>
            </div>
        </div>
        <?php
    }
}