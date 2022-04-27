<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Image Carousel Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_ImageCarousel extends \Elementor\Widget_Base
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
        return 'axi-imagecarousel';
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
        return esc_html__( 'AXi Image Carousel', 'axi-system' );
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
        # Content
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_content',
            [
                'label' => esc_html__( 'Content', 'axi-system' ),
            ]
        );
    
        $this->add_control(
            'title',
            [
                'label'   => esc_html__( 'Title', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Enter your title', 'axi-system' ),
                'default'     => esc_html__( 'What you’ll learn', 'axi-system' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__( 'Choose Image', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'axi-system' )
            ]
        );

        $this->add_control(
            'images',
            [
                'label'     => esc_html__( 'Images', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::REPEATER,
                'fields'    => $repeater->get_controls(),
                'separator' => 'before'
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
        # Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__( 'General', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_responsive_control(
            'columns',
            [
                'label'   => esc_html( 'Columns', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 4,
                ],
                'tablet_default' => [
                    'size' => 3
                ],
                'mobile_default' => [
                    'size' => 2
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ]
            ]
        );

        $this->add_control(
            'rows',
            [
                'label'   => esc_html( 'Rows', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 2,
                    ],
                ]
            ]
        );

        $this->add_responsive_control(
            'slide_scoll_num',
            [
                'label'   => esc_html( 'Slides per scoll', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 4,
                ],
                'tablet_default' => [
                    'size' => 3
                ],
                'mobile_default' => [
                    'size' => 2
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ]
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
                'type'  => \Elementor\Controls_Manager::SWITCHER
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

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'img_border',
                'selector'  => '{{WRAPPER}} .entry-image-box',
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} > .entry-image-box',
                ],
            ]
        );

        $this->end_controls_section();
        /* Style */
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
        $this->add_render_attribute( 'wrapper', 'class', 'axi-carousel-wrapper axi-image-carousel-wrapper' );

        $carousel_options = [
            'slidesToShow'   => absint( $settings['columns']['size'] ),
            'slidesToScroll' => absint( $settings['slide_scoll_num']['size'] ),
            'arrows'         => false,
            'dots'           => false,
            'speed'          => absint( $settings['slide_scroll_speed'] ),
            'autoplay'       => false,
            'infinite'       => false,
            'responsive'     => [
                [
                    "breakpoint" => 768,
                    'settings'   => [
                        'slidesToShow'   => absint( $settings['columns_mobile']['size'] ),
                        'slidesToScroll' => absint( $settings['slide_scoll_num_mobile']['size'] ),
                    ]
                ],
                [
                    "breakpoint" => 992,
                    'settings'   => [
                        'slidesToShow'   => absint( $settings['columns_tablet']['size'] ),
                        'slidesToScroll' => absint( $settings['slide_scoll_num_tablet']['size'] ),
                    ]
                ]
            ]
        ];

        if ( $settings['slide_auto_scroll'] )
        {
            $carousel_options['autoplay'] = true;
            $autoplay_speed = absint( $settings['slide_auto_scroll_speed'] );
            $autoplay_speed = $autoplay_speed >= 500 && $autoplay_speed <= 10000 ? $autoplay_speed : 5000;
            $carousel_options['autoplaySpeed'] = $autoplay_speed;
        }

        $rows = absint( $settings['rows']['size'] );
        $rows = ( 0 == $rows ? 1 : $rows );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="axi-carousel axi-image-carousel" data-axiel="carousel" data-options=<?php echo esc_attr( json_encode( $carousel_options ) ); ?>>
                <?php
                $total = count( $settings['images'] );
                $count = 0;

                foreach ( $settings['images'] as $key => $image ) :
                    $img = wp_get_attachment_image( absint( $image['image']['id'] ), 'medium' );
                    if ( ! $img ) :
                        continue;
                    endif;
                    $entry_html = $img;
                    if ( $image['link']['url'] ) :
                        $entry_html .= sprintf(
                            '<a class="image-box-link" href="%1$s" target="%2$s"%3$s>%4$s</a>',
                            esc_url( $image['link']['url'] ),
                            $image['link']['is_external'] ? '_blank' : '_self',
                            $image['link']['nofollow'] ? ' rel="nofollow"' : '',
                            esc_html( 'View', 'axi-system' )
                        );
                    endif;

                    if ( $count == 0 ) :
                        echo '<div class="carousel-entry">';
                    endif;

                    printf(
                        '<div class="entry-image-box">%s</div>',
                        $entry_html
                    );

                    $count++;

                    if ( $count > 0 && $count % $rows == 0 ) :
                        echo '</div>';
                        if ( $count < $total ) :
                            echo '<div class="carousel-entry">';
                        endif;
                    endif;
                endforeach;
                
                if ( $total % $rows > 0 ) :
                    echo '</div>';
                endif;
                ?>
            </div>
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
    protected function _content_template() {

    }
}