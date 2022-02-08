<?php
namespace Starter_Plugin;

// Variables cannot be namespaced in PHP, so they are encapsulated in the following class
class Constants
{
    /**
     * Global
     * These variables are provided to namespace plugin elements within Wordpress (namely options and API endpoints)
     */
    public static $slug = 'starter-plugin';
    public static $snake = 'starter_plugin';
    public static $settings = 'starter_plugin_settings';

    /**
     * Database
     * Unless a different value is provided to distinguish between versions, db_version defaults to 1.0
     * 
     * [WARNING] Defining a DB version is encouraged by Wordpress but not currently implemented in this starter
     * See: https://codex.wordpress.org/Creating_Tables_with_Plugins#A_Version_Option
     */
    public static $db_prefix = 'starter_plugin_';
    public $db_version;

    /**
     * API
     * Unless a different value is provided to distinguish between versions, api_version defaults to 1
     */
    public static $api_endpoint = 'starter-plugin';
    public $api_version;

    function __construct( array $args = [] )
    {
        $this->db_version = array_key_exists( 'db_version', $args ) ? (int) $args['db_version'] : '1.0'; // [WARNING] Not currently being used in this starter
        $this->api_version = array_key_exists( 'api_version', $args ) ? (int) $args['api_version'] : 1;
    }
}