<?php

namespace Starter_Plugin;

// Variables cannot be namespaced in PHP, so they are encapsulated in the following class
class Constants
{
    /**
     * Version
     */
    public static $PLUGIN_VERSION = '1.0.0';

    /**
     * Global
     * These variables are provided to namespace plugin elements within Wordpress (namely options and API endpoints)
     */
    public static $SLUG = 'starter-plugin';
    public static $SNAKE = 'starter_plugin';
    public static $SETTINGS = 'starter_plugin_settings';

    /**
     * Database
     * Unless a different value is provided to distinguish between versions, db_version defaults to 1.0
     * 
     * [WARNING] Defining a DB version is encouraged by Wordpress but not currently implemented in this starter
     * See: https://codex.wordpress.org/Creating_Tables_with_Plugins#A_Version_Option
     */
    public static $DB_PREFIX = 'starter_plugin_';
    public $DB_VERSION;

    /**
     * API
     * Unless a different value is provided to distinguish between versions, api_version defaults to 1
     */
    public static $API_ENDPOINT = 'starter-plugin';
    public $API_ROOT;

    function __construct(array $args = [])
    {
        $this->DB_VERSION = array_key_exists('db_version', $args) ? (int) $args['db_version'] : '1.0'; // [WARNING] Not currently being used in this starter
        $this->API_ROOT = array_key_exists('api_version', $args) ? $this::$API_ENDPOINT . '/v' . $args['api_version'] : 1;
    }
}
