<?php

namespace Pivot_Multimedia;

// Variables cannot be namespaced in PHP, so they are encapsulated in the following class
class Constants
{
    /**
     * Version
     */
    public static $THEME_VERSION = '1.0.0';

    /**
     * Global
     * These variables are provided to namespace plugin elements within Wordpress (namely options and API endpoints)
     */
    public static $SLUG = 'pivot-multimedia';
    public static $SNAKE = 'pivot_multimedia';
    public static $SETTINGS = 'pivot_multimedia_settings';

    /**
     * API
     * Unless a different value is provided to distinguish between versions, api_version defaults to 1
     */
    public static $API_ENDPOINT = 'pivot-multimedia';
    public $API_VERSION;

    function __construct(array $args = [])
    {
        $this->API_VERSION = array_key_exists('api_version', $args) ? (int) $args['api_version'] : 1;
    }
}
