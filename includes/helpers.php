<?php
/**
 * Helper functions
 *
 * @package AXi_System
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
{
    exit;
}

/**
 * Get plugin option based on name
 *
 * @since 1.0.0
 * @param string $opt_name
 * @param string $default
 * @return mixed
 */
function axisys_get_opt( $opt_name = '', $default = '' )
{
    $opt_name = trim( $opt_name );
    if ( ! $opt_name )
    {
        return $default;
    }
    $options = maybe_unserialize( get_option( AXISYS_OPT_NAME ) );
    if ( isset( $options[ $opt_name ] ) )
    {
        return $options[ $opt_name ];
    }
    return $default;
}

/**
 * Check if a string is valid telephone number
 *
 * @param string $tel
 * @return boolean
 */
function axisys_is_tel( $tel )
{
    $result = preg_match( '%^[+]?[0-9()/ -]*$%', $tel );
    return apply_filters( 'wpcf7_is_tel', $result, $tel );
}

/**
 * Generate unique id with specific length
 *
 * @param  integer $length Default to 8
 * @return string
 */
function axisys_guid( $length = 8 )
{
    $str = substr( md5( microtime() ), rand( 0, 26 ), $length );
    while ( strlen( $str ) < $length )
    {
        $str .= substr( md5( microtime() ), rand( 0, 26 ), $length );
    }
    return substr( $str, 0, $length );
}

/**
 * Get correct kses for escaping data before echoing
 *
 * @param string $type
 * @return array
 */
function axisys_kses( $type = 'heading' )
{
    $kses = [
        'span'   => [ 'style' => [], 'class' => [], 'id' => [] ],
        'strong' => [ 'style' => [], 'class' => [], 'id' => [] ],
        'em'     => [ 'style' => [], 'class' => [], 'id' => [] ],
        'b'      => [ 'style' => [], 'class' => [], 'id' => [] ],
        'i'      => [ 'style' => [], 'class' => [], 'id' => [] ],
        'br'     => []
    ];

    switch ( $type )
    {
        case 'paragraph':
        case 'heading':
            $kses['a'] = [ 'style' => [], 'class' => [], 'id' => [], 'href' => [], 'rel' => [] ];
            break;

        case 'blockquote':
            $kses['a'] = [ 'style' => [], 'class' => [], 'id' => [], 'href' => [], 'rel' => [] ];
            $kses['p'] = [ 'style' => [], 'class' => [], 'id' => [] ];
            $kses['q'] = [ 'style' => [], 'class' => [], 'id' => [] ];
            break;

        default: // Default to inline
            break;
    }

    return $kses;
}


/**
 * Get name and id formidable form
 */
function academyxi_get_formidable_form($limit)
{
	global $wpdb;
	$query   = 'SELECT id,name FROM ' . $wpdb->prefix . 'frm_forms ORDER BY id ASC LIMIT '. $limit;
	$forms = $wpdb->get_results( $query );
    return $forms;
}