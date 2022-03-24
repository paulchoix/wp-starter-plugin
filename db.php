<?php
namespace Starter_Plugin\DB;

use Starter_Plugin\Constants;

/**
 * From https://codex.wordpress.org/Creating_Tables_with_Plugins
 * You must put each field on its own line in your SQL statement.
 * You must have two spaces between the words PRIMARY KEY and the definition of your primary key.
 * You must use the key word KEY rather than its synonym INDEX and you must include at least one KEY.
 * KEY must be followed by a SINGLE SPACE then the key name then a space then open parenthesis with the field name then a closed parenthesis.
 * You must not use any apostrophes or backticks around field names.
 * Field types must be all lowercase.
 * SQL keywords, like CREATE TABLE and UPDATE, must be uppercase.
 * You must specify the length of all fields that accept a length parameter. int(11), for example.
 */


function setup()
{
    $database_tables = new DatabaseTables();
    $result = $database_tables->run();

    // [TODO] Handle errors in $result
}


class DatabaseTables
{
    public $prefix;
    public $charset_collate;

    // [WARNING] Is there a better way of storing these queries?
    public $queries = [
        'tag' => 'CREATE TABLE %s (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            created timestamp NOT NULL DEFAULT current_timestamp(),
            label varchar(63) NOT NULL,
            slug varchar(63) NOT NULL,
            deleted tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY  (id),
            UNIQUE KEY  label (label),
            UNIQUE KEY  slug (slug)
            ) %s;'
    ];

    function __construct()
    {
        global $wpdb;
        $this->charset_collate = $wpdb->get_charset_collate();
        $this->prefix = $wpdb->prefix . Constants::$db_prefix;
    }

    public function run()
    {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        try
        {
            foreach ( $this->queries as $table => $query ) dbDelta( sprintf( $query, $this->prefix . $table, $this->charset_collate ) );
            return true;
        } catch ( \Exception $e )
        {
            return $e;
        }
    }
}