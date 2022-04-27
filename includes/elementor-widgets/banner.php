<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Full Height Banner Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_Banner extends \Elementor\Widget_Base
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
        return 'axi-banner';
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
        return esc_html__( 'AXi Banner', 'axi-system' );
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
        return 'eicon-banner';
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
        # Banner Top
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_banner_top',
            [
                'label' => esc_html__( 'Top', 'axi-system' ),
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__( 'Logo', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $this->add_control(
            'logo_link',
            [
                'label' => esc_html__( 'Logo Link', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ]
            ]
        );

        $this->add_control(
            'phone_label',
            [
                'label'       => esc_html__( 'Phone Label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '1300 500 900',
                'label_block' => true
            ]
        );

        $this->add_control(
            'phone_link',
            [
                'label'   => esc_html__( 'Phone Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => 'tel:1300500900',
                ]
            ]
        );

        $this->end_controls_section();
        /* /Banner Top */

        /*--------------------------------------------------------------
        # Banner Title
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_banner_title',
            [
                'label' => esc_html__( 'Title', 'axi-system' ),
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
                'default'     => esc_html__( 'Add Your Heading Text Here', 'axi-system' ),
            ]
        );

        $this->add_control(
            'title_size',
            [
                'label'   => esc_html__( 'HTML Tag', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6'
                ],
                'default' => 'h2',
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
        /* /Banner Title */

        /*--------------------------------------------------------------
        # Banner Description
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_banner_desc',
            [
                'label' => esc_html__( 'Description', 'axi-system' ),
            ]
        );

        $this->add_control(
            'desc',
            [
                'label'   => '',
                'type'    => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Description', 'axi-system' )
            ]
        );

        $this->end_controls_section();
        /* /Banner Description */

        /*--------------------------------------------------------------
        # Banner Button
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_banner_button',
            [
                'label' => esc_html__( 'Download Button', 'axi-system' ),
            ]
        );

        $this->add_control(
            'show_btn',
            [
                'label' => esc_html__( 'Show Download Button', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Download your free course guide', 'axi-system' ),
                'label_block' => true,
                'condition'   => [
                    'show_btn' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'btn_shape',
            [
                'label'       => esc_html__( 'Button Shape', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'rectangular',
                'label_block' => true,
                'options'     => [
                    'rectangular' => esc_html__( 'Rectangular', 'axi-system' ),
                    'rounded'     => esc_html__( 'Rounded', 'axi-system' )
                ],
                'condition' => [
                    'show_btn' => 'yes'
                ]
            ]
        );

        $this->add_control(
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
                    'show_btn' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
        /* /Banner Button */

        /*--------------------------------------------------------------
        # Title Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .banner-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_align',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .banner-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'selector' => '{{WRAPPER}} .banner-title',
            ]
        );
        $this->end_controls_section();
        /* /Title Style */

        /*--------------------------------------------------------------
        # Description Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_desc_style',
            [
                'label' => esc_html__( 'Description Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .banner-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_align',
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
                    '{{WRAPPER}} .banner-desc' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typo',
                'selector' => '{{WRAPPER}} .banner-desc'
            ]
        );
        $this->end_controls_section();
        /* /Description Style */

        /*--------------------------------------------------------------
        # Button Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typo',
                'selector' => '{{WRAPPER}}.elementor-widget-axi-banner .banner-button'
            ]
        );

        $this->add_responsive_control(
            'btn_width',
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
                    '{{WRAPPER}}.elementor-widget-axi-banner .banner-button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-axi-banner .banner-actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-axi-banner .banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Button Style */
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
        $this->add_render_attribute( 'wrapper', 'class', 'axi-banner-wrapper' );

        $this->add_render_attribute( 'title', 'class', 'banner-title' );
        $this->add_inline_editing_attributes( 'title' );

        $this->add_render_attribute( 'desc', 'class', 'banner-desc' );
        $this->add_inline_editing_attributes( 'desc', 'advanced' );

        $desc = $this->get_settings_for_display( 'desc' );
        $desc = $this->parse_text_editor( $desc );

        $queries  = [];
        $replaces = [];

        parse_str( $_SERVER['QUERY_STRING'], $queries );
        foreach( $queries as $key => $value )
        {
            $replaces['{' . $key . '}'] = $value;
        }

        $title = str_replace( array_keys( $replaces ), $replaces, $settings['title'] );
        ?>
        <div class="axi-banner-top">
            <div class="sheet banner-top-sheet">
                <div class="branding">
                    <div class="logo"><?php
                        $logo_img = '';
                        $logo_img_src = wp_get_attachment_image_src( absint( $settings['logo']['id'] ), 'full' );
                        if ( $logo_img_src ) :
                            $logo_img = sprintf( '<img src="%s" class="logo-img"  alt="" />', esc_url( $logo_img_src[0] ) );
                        endif;
                        if ( $logo_img ) :
                            if ( ! empty( $settings['logo_link']['url'] ) ) :
                                printf(
                                    '<a class="logo-img-box" href="%1$s" target="%2$s"%3$s>%4$s</a>',
                                    esc_url( $settings['logo_link']['url'] ),
                                    $settings['logo_link']['is_external'] ? '_blank' : '_self',
                                    $settings['logo_link']['nofollow'] ? ' rel="nofollow"' : '',
                                    $logo_img
                                );
                            else :
                                printf(
                                    '<div class="logo-img-box">%s</div>',
                                    $logo_img
                                );
                            endif;
                        endif;
                    ?></div>
                </div>
                <div class="extras">
                    <div class="axi-phone-box"><?php
                        if ( ! empty( $settings['phone_link']['url'] ) && ! empty( $settings['phone_label'] ) ) :
                            printf(
                                '<a class="box-link" href="%1$s" target="%2$s"%3$s>' .
                                    '<svg class="box-icon" viewBox="0 0 128 128">' .
                                        ' <use href="#axi-icon-phone" xlink:href="#axi-icon-phone"></use>' .
                                    '</svg>' .
                                    '<span class="box-label">%4$s</span>' .
                                '</a>',
                                esc_url( $settings['phone_link']['url'] ),
                                $settings['phone_link']['is_external'] ? '_blank' : '_self',
                                $settings['phone_link']['nofollow'] ? ' rel="nofollow"' : '',
                                esc_html( $settings['phone_label'] )
                            );
                        endif;
                    ?></div>
                </div>
            </div>
        </div>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> data-axiel="banner">
            <div class="banner-inner">
                <div class="banner-content">
                    <?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title' ), $title ); ?>
                    <div <?php echo $this->get_render_attribute_string( 'desc' ); ?>><?php
                        echo $desc;
                    ?></div>
                </div>
                <?php
                if ( $settings['show_btn'] && ! empty( $settings['btn_link']['url'] ) ) :
                    echo '<div class="banner-actions">';
                    printf(
                        '<a class="banner-button shape-%1$s" href="%2$s" target="%3$s"%4$s>%5$s</a>',
                        esc_attr( $settings['btn_shape'] ),
                        esc_url( $settings['btn_link']['url'] ),
                        $settings['btn_link']['is_external'] ? '_blank' : '_self',
                        $settings['btn_link']['nofollow'] ? ' rel="nofollow"' : '',
                        esc_html( $settings['btn_text'] )
                    );
                    echo '</div>';
                endif;
                ?>
                <a href="javascript:;" class="banner-scroll-link" data-axirole="banner-scroll-link">
                    <span class="screen-reader-text"><?php esc_html_e( 'Scroll down', 'axi-system' ); ?></span>
                    <svg class="scroll-link-icon" viewBox="0 0 512 512">
                        <use href="#axi-icon-chevron" xlink:href="#axi-icon-chevron"></use>
                    </svg>
                </a>
            </div>
        </div>
        <div class="mobile-desc"><?php
            echo $desc;
        ?></div>
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
}