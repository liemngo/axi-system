<?php
namespace AXI_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Full Height Banner Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_SBanner extends \Elementor\Widget_Base
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
        return 'axi-sbanner';
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
        return esc_html__( 'AXi SBanner', 'axi-system' );
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
        # Banner Title
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_banner_title',
            [
                'label' => esc_html__( 'Content', 'axi-system' ),
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__( 'Logo', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src()
                ]
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
                'placeholder' => __( 'Type your Title here', 'axi-system' ),
            ]
        );
        
        $this->add_control(
            'title_tag',
            [
                'label' => __( 'HTML Tag', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->end_controls_section();
        /* /Banner Title */

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
                    '{{WRAPPER}} .axi-sbanner .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'title_width',
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
                    '{{WRAPPER}} .axi-sbanner .title' => 'max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .axi-sbanner .title' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'selector' => '{{WRAPPER}} .axi-sbanner .title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-sbanner .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-sbanner .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /* /Title Style */

        /*--------------------------------------------------------------
        # Logo Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'logo_style',
            [
                'label' => esc_html__( 'Logo Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'logo_width',
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
                    '{{WRAPPER}} .axi-sbanner .warpper-logo' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-sbanner .warpper-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-sbanner .warpper-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $course_id = get_field('select_course');
        if($course_id){
            $page_discipline_id = wp_get_post_terms( $course_id, 'axi_discipline' );
        }else{
            $page_discipline_id = wp_get_post_terms( get_the_ID() , 'axi_discipline' );
        }
        $discipline_logo_id = '';
        $discipline_name = '';
        if(!empty($page_discipline_id[0]->term_id)){
            $terms = get_terms( array(
                'taxonomy' => 'axi_discipline_link',
                'hide_empty' => false,
                'orderby' => 'id',
                'order' => 'DESC',
                'meta_query' => array(
                  array(
                     'key'       => '_discipline',
                     'value'     => $page_discipline_id[0]->term_id,
                     'compare'   => '='
                  )
                )
            ) );
            if($terms[0]->term_id){
                $discipline_logo_id = absint( get_term_meta( $terms[0]->term_id, '_discipline_logo', true ) );
            }
            $discipline_name = $terms[0]->name;
        }
        
        $title = '';
        if( empty( $settings['title'] ) ){
            if($discipline_name){
                $title = $discipline_name;
            }elseif(get_the_title($course_id)){
                $title = get_the_title($course_id);
            }elseif(is_singular('axi_course')){
                $title = get_the_title();
            }
        }else{
            $title = $settings['title'];
        }
        
        $image = '';
        if( empty($settings['logo']['id']) ){
            if(!empty($discipline_logo_id)){
                $image = wp_get_attachment_image_src($discipline_logo_id)[0];
            }elseif(!empty( get_field('_logo', $course_id) ) && !empty($course_id)){
                $image = wp_get_attachment_image_src(get_field('_logo', $course_id))[0];
            }
        }else{
            $image = $settings['logo']['url'];
        }
        
        $tag = $settings['title_tag'];
        $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];
        if ( ! in_array( $tag, $allowed_tags ) )
        {
            $tag = 'h2';
        }
        ?>
        <div class="axi-sbanner">
            <div class="sbanner-container">
                <?php
                    if($image != ''){
                        echo '<span class="warpper-logo"><img class="logo-image" src="'. $image .'" alt="'. $title .'"></span>';
                    }
                ?>
                <<?php echo $tag; ?> class="title"><?php echo wp_kses( $title, axisys_kses( 'heading' ) ); ?></<?php echo $tag; ?>>
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
    protected function _content_template() { }
}