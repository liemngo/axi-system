<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Banner Carousel Elementor Widget for AcademyXi.
 * 
 * @since 1.0.0
 */
class Widget_Banner_Carousel extends \Elementor\Widget_Base
{
    /**
     * Maximum number of slides
     *
     * @var integer
     * @access protected
     */
    protected $max_slide_count;

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
        $this->max_slide_count = 10;
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
        return 'axi-banner-carousel';
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
        return esc_html__( 'AXi Banner Carousel', 'axi-system' );
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
        return 'eicon-media-carousel';
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
            'slide_count',
            [
                'label'   => esc_html__( 'Slide Count', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => $this->max_slide_count,
                'default' => 2
            ]
        );

        $this->add_control(
            'slide_scroll_speed',
            [
                'label'       => esc_html__( 'Scroll Speed', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'min'         => 100,
                'max'         => 5000,
                'description' => esc_html__( 'Specify a scroll speed (in miliseconds)', 'axi-system' ),
                'placeholder' => 500,
                'default'     => 500,
                'conditions'  => [
                    'terms' => [
                        [
                            'name' => 'slide_count',
                            'operator' => '>',
                            'value' => 1
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'slide_auto_scroll',
            [
                'label' => esc_html__( 'Auto Scroll', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'slide_count',
                            'operator' => '>',
                            'value' => 1
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'slide_auto_scroll_speed',
            [
                'label' => esc_html__( 'Auto Scroll Delay', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'min'         => 500,
                'max'         => 10000,
                'description' => esc_html__( 'Specify a delay speed (in miliseconds)', 'axi-system' ),
                'placeholder' => 5000,
                'default'     => 5000,
                'condition'   => [
                    'slide_auto_scroll' => 'yes'
                ]
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
        # Slides
        --------------------------------------------------------------*/

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

        $slide_button = new \Elementor\Repeater();

        $slide_button->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true
            ]
        );

        $slide_button->add_control(
            'btn_shape',
            [
                'label'       => esc_html__( 'Button Shape', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'rectangular',
                'label_block' => true,
                'options'     => [
                    'rectangular' => esc_html__( 'Rectangular', 'axi-system' ),
                    'rounded'     => esc_html__( 'Rounded', 'axi-system' )
                ]
            ]
        );

        $slide_button->add_control(
            'btn_page_id',
            [
                'label'       => esc_html__( 'Button Page Link', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'options'     => $page_chooses,
                'default'     => '0'
            ]
        );

        $slide_button->add_control(
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
                    'btn_page_id' => '0'
                ]
            ]
        );
        
        for ( $i = 0; $i < $this->max_slide_count; $i++ )
        {
            $index = $i + 1;

            $this->start_controls_section(
                'section_slide_' . $index,
                [
                    'label'     => esc_html__( 'Slide #' . $index, 'axi-system' ),
                    'condition' => [
                        'slide_count' => $this->slide_conddition( $index )
                    ]
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bgimage',
                [
                    'label'   => esc_html__( 'Background Image', 'axi-system' ),
                    'type'    => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ]
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bg_position',
                [
                    'label'      => esc_html_x( 'Position', 'Background Control', 'axi-system' ),
                    'type'       => \Elementor\Controls_Manager::SELECT,
                    'responsive' => true,
                    'options'    => [
                        ''              => esc_html_x( 'Default', 'Background Control', 'axi-system' ),
                        'center center' => esc_html_x( 'Center Center', 'Background Control', 'axi-system' ),
                        'center left'   => esc_html_x( 'Center Left', 'Background Control', 'axi-system' ),
                        'center right'  => esc_html_x( 'Center Right', 'Background Control', 'axi-system' ),
                        'top center'    => esc_html_x( 'Top Center', 'Background Control', 'axi-system' ),
                        'top left'      => esc_html_x( 'Top Left', 'Background Control', 'axi-system' ),
                        'top right'     => esc_html_x( 'Top Right', 'Background Control', 'axi-system' ),
                        'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'axi-system' ),
                        'bottom left'   => esc_html_x( 'Bottom Left', 'Background Control', 'axi-system' ),
                        'bottom right'  => esc_html_x( 'Bottom Right', 'Background Control', 'axi-system' ),
                        'initial'       => esc_html_x( 'Custom', 'Background Control', 'axi-system' ),

                    ],
                    'selectors' => [
                        '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-position: {{VALUE}};',
                    ],
                    'condition' => [
                        'slide_' . $index . '_bgimage[url]!' => ''
                    ],
                    'default' => 'center center',
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bg_xpos',
                [
                    'label'      => esc_html_x( 'X Position', 'Background Control', 'axi-system' ),
                    'type'       => \Elementor\Controls_Manager::SLIDER,
                    'responsive' => true,
                    'size_units' => [ 'px', 'em', '%', 'vw' ],
                    'default'    => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'tablet_default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'mobile_default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -800,
                            'max' => 800,
                        ],
                        'em' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                        'vw' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-position: {{SIZE}}{{UNIT}} {{slide_' . $index . '_bg_ypos.SIZE}}{{slide_' . $index . '_bg_ypos.UNIT}}',
                    ],
                    'condition' => [
                        'slide_' . $index . '_bg_position' => [ 'initial' ],
                        'slide_' . $index . '_bgimage[url]!' => '',
                    ],
                    'required' => true,
                    'device_args' => [
                        \Elementor\Controls_Stack::RESPONSIVE_TABLET => [
                            'selectors' => [
                                '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-position: {{SIZE}}{{UNIT}} {{slide_' . $index . '_bg_ypos_tablet.SIZE}}{{slide_' . $index . '_bg_ypos_tablet.UNIT}}',
                            ],
                            'condition' => [
                                'slide_' . $index . '_bg_position_tablet' => [ 'initial' ],
                            ],
                        ],
                        \Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
                            'selectors' => [
                                '{{WRAPPER}} .axi-banner-carousel .banner-entry' => 'background-position: {{SIZE}}{{UNIT}} {{slide_' . $index . '_bg_ypos_mobile.SIZE}}{{slide_' . $index . '_bg_ypos_mobile.UNIT}}',
                            ],
                            'condition' => [
                                'slide_' . $index . '_bg_position_mobile' => [ 'initial' ],
                            ],
                        ],
                    ],
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bg_ypos',
                [
                    'label' => esc_html_x( 'Y Position', 'Background Control', 'axi-system' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'responsive' => true,
                    'size_units' => [ 'px', 'em', '%', 'vh' ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'tablet_default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'mobile_default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -800,
                            'max' => 800,
                        ],
                        'em' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                        'vh' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-position: {{slide_' . $index . '_bg_xpos.SIZE}}{{slide_' . $index . '_bg_xpos.UNIT}} {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'slide_' . $index . '_bg_position' => [ 'initial' ],
                        'slide_' . $index . '_bgimage[url]!' => '',
                    ],
                    'required' => true,
                    'device_args' => [
                        \Elementor\Controls_Stack::RESPONSIVE_TABLET => [
                            'selectors' => [
                                '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-position: {{slide_' . $index . '_bg_xpos_tablet.SIZE}}{{slide_' . $index . '_bg_xpos_tablet.UNIT}} {{SIZE}}{{UNIT}}',
                            ],
                            'condition' => [
                                'slide_' . $index . '_bg_position_tablet' => [ 'initial' ],
                            ],
                        ],
                        \Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
                            'selectors' => [
                                '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-position: {{slide_' . $index . '_bg_xpos_mobile.SIZE}}{{slide_' . $index . '_bg_xpos_mobile.UNIT}} {{SIZE}}{{UNIT}}',
                            ],
                            'condition' => [
                                'slide_' . $index . '_bg_position_mobile' => [ 'initial' ],
                            ],
                        ],
                    ],
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bg_attachment',
                [
                    'label' => esc_html_x( 'Attachment', 'Background Control', 'axi-system' ),
                    'description' => esc_html__( 'Note: Attachment Fixed works only on desktop.', 'axi-system' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        '' => esc_html_x( 'Default', 'Background Control', 'axi-system' ),
                        'scroll' => esc_html_x( 'Scroll', 'Background Control', 'axi-system' ),
                        'fixed' => esc_html_x( 'Fixed', 'Background Control', 'axi-system' ),
                    ],
                    'selectors' => [
                        '(desktop+){{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-attachment: {{VALUE}};',
                    ],
                    'condition' => [
                        'slide_' . $index . '_bgimage[url]!' => '',
                    ],
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bg_repeat',
                [
                    'label' => esc_html_x( 'Repeat', 'Background Control', 'axi-system' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'responsive' => true,
                    'options' => [
                        '' => esc_html_x( 'Default', 'Background Control', 'axi-system' ),
                        'no-repeat' => esc_html_x( 'No-repeat', 'Background Control', 'axi-system' ),
                        'repeat' => esc_html_x( 'Repeat', 'Background Control', 'axi-system' ),
                        'repeat-x' => esc_html_x( 'Repeat-x', 'Background Control', 'axi-system' ),
                        'repeat-y' => esc_html_x( 'Repeat-y', 'Background Control', 'axi-system' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-repeat: {{VALUE}};',
                    ],
                    'condition' => [
                        'slide_' . $index . '_bgimage[url]!' => '',
                    ],
                    'default' => 'no-repeat'
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bg_size',
                [
                    'label' => esc_html_x( 'Size', 'Background Control', 'axi-system' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'responsive' => true,
                    'options' => [
                        '' => esc_html_x( 'Default', 'Background Control', 'axi-system' ),
                        'auto' => esc_html_x( 'Auto', 'Background Control', 'axi-system' ),
                        'cover' => esc_html_x( 'Cover', 'Background Control', 'axi-system' ),
                        'contain' => esc_html_x( 'Contain', 'Background Control', 'axi-system' ),
                        'initial' => esc_html_x( 'Custom', 'Background Control', 'axi-system' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-size: {{VALUE}};',
                    ],
                    'condition' => [
                        'slide_' . $index . '_bgimage[url]!' => '',
                    ],
                    'default' => 'cover',
                ]
            );

            $this->add_control(
                'slide_' . $index . '_bg_custom_width',
                [
                    'label' => esc_html_x( 'Width', 'Background Control', 'axi-system' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'responsive' => true,
                    'size_units' => [ 'px', 'em', '%', 'vw' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        'vw' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 100,
                        'unit' => '%',
                    ],
                    'required' => true,
                    'selectors' => [
                        '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-size: {{SIZE}}{{UNIT}} auto',

                    ],
                    'condition' => [
                        'size' => [ 'initial' ],
                        'slide_' . $index . '_bgimage[url]!' => '',
                    ],
                    'device_args' => [
                        \Elementor\Controls_Stack::RESPONSIVE_TABLET => [
                            'selectors' => [
                                '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-size: {{SIZE}}{{UNIT}} auto',
                            ],
                            'condition' => [
                                'slide_' . $index . '_bg_size_tablet' => [ 'initial' ],
                            ],
                        ],
                        \Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
                            'selectors' => [
                                '{{WRAPPER}} .axi-banner-carousel .banner-entry-' . $index => 'background-size: {{SIZE}}{{UNIT}} auto',
                            ],
                            'condition' => [
                                'slide_' . $index . '_bg_size_mobile' => [ 'initial' ],
                            ],
                        ],
                    ],
                ]
            );

            $this->add_control(
                'slide_' . $index . '_content',
                [
                    'label' => esc_html__( 'Content', 'axi-system' ),
                    'type'  => \Elementor\Controls_Manager::WYSIWYG,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'slide_' . $index . '_buttons',
                [
                    'label'       => esc_html__( 'Buttons', 'axi-system' ),
                    'type'        => \Elementor\Controls_Manager::REPEATER,
                    'fields'      => $slide_button->get_controls(),
                    'separator'   => 'before',
                    'title_field' => '{{btn_text}}'
                ]
            );

            $this->end_controls_section();
        }
        /* / Slides */

        /*--------------------------------------------------------------
        # General Styling
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'slide_max_width',
            [
                'label'  => esc_html__( 'Slide Max Width', 'axi-system' ),
                'type'   => \Elementor\Controls_Manager::SLIDER,
                'units'  => [ 'px', '%', 'vw' ],
                'range'  => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-body' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'slide_padding',
            [
                'label'      => esc_html__( 'Slide Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-body-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* / General Styling */

        /*--------------------------------------------------------------
        # Background Overlay
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_background_overlay_style',
            [
                'label' => esc_html__( 'Background Overlay', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'slide_bg_overlay',
                'selector' => '{{WRAPPER}} .axi-banner-carousel .entry-background-overlay',
            ]
        );

        $this->add_control(
            'slide_bg_overlay_opacity',
            [
                'label' => esc_html__( 'Opacity', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => .5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-background-overlay' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'slide_bg_overlay_background' => [ 'classic', 'gradient' ],
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'slide_bg_overlay_css_filters',
                'selector' => '{{WRAPPER}} .axi-banner-carousel .entry-background-overlay',
            ]
        );

        $this->add_control(
            'slide_bg_overlay_blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Normal', 'axi-system' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-background-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
        /* / Background Overlay */

        /*--------------------------------------------------------------
        # Content Styling
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__( 'Content', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__( 'Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-content' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .axi-banner-carousel .entry-content'
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        /* / Content Styling */

        /*--------------------------------------------------------------
        # Buttons Styling
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'button_container_margin',
            [
                'label'      => esc_html__( 'Container Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => esc_html__( 'Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-button' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'button_bgcolor',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-button' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .axi-banner-carousel .entry-button'
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => esc_html__( 'Width', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 512,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-button' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_height',
            [
                'label' => esc_html__( 'Height', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 512,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-button' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-banner-carousel .entry-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* / Content Styling */
    }

    /**
     * Add custom condition for each slide
     *
     * @param  integer $index
     * @return array
     */
    private function slide_conddition( $index )
    {
        $values = [];
        for ( $i = $index; $i <= $this->max_slide_count; $i++ )
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
        $this->add_render_attribute( 'wrapper', 'class', 'axi-carousel-wrapper axi-banner-carousel-wrapper' );

        $prev_arrows_id = 'axi-prev-arrow-' . academyxi_guid();
        $next_arrows_id = 'axi-next-arrow-' . academyxi_guid();

        $carousel_options = [
            'slidesToShow'   => 1,
            'slidesToScroll' => 1,
            'arrows'         => true,
            'dots'           => true,
            'speed'          => absint( $settings['slide_scroll_speed'] ),
            'autoplay'       => false,
            'infinite'       => false,
            'responsive'     => [
                [
                    "breakpoint" => 768,
                    'settings'   => [
                        'arrows' => false
                    ]
                ]
            ],
            'prevArrow' => '#' . $prev_arrows_id,
            'nextArrow' => '#' . $next_arrows_id
        ];

        if ( $settings['slide_auto_scroll'] )
        {
            $carousel_options['autoplay'] = true;
            $autoplay_speed = absint( $settings['slide_auto_scroll_speed'] );
            $autoplay_speed = $autoplay_speed >= 500 && $autoplay_speed <= 10000 ? $autoplay_speed : 5000;
            $carousel_options['autoplaySpeed'] = $autoplay_speed;
        }

        $carousel_atts = [
            'class' => [
                'axi-carousel',
                'axi-banner-carousel'
            ]
        ];

        if ( $settings['slide_count'] > 1 )
        {
            $carousel_atts['data-axiel'] = 'carousel';
            $carousel_atts['data-options'] = json_encode( $carousel_options );
        }

        $this->add_render_attribute( 'carousel', $carousel_atts );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'carousel' ); ?>>
                <?php
                for ( $i = 0; $i < $settings['slide_count']; $i ++ ) :

                    $index = $i + 1;
                    $image_url = '';

                    if ( ! empty( $settings['slide_' . $index . '_bgimage']['url'] ) ) :
                        $image_url = $settings['slide_' . $index . '_bgimage']['url'];
                    endif;

                    $item_content = $this->parse_text_editor( $settings['slide_' . $index . '_content'] );
                    $item_buttons = $settings['slide_' . $index . '_buttons'];
                    ?>
                    <div class="banner-slide">
                        <div class="banner-entry banner-entry-<?php echo esc_attr( $index ); ?>"
                            <?php echo ( $image_url ? 'style="background-image:url(' . esc_url( $image_url ) . ')"' : '' ); ?>>
                            <div class="entry-background-overlay"></div>
                            <div class="entry-body">
                                <div class="entry-body-inner">
                                    <div class="entry-content">
                                        <?php echo $item_content; ?>
                                    </div>
                                    <div class="entry-actions">
                                        <?php
                                        foreach ( $item_buttons as $button ) :
                                            if ( empty( $button['btn_text'] ) ) :
                                                continue;
                                            endif;
                                            $btn_html = '';
                                            $btn_page_id = absint( $button['btn_page_id'] );
                                            if ( $btn_page_id > 0 ) :
                                                $btn_html = sprintf(
                                                    '<a class="entry-button shape-%1$s" href="%2$s"><span class="button-label">%3$s</span></a>',
                                                    esc_attr( $button['btn_shape'] ),
                                                    esc_url( get_permalink( $btn_page_id ) ),
                                                    esc_html( $button['btn_text'] )
                                                );
                                            elseif ( ! empty( $button['btn_link']['url'] ) ) :
                                                $btn_html = sprintf(
                                                    '<a class="entry-button shape-%1$s" href="%2$s" target="%3$s"%4$s><span class="button-label">%5$s</span></a>',
                                                    esc_attr( $button['btn_shape'] ),
                                                    esc_url( $button['btn_link']['url'] ),
                                                    $button['btn_link']['is_external'] ? '_blank' : '_self',
                                                    $button['btn_link']['nofollow'] ? ' rel="nofollow"' : '',
                                                    esc_html( $button['btn_text'] )
                                                );
                                            endif;

                                            if ( $btn_html ) :
                                                echo $btn_html;
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endfor;
                ?>
            </div>
            <?php if ( $settings['slide_count'] > 1 ) : ?>
                <div class="axi-carousel-arrows">
                    <?php 
                        printf(
                            '<button id="%1$s" class="axi-slick-prev">' .
                                '<span class="screen-reader-text arrow-text">%2$s</span>' .
                                '<svg viewBox="0 0 512 512" class="arrow-icon" role="img">' .
                                    ' <use href="#axi-icon-chevron" xlink:href="#axi-icon-chevron"></use>' .
                                '</svg>' .
                            '</button>',
                            esc_attr( $prev_arrows_id ),
                            esc_html__( 'Previous', 'axi-system' )
                        );
                        printf(
                            '<button id="%1$s" class="axi-slick-next">' .
                                '<span class="screen-reader-text arrow-text">%2$s</span>' .
                                '<svg viewBox="0 0 512 512" class="arrow-icon" role="img">' .
                                    ' <use href="#axi-icon-chevron" xlink:href="#axi-icon-chevron"></use>' .
                                '</svg>' .
                            '</button>',
                            esc_attr( $next_arrows_id ),
                            esc_html__( 'Next', 'axi-system' )
                        );
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}