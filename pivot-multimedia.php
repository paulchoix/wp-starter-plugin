<?php

namespace Pivot_Multimedia;

/*
Plugin Name: Pivot - Multimédia
Description: Extension pour les pages de multimédia.
Author: Paolo Miriello
Author URI: https://pivot.quebec
Text Domain: pivot-multimedia
Domain Path: /languages
*/


/**
 * Include scripts
 */

require_once('constants.php');
require_once('helpers.php');

use Pivot_Multimedia\Constants;


/**
 * Activation and Setup
 */

function activate()
{
    // Uncomment to create a subdirectory in Wordpress' upload folder
    /*$upload = wp_upload_dir();
    $upload_directory = $upload['basedir'] . '/' . Constants::$snake;
    if ( ! is_dir( $upload_directory ) ) wp_mkdir_p( $upload_directory );
    //if ( ! is_dir( implode( '/', [ $upload_directory, 'subdirectory' ] ) ) ) wp_mkdir_p( $upload_directory );*/
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate');


// Require settings (always place after setup/db)
require_once('settings.php');
require_once('types.php');
require_once('taxonomies.php');


function enqueue_scripts()
{
    // Vendor styles and scripts

    // Pivot Multimedia styles and scripts
    wp_enqueue_style(Constants::$SLUG . '-' . Constants::$THEME_VERSION,  plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_script(Constants::$SLUG . '-' . Constants::$THEME_VERSION,  plugin_dir_url(__FILE__) . 'assets/js/main.js', ['wp-i18n']);
    wp_set_script_translations(Constants::$SLUG, Constants::$SLUG);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');


function enqueue_admin_scripts()
{
    //wp_enqueue_script( Constants::$SLUG . '-js-admin',  plugin_dir_url( __FILE__ ) . 'assets/js/admin.js' );         
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_scripts');


// Make sure Javascript is loaded as a module and include the API endpoint
function script_modify($tag, $handle, $src)
{
    if ($handle !== Constants::$SLUG . '-js' && $handle !== Constants::$SLUG . '-js-admin') return $tag;

    $CONSTANTS = new Constants();
    $api_endpoint = get_home_url() . '/wp-json/' . $CONSTANTS::$API_ENDPOINT . '/v' . $CONSTANTS->api_version;
    return sprintf('<script id="%s" src="%s" type="module" data-api="%s"></script>', $handle, esc_url($src), $api_endpoint);
}
add_filter('script_loader_tag', __NAMESPACE__ . '\\script_modify', 10, 3);


// Register API routes
add_action('rest_api_init', function () {
    $CONSTANTS = new Constants();

    /*register_rest_route( Constants::$api_endpoint . '/v' . $constants->api_version, 'resource', [
      'methods' => 'GET',
      'callback' => 'function',
      'permission_callback' => '__return_true', // This makes the endpoint public
    ]);*/
});


// Load translation files
function load_textdomain()
{
    load_plugin_textdomain('pivot-multimedia', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', __NAMESPACE__ . '\\load_textdomain');
