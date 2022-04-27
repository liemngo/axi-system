<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Download Button Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_CDLButton extends \Elementor\Widget_Base
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
        return 'axi-cdlbutton';
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
        return esc_html__( 'AXi Floating Download Button', 'axi-system' );
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
        return 'eicon-button';
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
        /**
         * >>> Start Section
         */
        $this->start_controls_section(
            'section_content_base',
            [
                'label' => esc_html__( 'Content', 'axi-system' ),
            ]
        );
        
        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Download your free course guide', 'axi-system' ),
                'label_block' => true
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
                ]
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label'   => esc_html__( 'Button Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ]
            ]
        );
        $this->add_control(
            'btn_width',
            [
                'label'   => esc_html__( 'Button Width', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__( 'Auto', 'axi-system' ),
                    'full' => esc_html__( 'Full width', 'axi-system' ),
                ]
            ]
        );
        $this->add_control(
            'btn_pos',
            [
                'label'   => esc_html__( 'Position', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => esc_html__( 'Auto width, left aligned', 'axi-system' ),
                    'right' => esc_html__( 'Auto width, right aligned', 'axi-system' )
                ],
                'condition' => [
                    'btn_width' => 'auto'
                ]
            ]
        );
        $this->add_control(
            'btn_content_on',
            [
                'label'     => esc_html__( 'Show additional content', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'btn_width' => 'full'
                ]
            ]
        );
        $this->add_control(
            'acontent',
            [
                'label'     => esc_html__( 'Additional Content', 'axi-system'),
                'type'      => \Elementor\Controls_Manager::WYSIWYG,
                'condition' => [
                    'btn_width'       => 'full',
                    'btn_content_on!' => ''
                ],
                'default' => 'Lorem ipsum dolor sit amet, consectetur'
            ]
        );

        $this->add_control(
            'hide_on_ids',
            [
                'label'       => esc_html__( 'Autohide on section ids', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'title'       => esc_html__( 'Add your custom ids WITHOUT the Pound key, separated by commas. e.g: my-id-1, my-id-2, my-id-3. These IDs are taken from section ID where you want the button to hide.', 'axi-system' ),
                'label_block' => true,
                'default'     => '',
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
                'label'    => esc_html__( 'Button Font', 'axi-system' ),
                'name'     => 'btn_typo',
                'selector' => '#axi-download-global .axi-cdlbutton .download-button'
            ]
        );
        $this->add_control(
            'btn_color',
            [
                'label'     => esc_html__( 'Button Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '#axi-download-global .axi-cdlbutton .download-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_bgcolor',
            [
                'label'     => esc_html__( 'Button Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3673fc',
                'selectors' => [
                    '#axi-download-global .axi-cdlbutton .download-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__( 'Button Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '#axi-download-global .axi-cdlbutton .download-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btn_cbgcolor',
            [
                'label'     => esc_html__( 'Container Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '#axi-download-global .axi-cdlbutton' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_width' => 'full'
                ]
            ]
        );
        $this->end_controls_section();
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

        $css_classes = [
            'axi-cdlbutton'
        ];
        
        if ( 'full' == $settings['btn_width'] )
        {
            if ( $settings['btn_content_on'] )
            {
                $css_classes[] = 'full-width-2c';
            }
            else
            {
                $css_classes[] = 'full-width';
            }
        }
        else
        {
            $css_classes[] = $settings['btn_pos'] . '-anchoed';
        }

        $this->add_render_attribute( 'wrapper', [
            'class'        => implode( ' ', $css_classes ),
            'data-axiel'   => 'floating-download-btn',
            'data-hide-on' => $settings['hide_on_ids']
        ]);
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="download-boxes">
                <?php
                if ( 'full' == $settings['btn_width'] && $settings['btn_content_on'] ) : ?>
                    <div class="content-box">
                        <?php 
                            $acontent = $this->get_settings_for_display( 'acontent' );
                            $acontent = $this->parse_text_editor( $acontent );
                            echo $acontent;
                        ?>
                    </div>
                    <?php
                endif; ?>
                <div class="button-box">
                    <?php
                        $url = $settings['btn_link']['url'] ? $settings['btn_link']['url'] : '#';
                        printf(
                            '<a class="download-button shape-%1$s" href="%2$s" target="%3$s"%4$s>%5$s</a>',
                            esc_attr( $settings['btn_shape'] ),
                            esc_url( $url ),
                            $settings['btn_link']['is_external'] ? '_blank' : '_self',
                            $settings['btn_link']['nofollow'] ? ' rel="nofollow"' : '',
                            esc_html( $settings['btn_text'] )
                        );
                    ?>
                </div>
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
}