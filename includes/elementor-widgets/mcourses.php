<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom MCourse Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_MCourses extends \Elementor\Widget_Base
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
        return 'axi-mcourses';
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
        return esc_html__( 'AXi Mode Courses', 'axi-system' );
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
        global $post;

        /*--------------------------------------------------------------
        # Content
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Base', 'axi-system' ),
            ]
        );

        $this->add_control(
            'select_prefix',
            [
                'label'   => esc_html__( 'Select Prefix Title', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Enter your title', 'axi-system' ),
                'default'     => esc_html__( "I am interested in learning", 'axi-system' ),
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Course Button Text', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Learn More', 'axi-system' ),
                'label_block' => true,
                'separator'   => 'before'
            ]
        );

        $this->add_control(
            'btn_link',
            [
                'label'   => esc_html__( 'Course Button Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                ]
            ]
        );

        $this->add_control(
            'hide_title_dropdown', [
                'label' => esc_html__( 'Hide title and dropdown', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER
            ]
        );
        
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'axi-system' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT
            ]
        );

        $disciplines = wp_get_post_terms( $post->ID, 'axi_discipline' );

        $cqargs = [
            'post_type'      => 'axi_course',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'paged'          => 1
        ];

        if ( ! is_wp_error( $disciplines ) && ! empty( $disciplines ) )
        {
            $discipline_term_ids = [];
            foreach( $disciplines as $discipline )
            {
                $discipline_term_ids[] = $discipline->term_id;
            }
            $cqargs['tax_query'] = [
                [
                    'taxonomy'         => 'axi_discipline',
                    'field'            => 'id',
                    'terms'            => $discipline_term_ids,
                    'include_children' => true,
                    'operator'         => 'IN',
                ]
            ];
        }

        $course_posts = get_posts( $cqargs );
        $course_select_options = [
            0 => esc_html__( '- Add Course -', 'axi-system' )
        ];

        foreach( $course_posts as $course )
        {
            $course_select_options[ $course->ID ] = '[#' . $course->ID .'] ' . $course->post_title;
        }

        $repeater->add_control(
            'courses',
            [
                'label'       => esc_html__( 'Courses', 'axi-system' ),
                'label_block' => true,
                'type'        => 'axi_sortable_select',
                'options'     => $course_select_options,
                'default'     => ''
            ]
        );

        $repeater->add_control(
            'cbuttons',
            [
                'label'       => esc_html__( 'Course Buttons', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager                 :: TEXTAREA,
                'description' => esc_html__( 'One per line with proper format: [Label] | [URL] | [_blank or _self] | [nofollow or empyty]. These lines and added courses are realted respectively. Leave blank to use the Course Button settings above.', 'axi-system' ),
                'placeholder' => '[Label] | [URL] | [_blank or _self] [nofollow or empyty]'
            ]
        );

        $repeater->add_control(
            'optid',
            [
                'label'       => esc_html__( 'Option ID', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id. Copy this to (or paste from) AXi Study Modes entry respectively.', 'axi-system' ),
                'label_block' => true,
                'default'     => ''
            ]
        );

        $this->add_control(
            'modes',
            [
                'label'       => esc_html__( 'Modes', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
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
        # General Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_general_style',
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
                    'size' => 3,
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

        $this->end_controls_section();
        /* /General Style */

        /*--------------------------------------------------------------
        # Item Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'Course Items', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__( 'Title', 'axi-system' ),
                'name'     => 'courses_title_typo',
                'selector' => '{{WRAPPER}} .mcourse-entry .entry-title',
                
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__( 'Sumary', 'axi-system' ),
                'name'     => 'courses_summary_typo',
                'selector' => '{{WRAPPER}} .mcourse-entry .entry-content',
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
        $this->add_render_attribute( 'wrapper', [
            'class'               => 'axi-mcources-wrapper',
            'data-axiwidget-role' => 'container'
        ]);
        $this->add_inline_editing_attributes( 'select_prefix' );

        $carousel_options = [
            'slidesToShow'   => absint( $settings['columns']['size'] ),
            'slidesToScroll' => 1,
            'arrows'         => false,
            'dots'           => false,
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
        
        $disciplines = wp_get_post_terms( $post->ID, 'axi_discipline' );
        $disciplines_ids = [];
        if ( $disciplines && ! is_wp_error( $disciplines ) )
        {
            foreach ( $disciplines as $discipline )
            {
                $disciplines_ids[] = $discipline->term_id;
            }
        }
        $el_id = ! empty( $settings['_element_id'] ) ? trim( $settings['_element_id'] ) : '';

        $btn_text = $settings['btn_text'] ? $settings['btn_text'] : esc_html__( 'Learn More', 'axi-system' );
        $btn_link = $settings['btn_link'];
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="section-header<?php echo ( $settings['hide_title_dropdown'] ? ' hidden' : '' ); ?>">
                <h4 class="section-title-prefix"><?php echo esc_html( $settings['select_prefix'] ); ?></h4>
                <div class="mode-select-box" data-axiel="mode-select" data-axi-wrapperid="<?php echo esc_attr( $el_id ); ?>">
                    <a href="javascript:;" class="mode-select-display" data-axirole="mode-select-display"><?php
                        esc_html_e( 'Select one', 'axi-system' );
                    ?></a>
                    <ul class="mode-select-dropdown" data-axirole="mode-select-dropdown">
                        <?php
                        $showed_modes = [];
                        foreach( $settings['modes'] as $mode ) :
                            if ( array_key_exists( $mode['optid'], $showed_modes ) ) :
                                continue;
                            endif;
                            $mode['optid'] = 'mcid-' . ( $mode['optid'] ? trim( $mode['optid'] ) : academyxi_guid() );
                            $showed_modes[ $mode['optid'] ] = $mode;
                            printf(
                                '<li data-mode="%1$s">' .
                                    '<a href="javascript:;">%2$s</a>' .
                                '</li>',
                                esc_attr( $mode['optid'] ),
                                $mode['title'] ? esc_html( $mode['title'] ) : esc_html__( 'Untitled', 'axi-system' )
                            );
                        endforeach;
                        ?>
                    </ul>
                    <svg class="dropdown-icon" viewBox="0 0 512 512">
                         <use href="#axi-icon-chevron" xlink:href="#axi-icon-chevron"></use>
                    </svg>
                </div>
            </div>
            <div class="section-content">
                <?php
                foreach( $showed_modes as $mode ) :
                    $course_ids = explode( ',', $mode['courses'] );
                    $course_btns = explode( "\n", $mode['cbuttons'] );
                    $courses = [];
                    foreach( $course_ids as $ckey => $course_id ) :
                        $cid = absint( $course_id );
                        if ( $cid <= 0 ) :
                            unset( $course_ids[ $ckey ] );
                        endif;
                        if ( empty( $disciplines_ids ) ) :
                            continue;
                        endif;
                        $cdisciplines = wp_get_post_terms( $cid, 'axi_discipline' );
                        if ( ! $cdisciplines || is_wp_error( $cdisciplines ) ) :
                            continue;
                        endif;
                        $valid = false;
                        foreach ( $cdisciplines as $cdiscipline ) :
                            if ( in_array( $cdiscipline->term_id, $disciplines_ids ) ) :
                                $valid = true;
                                break;
                            endif;
                        endforeach;
                        if ( ! $valid ) :
                            unset( $course_ids[ $ckey ] );
                        endif;
                        $cbtn_data = [];
                        if ( ! empty( $course_btns[ $ckey ] ) ) :
                            $cbtn_data = explode( '|', $course_btns[ $ckey ] );
                            foreach( $cbtn_data as $btnKey => $btnVal ) :
                                $cbtn_data[ $btnKey ] = trim( $btnVal );
                            endforeach;
                        endif;
                        $courses[ $course_id ] = [
                            'label'       => '',
                            'url'         => '',
                            'is_external' => '',
                            'nofollow'    => ''
                        ];
                        if ( ! empty( $cbtn_data[0] ) ) :
                            $courses[ $course_id ]['label'] = $cbtn_data[0] ? $cbtn_data[0] : $btn_text;
                        else :
                            $courses[ $course_id ]['label'] = $btn_text;
                        endif;
                        if ( ! empty( $cbtn_data[1] ) ) :
                            $courses[ $course_id ]['url'] = $cbtn_data[1];
                        else :
                            $courses[ $course_id ]['url'] = ! empty( $btn_link['url'] ) ? $btn_link['url'] : '';
                        endif;
                        if ( ! empty( $cbtn_data[2] ) ) :
                            $courses[ $course_id ]['is_external'] = ( '_blank' == $cbtn_data[2] ) ? '_blank' : '';
                        else :
                            $courses[ $course_id ]['is_external'] = $btn_link['is_external'] ? '_blank' : '';
                        endif;
                        if ( ! empty( $cbtn_data[3] ) ) :
                            $courses[ $course_id ]['nofollow'] = ( 'nofollow' == $cbtn_data[3] ) ? 'nofollow' : '';
                        else :
                            $courses[ $course_id ]['nofollow'] = $btn_link['nofollow'] ? 'nofollow' : '';
                        endif;
                    endforeach;
                    $carousel_classes = [
                        'axi-carousel',
                        'axi-courses-carousel',
                        'axi-courses-carousel-tablet-c' . $settings['columns_tablet']['size'],
                        'axi-courses-carousel-desktop-c' . $settings['columns']['size']
                    ];
                    ?>
                    <div class="course-list-mode" data-axiel="courselist-mode" data-mode="<?php echo esc_attr( $mode['optid'] ); ?>">
                        <div class="<?php echo esc_attr( implode( ' ', $carousel_classes ) ); ?>"
                            data-axiel="mobile-carousel-only" data-options=<?php echo esc_attr( json_encode( $carousel_options ) ); ?>>
                            <?php
                            foreach( $courses as $course_id => $course_btn ) :
                                $course = get_post( $course_id );
                                if ( ! $course ) :
                                    continue;
                                endif;
                                ?>
                                <div class="carousel-entry">
                                    <div class="mcourse-entry">
                                        <h3 class="entry-title"><?php echo esc_html( get_the_title( $course ) ); ?></h3>
                                        <div class="entry-content"><?php
                                            $course_content = do_shortcode( $course->post_content );
                                            echo wpautop( $course_content );

                                            $prerequisites = get_post_meta( $course->ID, '_prerequisites', true );
                                            if ( ! $prerequisites ) :
                                                printf(
                                                    '<div class="prerequisites-req">%s</div>',
                                                    esc_html__( '*No prerequisites', 'axi-system' )
                                                );
                                            endif;
                                        ?></div>
                                        <?php
                                            if ( ! empty( $course_btn['url'] ) ) :
                                                echo '<div class="entry-footer">';
                                                printf(
                                                    '<a class="course-link" href="%1$s" target="%2$s"%3$s>%4$s</a>',
                                                    esc_url( $course_btn['url'] ),
                                                    $course_btn['is_external'] ? '_blank' : '_self',
                                                    $course_btn['nofollow'] ? ' rel="nofollow"' : '',
                                                    $course_btn['label']
                                                );
                                                echo '</div>';
                                            endif;
                                        ?>
                                    </div>
                                </div>
                                <?php
                            endforeach; ?>
                        </div>
                    </div>
                    <?php
                endforeach; ?>
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
    protected function _content_template() {}

    public function on_import( $element )
    {
        return \Elementor\Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
    }
}