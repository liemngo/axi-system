<?php
namespace AXi_System;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
{
    exit;
}

class Location
{
    /**
     * Plugin instance.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var self
     */
    private static $_instance = null;

    /**
     * User IP
     *
     * @var string
     * @access protected
     * @static
     */
    protected static $user_ip;

    /**
     * All available locations
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $loc_terms;

    /**
     * Default location term
     *
     * @var \WP_Term
     * @access protected
     * @static
     */
    protected static $default_loc_term;

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
     * Constructor.
     *
     * @since 1.0.0
     * @access private
     */
    private function __construct()
    {
        add_action( 'wp_ajax_axi_location_select_ajax_action', [ $this, 'ajax_location_select' ] );
        add_action( 'wp_ajax_nopriv_axi_location_select_ajax_action', [ $this, 'ajax_location_select' ] );

        add_action( 'wp_ajax_axi_get_selected_location_action', [ $this, 'ajax_get_selected_location' ] );
        add_action( 'wp_ajax_nopriv_axi_get_selected_location_action', [ $this, 'ajax_get_selected_location' ] );

        add_action( 'template_redirect', [ $this, 'location_redirection' ] );

        add_action( 'wp_footer', [ $this, 'footer_stuff' ] );

        add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 100 );

        if ( ! isset( $_COOKIE[ AXISYS_LOCATION_COOKIE ] ) )
        {
            self::set_cookie();
        }
    }

    /**
     * Get current term from Cookie data.
     *
     * @return \WP_Term|\WP_Error|null
     */
    public static function get_current_term()
    {
        $cookie_data = self::get_cookie();
        if ( $cookie_data )
        {
            $term_id = (int)$cookie_data['id'];
            return get_term( $term_id, 'axi_location' );
        }
        return self::get_default_loc_term();
    }

    /**
     * Get Cookie data
     *
     * @return array
     */
    public static function get_cookie()
    {
        if ( ! isset( $_COOKIE[ AXISYS_LOCATION_COOKIE ] ) )
        {
            return false;
        }
        $cookie_data = explode( '|', $_COOKIE[ AXISYS_LOCATION_COOKIE ] );
        if ( count( $cookie_data ) !== 2 )
        {
            return false;
        }

        list( $id, $slug ) = $cookie_data;
        return compact( 'id', 'slug' );
    }

    /**
     * Get location term based on id or slug.
     * Cookie is set before registering custom taxonomies, so we can not use get_term in this case.
     *
     * @param string $field
     * @param int|string $value
     * @return \WP_Term|false
     */
    protected static function get_location_term_by( $field, $value )
    {
        /**
         * @var \wpdb $wpdb
         * @global
         */
        global $wpdb;

        if ( $field != 'id' && $field != 'slug' )
        {
            return false;
        } 
        if ( ! $value )
        {
            return false;
        }
        $query_str = "SELECT DISTINCT t.*, tt.taxonomy
                        FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy as tt
                            ON ( t.term_id = tt.term_id AND tt.taxonomy=%s)
                        WHERE";  
        $query = '';  
        if ( $field == 'slug' )
        {
            $query_str .= " t.slug=%s";
        }
        else
        {
            $query_str .= " t.term_id=%d";
        }
        $query_str .= " LIMIT 1;";

        $query = $wpdb->prepare( $query_str, 'axi_location', $value );
        $terms = $wpdb->get_results( $query );
        if ( ! empty( $terms ) )
        {
            return $terms[0];
        }
        return false;
    }

    /**
     * Set cookie data
     *
     * @param integer $location_id
     * @return void
     */
    public static function set_cookie( $location_id = 0 )
    {
        if ( $location_id )
        {
            $loc_term = self::get_location_term_by( 'id', $location_id );
        }
        else
        {
            $ipdata = @json_decode( file_get_contents( "http://ip-api.com/json/" . self::get_user_ip() ), true );
            $city = '';
            if ( isset( $ipdata['city'] ) )
            {
                $city = trim( strtolower( preg_replace( '/\s/', '', $ipdata['city'] ) ) );
            }
            $loc_term = self::get_location_term_by( 'slug', $city );
        }

        if ( ! $loc_term )
        {
            $default_loc_term_id = absint( axisys_get_opt( 'default_location_id' ) );
            $loc_term = self::get_location_term_by( 'id', $default_loc_term_id );
        }

        if ( $loc_term )
        {
            $location_cookie = $loc_term->term_id . '|' . $loc_term->slug;
            $secured = is_ssl() && 'https' === parse_url( get_option( 'home' ), PHP_URL_SCHEME );
            $expire  = time() + 2 * DAY_IN_SECONDS;

            setcookie( AXISYS_LOCATION_COOKIE, $location_cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, $secured, true );
            if ( COOKIEPATH != SITECOOKIEPATH )
            {
                setcookie( AXISYS_LOCATION_COOKIE, $location_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secured, true );
            }
        }
    }

    /**
     * Get user ip
     *
     * @return string
     */
    protected static function get_user_ip()
    {
        if ( self::$user_ip )
        {
            return self::$user_ip;
        }

        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )
        { 
            self::$user_ip = $_SERVER['HTTP_CLIENT_IP']; 
        } 
        else if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
        { 
            self::$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
        } 
        else
        { 
            self::$user_ip = $_SERVER['REMOTE_ADDR']; 
        }

        return self::$user_ip;
    }

    /**
     * Get location terms
     *
     * @return array [term_id => name]
     */
    protected static function get_loc_terms()
    {
        if ( self::$loc_terms )
        {
            return self::$loc_terms;
        }

        /**
         * @var \wpdb $wpdb
         * @global
         */
        global $wpdb;
        $query_str = "SELECT DISTINCT t.*, tt.taxonomy
                        FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy as tt
                            ON ( t.term_id = tt.term_id AND tt.taxonomy=%s);";  
        $query = $wpdb->prepare( $query_str, 'axi_location' );
        $terms = $wpdb->get_results( $query );

        foreach( $terms as $term )
        {
            self::$loc_terms[ $term->term_id ] = $term;
        }

        return self::$loc_terms;
    }

    /**
     * Get default lcation
     *
     * @return \WP_Term
     */
    protected static function get_default_loc_term()
    {
        if ( self::$default_loc_term )
        {
            return self::$default_loc_term;
        }
        $default_loc_term_id = absint( axisys_get_opt( 'default_location_id' ) );
        self::$default_loc_term = get_term( $default_loc_term_id, 'axi_location' );
        return self::$default_loc_term;
    }

    /**
     * Clear cookies
     *
     * @return void
     */
    public static function clear_cookie()
    {
        setcookie( AXISYS_LOCATION_COOKIE, ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        setcookie( AXISYS_LOCATION_COOKIE, ' ', time() - YEAR_IN_SECONDS, SITECOOKIEPATH, COOKIE_DOMAIN );
    }

    /**
     * Build url for redirection
     * 
     * @param string   $url      Current url
     * @param \WP_Term $location Requested term
     */
    function build_url( $url, $location = null )
    {
        $url_parts = explode( '/', $url );
        $part_length = count( $url_parts );

        if ( $part_length < 3 )
        {
            return false;
        }

        $needed_key = $part_length - 1;
        $last_part  = trim( $url_parts[ $part_length - 1 ] );
        
        // URL ended up with a forward slash
        if ( ! strlen( $last_part ) )
        {
            $needed_key = $part_length - 2;
            $last_part = trim( $url_parts[ $needed_key ] );
        }
        
        // URL has query on it
        if ( false !== strpos( '?', $last_part ) || false !== strpos( '&', $last_part ) )
        {
            $needed_key = $part_length - 3;
            $last_part = trim( $url_parts[ $needed_key ] );
        }

        $last_part_arr = explode( '-', $last_part );
        $original_last_part = $last_part;
        $cur_location_slug = $last_part_arr[ count( $last_part_arr ) - 1 ];

        if ( $cur_location_slug )
        {
            $cur_location = get_term_by( 'slug', $cur_location_slug, 'axi_location' );
            if ( $cur_location )
            {
                array_pop( $last_part_arr );
                array_push( $last_part_arr, $location->slug );
                $last_part = implode( '-', $last_part_arr );
            }
        }

        $url_parts[ $needed_key ] = $last_part;

        return implode( '/', $url_parts );
    }

    /**
     * Location select ajax hook
     *
     * @return void
     */
    function ajax_location_select()
    {
        $response = [
            'status' => 'failed',
            'message' => '',
            'url' => ''
        ];

        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'axi_ajax_location_select_noncea' ) )
        {
            $response['message'] = esc_html__( 'Are you cheating?', 'axi-system' );
        }

        if ( ! isset( $_REQUEST['url'] ) )
        {
            $response['message'] = esc_html( 'Invalid url', 'axi-system' );
        }

        $location_id = isset( $_REQUEST['locationID'] ) ? absint( $_REQUEST['locationID'] ) : 0;
        if ( ! $location_id )
        {
            $response['message'] = esc_html( 'Invalid location id', 'axi-system' );
        }

        $location = get_term( $location_id, 'axi_location' );
        if ( ! $location )
        {
            $response['message'] = esc_html__( 'Invalid location', 'axi-system' );
        }

        $new_url = $this->build_url( esc_url_raw( $_REQUEST['url'] ), $location );

        if ( ! $new_url )
        {
            $response['message'] = esc_html( 'Invalid url', 'axi-system' );
        }

        if ( $response['message'] )
        {
            echo json_encode( $response );
            exit;
        }

        $response['status'] = 'cookie';

        if ( isset( $_COOKIE[ AXISYS_LOCATION_COOKIE ] ) )
        {
            self::clear_cookie();
        }

        self::set_cookie( $location->term_id );

        $response['status'] = 'success';
        $response['url'] = $new_url;

        echo json_encode( $response );
        exit;
    }

    /**
     * Get stored location cookie ajax (for front-end just in case of caching)
     *
     * @return string
     */
    function ajax_get_selected_location()
    {
        $response = [
            'status' => 'failed',
            'message' => '',
            'location' => '',
            'url' => ''
        ];

        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'ajax_get_selected_location_noncea' ) )
        {
            $response['message'] = esc_html__( 'Are you cheating?', 'axi-system' );
        }

        if ( ! isset( $_REQUEST['url'] ) )
        {
            $response['message'] = esc_html( 'Invalid url', 'axi-system' );
        }

        if ( ! isset( $_COOKIE[ AXISYS_LOCATION_COOKIE ] ) )
        {
            self::set_cookie();
        }

        $term = self::get_current_term();

        if ( empty( $term ) || is_wp_error( $term ) )
        {
            $response['message'] = esc_html__( 'Something went wrong, we\'re investigating issues, please be patient.', 'axi-system' );
        }

        if ( $response['message'] )
        {
            echo json_encode( $response );
            exit;
        }

        $response['status'] = 'success';

        $req_url = esc_url_raw( $_REQUEST['url'] );
        $new_url = $this->build_url( $req_url, $term );

        $response['url'] = $new_url;
        $response['location'] = $term->term_id;

        echo json_encode( $response );
        exit;
    }

    function location_redirection()
    {
        // Any kind of permalink except plain one.
        if ( ! get_option( 'permalink_structure' ) )
        {
            return;
        }

        $cur_loc = self::get_current_term();
        if ( empty( $cur_loc ) || is_wp_error( $cur_loc ) )
        {
            return;
        }

        global $wp;
        $query_args  = empty( $_GET ) ? [] : $_GET;
        $cur_url = home_url( add_query_arg( $query_args, $wp->request ) );
        $new_url = $this->build_url( $cur_url, $cur_loc );

        if ( $new_url != $cur_url )
        {
            wp_safe_redirect( $new_url );
        }
    }

    function footer_stuff()
    {
        $nonce = wp_create_nonce( 'ajax_get_selected_location_noncea' );
        echo '<script>var axiGetSelectedLocNonce="' . esc_attr( $nonce ) . '";</script>';
    }

    /**
     * Create additional menu locations
     *
     * @return void
     */
    function after_setup_theme()
    {
        static $once;

        if ( ! get_theme_support( 'menus' ) )
        {
            return;
        }

        if ( ! $once )
        {
            $loc_terms = self::get_loc_terms();
            $menu_locations = get_registered_nav_menus();

            if ( empty( $loc_terms ) )
            {
                return;
            }

            foreach ( $loc_terms as $loc_term )
            {
                if ( empty( $loc_term->slug ) || empty( $loc_term->name ) )
                {
                    continue;
                }

                foreach ( $menu_locations as $menu_loc => $menu_loc_name )
                {
                    register_nav_menu(
                        $menu_loc . '-' . $loc_term->slug,
                        $menu_loc_name . ' - ' . esc_html( $loc_term->name )
                    );
                }
            }

            $once = true;
        }
    }
}