<?php
/**
 * Plugin Name: AXi System
 * Description: System implementation for AcademyXi.
 * Plugin URI:  https://example.com/
 * Version:     1.0.0
 * Author:      John Doe
 * Author URI:  https://example.com/
 * Text Domain: axi-system
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
{
    exit;
}

define( 'AXISYS_VERSION', '1.0.0' );
define( 'AXISYS_LAST_STABLE_VERSION', '1.0.0' );

define( 'AXISYS_BASE', plugin_basename( __FILE__ ) );
define( 'AXISYS_PATH', plugin_dir_path( __FILE__ ) );
define( 'AXISYS_URL', plugins_url( '/', __FILE__ ) );

define( 'AXISYS_OPT_GROUP', 'axisys' );
define( 'AXISYS_OPT_NAME', 'axisys' );

define( 'AXISYS_ADMIN_PAGE_ID', 'axisys' );
define( 'AXISYS_ADMIN_SETTINGS_PAGE_ID', 'axisys-settings' );

define( 'AXISYS_TMPL_HOME', 'axisys-tmpl-home.php' );
define( 'AXISYS_TMPL_LANDING', 'axisys-tmpl-landing.php' );
define( 'AXISYS_TMPL_MODALITY', 'axisys-tmpl-modality.php' );
define( 'AXISYS_TMPL_COURSE', 'axisys-tmpl-course.php' );
define( 'AXISYS_TMPL_DISCIPLINE', 'axisys-tmpl-discipline.php' );

define( 'AXISYS_LOCATION_COOKIE', 'axisys_location_' . COOKIEHASH );

add_action( 'plugins_loaded', 'axisys_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) )
{
    add_action( 'admin_notices', 'axisys_fail_php_version' );
}
elseif ( ! version_compare( get_bloginfo( 'version' ), '4.8', '>=' ) )
{
    add_action( 'admin_notices', 'axisys_fail_wp_version' );
}
else
{
    require AXISYS_PATH . 'includes/plugin.php';
}

/**
 * Load textdomain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function axisys_load_plugin_textdomain()
{
    load_plugin_textdomain( 'axi-system' );
}

/**
 * Admin notice for minimum PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function axisys_fail_php_version()
{
    $message = sprintf(
        wp_kses(
            /* translators: %s: Your server PHP version */
            __( '<strong>AXi System</strong> requires PHP version 5.6+. Because you are using PHP version %s, plugin is currently NOT RUNNING', 'axi-system' ),
            array( 'strong' => array() )
        ),
        PHP_VERSION
    );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo $html_message; // WPCS: XSS ok.
}

/**
 * Admin notice for minimum WordPress version.
 *
 * @since 1.5.0
 *
 * @return void
 */
function axisys_fail_wp_version()
{
    $message = sprintf(
        wp_kses(
            /* translators: %s: Your server PHP version */
            __( '<strong>AXi System</strong> requires WordPress version 5.0+. Because you are using WordPress version %s, the plugin is currently NOT RUNNING', 'axi-system' ),
            array( 'strong' => array() )
        ),
        get_bloginfo( 'version' )
    );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo $html_message; // WPCS: XSS ok.
}
