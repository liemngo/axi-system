<?php
namespace AXi_System\Elementor;
use AXi_System\API_Request;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom Stars Elementor Widget for AcademyXi.
 *
 * @since 1.0.0
 */
class Widget_Stars extends \Elementor\Widget_Base
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
        return 'axi-stars';
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
        return esc_html__( 'AXi Stars', 'axi-system' );
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
        return 'eicon-star';
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
            'auto', [
                'label'   => esc_html__( 'Auto Fetch Data', 'axi-system' ),
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'rating',
            [
                'label'     => esc_html__( 'Star Rating', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'default'   => 5,
                'condition' => [
                    'auto!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'total',
            [
                'label'     => esc_html__( 'Total Reviewers', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'default'   => 5473,
                'condition' => [
                    'auto!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'desc',
            [
                'label'     => esc_html__( 'Description', 'axi-system' ),
                'type'      => \Elementor\Controls_Manager::WYSIWYG,
                'separator' => 'before',
                'default'   => esc_html__( 'Description', 'axi-system' )
            ]
        );

        $this->add_control(
            'desc_help',
            [
                'label' => '',
                'type'  => \Elementor\Controls_Manager::RAW_HTML,
                'raw'   => sprintf(
                    '<small><em>%s</em></small>',
                    esc_html__( 'Use {rating} and {total} for avg rating and total ratings.', 'axi-system' )
                )
            ]
        );

        $this->add_control(
            'api_endpoint',
            [
                'label'       => esc_html__( 'API Endpoint', 'axi-system' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'description' => esc_html__( 'Enter subdomain for API end point including slash (eg. /nps). You should setup API within admin area before use this.', 'axi-system' ),
                'condition'   => [
                    'auto' => 'yes'
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
        $this->add_render_attribute( 'wrapper', 'class', 'axi-stars-wrapper' );
        $dterms = wp_get_post_terms( $post->ID, 'axi_discipline' );
        $lterms = wp_get_post_terms( $post->ID, 'axi_location' );
        $rating = floatval( $settings['rating'] );
        $total  = absint( $settings['total'] );

        if ( $settings['auto'] && function_exists( 'curl_init' ) && function_exists( 'curl_setopt' ) && function_exists( 'curl_exec' ) && function_exists( 'curl_close' ) && function_exists( 'hash_hmac' ) )
        {
            $disciplines = [];
            if ( ! is_wp_error( $dterms ) )
            {
                foreach( $dterms as $term )
                {
                    $disciplines[] = $term->name;
                }
            }

            $locations = [];
            if ( ! is_wp_error( $lterms ) )
            {
                foreach( $lterms as $term )
                {
                    $locations[] = $term->name;
                }
            }

            $rating_data = API_Request::instance()->get_star_ratings([
                'discipline' => implode( ',', $disciplines ),
                'location'   => implode( ',', $locations )
            ]);

            if ( $rating_data['rating'] )
            {
                $rating = $rating_data['rating'];
            }
            if ( $rating_data['total'] )
            {
                $total = $rating_data['total'];
            }
        }
        $rating = round( $rating, 1 );
        $rating = ( $rating < 0.5 || $rating > 5 ) ? 5 : $rating;
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php 
                $star_markup = '';
                for ( $i=0; $i < 5; $i++ ) :
                    $star_markup .= '<li><svg class="axi-icon-star" viewBox="0 0 512 512"> <use href="#axi-icon-star" xlink:href="#axi-icon-star"></use></svg></li>';
                endfor;
            ?>
            <div class="stars">
                <ul class="starlist current-stars" style="width:<?php echo esc_attr( $rating * 20 ); ?>%"><?php echo $star_markup; ?></ul>
                <ul class="starlist all-stars"><?php echo $star_markup; ?></ul>
            </div>
            <div class="desc"><?php
                $desc = $this->get_settings_for_display( 'desc' );
                $desc = $this->parse_text_editor( $desc );
                $desc = str_replace(
                    [
                        '{rating}',
                        '{total}'
                    ],
                    [
                        $rating,
                        $total
                    ],
                    $desc
                );
                echo $desc;
            ?></div>
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