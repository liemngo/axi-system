<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Icon Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_Icon extends \Elementor\Widget_Base
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
        return 'axi-icon';
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
        return esc_html__( 'AXi Icon Box', 'axi-system' );
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
        return 'eicon-favorite';
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
        # Title
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_title',
            [
                'label' => esc_html__( 'Title', 'axi-system' ),
            ]
        );
        
        $this->add_control(
            'title',
            [
                'label'   => esc_html__( 'Title', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Enter your title', 'axi-system' ),
                'default'     => esc_html__( 'What you’ll learn', 'axi-system' ),
            ]
        );
        
        $this->add_responsive_control(
            'title_width',
            [
                'label'   => esc_html__( 'Title Width', 'axi-system' ),
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
                    '{{WRAPPER}} .axi-eicon .icon-title' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
        /* /Title */

        /*--------------------------------------------------------------
        # Icon
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_content_icon',
            [
                'label' => esc_html__( 'Icon', 'axi-system' ),
            ]
        );
        
        $this->add_control(
            'icon_type',
            [
                'label'   => esc_html__( 'Icon Type', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'icon'  => esc_html__( 'Icon', 'axi-system' ),
                    'image' => esc_html__( 'Image', 'axi-system' )
                ],
                'default' => 'icon'
            ]
        );

        $this->add_control(
            'image',
            [
                'label'   => esc_html__( 'Choose Image', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image',
                ]
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'   => esc_html__( 'Choose Icon', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'fa4compatibility' => 'icon',
                'condition'        => [
                    'icon_type' => 'icon',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_fsize',
            [
                'label'      => esc_html__( 'Icon Size', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 14,
                        'max' => 256,
                    ]
                ],
                'condition' => [
                    'icon_type' => 'icon',
                ],
                'selectors' => [
                    '{{WRAPPER}} .axi-eicon .icon-box .icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_width',
            [
                'label'   => esc_html__( 'Icon Width', 'axi-system' ),
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
                    '{{WRAPPER}} .axi-eicon .icon-box .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shape',
            [
                'label'   => esc_html__( 'Shape', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'circle' => esc_html__( 'Circle', 'axi-system' ),
                    'square' => esc_html__( 'Square', 'axi-system' ),
                ],
                'default' => 'circle'
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-eicon .icon-box .icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'   => esc_html__( 'Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'axi-system' ),
            ]
        );
    
        $this->end_controls_section();
        /* /Icon */
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
        $before = $after = '';
        
        $this->add_render_attribute( 'wrapper', 'class', 'axi-eicon' );

        if ( ! empty( $settings['link']['url'] ) )
        {
            $before = '<a href="' . esc_url( $settings['link']['url'] ) . '"';
            if ( ! empty( $settings['link']['is_external'] ) )
            {
                $before .= ' target="_blank"';
            }
            if ( $settings['link']['nofollow'] )
            {
                $before .= ' rel="nofollow"';
            }
            $before .= '>';
            $after = '</a>';
        }
        $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="icon-box"><?php
                $icon_html = '';
                if ( 'icon' == $settings['icon_type'] ) :
                    if ( ! isset( $settings['icon'] ) && ! $migration_allowed ) :
                        $settings['icon'] = 'fa fa-check';
                    endif;

                    $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
                    $is_new = ! isset( $settings['icon'] ) && $migration_allowed;
                    if ( ! empty( $settings['icon'] ) || ( ! empty( $settings['selected_icon']['value'] ) && $is_new ) ) :
                        if ( $is_new || $migrated ) :
                            ob_start();
                            \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                            $icon_html = ob_get_clean();
                        else :
                            $icon_html = sprintf( '<i class="%s" aria-hidden="true"></i>', esc_attr( $settings['icon'] ) );
                        endif;
                    endif;
                else :
                    $image_id = absint( $settings['image']['id'] );
                    $icon_html = wp_get_attachment_image( $image_id, 'thumbnail' );
                endif;
                echo '<div class="icon shape-' . esc_attr( $settings['shape'] ) . '">';
                echo $before . $icon_html . $after;
                echo '</div>';
            ?></div>
            <div class="content-box">
                <div class="icon-title"><?php
                    $title = $this->get_settings_for_display( 'title' );
                    $title = $this->parse_text_editor( $title );
                    echo $title;
                ?></div>
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

    public function on_import( $element ) {
        return \Elementor\Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
    }
}