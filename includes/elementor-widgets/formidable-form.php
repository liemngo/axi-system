<?php
namespace AXI_System\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Full Height Banner Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_FormidableForm extends \Elementor\Widget_Base
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
        return 'axi-formidable';
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
        return esc_html__( 'AXi Formidable Form', 'axi-system' );
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
        return 'eicon-form-horizontal';
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
            'formidable_title',
            [
                'label' => esc_html__( 'Content', 'axi-system' ),
            ]
        );

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'axi-system' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Title here', 'axi-system' ),
			]
		);
		$data = array();
		if(function_exists('academyxi_get_formidable_form')){
			$forms = academyxi_get_formidable_form(20);
			if($forms){
				$data[ '0' ] = __( 'Select form', 'axi-system' );
				foreach ( $forms as $form ) {
					$data[ $form->id ] = $form->name;
				}
			}
		}
        $this->add_control(
            'form_id',
            [
                'label'   => esc_html__( 'Form:', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $data
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
                    '{{WRAPPER}} .axi-formidable h2' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .axi-formidable h2' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'selector' => '{{WRAPPER}} .axi-formidable h2',
            ]
        );
        $this->end_controls_section();
        /* /Title Style */
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
	
        ?>
        <div id="axi-formidable-form" class="axi-formidable">
			<?php if($settings['title']){ ?>
				<h2><?php echo $settings['title']; ?></h2>
			<?php } ?>
			<?php if($settings['form_id']){ ?>
				<?php echo do_shortcode('[formidable id='.$settings['form_id'] .']'); ?>
			<?php } ?>
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