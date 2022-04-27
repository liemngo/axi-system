<?php
namespace AXi_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor widget for AXi NavMenu Side
 *
 * @since 1.0.0
 */
class Widget_NavMenu_Side extends \Elementor\Widget_Base
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
        return 'axi-navmenu-side';
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
        return esc_html__( 'AXi Side NavMenu', 'axi-system' );
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
        # BASE
        --------------------------------------------------------------*/
        $this->start_controls_section(
            'section_base',
            [
                'label' => esc_html__( 'Menus', 'axi-system' ),
            ]
        );
		
		$this->add_control(
			'show_button',
			[
				'label' => __( 'Show Button', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'axi-system' ),
					'yes' => __( 'Yes', 'axi-system' ),
					'no' => __( 'No', 'axi-system' ),
				],
				'default' => 'default',
			]
		);
		$this->add_control(
			'button_title', [
				'label' => __( 'Button Title', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Get your course guide' , 'axi-system' ),
				'label_block' => true,
				'condition' => ['show_button' => 'yes']
			]
		);
		
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'menu_title', [
				'label' => __( 'Title', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '' , 'axi-system' ),
				'label_block' => true,
			]
		);
		$menus = get_option( 'menu_side_options' );
		$blanks = array(
			'axi-feedbacks' => 'AXi Feedbacks',
			'axi-course-list' => 'AXi Course List',
			'axi-instructors' => 'AXi Instructors',
			'axi-accordion' => 'AXi Accordion',
			'axi-feedbacks' => 'AXi Comment Textx',
			'axi-comment-textx' => 'AXi Feedbacks',
			'axi-media-cards' => 'AXi Media Cards',
			'axi-comparison-table' => 'AXi Comparison Table',
			'axi-formidable-form' => 'AXi Formidable Form',
			'axi-media-cards-icon' => 'AXi Media Cards Icon',
			'axi-feedbacks-user' => 'AXi Feedbacks User',
			'axi-organisations' => 'AXi Organisations',
		);
		$data=array();
		if($menus){
			$data[''] = __( 'Select', 'axi-system' );
			foreach($menus as $menu){
				$data[$menu['menu_id']] = $menu['menu_name'];
			}
		}else{
			$data[''] = __( 'Select', 'axi-system' );
			foreach( $blanks as $key => $value ){
				$data[$key] = $value;
			}
		}

		$repeater->add_control(
			'menu_linkid', [
				'label' => __( 'List Link ID', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $data,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'menu_link_manually', [
				'label' => __( 'ID Manually', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '' , 'axi-system' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'list',
			[
				'label' => __( 'Lists Menu', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'menu_title' => __( 'Title #1', 'axi-system' ),
					],
					[
						'menu_title' => __( 'Title #2', 'axi-system' ),
					],
				],
				'title_field' => '{{{ menu_title }}}',
			]
		);
		
        $this->end_controls_section();
        /* /BASE */
		
		/* style */

        $this->start_controls_section(
            'section_menu_style',
            [
                'label' => __( 'Menu Style', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'menu_color',
            [
                'label' => __( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li > a.menu-item' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typo',
                'selector' => '{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li a'
            ]
        );
		
		$this->add_responsive_control(
			'meu_align',
			[
				'label' => __( 'Alignment', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'axi-system' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'axi-system' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'axi-system' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'axi-system' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);
		
		$this->add_responsive_control(
			'menu_margin',
			[
				'label' => __( 'Menu Margin', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_padding',
			[
				'label' => __( 'Menu Padding', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
        $this->end_controls_section();

        $this->start_controls_section(
            'section_active_menu_style',
            [
                'label' => __( 'Active Menu Style', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'active_menu_color',
            [
                'label' => __( 'Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li.active > a.menu-item' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'active_menu_typo',
                'selector' => '{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li.active > a.menu-item'
            ]
        );
		
		$this->add_responsive_control(
			'active_menu_align',
			[
				'label' => __( 'Alignment', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'axi-system' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'axi-system' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'axi-system' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'axi-system' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} #axi-navmenu-side .axi-sbanner .title' => 'text-align: {{VALUE}};',
                ],
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'active_menu_margin',
			[
				'label' => __( 'Menu Margin', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'active_menu_padding',
			[
				'label' => __( 'Menu Padding', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #axi-navmenu-side ul.nav-menu-side li.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
        $this->end_controls_section();
		
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Button Style', 'axi-system' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'button_color',
            [
                'label' => __( 'Button Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #axi-navmenu-side .btn-menu' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typo',
                'selector' => '{{WRAPPER}} #axi-navmenu-side .btn-menu'
            ]
        );
        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'axi-system' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #axi-navmenu-side .btn-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_responsive_control(
			'button_align',
			[
				'label' => __( 'Alignment', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'axi-system' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'axi-system' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'axi-system' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'axi-system' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} #axi-navmenu-side .footer-navmenu-side' => 'text-align: {{VALUE}};',
                ],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} #axi-navmenu-side .btn-menu',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #axi-navmenu-side .btn-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		
		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __( 'Margin', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #axi-navmenu-side .btn-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #axi-navmenu-side .btn-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();
		/* /style */
		
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
		if ( $settings['list'] ) {
			echo '<div id="axi-navmenu-side">';
				echo '<div class="container-navmenu-side">';
					echo '<div class="wrapper">';
						echo '<ul class="nav-menu-side">';
						foreach (  $settings['list'] as $item ) {
							$link='';
							if($item['menu_link_manually'] != ''){
								$link=$item['menu_link_manually'];
							}elseif($link=$item['menu_linkid'] != ''){
								$link=$item['menu_linkid'];
							}
							if(($link != 'default') && !empty($link)){
								echo '<li><a class="item-' . $item['_id'] . ' menu-item" data-axiscroll="false" href="#' . $link . '">' . $item['menu_title'] . '</a></li>';
							}
						}
						echo '</ul>';
						if($settings['show_button'] == 'yes' && !empty($settings['button_title'])){
							echo '<div class="footer-navmenu-side"><a data-axiscroll="false" href="#axi-formidable-form" class="btn-menu">'. $settings['button_title'] .'</a></div>';
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					// Select all links with hashes
					$('#axi-navmenu-side a[href*="#"]')
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
					  });
				  
					$(window).scroll(function() {
						if($('#axi-navmenu-side').length > 0){
							$('#axi-navmenu-side').parents('section.elementor-top-section').addClass('section-navmenu-side');
							
							var scrollDistance = $(window).scrollTop();
							var height_menu = document.getElementById("axi-navmenu-side");
							
							var section_id_start = $('ul.nav-menu-side li:nth-child(1) a').attr('href');
							if( $(section_id_start+'.elementor-top-section').length > 0){
								var menus_croll_start = $(section_id_start+'.elementor-top-section').offset().top;
							}else{
								var menus_croll_start =  $(section_id_start).parents('section.elementor-top-section').offset().top;
							}
							var section_id_last = $('ul.nav-menu-side li:last-child a').attr('href');
							if( $(section_id_last+'.elementor-top-section').length > 0){
								
								var menus_croll_end = $(section_id_last+'.elementor-top-section').offset().top + $(section_id_last+'.elementor-top-section').outerHeight(true) - height_menu.offsetHeight - 50;
							}else{
								var menus_croll_end = $(section_id_last).parents('section.elementor-top-section').offset().top + $(section_id_last).parents('section.elementor-top-section').outerHeight(true) - height_menu.offsetHeight - 50;
							}

							if(scrollDistance > 0){
								$('#axi-navmenu-side').parents('section.elementor-top-section').css({
									'top': menus_croll_start + 50
								});
							}
							if(menus_croll_start < scrollDistance){
								$('#axi-navmenu-side').parents('section.elementor-top-section').css({
									'top': scrollDistance + 50
								});
							}
							if(menus_croll_end < scrollDistance){
								$('#axi-navmenu-side').parents('section.elementor-top-section').css({
									'top': menus_croll_end
								});
							}
							
							$('ul.nav-menu-side li').each(function(i) {
								var id_section = $(this).find('a').attr('href');
								if( $(id_section+'.elementor-top-section').length > 0){
									var position_crl = $(id_section).offset().top - 50;
								}else{
									if($(id_section).closest('section.elementor-top-section').length > 0){
										var position_crl = $(id_section).closest('section.elementor-top-section').offset().top - 50;
									}
								}
								if(position_crl <= scrollDistance) {
									$('ul.nav-menu-side li').siblings().removeClass("active");
									$('ul.nav-menu-side li').eq(i).addClass('active');
								}
							});
						}
					}).scroll();
				});
			</script>
		<?php
		}
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