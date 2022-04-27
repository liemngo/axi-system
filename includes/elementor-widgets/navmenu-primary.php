<?php
namespace AXi_System\Elementor;
use AXi_System\Location;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom NavMenu Primary Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_NavMenu_Primary extends \Elementor\Widget_Base
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
        return 'axi-navmenu-primary';
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
        return esc_html__( 'AXi NavMenu Primary', 'axi-system' );
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
        return 'eicon-nav-menu';
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
        # Logo
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_site_logo',
            [
                'label' => esc_html__( 'Site Logo', 'axi-system' ),
            ]
        );

        $this->add_control(
            'logo_img',
            [
                'label'   => esc_html__( 'Choose Image', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'logo_img_link',
            [
                'label'   => esc_html__( 'Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'axi-system' )
            ]
        );

        $this->add_responsive_control(
            'logo_img_size',
            [
                'label'   => esc_html__( 'Width', 'axi-system' ),
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
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-box img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /* /Logo */

        /*--------------------------------------------------------------
        # Base
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_base',
            [
                'label' => esc_html__( 'Menus', 'axi-system' ),
            ]
        );
        $locs = array_merge(
            [
                0 => esc_html__( '&mdash; Select &mdash;', 'axi-system' )
            ],
            get_registered_nav_menus()
        );
        $this->add_control(
            'location',
            [
                'label'       => esc_html__( 'Select Desktop Location', 'axi-system' ),
                'description' => esc_html__( 'Please note that your menu should assigned to this location in order to be showed up.', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'options'     => $locs,
                'default'     => 0
            ]
        );
        $this->add_control(
            'location_mobile',
            [
                'label'       => esc_html__( 'Select Mobile Location', 'axi-system' ),
                'description' => esc_html__( 'Please note that your menu should assigned to this location in order to be showed up.', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'options'     => $locs,
                'default'     => 0
            ]
        );
        $this->end_controls_section();
        /* /Base */

        /*--------------------------------------------------------------
        # Login
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_login',
            [
                'label' => esc_html__( 'Login', 'axi-system' ),
            ]
        );

        $this->add_control(
            'login_enabled', [
                'label' => esc_html__( 'Enable', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'login_label',
            [
                'label'       => esc_html__( 'Phone Label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Student Login', 'axi-system' ),
                'label_block' => true,
                'condition'   => [
                    'login_enabled' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'login_link', [
                'label'   => esc_html__( 'Page URL', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'login_enabled' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'login_icon_type',
            [
                'label'   => esc_html__( 'Icon Type', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'axi-system' ),
                    'icon'    => esc_html__( 'Icon', 'axi-system' ),
                    'image'   => esc_html__( 'Image', 'axi-system' ),
                    'none'    => esc_html__( 'Disabled', 'axi-system' ),
                ],
                'condition' => [
                    'login_enabled' => 'yes'
                ],
                'default' => 'default'
            ]
        );

        $this->add_control(
            'login_icon_image',
            [
                'label'   => esc_html__( 'Choose Image', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'login_enabled'   => 'yes',
                    'login_icon_type' => 'image',
                ]
            ]
        );

        $this->add_control(
            'login_icon',
            [
                'label'   => esc_html__( 'Choose Icon', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'fa4compatibility' => 'icon',
                'condition'        => [
                    'login_enabled'   => 'yes',
                    'login_icon_type' => 'icon',
                ]
            ]
        );

        $this->add_responsive_control(
            'login_icon_fsize',
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
                    'login_enabled'   => 'yes',
                    'login_icon_type' => 'icon',
                ],
                'selectors' => [
                    '{{WRAPPER}} .login-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'login_icon_width',
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
                'condition' => [
                    'login_enabled'    => 'yes',
                    'login_icon_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}} .login-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Login */

        /*--------------------------------------------------------------
        # Location
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_location',
            [
                'label' => esc_html__( 'Location', 'axi-system' ),
            ]
        );

        $this->add_control(
            'location_enabled', [
                'label' => esc_html__( 'Show Location', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'location_icon_type',
            [
                'label'   => esc_html__( 'Icon Type', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'axi-system' ),
                    'icon'    => esc_html__( 'Icon', 'axi-system' ),
                    'image'   => esc_html__( 'Image', 'axi-system' ),
                    'none'    => esc_html__( 'Disabled', 'axi-system' ),
                ],
                'condition' => [
                    'location_enabled' => 'yes'
                ],
                'default' => 'default'
            ]
        );

        $this->add_control(
            'location_icon_image',
            [
                'label'   => esc_html__( 'Choose Image', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'location_enabled'   => 'yes',
                    'location_icon_type' => 'image',
                ]
            ]
        );

        $this->add_control(
            'location_icon',
            [
                'label'   => esc_html__( 'Choose Icon', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'fa4compatibility' => 'icon',
                'condition' => [
                    'location_enabled'   => 'yes',
                    'location_icon_type' => 'icon',
                ]
            ]
        );

        $this->add_responsive_control(
            'location_icon_fsize',
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
                    'location_enabled'   => 'yes',
                    'location_icon_type' => 'icon',
                ],
                'selectors' => [
                    '{{WRAPPER}} .location-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'location_icon_width',
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
                'condition' => [
                    'location_enabled'    => 'yes',
                    'location_icon_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}} .location-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Location */

        /*--------------------------------------------------------------
        # Separator
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_separator',
            [
                'label' => esc_html__( 'Separators', 'axi-system' ),
            ]
        );
        $this->add_responsive_control(
            'sep_width', [
                'label'   => esc_html__( 'Separator Width', 'axi-system' ),
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
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .separator' => 'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'sep_height', [
                'label'   => esc_html__( 'Separator Height', 'axi-system' ),
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
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .separator' => 'height:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'sep_color',
            [
                'label'     => esc_html__( 'Separator Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .separator' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $this->end_controls_section();
        /* /Separator */
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
        $this->add_render_attribute( 'wrapper', [
            'class' => 'axi-navmenu-primary',
        ]);

        $locations = [];
        $cur_loc_term = Location::get_current_term();
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="logo-box-wrapper">
                <?php
                    $this->add_render_attribute( 'logo_img_link', 'class', 'logo-box' );
                    $logo_box_html = '';

                    if ( ! empty( $settings['logo_img_link']['url'] ) )
                    {
                        $this->add_link_attributes( 'logo_img_link', $settings['logo_img_link'] );
                        
                    }
            
                    if ( ! empty( $settings['logo_img']['url'] ) )
                    {
                        $this->add_render_attribute( 'logo_img', 'src', $settings['logo_img']['url'] );
                        $this->add_render_attribute( 'logo_img', 'alt', \Elementor\Control_Media::get_image_alt( $settings['logo_img'] ) );
                        $this->add_render_attribute( 'logo_img', 'title', \Elementor\Control_Media::get_image_title( $settings['logo_img'] ) );
            
                        $logo_box_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'logo_img' );
            
                        if ( ! empty( $settings['logo_img_link']['url'] ) )
                        {
                            $logo_box_html = '<a ' . $this->get_render_attribute_string( 'logo_img_link' ) . '>' . $logo_box_html . '</a>';
                        }
                        else
                        {
                            $logo_box_html = '<div ' . $this->get_render_attribute_string( 'logo_img_link' ) . '>' . $logo_box_html . '</div>';
                        }
                    }
                    echo $logo_box_html;
                ?>
            </div>
            <?php $uniqid = 'axi-navmenu-' . axisys_guid( 16 ); ?>
            <div class="mobile-tools-wrapper">
                <a href="javascript:void(0)" class="mobile-nav-toggle" data-axiel="navmenu-primary-menu-toggle" data-navmenu-box-id="<?php echo esc_attr( $uniqid ); ?>"><?php
                    echo '<span class="nt-hamburger">';
                    echo    '<span class="slice slice-1"></span>';
                    echo    '<span class="slice slice-2"></span>';
                    echo    '<span class="slice slice-3"></span>';
                    echo '</span>';
                    echo '<span class="nt-cross">';
                    echo    '<span class="cross-line cross-line-1"></span>';
                    echo    '<span class="cross-line cross-line-2"></span>';
                    echo '</span>';
                    echo '<span class="sr-only">' . esc_html__( 'Toggle', 'axi-system' ) . '</span>';
                ?></a>
            </div>
            <div class="menu-box-wrapper" id="<?php echo esc_attr( $uniqid ); ?>">
                <div class="menu-box">
                <?php
                    $desktop_nav_menu_args = [
                        'menu_class' => 'axi-navmenu axi-navmenu-desktop',
                        'container'  =>  ''
                    ];
                    $mobile_nav_menu_args = [
                        'menu_class' => 'axi-navmenu axi-navmenu-mobile',
                        'container'  =>  ''
                    ];

                    $nav_menu_locs = get_registered_nav_menus();

                    // Check if location based nav menus exists.
                    if ( $cur_loc_term && ! is_wp_error( $cur_loc_term ) )
                    {
                        $desktop_nav_menu_loc = $settings['location'] . '-' . $cur_loc_term->slug;
                        $mobile_nav_menu_loc = $settings['location_mobile'] . '-' . $cur_loc_term->slug;

                        if ( array_key_exists( $desktop_nav_menu_loc, $nav_menu_locs ) && has_nav_menu( $desktop_nav_menu_loc ) )
                        {
                            $desktop_nav_menu_args['theme_location'] = $desktop_nav_menu_loc;
                        }
                        if ( array_key_exists( $mobile_nav_menu_loc, $nav_menu_locs ) && has_nav_menu( $mobile_nav_menu_loc ) )
                        {
                            $mobile_nav_menu_args['theme_location'] = $mobile_nav_menu_loc;
                        }
                    }
                    
                    // If location based menus does not exists, fallback to default ones.
                    if ( empty( $desktop_nav_menu_args['theme_location'] ) && has_nav_menu( $settings['location'] ) )
                    {
                        $desktop_nav_menu_args['theme_location'] = $settings['location'];
                    }
                    if ( empty( $mobile_nav_menu_args['theme_location'] ) && has_nav_menu( $settings['location_mobile'] ) )
                    {
                        $mobile_nav_menu_args['theme_location'] = $settings['location_mobile'];
                    }

                    // Show up if menu exists
                    if ( ! empty( $desktop_nav_menu_args['theme_location'] ) )
                    {
                        wp_nav_menu( $desktop_nav_menu_args );
                    }
                    if ( ! empty( $mobile_nav_menu_args['theme_location'] ) )
                    {
                        wp_nav_menu( $mobile_nav_menu_args );
                    }

                    // Login link
                    if ( $settings['login_enabled'] )
                    {
                        $login_html = sprintf(
                            '<a class="login-link" href="%1$s" target="%2$s"%3$s>',
                            esc_url( $settings['login_link']['url'] ),
                            $settings['login_link']['is_external'] ? '_blank' : '_self',
                            $settings['login_link']['nofollow'] ? ' rel="nofollow"' : ''
                        );

                        $login_icon = '';
                        if ( $settings['login_icon_type'] == 'default' )
                        {
                            $login_icon = '<span class="default-icon">' .
                                '<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.85 22.45">' .
                                    '<g>' .
                                        '<path class="cls-1" d="M9.79,11.47A5.79,5.79,0,0,1,4,5.74a5.78,5.78,0,1,1,11.56,0,5.79,5.79,0,0,1-5.73,5.74ZM9.79,2a3.76,3.76,0,1,0,0,7.52h0A3.76,3.76,0,1,0,9.79,2Z" transform="translate(0 0.05)"/>' .
                                        '<path class="cls-1" d="M1,22.4H1a1,1,0,0,1-1-1,9.92,9.92,0,0,1,9.92-9.92h0a9.93,9.93,0,0,1,9.92,9.92,1,1,0,0,1-2,0,7.93,7.93,0,0,0-7.92-7.92h0A7.92,7.92,0,0,0,2,21.4,1,1,0,0,1,1,22.4Z" transform="translate(0 0.05)"/>' .
                                    '</g>' .
                                '</svg>' .
                            '</span>';
                        }

                        if ( $login_icon )
                        {
                            $login_html .= '<span class="login-icon">';
                            $login_html .= $login_icon; // WPCS XSS Ok.
                            $login_html .= '</span>';
                        }

                        if ( $settings['login_label'] )
                        {
                            $login_html .= sprintf(
                                '<span class="login-label">%s</span>',
                                esc_html( $settings['login_label'] )
                            );
                        }
                        $login_html .= '</a>';

                        printf( '<div class="separator"></div><div class="axi-navmenu-login">%s</div>', $login_html ); // WPCS XSS Ok.
                    }

                    // Location select
                    if ( $settings['location_enabled'] )
                    {
                        $loc_terms = get_terms( [
                            'taxonomy'   => 'axi_location',
                            'hide_empty' => false
                        ] );
                        
                        // $cur_loc had been used for wp_nav_menu
                        $cur_loc = [];
                        if ( $cur_loc_term )
                        {
                            $cur_loc = [
                                'id' => $cur_loc_term->term_id,
                                'name' => $cur_loc_term->name
                            ];
                        }
                        if ( ! is_wp_error( $loc_terms ) )
                        {
                            foreach ( $loc_terms as $loc_term )
                            {
                                if ( ! $cur_loc )
                                {
                                    $cur_loc['id'] = $loc_term->term_id;
                                    $cur_loc['name'] = $loc_term->name;
                                }
                                $locations[ $loc_term->term_id ] = $loc_term->name;
                            }
                        }
                        
                        $loc_html = '';
                        $loc_icon = '';
                        if ( $settings['location_icon_type'] == 'default' )
                        {
                            $loc_icon = '<span class="default-icon">' .
                                '<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.51 22.4">' .
                                    '<g fill="#2d2a2a">' .
                                        '<path fill="#2d2a2a" class="cls-1" d="M7.71,12.15h0a4.44,4.44,0,1,1,4.46-4.44A4.48,4.48,0,0,1,7.69,12.15Zm0-6.88A2.45,2.45,0,0,0,5.26,7.71a2.48,2.48,0,0,0,2.46,2.44,2.44,2.44,0,0,0,0-4.88Z"/>' .
                                        '<path fill="#2d2a2a" d="M7.64,22.4a1.54,1.54,0,0,1-1.34-.78c0-.05-6.3-9.84-6.3-13.91A7.7,7.7,0,0,1,7.68,0h0a7.88,7.88,0,0,1,7.79,7.79c0,3.53-5.07,11.86-6.43,13.89a1.64,1.64,0,0,1-1.32.72ZM8,20.56l0,0ZM7.68,2A5.7,5.7,0,0,0,2,7.71c0,2.34,3.25,8.5,5.71,12.42,2.11-3.25,5.8-9.9,5.8-12.33A5.87,5.87,0,0,0,7.7,2Z"/>' .
                                    '</g>' .
                                '</svg>' .
                            '</span>';
                        }
                        if ( $loc_icon )
                        {
                            $loc_html .= sprintf( '<span class="location-icon">%s</span>', $loc_icon ); // WPCS XSS Ok.
                        }

                        $nonce = wp_create_nonce( 'axi_ajax_location_select_noncea' );
                        $loc_html .= '<div class="axi-select-ui" data-axiel="select-ui">';
                            $loc_html .= sprintf(
                                '<div class="axi-select-display%1$s" data-axirole="display">%2$s</div>',
                                $cur_loc ? '' : ' placeholder',
                                $cur_loc ? $cur_loc['name'] : ''
                            );
                            $loc_html .= '<ul class="axi-select-dropdown" data-axirole="dropdown">';
                                foreach( $locations as $id => $option )
                                {
                                    $loc_html .= sprintf(
                                        '<li class="%1$s"><a data-value="%2$s" href="javascript:void(0);">%3$s</a></li>',
                                        $id == $cur_loc['id'] ? 'active' : '',
                                        esc_attr( $id ),
                                        esc_html( $option )
                                    );
                                }
                            $loc_html .= '</ul>';
                            $loc_html .= '<select data-axirole="select" style="display:none" data-axiel="location-select" ' .
                                            'data-nonce="' . esc_attr( $nonce ) . '" ' .
                                            'data-action="axi_location_select_ajax_action" '.
                                            'data-current-value="' . esc_attr( $cur_loc['id'] ) . '">';
                                foreach( $locations as $id => $option )
                                {
                                    $loc_html .= sprintf(
                                        '<option value="%1$s" %2$s>%3$s</option>',
                                        esc_attr( $id ),
                                        selected( $id, $cur_loc['id'], false ),
                                        esc_html( $option )
                                    );
                                }
                            $loc_html .= '</select>';
                        $loc_html .= '</div>';

                        $loc_html .= '<div class="mobile-loc-select" data-axiel="hor-location-select" ' .
                                        'data-nonce="' . esc_attr( $nonce ) . '" ' .
                                        'data-action="axi_location_select_ajax_action" ' .
                                        'data-current-value="' . esc_attr( $cur_loc['id'] ) . '">';
                        $loc_html .= sprintf(
                            '<div class="location-title-box">' .
                                '<span class="mobile-loc-title">%1$s%2$s</span>' .
                            '</div>',
                            $loc_icon ? '<span class="location-icon">' . $loc_icon . '</span>' : '',
                            esc_html__( 'Location:', 'axi-system' )
                        );
                        if ( $locations )
                        {
                            $carousel_options = [
                                'slidesToShow'   => 2,
                                'slidesToScroll' => 1,
                                'arrows'         => false,
                                'dots'           => false,
                                'speed'          => 350,
                                'autoplay'       => false,
                                'infinite'       => false,
                                'variableWidth'  => true
                            ];
                            $loc_html .= '<div class="location-links-box">' .
                                '<div class="location-links" data-axiel="carousel" ' .
                                    'data-options="' . esc_attr( json_encode( $carousel_options ) ) . '">';
                        }
                        foreach( $locations as $id => $option )
                        {
                            $loc_html .= sprintf(
                                '<div class="location-link-entry" data-axicarousel-slide="%1$s"><a href="#" data-location="%2$s" class="loc%3$s">%4$s</a></div>',
                                $id == $cur_loc['id'] ? 'active': 'idle',
                                esc_attr( $id ),
                                $id == $cur_loc['id'] ? ' selected': '',
                                esc_html( $option )
                            );
                        }
                        if ( $locations )
                        {
                            $loc_html .= '</div>' .
                                '</div>';
                        }
                        $loc_html .= '</div>';
                        
                        printf( '<div class="separator"></div><div class="axi-navmenu-location">%s</div>', $loc_html ); // WPCS XSS Ok.
                    }
                    if ( is_active_sidebar( 'mobile-nav-promotion' ) )
                    {
                        echo '<div class="mobile-nav-promotion">';
                        dynamic_sidebar( 'mobile-nav-promotion' );
                        echo '</div>';
                    }
                ?>
                </div><!-- /.menu-box -->
            </div><!-- /.menu-box-wrapper -->
        </div><!-- /.axi-navmenu-primary -->
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