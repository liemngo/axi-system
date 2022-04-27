<?php
namespace AXi_System\Elementor;
use AXi_System\API_Request;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Full Height Banner Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_CourseAttributes extends \Elementor\Widget_Base
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
        return 'axi-courseattributes';
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
        return esc_html__( 'AXi Course Attributes', 'axi-system' );
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
        return 'fa fa-th-list';
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
            'course_attributes_title',
            [
                'label' => esc_html__( 'Content', 'axi-system' ),
            ]
        );
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
            'type_course_attributes',
            [
                'label' => __( 'Type', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'manual',
                'options' => [
                    'manual' => __( 'Manual', 'axi-system' ),
                    'intake' => __( 'Intake', 'axi-system' ),
                    'discount' => __( 'Discount', 'axi-system' ),
                ],
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'axi-system' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ]
            ]
        );
        
        $repeater->add_control(
            'list_title', [
                'label' => __( 'Title', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __( '' , 'axi-system' ),
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'lists',
            [
                'label' => __( 'List Course Attributes', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => __( 'Item #1', 'axi-system' ),
                    ],
                    [
                        'list_title' => __( 'Item #2', 'axi-system' ),
                    ],
                ],
            ]
        );

        $pages = get_pages();
        $page_chooses = [
            0 => esc_html__( '-- Custom Link --', 'axi-system' )
        ];
        if ( $pages )
        {
            foreach ( $pages as $page )
            {
                $page_chooses[ $page->ID ] = get_the_title( $page );
            }
        }
        
        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button 1 Label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Apply now',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'btn_page_id',
            [
                'label'       => esc_html__( 'Button 1 Page Link', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => $page_chooses,
                'label_block' => true,
                'default'     => '0'
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label'   => esc_html__( 'Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'btn_page_id' => '0'
                ],
                'default' => [
                    'url' => '#',
                ]
            ]
        );
        
        $this->add_control(
            'alt_btn_text',
            [
                'label'       => esc_html__( 'Alt Button 1 Label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Express Interest',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'alt_btn_page_id',
            [
                'label'       => esc_html__( 'Alt Button 1 Page Link', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => $page_chooses,
                'label_block' => true,
                'default'     => '0'
            ]
        );
        $this->add_control(
            'alt_btn_link',
            [
                'label'   => esc_html__( 'Alt Button 1 Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'alt_btn_page_id' => '0'
                ],
                'default' => [
                    'url' => '#',
                ]
            ]
        );
        
        $this->add_control(
            'btn_text_2',
            [
                'label'       => esc_html__( 'Button 2 Label', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Get course guide',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'btn_page_id_2',
            [
                'label'       => esc_html__( 'Button 2 Page Link', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => $page_chooses,
                'label_block' => true,
                'default'     => '0'
            ]
        );
        $this->add_control(
            'btn_link_2',
            [
                'label'   => esc_html__( 'Button 2 Link', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'btn_page_id_2' => '0'
                ],
                'default' => [
                    'url' => '#',
                ]
            ]
        );
        
        $this->end_controls_section();
        /* /Content */


        /*--------------------------------------------------------------
        # Icon Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'icon_style',
            [
                'label' => esc_html__( 'Icon Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-course-attributes .item-course .icon-course' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .axi-course-attributes .item-course .icon-course .wrap img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'border_icon_width',
            [
                'label'   => esc_html__( 'Border Width', 'axi-system' ),
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
                    '{{WRAPPER}} .axi-course-attributes .item-course .icon-course' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .axi-course-attributes .icon-course' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-course-attributes .item-course .icon-course' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-course-attributes .item-course .icon-course' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        /* /Icon Style */
        
        /*--------------------------------------------------------------
        # Label Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'label_style',
            [
                'label' => esc_html__( 'Label Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typo',
                'selector' => '{{WRAPPER}} .axi-course-attributes .item-course .data-title .title',
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__( 'Text Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-course-attributes .item-course .data-title .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        

        $this->add_responsive_control(
            'label_align',
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
                    '{{WRAPPER}} .axi-course-attributes .item-course .data-title .title' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        
        
        $this->add_responsive_control(
            'label_width',
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
                    '{{WRAPPER}} .axi-course-attributes .item-course .data-title .wrap' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-course-attributes .item-course .data-title .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-course-attributes .item-course .data-title .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        /* /label Style */
        
        /*--------------------------------------------------------------
        # Button Style
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'button_style',
            [
                'label' => esc_html__( 'Button Style', 'axi-system' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typo',
                'selector' => '{{WRAPPER}} .axi-course-attributes a.btn-course-attributes',
            ]
        );
        
        $this->add_control(
            'button_color',
            [
                'label'     => esc_html__( 'Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-course-attributes a.btn-course-attributes' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_align',
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
                    '{{WRAPPER}} .axi-course-attributes a.btn-course-attributes' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'button_background',
            [
                'label'     => esc_html__( 'Background Color', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axi-course-attributes a.btn-course-attributes' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        
        $this->add_responsive_control(
            'button_width',
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
                    '{{WRAPPER}} .axi-course-attributes a.btn-course-attributes' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_margin',
            [
                'label'      => esc_html__( 'Margin', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-course-attributes a.btn-course-attributes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'axi-system' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .axi-course-attributes a.btn-course-attributes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        /* /label Style */
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
        $get_id = get_field('select_course');
        $course_id = '';
        if($get_id){
            $course_id = get_field('select_course');
        }else{
            $course_id = get_the_ID();
        }
        $data_api = API_Request::instance()->get_course_atts( [
            'action' => 'intakes',
            'id' => strval( $course_id ) // '65edebc9-9bc3-4898-a1f4-213f714b7a4c'
		] );

        ?>
        <div class="axi-course-attributes">
            <div class="top-course-attributes">
                <div class="item-row">
                <?php
                    if($settings['lists']){ 
                        foreach($settings['lists'] as $item){
                            if($item['type_course_attributes'] == 'intake'){
								if(!empty($settings['alt_btn_text']) || !empty($data_api['status'])){
                                ?><div class="col-item-5 item-course intake">
                                    <div class="wrapper">
                                        <?php if($item['icon']['url']){ ?>
                                        <div class="icon-course">
                                            <div class="wrap">
                                                <img src="<?php echo $item['icon']['url']; ?>" alt="<?php echo $item['list_title']; ?>" >
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php
                                            if($data_api['status'] == 'success'){
                                                if(isset($data_api['data']['intakes'])){
                                                    if(count($data_api['data']['intakes']) > 1){ ?>
                                                        <div class="data-title dropdown">
                                                            <div class="dropdown-wrapper">
                                                                <div class="ae-dropdown dropdown">
                                                                    <div class="ae-select">
                                                                        <span class="ae-select-content"></span><svg xmlns="http://www.w3.org/2000/svg" width="12.129" height="7.49" viewBox="0 0 12.129 7.49">
                                                                          <path id="Path_453" data-name="Path 453" d="M3.342,1.5,7.982,6.133,12.621,1.5l1.425,1.425L7.982,8.994,1.917,2.929Z" transform="translate(-1.917 -1.504)"/>
                                                                        </svg>
                                                                    </div>
                                                                    <ul class="dropdown-menu ae-hide">
                                                                    <?php $j=0; foreach($data_api['data']['intakes'] as $data){ $j++; ?>
                                                                        <li data-value="<?php echo sanitize_title($data['start']);?>|<?php echo sanitize_title($data['end']); ?>" <?php if($j==1){echo 'class="selected"'; }?>><?php if($data['start']){ echo $data['start'] .' - '; } echo $data['end']; ?></li>
                                                                    <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }else{ ?>
                                                        <div class="data-title">
                                                            <?php foreach($data_api['data']['intakes'] as $data){ ?>
                                                                <div class="wrap"><h4 class="title"><?php if($data['start']){ echo $data['start'] .' - '; } echo $data['end']; ?></h4></div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php }
                                                }
                                                
                                            }elseif($settings['alt_btn_text']){ 
                                        ?>
                                            <div class="data-title">
                                                <div class="wrap"><h4 class="title"><?php echo $settings['alt_btn_text']; ?></h4></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div><?php
								}
                            }elseif($item['type_course_attributes'] == 'discount'){
                                    $get_course_promotion_title = '';
                                    $args = array(
                                        'post_type' => 'axi_promotion',
                                        'posts_per_page' => 1,
                                        'order' => 'DESC',
                                        'orderby' => 'date',
                                        'meta_query'       => array(
                                            'relation'    => 'AND',
                                            array(
                                                'key'          => '_promotion_status',
                                                'value'        => 'enabled',
                                                'compare'      => '=',
                                            ),
                                            array(
                                                'key'          => '_promotion_type',
                                                'value'        => 'course',
                                                'compare'      => '=',
                                            ),
                                            array(
                                                'key'          => '_course_id',
                                                'value'        => $course_id,
                                                'compare' => 'LIKE'
                                            )
                                        ),
                                    );
                                    $wp_query = new \WP_Query( $args );
                                    if( $wp_query->have_posts() ) :  
                                        while ( $wp_query->have_posts() ) : $wp_query->the_post();
                                            $get_course_promotion_title = get_the_title();
                                        endwhile;
                                    endif;
                                    wp_reset_query();
                                    
                                    
                                    $get_discipline_promotion_title = '';
                                    $page_discipline_id = wp_get_post_terms( $course_id, 'axi_discipline' );
                                    if(!empty($page_discipline_id[0]->term_id)){
                                        $args = array(
                                            'post_type' => 'axi_promotion',
                                            'posts_per_page' => 1,
                                            'order' => 'DESC',
                                            'orderby' => 'date',
                                            'meta_query'       => array(
                                                'relation'    => 'AND',
                                                array(
                                                    'key'          => '_promotion_status',
                                                    'value'        => 'enabled',
                                                    'compare'      => '=',
                                                ),
                                                array(
                                                    'key'          => '_promotion_type',
                                                    'value'        => 'discipline',
                                                    'compare'      => '=',
                                                ),
                                                array(
                                                    'key'          => '_discipline',
                                                    'value'        => $page_discipline_id[0]->term_id,
                                                    'compare' => 'LIKE'
                                                )
                                            ),
                                        );
                                        $wp_query = new \WP_Query( $args );
                                        if( $wp_query->have_posts() ) :  
                                            while ( $wp_query->have_posts() ) : $wp_query->the_post();
                                                $get_discipline_promotion_title = get_the_title();
                                            endwhile;
                                        endif;
                                        wp_reset_query();
                                    }
                                    if(!empty($get_course_promotion_title)){
                                    ?><div class="col-item-5 item-course off-Offer">
                                        <div class="wrapper">
                                            <?php if($item['icon']['url']){ ?>
                                            <div class="icon-course">
                                                <div class="wrap">
                                                    <img src="<?php echo $item['icon']['url']; ?>" alt="<?php echo $get_course_promotion_title; ?>" >
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="data-title">
                                                <div class="wrap">
                                                    <h4 class="title"><?php echo $get_course_promotion_title; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div><?php
                                    }elseif(!empty($get_discipline_promotion_title)){
                                    ?><div class="col-item-5 item-course off-Offer">
                                        <div class="wrapper">
                                            <?php if($item['icon']['url']){ ?>
                                            <div class="icon-course">
                                                <div class="wrap">
                                                    <img src="<?php echo $item['icon']['url']; ?>" alt="<?php echo $get_discipline_promotion_title; ?>" >
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="data-title">
                                                <div class="wrap">
                                                    <h4 class="title"><?php echo $get_discipline_promotion_title; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div><?php
                                    }
                            }else{
                                ?><div class="col-item-5 item-course">
                                    <div class="wrapper">
                                        <?php if($item['icon']['url']){ ?>
                                        <div class="icon-course">
                                            <div class="wrap">
                                                <img src="<?php echo $item['icon']['url']; ?>" alt="<?php echo $item['list_title']; ?>" >
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if($item['list_title']){ ?>
                                            <div class="data-title">
                                                <div class="wrap"><h4 class="title"><?php echo $item['list_title']; ?></h4></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div><?php
                            }
                        }
                    } ?>
                </div>
            </div>
            <div class="wrapper-button">
                <?php
                if ( $data_api['status'] == 'success' && isset( $data_api['data']['intakes'] ) ) :
                    if ( $settings['btn_text'] ) :
                        $btn_page_id = absint( $settings['btn_page_id'] );
                        if ( $btn_page_id > 0 ) :
                            printf(
                                '<a href="%1$s" class="btn-applynow btn-course-attributes%2$s">%3$s</a>',
                                esc_url( get_permalink( $btn_page_id ) ),
                                $settings['btn_text_2'] ? ' btn-right' : '',
                                esc_html( $settings['btn_text'] )
                            );
                        elseif ( $settings['btn_link']['url'] ) :
                            printf(
                                '<a href="%1$s" class="btn-applynow btn-course-attributes%2$s"%3$s>%4$s</a>',
                                esc_url( $settings['btn_link']['url'] ),
                                $settings['btn_text_2'] ? ' btn-right' : '',
                                $settings['btn_link']['is_external'] ? ' target="_blank"' : '',
                                esc_html( $settings['btn_text'] )
                            );
                        endif;
                    endif;
                elseif ( $settings['alt_btn_text'] ) :
                    $alt_btn_page_id = absint( $settings['alt_btn_page_id'] );
                    if ( $alt_btn_page_id > 0 ) :
                        printf(
                            '<a href="%1$s" class="btn-course-attributes%2$s">%3$s</a>',
                            esc_url( get_permalink( $alt_btn_page_id ) ),
                            $settings['btn_text_2'] ? ' btn-right' : '',
                            esc_html( $settings['alt_btn_text'] )
                        );
                    elseif ( $settings['alt_btn_link']['url'] ) :
                        printf(
                            '<a href="%1$s" class="btn-course-attributes%2$s"%3$s>%4$s</a>',
                            esc_url( $settings['alt_btn_link']['url'] ),
                            $settings['btn_text_2'] ? ' btn-right' : '',
                            $settings['alt_btn_link']['is_external'] ? ' target="_blank"' : '',
                            esc_html( $settings['alt_btn_text'] )
                        );
                    endif;
                endif;
                if ( $settings['btn_text_2'] ) :
                    $btn_page_id_2 = absint( $settings['btn_page_id_2'] );
                    if ( $btn_page_id_2 > 0 ) :
                        printf(
                            '<a href="%1$s" class="btn-course-attributes">%2$s</a>',
                            esc_url( get_permalink( $btn_page_id_2 ) ),
                            esc_html( $settings['btn_text_2'] )
                        );
                    elseif ( $settings['btn_link_2']['url'] ) :
                        printf(
                            '<a href="%1$s" class="btn-course-attributes">%2$s</a>',
                            esc_url( $settings['btn_link_2']['url'] ),
                            esc_html( $settings['btn_text_2'] )
                        );
                    endif;
                endif; ?>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    function fnBuildURL( url, value ) {
                        if ( ! url ) {
                            url = window.location.href;
                        }
                        if ( 'undefined' == typeof value ) {
                            return url;
                        }
                        var urlParts = url.split( '#' );
                        if ( urlParts[0].length && -1 != urlParts[0].indexOf( '?' ) ) {
                            urlParts[0] = urlParts[0] + '&date=' + value;
                        }
                        else {
                            urlParts[0] = urlParts[0] + '?date=' + value;
                        }
                        return urlParts.join( '#' );
                    }
                    $('.axi-course-attributes .ae-select-content').text($('.axi-course-attributes .data-title.dropdown ul > li.selected').text());
                    var button_applynow_href = $('.axi-course-attributes a.btn-applynow').attr('href');
                    var curl_value = $('.axi-course-attributes .dropdown-wrapper .ae-dropdown .dropdown-menu li.selected').attr('data-value');
                    var newUrl = fnBuildURL( button_applynow_href, curl_value );
                    $('.axi-course-attributes a.btn-applynow').attr("href", newUrl);
                    var newOptions = $('.axi-course-attributes .data-title.dropdown ul > li');
                    newOptions.click(function() {
                        $('.axi-course-attributes .ae-select-content').text($(this).text());
                        $('.axi-course-attributes .dropdown-menu > li').removeClass('selected');
                        $(this).addClass('selected');
                        var curl_value = $(this).attr('data-value');
                        var newUrl = fnBuildURL( button_applynow_href, curl_value );
                        $('.axi-course-attributes a.btn-applynow').attr("href", newUrl);
                    });

                    var aeDropdown = $('.axi-course-attributes .ae-dropdown');
                    aeDropdown.click(function(e) {
                        $('.axi-course-attributes .dropdown-menu').toggleClass('ae-hide');
                    });
                    $(document).click(function(e) {
                      $('.axi-course-attributes .ae-dropdown')
                        .not($('.axi-course-attributes .ae-dropdown').has($(e.target)))
                        .children('.dropdown-menu')
                        .addClass('ae-hide');
                    });
                    
                    // Select all links with hashes
                    /* $('.axi-course-attributes a[href*="#"]')
                      // Remove links that don't actually link to anything
                      .not('[href="#"]')
                      .not('[href="#0"]')
                      .click(function(event) {
                        // On-page links
                        if (
                          location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
                          && 
                          location.hostname == this.hostname
                        ) {
                          // Figure out element to scroll to
                          var target = $(this.hash);
                            if( $(this.hash+'.elementor-top-section').length > 0){
                                var target = $(this.hash+'.elementor-top-section');
                            }else{
                                var target =  $(this.hash).parents('section.elementor-top-section');
                            }
                          target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                          // Does a scroll target exist?
                          if (target.length) {
                            // Only prevent default if animation is actually gonna happen
                            event.preventDefault();
                            $('html, body').animate({
                              scrollTop: target.offset().top
                            }, 1000, function() {
                              // Callback after animation
                              // Must change focus!
                              var $target = $(target);
                              $target.focus();
                              if ($target.is(":focus")) { // Checking if the target was focused
                                return false;
                              } else {
                                $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
                                $target.focus(); // Set focus again
                              };
                            });
                          }
                        }
                      }); */
                });
            </script>
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
