<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Instructors Elementor Widget for AcademyXi 
 *
 * @since 1.0.0
 */
class Widget_Instructors extends \Elementor\Widget_Base
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
        return 'axi-instructors';
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
        return esc_html__( 'AXi Instructors', 'axi-system' );
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
        $qargs = [
            'post_type'      => 'axi_instructor',
            'post_status'    => 'publish',
            'posts_per_page' => -1
        ];

        $instructors = get_posts( $qargs );
        $data = [];

        foreach( $instructors as $instructor )
        {
            $data[ $instructor->ID ] = '[#' . $instructor->ID . '] ' . $instructor->post_title;
        }

        /*--------------------------------------------------------------
        # Content
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_base',
            [
                'label' => esc_html__( 'Base', 'axi-system' ),
            ]
        );

        $this->add_control(
            'slide_scroll_speed',
            [
                'label'       => esc_html__( 'Scroll Speed', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'description' => esc_html__( 'Specify a scroll speed (in miliseconds)', 'axi-system' ),
                'placeholder' => 500,
                'default'     => 500
            ]
        );

        $this->add_control(
            'slide_auto_scroll',
            [
                'label' => esc_html__( 'Auto Scroll', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
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

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'post_id',
            [
                'label'   => esc_html__( 'Choose', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $data
            ]
        );

        $this->add_control(
            'instructors',
            [
                'label'       => esc_html__( 'Instructors', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => esc_html__( 'Instructor #', 'axi-system' ) . '{{post_id}}',
                'separator'   => 'before'
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
        /* /Content */

        /*--------------------------------------------------------------
        # Image Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_image_styling',
            [
                'label' => esc_html__( 'Image', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_shape',
            [
                'label'   => esc_html__( 'Shape', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'circle'  => esc_html__( 'Circle', 'axi-system' ),
                    'rounded' => esc_html__( 'Rounded', 'axi-system' ),
                    'square'  => esc_html__( 'Square', 'axi-system' )
                ],
                'default' => 'circle'
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'   => esc_html__( 'Width', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Image Style */

        /*--------------------------------------------------------------
        # Name Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_instructor_name',
            [
                'label' => esc_html__( 'Name', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'name_align',
            [
                'label'   => esc_html__( 'Alignment', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'axi-system' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'axi-system' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'axi-system' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'axi-system' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typo',
                'selector' => '{{WRAPPER}} .axi-instructor-entry .entry-title',
            ]
        );

        $this->add_responsive_control(
            'name_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'name_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Name Style */

        /*--------------------------------------------------------------
        # Summary Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_instructor_summary',
            [
                'label' => esc_html__( 'Summary', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'summary_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'summary_align',
            [
                'label'   => esc_html__( 'Alignment', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'axi-system' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'axi-system' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'axi-system' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'axi-system' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'summary_typo',
                'selector' => '{{WRAPPER}} .axi-instructor-entry .entry-content',
            ]
        );

        $this->add_responsive_control(
            'summary_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'summary_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-instructor-entry .entry-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Summary Style */
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
        $this->add_render_attribute( 'wrapper', 'class', 'axi-instructors-wrapper axi-carousel-wrapper' );

        $prev_arrows_id = 'axi-prev-arrow-' . academyxi_guid();
        $next_arrows_id = 'axi-next-arrow-' . academyxi_guid();

        $carousel_options = [
            'slidesToShow'   => 3,
            'slidesToScroll' => 1,
            'arrows'         => true,
            'dots'           => false,
            'speed'          => absint( $settings['slide_scroll_speed'] ),
            'autoplay'       => false,
            'infinite'       => true,
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
        
        $instructor_ids = [];
        if ( ! empty( $settings['instructors'] ) && is_array( $settings['instructors'] ) )
        {
            foreach ( $settings['instructors'] as $instructor )
            {
                $instructor_id = (int)$instructor['post_id'];
                if ( $instructor_id <= 0 )
                {
                    continue;
                }
                if ( ! in_array( $instructor_id, $instructor_ids ) )
                {
                    $instructor_ids[] = $instructor_id;
                }
            }
        }

        if ( count( $instructor_ids ) < 2 )
        {
            $carousel_options['slidesToShow'] = 1;
        }
        else
        {
            $carousel_options['responsive'] = [
                [
                    'breakpoint' => 992,
                    'settings'   => [
                        'slidesToShow' => 2
                    ]
                ]
            ];
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php
            if ( $instructor_ids ) : ?>
                <div class="axi-carousel axi-instructor-carousel" data-axiel="carousel" data-options=<?php echo esc_attr( json_encode( $carousel_options ) ); ?>>
                    <?php
                    foreach( $instructor_ids as $instructor_id ) :
                        $instructor_post = get_post( $instructor_id );
                        if ( empty( $instructor_post ) ) :
                            continue;
                        endif;
                        $ilink = get_post_meta( $instructor_id, '_page_link', true );
                        ?>
                        <div class="carousel-entry">
                            <div class="axi-instructor-entry">
                                <div class="entry-image">
                                    <?php $image_src = get_the_post_thumbnail_url( $instructor_id, 'medium' ); ?>
                                    <?php
                                    $attr_str = 'class="image-holder ' . esc_attr( $settings['image_shape'] ) . '-shape"';
                                    $attr_str .= ( $image_src ? ' style="background-image:url(' . esc_url( $image_src ) . ')"' : '' );
                                    if ( $ilink ) :
                                        echo '<a href="' . esc_url( $ilink ) . '" target="_blank"' . $attr_str . '>';
                                    else :
                                        echo '<div ' . $attr_str . '>';
                                    endif;
                                    echo get_the_post_thumbnail( $instructor_post, 'medium' );
                                    echo ( $ilink ? '</a>' : '</div>' );
                                    ?>
                                </div>
                                <h4 class="entry-title"><?php
                                    if ( $ilink ) :
                                        echo '<a href="' . esc_url( $ilink ) . '" target="_blank">';
                                    endif;
                                    echo get_the_title( $instructor_post );
                                    echo ( $ilink ? '</a>' : '' );
                                ?></h4>
                                <div class="entry-content">
                                    <?php
                                        $instructor_post_content = do_shortcode( $instructor_post->post_content );
                                        echo wpautop( $instructor_post_content );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach; ?>
                </div><!-- axi-carousel -->
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
                <?php
            endif; ?>
        </div>
        <?php
    }
}