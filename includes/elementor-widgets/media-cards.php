<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Media Cards Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_Media_Cards extends \Elementor\Widget_Base
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
        return 'axi-media-cards';
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
        return esc_html__( 'AXi Media Cards', 'axi-system' );
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
        # Content
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_base',
            [
                'label' => esc_html__( 'Base', 'axi-system' ),
            ]
        );
        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html( 'Columns', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'tablet_default' => [
                    'size' => 2
                ],
                'mobile_default' => [
                    'size' => 1
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 4,
                    ],
                ]
            ]
        );
        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => esc_html__( 'Item Spacing', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .mediacard-entry' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Item Padding', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .axi-media-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->add_control(
            'item_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-media-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $card = new \Elementor\Repeater();
        $card->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ]
            ]
        );
        $card->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your title', 'axi-system' )
            ]
        );
        $card->add_control(
            'desc',
            [
                'label' => esc_html__( 'Description', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Description', 'axi-system' )
            ]
        );
        $card->add_control(
            'image_pos',
            [
                'label' => esc_html__( 'Image Position', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'axi-system' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'axi-system' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false
            ]
        );
        $this->add_control(
            'image_height_full',
            [
                'label' => esc_html__( 'Full Height Image', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SWITCHER
            ]
        );
        $this->add_control(
            'cards',
            [
                'label' => esc_html__( 'Cards', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $card->get_controls(),
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'view',
            [
                'label' => esc_html__( 'View', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->end_controls_section();
        /* /Content */

        /*--------------------------------------------------------------
        # Item Image Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_image_box',
            [
                'label' => esc_html__( 'Item Image Box', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'iimage_box_width',
            [
                'label' => esc_html__( 'Image Box Width', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .entry-image-box' => 'width:{{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'iimage_box_margin',
            [
                'label' => esc_html__( 'Margin', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'iimage_box_padding',
            [
                'label' => esc_html__( 'Padding', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Item Image Style */

        /*--------------------------------------------------------------
        # Item Content Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_content_box',
            [
                'label' => esc_html__( 'Item Content Box', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'icontent_box_margin',
            [
                'label' => esc_html__( 'Margin', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-content-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icontent_box_padding',
            [
                'label' => esc_html__( 'Padding', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-content-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Item Content Style */

        /*--------------------------------------------------------------
        # Item Title Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_title',
            [
                'label' => esc_html__( 'Item Title', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'ititle_color',
            [
                'label' => esc_html__( 'Text Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ititle_align',
            [
                'label' => esc_html__( 'Alignment', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'axi-system' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'axi-system' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'axi-system' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'axi-system' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .entry-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ititle_typo',
                'selector' => '{{WRAPPER}} .entry-title',
            ]
        );
        $this->add_responsive_control(
            'ititle_margin',
            [
                'label' => esc_html__( 'Margin', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ititle_padding',
            [
                'label' => esc_html__( 'Padding', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Item Title Style */

        /*--------------------------------------------------------------
        # Description Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling_content',
            [
                'label' => esc_html__( 'Item Description', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'idesc_color',
            [
                'label' => esc_html__( 'Text Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'idesc_align',
            [
                'label' => esc_html__( 'Alignment', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'axi-system' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'axi-system' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'axi-system' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'axi-system' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .entry-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'idesc_typo',
                'selector' => '{{WRAPPER}} .entry-content',
            ]
        );
        $this->add_responsive_control(
            'idesc_margin',
            [
                'label' => esc_html__( 'Margin', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'idesc_padding',
            [
                'label' => esc_html__( 'Padding', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Description Style */
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
        $entries_class = [
            'axi-carousel axi-media-card-entries',
            'mediacards-desktop-col-' . $settings['columns']['size'],
            'mediacards-tablet-col-' . $settings['columns_tablet']['size'],
            'mediacards-mobile-col-' . $settings['columns_mobile']['size']
        ];
        $this->add_render_attribute( 'wrapper', 'class', 'axi-carousel-wrapper axi-media-cards-wrapper' );

        $carousel_options = [
            'slidesToShow'   => absint( $settings['columns']['size'] ),
            'slidesToScroll' => 1,
            'arrows'         => false,
            'dots'           => true,
            'speed'          => 350,
            'autoplay'       => false,
            'infinite'       => false,
            'responsive'     => [
                [
                    "breakpoint" => 768,
                    'settings'   => [
                        'slidesToShow' => absint( $settings['columns_mobile']['size'] ),
                    ]
                ],
                [
                    "breakpoint" => 992,
                    'settings'   => [
                        'slidesToShow' => absint( $settings['columns_tablet']['size'] ),
                    ]
                ]
            ]
        ];

        $this->add_render_attribute( 'entries', [
            'class' => $entries_class,
            'data-axiel' => 'mobile-carousel-only',
            'data-options' => json_encode( $carousel_options )
        ] );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'entries' ); ?>>
                <?php
                foreach( $settings['cards'] as $card ) : ?>
                    <div class="mediacard-entry">
                        <div class="axi-media-card axi-media-card-image-<?php echo esc_attr( $card['image_pos'] );
                            echo ( 'yes' == $settings['image_height_full'] ? ' axi-media-card-image-full-height' : '' ); ?>">
                            <div class="entry-image-box">
                                <?php 
                                    $image_src = $image_html = '';
                                    if ( ! empty( $card['image']['id'] ) ) :
                                        $image_src = wp_get_attachment_image_url( $card['image']['id'], 'large' );
                                        $image_html = wp_get_attachment_image( $card['image']['id'], 'large' );
                                    elseif ( ! $image_src && isset( $card['image']['url'] ) ) :
                                        $image_src = $card['image']['url'];
                                        $image_html = '<img src="' . esc_url( $card['image']['url'] ) . '" alt=""/>';
                                    endif;
                                    echo '<div class="entry-image"';
                                    if ( 'yes' == $settings['image_height_full'] ) :
                                        echo ' style="background-image:url(' . esc_url( $image_src ) . ')"';
                                    endif;
                                    echo '>';
                                    echo $image_html;
                                    echo '</div>';
                                ?>
                            </div>
                            <div class="entry-content-box">
                                <div class="entry-content-box-inner">
                                <?php
                                    if ( $card['title'] ) :
                                        printf( '<h3 class="entry-title">%s</h3>', esc_html( $card['title'] ) );
                                    endif;
                                    $desc = $this->parse_text_editor( $card['desc'] );
                                    if ( $desc ) :
                                        printf( '<div class="entry-content">%s</div>', $desc );
                                    endif;
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach; ?>
            </div>
        </div>
        <?php
    }
}