<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Feedbacks Elementor Widget for AcademyXi
 *
 * @since 1.0.0
 */
class Widget_Feedbacks extends \Elementor\Widget_Base
{
    /**
     * Feedback array, contains all possible ulfiltered and filtered.
     *
     * @var array
     * @access protected
     */
    protected $feedbacks;

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
        $this->generate_feedback_data();
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
        return 'axi-feedbacks';
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
        return esc_html__( 'AXi Feedbacks', 'axi-system' );
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
        return 'eicon-testimonial';
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
            'columns',
            [
                'label' => esc_html( 'Columns', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 2,
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

        $this->add_control(
            'discipline_filter_on',
            [
                'label' => esc_html__( 'Filter by Page Discipline', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'type_filter',
            [
                'label' => esc_html__( 'Filter by Feedback Type', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SELECT,
                'options'     => [
                    ''              => esc_html__( 'Unfiltered', 'axi-system' ),
                    'user'          => esc_html__( 'User', 'axi-system' ),
                    'corporate'     => esc_html__( 'Corporate', 'axi-system' ),
                    'studentclient' => esc_html__( 'Student, Client', 'axi-system' ),
                ]
            ]
        );

        $this->add_control(
            'unfiltered_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['unfiltered'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on' => '',
                    'type_filter' => ''

                ]
            ]
        );

        $this->add_control(
            'discipline_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['discipline'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on!' => '',
                    'type_filter' => ''
                ]
            ]
        );

        $this->add_control(
            'user_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['user'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on' => '',
                    'type_filter' => 'user'
                ]
            ]
        );

        $this->add_control(
            'corporate_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['corporate'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on' => '',
                    'type_filter' => 'corporate'
                ]
            ]
        );

        $this->add_control(
            'studentclient_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['studentclient'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on' => '',
                    'type_filter' => 'studentclient'
                ]
            ]
        );

        $this->add_control(
            'discipline_user_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['discipline_user'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on!' => '',
                    'type_filter' => 'user'
                ]
            ]
        );

        $this->add_control(
            'discipline_corporate_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['discipline_corporate'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on!' => '',
                    'type_filter' => 'corporate'
                ]
            ]
        );

        $this->add_control(
            'discipline_studentclient_feedbacks',
            [
                'label'       => esc_html__( 'Feedbacks', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $this->feedbacks['discipline_studentclient'],
                'default'     => '',
                'separator'   => 'before',
                'condition'   => [
                    'discipline_filter_on!' => '',
                    'type_filter' => 'studentclient'
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
        # Item Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'Item Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'item_background',
            [
                'label'     => esc_html__( 'Background', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-feedback-entry' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_color_quote',
            [
                'label'     => esc_html__( 'Color icon Quote', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-feedback-entry .author-image-box .axi-icon-quot' => 'color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_control(
			'width_slider',
			[
				'label' => __( 'Width', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .axi-feedbacks' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
        /* /Item Style */
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
        global $post;

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', 'axi-feedbacks-wrapper axi-carousel-wrapper' );

        $prev_arrows_id = 'axi-prev-arrow-' . academyxi_guid();
        $next_arrows_id = 'axi-next-arrow-' . academyxi_guid();

        $columns = absint( $settings['columns']['size'] );
        $columns = ( $columns < 1 || $columns > 2 ) ? 2 : $columns;

        $carousel_options = [
            'slidesToShow'   => $columns,
            'slidesToScroll' => 1,
            'arrows'         => true,
            'dots'           => false,
            'speed'          => absint( $settings['slide_scroll_speed'] ),
            'autoplay'       => false,
            'infinite'       => false,
            'prevArrow'      => '#' . $prev_arrows_id,
            'nextArrow'      => '#' . $next_arrows_id
        ];

        if ( $settings['slide_auto_scroll'] )
        {
            $carousel_options['autoplay'] = true;
            $autoplay_speed = absint( $settings['slide_auto_scroll_speed'] );
            $autoplay_speed = $autoplay_speed >= 500 && $autoplay_speed <= 10000 ? $autoplay_speed : 5000;
            $carousel_options['autoplaySpeed'] = $autoplay_speed;
        }
        
        $feedback_settings = '';
        $feedback_ids = [];
        $feedback_options = [];

        if ( $settings['discipline_filter_on'] )
        {
            switch( $settings['type_filter'] )
            {
                case 'user':
                    $feedback_settings = $settings['discipline_user_feedbacks'];
                    $feedback_options = $this->feedbacks['discipline_user'];
                    break;
                case 'corporate':
                    $feedback_settings = $settings['discipline_corporate_feedbacks'];
                    $feedback_options = $this->feedbacks['discipline_corporate'];
                    break;
                case 'studentclient':
                    $feedback_settings = $settings['discipline_studentclient_feedbacks'];
                    $feedback_options = $this->feedbacks['discipline_studentclient'];
                    break;
                default:
                    $feedback_settings = $settings['discipline_feedbacks'];
                    $feedback_options = $this->feedbacks['discipline'];
                    break;
            }
        }
        else
        {
            switch( $settings['type_filter'] )
            {
                case 'user':
                    $feedback_settings = $settings['user_feedbacks'];
                    $feedback_options = $this->feedbacks['user'];
                    break;
                case 'corporate':
                    $feedback_settings = $settings['corporate_feedbacks'];
                    $feedback_options = $this->feedbacks['corporate'];
                    break;
                case 'studentclient':
                    $feedback_settings = $settings['studentclient_feedbacks'];
                    $feedback_options = $this->feedbacks['studentclient'];
                    break;
                default:
                    $feedback_settings = $settings['unfiltered_feedbacks'];
                    $feedback_options = $this->feedbacks['unfiltered'];
                    break;
            }
        }

        if ( isset( $feedback_options[0] ) )
        {
            unset( $feedback_options[0] );
        }

        $feedback_ids = explode( ',', $feedback_settings );
        foreach( $feedback_ids as $key => $feedback_id )
        {
            if ( ! array_key_exists( $feedback_id, $feedback_options ) )
            {
                $feedback_ids[ $key ] = false;
            }
        }
        $feedback_ids = array_filter( $feedback_ids );
        if ( count( $feedback_ids ) < 2 )
        {
            $carousel_options['slidesToShow'] = 1;
        }
        else
        {
            $carousel_options['responsive'] = [
                [
                    'breakpoint' => 1200,
                    'settings'   => [
                        'slidesToShow' => 1
                    ]
                ]
            ];
        }
		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php
            if ( $feedback_ids ) :
                ?>
                <div class="axi-carousel axi-feedback-carousel" data-axiel="carousel" data-options=<?php echo esc_attr( json_encode( $carousel_options ) ); ?>>
                    <?php
                    foreach( $feedback_ids as $fid ) :
                        $feedback_post = get_post( $fid );
                        if ( empty( $feedback_post ) ) :
                            continue;
                        endif;
                        ?>
                        <div class="carousel-entry">
                            <div class="axi-feedback-entry">
                                <div class="entry-header">
                                    <div class="entry-image">
                                        <div class="author-image-box">
                                            <div class="image-holder"><?php echo get_the_post_thumbnail( $feedback_post, 'thumbnail' ); ?></div>
                                            <svg class="axi-icon-quot" viewBox="0 0 512 512">
                                                <use href="#axi-icon-quot" xlink:href="#axi-icon-quot"></use>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="entry-info">
                                        <h4 class="entry-highlight">“<?php
                                            echo esc_html( get_post_meta( $feedback_post->ID, '_highlight', true ) );
                                        ?>”</h4>
                                        <h5 class="entry-name"><?php
                                            echo get_the_title( $feedback_post );
                                        ?></h5>
                                        <div class="entry-designation"><?php
                                            echo esc_html( get_post_meta( $feedback_post->ID, '_designation', true ) );
                                        ?></div>
                                        <?php 
                                            if(get_post_meta( $feedback_post->ID, '_rating', true ) > 0){
                                                $rating = floatval( get_post_meta( $feedback_post->ID, '_rating', true ) );
                                                $rating = round( $rating, 1 );
                                                $rating = ( $rating < 0.5 || $rating > 5 ) ? 5 : $rating;

                                                if ( $rating > 0 ) :
                                                    $star_markup = '';
                                                    for ( $i = 0; $i < 5; $i++ ) :
                                                        $star_markup .= '<li><svg class="axi-icon-star" viewBox="0 0 512 512"> <use href="#axi-icon-star" xlink:href="#axi-icon-star"></use></svg></li>';
                                                    endfor;
                                                    ?>
                                                    <div class="stars">
                                                        <ul class="starlist current-stars" style="width:<?php echo esc_attr( $rating * 20 ); ?>%"><?php echo $star_markup; ?></ul>
                                                        <ul class="starlist all-stars"><?php echo $star_markup; ?></ul>
                                                    </div>
                                                    <?php
                                                endif;
                                            }
                                        ?>
                                    </div>
                                </div>

                                <div class="entry-content">
                                    <?php
                                        $feedback_post_content = do_shortcode( $feedback_post->post_content );
                                        echo wpautop( $feedback_post_content );
                                    ?>
                                </div>
                            </div>
                        </div><!-- carousel-entry -->
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

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _content_template() {}

    private function generate_feedback_data()
    {
        if ( $this->feedbacks )
        {
            return $this->feedbacks;
        }

        global $post;
        $pdis = wp_get_post_terms( $post->ID, 'axi_discipline', [ 'fields' => 'ids' ] );

        if ( ! empty( $pdis ) && ! is_wp_error( $pdis ) )
        {
            $pdis = $pdis[0];
        }
        else
        {
            $pdis = 0;
        }

        $this->feedbacks = [
            'unfiltered'               => [],
            'discipline'               => [],
            'user'                     => [],
            'corporate'                => [],
            'studentclient'            => [],
            'discipline_user'          => [],
            'discipline_corporate'     => [],
            'discipline_studentclient' => []
        ];

        foreach( $this->feedbacks as $key => $feedback )
        {
            $this->feedbacks[ $key ][0] = esc_html__( '- Add New -', 'axi-system' );
        }

        $feedbacks = get_posts([
            'post_type'      => 'axi_feedback',
            'post_status'    => 'publish',
            'posts_per_page' => -1
        ]);

        foreach( $feedbacks as $feedback )
        {
            $feedback_dis = wp_get_post_terms( $feedback->ID, 'axi_discipline', [ 'fields' => 'ids' ] );
            $feedback_type = get_post_meta( $feedback->ID, '_type', true );
            $feedback_item = '[#' . $feedback->ID . '] ' . $feedback->post_title;
            $dis_filter = false;

            $this->feedbacks['unfiltered'][ $feedback->ID ] = $feedback_item;

            if ( ! empty( $feedback_dis ) && ! is_wp_error( $feedback_dis ) && $pdis && in_array( $pdis, $feedback_dis ) )
            {
                $dis_filter = true;
                $this->feedbacks['discipline'][ $feedback->ID ] = $feedback_item;
            }

            switch( $feedback_type )
            {
                case 'user':
                    $this->feedbacks['user'][ $feedback->ID ] = $feedback_item;
                    if ( $dis_filter )
                    {
                        $this->feedbacks['discipline_user'][ $feedback->ID ] = $feedback_item;
                    }
                    break;
                case 'corporate':
                    $this->feedbacks['corporate'][ $feedback->ID ] = $feedback_item;
                    if ( $dis_filter )
                    {
                        $this->feedbacks['discipline_corporate'][ $feedback->ID ] = $feedback_item;
                    }
                    break;
                case 'studentclient':
                    $this->feedbacks['studentclient'][ $feedback->ID ] = $feedback_item;
                    if ( $dis_filter )
                    {
                        $this->feedbacks['discipline_studentclient'][ $feedback->ID ] = $feedback_item;
                    }
                    break;
                default:
                    break;
            }
        }
    }
}