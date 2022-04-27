<?php
namespace AXi_System;

use WP_Query;

/**
 * Shortcodes
 * 
 * @since 1.0.0
 */
class Shortcodes
{
    /**
     * Class instance.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var self
     */
    private static $_instance = null;
    
    /**
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return self An instance of the class.
     */
    public static function instance()
    {
        if ( is_null( self::$_instance ) )
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        add_shortcode( 'axi_discipline_pdata', [ $this, 'discipline_pdata_shortcode'] );
        add_shortcode( 'axi_location_pdata', [ $this, 'location_pdata_shortcode'] );
        add_shortcode( 'axi_delivery_mode_pdata', [ $this, 'delivery_mode_pdata_shortcode'] );
        add_shortcode( 'axi_course_type_pdata', [ $this, 'course_type_pdata_shortcode'] );
        add_shortcode( 'axi_course_pdata', [ $this, 'course_pdata_shortcode'] );

        add_shortcode( 'axi_show_discipline', [ $this, 'show_term_shortcode' ] );
        add_shortcode( 'axi_show_delivery_mode', [ $this, 'show_term_shortcode' ] );
        add_shortcode( 'axi_show_location', [ $this, 'show_term_shortcode' ] );
        add_shortcode( 'axi_show_course_type', [ $this, 'show_course_type_shortcode' ] );
        add_shortcode( 'axi_show_course_guide', [ $this, 'show_course_guide_shortcode' ] );
        add_shortcode( 'axi_discipline_guide_link', [ $this, 'discipline_guide_link_shortcode' ] );
        
        add_shortcode( 'axi_utm', [ $this, 'utm_shortcode' ] );
        add_shortcode( 'axi_nonce', [ $this, 'nonce_shortcode' ] );

        add_shortcode( 'axisys404', [ $this, 'custom_404_page_content_shortcode' ] );
    }
    
    /**
     * Shortcode to show discipline page data.
     *
     * @param array $atts
     * @return string
     */
    function discipline_pdata_shortcode( $atts )
    {
        $post = get_post();
        $disciplines = wp_get_post_terms( $post->ID, 'axi_discipline' );
        if ( empty( $disciplines ) || is_wp_error( $disciplines ) )
        {
            return '';
        }
        return $disciplines[0]->term_id;
    }
    
    /**
     * Shortcode to show location page data.
     *
     * @param array $atts
     * @return string
     */    
    function location_pdata_shortcode( $atts )
    {
        $atts = shortcode_atts( [
            'cookie' => 'true'
        ], $atts );
        global $post;

        $location_id = 0;
        $location = Location::get_current_term();
        if ( $location && ! is_wp_error( $location ) )
        {
            $location_id = $location->term_id;
        }
        else
        {
            $locations = wp_get_post_terms( $post->ID, 'axi_location' );
            if ( ! empty( $locations ) && ! is_wp_error( $locations ) )
            {
                $location_id = $locations[0]->term_id;
            }
        }
        return $location_id;
    }

    /**
     * Shortcode to show delivery mode page data.
     *
     * @param array $atts
     * @return string
     */
    function delivery_mode_pdata_shortcode( $atts )
    {
        $post = get_post();
        $delivery_modes = wp_get_post_terms( $post->ID, 'axi_delivery_mode' );
        if ( empty( $delivery_modes ) || is_wp_error( $delivery_modes ) )
        {
            return '';
        }
        return $delivery_modes[0]->term_id;
    }

    /**
     * Shortcode to show crouse type page data.
     *
     * @param array $atts
     * @return string
     */
    function course_type_pdata_shortcode( $atts )
    {
        $post = get_post();
        $course_type = absint( get_post_meta( $post->ID, '_course_type', true ) );
        if ( $course_type )
        {
            return $course_type;
        }
        return '';
    }

    /**
     * Shortcode to show course page data.
     *
     * @param array $atts
     * @return string
     */
    function course_pdata_shortcode( $atts )
    {
        $post = get_post();
        $course = absint( get_post_meta( $post->ID, '_course', true ) );
        if ( $course )
        {
            return $course;
        }
        return '';
    }


    /**
     * Shortcode to show term name based on id.
     *
     * @param array $atts
     * @return string
     */
    function show_term_shortcode( $atts )
    {
        $atts = shortcode_atts( [
            'id' => ''
        ], $atts );
        $id = absint( $atts['id'] );
        if ( ! $id )
        {
            return '';
        }
        $term = get_term( $id );
        if ( ! $term )
        {
            return '';
        }
        return $term->name;
    }

    /**
     * Shortcode to show course type names based on ids.
     *
     * @param array $atts
     * @return string
     */
    function show_course_type_shortcode( $atts )
    {
        $atts = shortcode_atts( array(
            'id' => ''
        ), $atts );
        $ids = explode( ',', $atts['id'] );
        if ( empty( $ids ) )
        {
            return '';
        }
        $good_ones = array();
        foreach( $ids as $id )
        {
            $the_id = absint( $id );
            if ( ! $id )
            {
                continue;
            }
            $term = get_term( $the_id );
            if ( ! $term )
            {
                continue;
            }
            $good_ones[] = $term->name;
        }
        if ( ! $good_ones )
        {
            return '';
        }
        return implode( ' | ', $good_ones );
    }

    /**
     * Shortcode to show course guide based on course id.
     *
     * @param array $atts
     * @return string
     */
    function show_course_guide_shortcode( $atts )
    {
        $atts = shortcode_atts( array(
            'label' => esc_html__( 'Download', 'axi-system' ),
            'course_id' => ''
        ), $atts );
        $course_id = absint( $atts['course_id'] );
        if ( ! $course_id )
        {
            return;
        }
        $guide_id = absint( get_post_meta( $course_id, '_course_guide', true ) );
        $guide = wp_get_attachment_url( $guide_id );
        if ( $guide )
        {
            return sprintf(
                '<a href="%1$s" target="_blank">%2$s</a>',
                esc_url( $guide ),
                esc_html( $atts['label'] )
            );
        }
        return '';
    }

    /**
     * Shortcode for generate course download link
     *
     * @param array $atts
     * @return string
     */
    function course_download_link_shortcode( $atts )
    {
        $atts = shortcode_atts( array(
            'discipline' => '',
            'course_type' => '',
            'delivery_mode' => '',
        ), $atts );

        $discipline = absint( $atts['discipline'] );
        $course_type = absint( $atts['course_type'] );
        $delivery_mode = absint( $atts['delivery_mode'] );

        $html = sprintf(
            '<p style="margin:0;font-size:12px;font-style:italic">%s</p>',
            esc_html__( 'There is no course matches your requirenemt. We will update soon.', 'axi-system' )
        );

        if ( ! $discipline || ! $course_type || ! $delivery_mode )
        {
            return $html;
        }

        $courses = get_posts( array(
            'post_type' => 'axi_course',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'paged' => 1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'axi_discipline',
                    'field' => 'id',
                    'terms' => array( $discipline ),
                    'include_children' => true,
                    'operator' => 'IN'
                ),
                array(
                    'taxonomy' => 'axi_course_type',
                    'field' => 'id',
                    'terms' => array( $course_type ),
                    'include_children' => true,
                    'operator' => 'IN'
                ),
                array(
                    'taxonomy' => 'axi_delivery_mode',
                    'field' => 'id',
                    'terms' => array( $delivery_mode ),
                    'include_children' => true,
                    'operator' => 'IN'
                )
            )
        ));

        if ( empty( $courses ) )
        {
            return $html;
        }

        $links = [];
        $valid_courses = [];

        foreach ( $courses as $course )
        {
            $cguide_id = absint( get_post_meta( $course->ID, '_course_guide', true ) );
            if ( ! $cguide_id )
            {
                continue;
            }
            $cguide_url = wp_get_attachment_url( $cguide_id );
            if ( ! $cguide_url )
            {
                continue;
            }
        
            $links[] = sprintf(
                '<li><a href="%1$s" target="_blank">%2$s</a></li>',
                esc_url( $cguide_url ),
                esc_html( get_the_title( $course ) )
            );
            $valid_courses[] = array(
                'guide'  => $cguide_url,
                'course' => $course
            );
        }

        if ( empty( $links ) )
        {
            return $html;
        }

        if ( count( $valid_courses ) === 1 )
        {
            $html = sprintf(
                '%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s <em>%5$s</em>.',
                esc_html__( 'It all starts with the', 'axi-system' ),
                esc_url( $valid_courses[0]['guide'] ),
                esc_html__( 'course guide', 'axi-system' ),
                esc_html__( 'you requested for', 'axi-system' ),
                esc_html( get_the_title( $valid_courses[0]['course'] ) )
            );
        }
        else
        {
            $html  = '<ul style="margin:0">';
            $html .= implode( '', $links );
            $html .= '</ul>';
        }
        return $html;
    }

    /**
     * Fetch course guides with link
     *
     * @param array $guides
     * @return void
     */
    private function fetch_guides_with_link( $guides )
    {
        $results = [];
        foreach ( $guides as $guide )
        {
            $guide_id = absint( get_term_meta( $guide->term_id, '_discipline_guide', true ) );
            if ( ! $guide_id )
            {
                continue;
            }
            $guide_url = wp_get_attachment_url( $guide_id );
            if ( ! $guide_url )
            {
                continue;
            }
            $results[] = array(
                'id'   => $guide->term_id,
                'url'  => $guide_url,
                'name' => $guide->name
            );
        }
        return $results;
    }

    /**
     * Shortcode for generate guide link
     *
     * @param array $atts
     * @return void
     */
    function discipline_guide_link_shortcode( $atts )
    {
        $atts = shortcode_atts( array(
            'discipline' => '',
            'location' => '',
            'delivery_mode' => '',
            'return' => ''
        ), $atts );

        $discipline = absint( $atts['discipline'] );
        $location = absint( $atts['location'] );
        $delivery_mode = absint( $atts['delivery_mode'] );
        $is_raw = $atts['return'] == 'raw' ? true : false;
        $valid_guides = [];

        $msg = $is_raw ? '' : sprintf(
            '<p style="margin:0;font-size:12px;font-style:italic">%s</p>',
            esc_html__( 'There is no guide matches your requirenemt. We will update soon.', 'axi-system' )
        );

        if ( ! $discipline )
        {
            return $msg;
        }

        $discipline_meta_query = array(
            'key' => '_discipline',
            'value' => $discipline,
            'compare' => '='
        );

        $qargs = array(
            'taxonomy' => 'axi_discipline_guide',
            'hide_empty' => false
        );

        if ( $location && $delivery_mode )
        {
            $meta_query = array(
                $discipline_meta_query,
                array(
                    'key' => '_location',
                    'value' => $location,
                    'compare' => '='
                ),
                array(
                    'key' => '_delivery_mode',
                    'value' => $delivery_mode,
                    'compare' => '='
                )
            );
            $guides = get_terms( array_merge(
                $qargs,
                array(
                    'meta_query' => $meta_query
                )
            ));

            $valid_guides = $this->fetch_guides_with_link( $guides );
        }

        if ( empty( $valid_guides ) && $delivery_mode )
        {
            $meta_query = array(
                $discipline_meta_query,
                array(
                    'key' => '_delivery_mode',
                    'value' => $delivery_mode,
                    'compare' => '='
                )
            );
            $guides = get_terms( array_merge(
                $qargs,
                array(
                    'meta_query' => $meta_query
                )
            ));
            $valid_guides = $this->fetch_guides_with_link( $guides );
        }

        if ( empty( $valid_guides ) && $location )
        {
            $meta_query = array(
                $discipline_meta_query,
                array(
                    'key' => '_location',
                    'value' => $location,
                    'compare' => '='
                )
            );
            $guides = get_terms( array_merge(
                $qargs,
                array(
                    'meta_query' => $meta_query
                )
            ));
            $valid_guides = $this->fetch_guides_with_link( $guides );
        }

        if ( empty( $valid_guides ) )
        {
            $meta_query = array(
                $discipline_meta_query
            );
            $guides = get_terms( array_merge(
                $qargs,
                array(
                    'meta_query' => $meta_query
                )
            ));
            $valid_guides = $this->fetch_guides_with_link( $guides );
        }

        if ( empty( $valid_guides ) )
        {
            $default_guide_name = axisys_get_opt( 'default_guide_name' );
            $default_guide_url = axisys_get_opt( 'default_guide_url' );
            if ( ! $default_guide_url )
            {
                return $msg;
            }
            if ( ! $default_guide_name )
            {
                $default_guide_name = esc_html__( 'AcademyXi Guide', 'axi-system' );
            }
            $valid_guides[] = array(
                'id'   => 0,
                'url'  => $default_guide_url,
                'name' => $default_guide_name
            );
        }

        if ( $is_raw )
        {
            return $valid_guides[0]['url'];
        }
        else
        {
            return sprintf(
                '%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s <em>%5$s</em>.',
                esc_html__( 'It all starts with the', 'axi-system' ),
                esc_url( $valid_guides[0]['url'] ),
                esc_html__( 'course guide', 'axi-system' ),
                esc_html__( 'you requested for', 'axi-system' ),
                esc_html( $valid_guides[0]['name'] )
            );
        }

        return $msg;
    }


    /**
     * Shortcode for showing utm params
     *
     * @param array $atts
     * @return void
     */
    function utm_shortcode( $atts )
    {
        $atts = shortcode_atts( array(
            'param'  => '',
            'decode' => false
        ), $atts );

        $whitelist_params = array(
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'campaignid',
            'adid',
            'adgroupid',
            'gclid'
        );
        if ( ! in_array( $atts['param'], $whitelist_params ) )
        {
            return '';
        }

        $res = '';

        if ( ! empty( $_COOKIE[ $atts['param'] ] ) )
        {
            $res = $_COOKIE[ $atts['param'] ];
        }
        elseif ( ! empty( $_GET[ $atts['param'] ] ) )
        {
            $res = $_GET[ $atts['param'] ];
        }

        if ( $atts['decode'] )
        {
            $res = base64_decode( $res );
        }

        return esc_attr( $res );
    }

    /**
     * Shortcode for showing nonce fetched from api
     *
     * @param array $atts
     * @return void
     */
    function nonce_shortcode( $atts )
    {
        $atts = shortcode_atts( array(
            'decode' => false
        ), $atts );
        $nonce = '';

        if ( ! function_exists( 'curl_init' ) || ! function_exists( 'curl_setopt' ) || ! function_exists( 'curl_exec' ) || ! function_exists( 'curl_close' ) || ! function_exists( 'hash_hmac' ) )
        {
            return '';
        }
        $headers = array(
            'Accept: application/json; charset=utf-8',
            'Content-Type: application/x-www-form-urlencoded',
            'API-KEY: 7f1f24d1ac1a35fd5bf7273b'
        );

        $ch = curl_init();
        $url = 'https://axi-api.academyxi.com/api/nonce?action=nonce';
        curl_setopt_array( $ch, array(
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_URL            => $url,
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'curl'
        ));
        $result = curl_exec( $ch );
        $data = null;
        curl_close( $ch );

        if ( ! empty( $result ) )
        {
            $data = json_decode( trim( utf8_encode( $result ) ), true );
        }
        if ( isset( $data['status'] ) && 'success' == $data['status'] )
        {
            if ( isset( $data['data']['data'] ) )
            {
                $nonce = $data['data']['data'];
            }
        }
        if ( $atts['decode'] )
        {
            $nonce = base64_decode( $nonce );
        }
        return $nonce;
    }

    /**
     * Custom 404 page content shortcode
     * This populate custom page content within 404.php
     *
     * @param array $atts
     * @return void
     */
    function custom_404_page_content_shortcode( $atts )
    {
        if ( ! is_404() )
        {
            return '';
        }

        $page_id = (int)axisys_get_opt( 'custom_404_page_id' );
        if ( $page_id <= 0 )
        {
            return '';
        }

        $query = new WP_Query( [
            'post_type'           => 'page',
            'post__in'            => [ $page_id ],
            'ignore_sticky_posts' => true
        ] );
        
        ob_start();

        if ( $query->have_posts() )
        {
            while ( $query->have_posts() )
            {
                $query->the_post();
                // Not necessary but just in case
                if ( get_the_ID() == $page_id )
                {
                    the_content();
                }
            }
            wp_reset_postdata();
        }

        $html = ob_get_clean();
        return $html;
    }
}