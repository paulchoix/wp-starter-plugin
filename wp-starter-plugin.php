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

require_once('constants.php');
require_once('helpers.php');

use Starter_Plugin\Constants;


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


// Hook database setup to activation
/*require_once 'db.php';
register_activation_hook( __FILE__, '\\Starter_Plugin\\DB\\setup' );*/


// Require settings (always place after setup/db)
require_once 'settings.php';


function enqueue_scripts()
{
    $version = Constants::$VERSION;

    // Plugin CSS
    wp_enqueue_style("starter-plugin-{$version}", get_stylesheet_uri());

    // Plugin JS
    wp_enqueue_script("starter-plugin-{$version}", get_template_directory_uri() . '/assets/js/main.js', ['wp-i18n']);

    // Vendor CSS

    // Vendor JS

    // Filter for Plugin JS (allows modules and adds API endpoint)
    add_filter('script_loader_tag', __NAMESPACE__ . '\\script_modify', 10, 3);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');


function enqueue_admin_scripts()
{
    //wp_enqueue_script( Constants::$SLUG . '-js-admin',  plugin_dir_url( __FILE__ ) . 'assets/js/admin.js' );         
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_scripts');


// From: https://stackoverflow.com/questions/58931144/enqueue-javascript-with-type-module
// Adds module tag and API endpoint to starter-plugin-js script
function script_modify($tag, $handle, $src)
{
    $version = Constants::$VERSION;

    if (!in_array($handle, ["starter-plugin-{$version}"])) { // Add additional handles if necessary
        return $tag;
    }

    $CONSTANTS = new Constants();

    $api_endpoint = get_home_url() . '/wp-json/' . $CONSTANTS->API_ROOT;
    $tag = sprintf('<script id="%s" type="module" data-api="%s" src="%s"></script>', $handle, $api_endpoint, esc_url($src));
    return $tag;
}


// Register API routes
add_action('rest_api_init', function () {
    $CONSTANTS = new Constants();

    /*register_rest_route( Constants::$API_ROOT, 'resource', [
      'methods' => 'GET',
      'callback' => 'function',
      'permission_callback' => '__return_true', // This makes the endpoint public
    ]);*/
});


// Load translation files
function load_textdomain()
{
    load_plugin_textdomain('starter-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', __NAMESPACE__ . '\\load_textdomain');
