<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Study Modes Elementor Widget for AcademyXi
 *
 * @since 1.0.0
 */
class Widget_StudyModes extends \Elementor\Widget_Base
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
        return 'axi-study-modes';
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
        return esc_html__( 'AXi Study Modes', 'axi-system' );
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
            'corresponding_mcourse_id',
            [
                'label'       => esc_html__( 'Course List CSS ID', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id. This is corresponding course list section ID where the mode drowndown and courses are populated', 'axi-system' ),
                'label_block' => true
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
        # Modes
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_modes',
            [
                'label' => esc_html__( 'Modes', 'axi-system' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__( 'Icon', 'axi-system' ),
                    'image' => esc_html__( 'Image', 'axi-system' )
                ],
                'default' => 'icon'
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image',
                ]
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Choose Icon', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'fa4compatibility' => 'icon',
                'condition' => [
                    'icon_type' => 'icon',
                ]
            ]
        );

        $repeater->add_control(
            'mcourse_tid',
            [
                'label' => esc_html__( 'Axi MCourse Option ID', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id. Copy this to (or paste from) AXi Mode Courses entry respectively.', 'axi-system' ),
                'label_block' => true,
                'default' => '',
            ]
        );

        $this->add_control(
            'modes',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    'icon_type' => 'icon',
                    'selected_icon' => [
                        'value' => 'fas fa-check',
                        'library' => 'fa-solid',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
        /* /Modes */

        /*--------------------------------------------------------------
        # Styling
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_styling',
            [
                'label' => esc_html__( 'Styling', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_responsive_control(
            'item_width',
            [
                'label'   => esc_html__( 'Item Width', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1280,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .study-mode-entries .mode-entry-link' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_space',
            [
                'label'   => esc_html__( 'Items Spacing', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .study-mode-entries .mode-entry-box' => 'padding-left:{{SIZE}}{{UNIT}};padding-right:{{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();
        /* /Styling */
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
        $this->add_render_attribute( 'wrapper', 'class', ['axi-study-modes-wrapper', 'axi-carousel-wrapper'] );

        $fallback_defaults = [
            'fa fa-check'
        ];
        $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();

        $prev_arrows_id = 'axi-prev-arrow-' . academyxi_guid();
        $next_arrows_id = 'axi-next-arrow-' . academyxi_guid();

        $carousel_options = [
            'slidesToShow'   => absint( $settings['columns']['size'] ),
            'slidesToScroll' => absint( $settings['columns']['size'] ),
            'arrows'         => true,
            'dots'           => false,
            'speed'          => 350,
            'autoplay'       => false,
            'infinite'       => false,
            'responsive'     => [
                [
                    "breakpoint" => 768,
                    'settings'   => [
                        'slidesToShow' => absint( $settings['columns_mobile']['size'] ),
                        'slidesToScroll' => absint( $settings['columns_mobile']['size'] ),
                    ]
                ],
                [
                    "breakpoint" => 992,
                    'settings'   => [
                        'slidesToShow' => absint( $settings['columns_tablet']['size'] ),
                        'slidesToScroll' => absint( $settings['columns_tablet']['size'] ),
                    ]
                ]
            ],
            'prevArrow' => '#' . $prev_arrows_id,
            'nextArrow' => '#' . $next_arrows_id
        ];
        if ( count( $settings['modes'] ) < 2 )
        {
            $carousel_options[ 'centerMode' ] = true;
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div
                class="axi-carousel study-mode-entries<?php
                    echo ' desktop-c' . absint( $settings['columns']['size'] );
                    echo ' tablet-c' . absint( $settings['columns_tablet']['size'] );
                    echo ' mobile-c' . absint( $settings['columns_mobile']['size'] );
                    ?>"
                data-axiel="mode-triggers"
                data-axitarget="<?php echo esc_attr( $settings['corresponding_mcourse_id'] ); ?>"
                data-coptions=<?php echo esc_attr( json_encode( $carousel_options ) ); ?>>
                <?php
                foreach ( $settings['modes'] as $index => $item ) :
                    ?>
                    <div class="mode-entry-box">
                        <?php 
                            $icon_type = $icon_html = $mode_name = $mode_id = $img_url = '';
                            if ( 'icon' == $item['icon_type'] ) :
                                if ( ! isset( $item['icon'] ) && ! $migration_allowed ) :
                                    $item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
                                endif;

                                $migrated = isset( $item['__fa4_migrated']['selected_icon'] );
                                $is_new = ! isset( $item['icon'] ) && $migration_allowed;
                                if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) :
                                    if ( $is_new || $migrated ) :
                                        ob_start();
                                        \Elementor\Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] );
                                        $icon_html = ob_get_clean();
                                    else :
                                        $icon_html = sprintf( '<i class="%s" aria-hidden="true"></i>', esc_attr( $item['icon'] ) );
                                    endif;
                                    $icon_type = 'icon';
                                endif;
                            else :
                                $image_id = absint( $item['image']['id'] );
                                $icon_html = wp_get_attachment_image( $image_id, 'full' );
                                $img_url = wp_get_attachment_image_url( $image_id, 'full' );
                                $icon_type = 'image';
                            endif;

                            $mcourse_tid = 'mcid-' . ( $item['mcourse_tid'] ? trim( $item['mcourse_tid'] ) : academyxi_guid() );

                            echo '<div class="mode-entry">';
                            printf(
                                '<a href="javascript:void(0)" class="mode-entry-link" data-axirole="mode-trigger" data-mode="%1$s">' .
                                    '<span class="mode-%2$s"%3$s>%4$s</span>' .
                                    '<span class="mode-name">%5$s</span>' .
                                '</a>',
                                esc_attr( $mcourse_tid ),
                                $icon_type,
                                $icon_type = 'image' ? ' style="background-image:url(' . esc_url( $img_url ) . ')"' : '',
                                $icon_html,
                                empty( $item['title'] ) ? esc_html__( 'Untitled', 'axi-system' ) : esc_html( $item['title'] )
                            );
                            echo '</div>';
                        ?>
                    </div>
                    <?php
                endforeach; ?>
            </div>
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

    public function on_import( $element ) {
        return \Elementor\Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
    }
}