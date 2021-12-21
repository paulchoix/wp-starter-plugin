<?php
namespace Starter_Plugin;

/*
Plugin Name: WP Starter Plugin
Description: A basic starter plugin for Wordpress.
Author: Paolo Miriello
Author URI: https://developer.wordpress.com
Text Domain: starter-plugin
Domain Path: /languages
*/


/**
 * Include scripts
 */

// Uncomment if using Composer
// require_once( 'vendor/autoload.php' );

require_once( 'constants.php' );
require_once( 'helpers.php' );


/**
 * Activation and Setup
 */

function activate()
{

    // Uncomment to create a subdirectory in Wordpress' upload folder
    /*$upload = wp_upload_dir();
    $upload_directory = $upload['basedir'] . '/' . \Starter_Plugin\Constants::$snake;
    if ( ! is_dir( $upload_directory ) ) wp_mkdir_p( $upload_directory );
    //if ( ! is_dir( implode( '/', [ $upload_directory, 'subdirectory' ] ) ) ) wp_mkdir_p( $upload_directory );*/

}
register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );


// Hook database setup to activation
/*require_once 'db.php';
register_activation_hook( __FILE__, '\\Starter_Plugin\\DB\\setup' );*/


function enqueue_scripts()
{

    // Vendor styles and scripts

    // Starter Plugin styles and scripts
    wp_enqueue_style( \Starter_Plugin\Constants::$slug . '-css',  plugin_dir_url( __FILE__ ) . 'style.css' );
    wp_enqueue_script( \Starter_Plugin\Constants::$slug . '-js',  plugin_dir_url( __FILE__ ) . 'assets/js/main.js', ['wp-i18n'] );
    wp_set_script_translations( \Starter_Plugin\Constants::$slug, \Starter_Plugin\Constants::$slug );

}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );


function enqueue_admin_scripts()
{

    //wp_enqueue_script( \Starter_Plugin\Constants::$slug . 'js-admin',  plugin_dir_url( __FILE__ ) . 'js/admin.js' );         

}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_scripts' );


// Make sure Javascript is loaded as a module and include the API endpoint
function js_module( $tag, $handle, $src )
{

    if ( $handle !== \Starter_Plugin\Constants::$slug . '-js' && $handle !== \Starter_Plugin\Constants::$slug . '-js-admin' ) return $tag;
    
    $CONSTANTS = new \Starter_Plugin\Constants;
    $api_endpoint = get_home_url() . '/wp-json/' . $CONSTANTS::$api_endpoint . '/v' . $CONSTANTS::$api_version;
    return sprintf( '<script id="%s" src="%s" type="module" data-api="%s"></script>', $handle, esc_url( $src ), $api_endpoint );

}
add_filter( 'script_loader_tag', __NAMESPACE__ . '\\js_module', 10, 3 );