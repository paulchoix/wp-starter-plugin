<?php
namespace Starter_Plugin\Models;

use Starter_Plugin\Constants;

// [WARNING] Should results always be returned as associative arrays?
class Base_Model
{
    // To be inherited when extending Base_Model
    protected $db_prefix;
    protected $table_string;

    // To be defined when extending Base_Model
    protected $table; // string
    protected $get_fields; // array of strings
    protected $post_fields; // array of strings
    protected $user_fields; // array of strings
    protected $user_id_field; // string

    function __construct()
    {
        global $wpdb;
        $this->db_prefix = $wpdb->prefix . Constants::$db_prefix;
        $this->table_string = $this->db_prefix . $this->table;
    }

    /**
     * Methods
     */

    // Gets one or many rows
    public function get( $id = null, $query_params = null )
    {
        global $wpdb;
        $fields_string = implode( ', ', $this->get_fields );
        
        // Return a single row if ID is specified
        if ( $id ) return $wpdb->get_row( sprintf( "SELECT %s FROM %s WHERE ID=%s", $fields_string, $this->table_string, $id ), 'ARRAY_A' );

        // Return multiple rows with or without WHERE clause if ID is not specified
        $query_string = "SELECT %s FROM %s";
        if ( $query_params )
        {
            if ( array_key_exists( 'where', $query_params ) ) $query_string .= sprintf( ' WHERE %s', $query_params['where'] );
            if ( array_key_exists( 'order_by', $query_params ) ) $query_string .= sprintf( ' ORDER BY %s', $query_params['order_by'] );
            if ( array_key_exists( 'limit', $query_params ) ) $query_string .= sprintf( ' LIMIT %s', $query_params['limit'] );
            if ( array_key_exists( 'offset', $query_params ) ) $query_string .= sprintf( ' OFFSET %s', $query_params['offset'] );
        }

        $rows = $wpdb->get_results( sprintf( $query_string, $fields_string, $this->table_string ), 'ARRAY_A' );
        return $rows;
    }

    // Return the number of rows corresponding to a query
    public function count( $query_params = null, $column_name = 'id' )
    {
        global $wpdb;

        $query_string = "SELECT COUNT(%s) FROM %s";
        if ( $query_params )
        {
            if ( array_key_exists( 'where', $query_params ) ) $query_string .= sprintf( ' WHERE %s', $query_params['where'] );
            if ( array_key_exists( 'order_by', $query_params ) ) $query_string .= sprintf( ' ORDER BY %s', $query_params['order_by'] );
            if ( array_key_exists( 'limit', $query_params ) ) $query_string .= sprintf( ' LIMIT %s', $query_params['limit'] );
            if ( array_key_exists( 'offset', $query_params ) ) $query_string .= sprintf( ' OFFSET %s', $query_params['offset'] );
        }

        $result = $wpdb->get_var( sprintf( $query_string, $column_name, $this->table_string ) );
        return $result;
    }

    // Inserts or updates a row
    public function post( $data, int $id = null, $id_key = 'id', $slug_key = 'slug' )
    {
        global $wpdb;

        if ( array_key_exists( $slug_key, $data ) )
        {
            $slugs = $this->get_slugs( $data[$slug_key], $slug_key );
            if ( $slugs )
            {
                $slug_index = 1;
                while( in_array( $data[$slug_key] . '-' . $slug_index, $slugs)  ) $slug_index += 1;
                $data[$slug_key] = $data[$slug_key] . '-' . $slug_index;
            }
        }
        if ( $id ) $data[$id_key] = $id;
        $data = $this->prepare_wp_insert( $data );

        // Update a record if the ID is specified
        if ( $id )
        {
            $wpdb->update( $this->table_string, $data['data'], $data['types'] );
            return $id;
        }

        // Insert a record if no ID is specified, return ID
        $wpdb->insert( $this->table_string, $data['data'], $data['types'] );
        return $wpdb->insert_id;
    }

    // Deletes a row - returns 1 if successful, false if failed
    public function delete( int $id, $id_string = 'id' )
    {
        global $wpdb;
        return $wpdb->delete( $this->table_string, [$id_string => $id] );
    }

    /**
     * Utilities
     */

    // Appends a Wordpress user to a row based on the model's user_id_field
    protected function append_wp_user( $row, $user_field_key = '_wp_user' )
    {
        global $wpdb;
        $fields_string = implode( ', ', $this->user_fields );

        $user = $wpdb->get_row( sprintf( "SELECT %s FROM wp_users WHERE ID=%s", $fields_string, $row[$this->user_id_field] ), 'ARRAY_A' );
        $row[$user_field_key] = $user;

        return $row;
    }

    // Returns all slugs from a given table
    protected function get_slugs( $slug = null, $slug_key )
    {
        global $wpdb;

        $query_string = sprintf( "SELECT %s FROM %s", $slug_key, $this->table_string );
        if( $slug ) $query_string = sprintf( $query_string . " WHERE %s LIKE '%s%%'", $slug_key, $slug );
        $rows = $wpdb->get_results( $query_string, 'ARRAY_A' );
        
        $output = [];
        if( $rows ) foreach( $rows as $row ) array_push( $output, $row[$slug_key] );

        return $output;
    }

    // Takes an associative array or object and prepares it for wpdb::insert
    protected function prepare_wp_insert( $input )
    {
        $data = [];
        $types = [];

        foreach( $this->post_fields as $field )
        {
            if( !array_key_exists( $field, $input ) ) continue;
            if( $input[$field] == null ) continue; // This is because Wordpress does not provide a NULL type for wpdb::insert data - better not to insert the value at all

            switch( gettype( $input[$field] ) )
            {
                case 'integer':
                    $data[$field] = $input[$field];
                    array_push( $types, '%d' );
                    break;

                case 'double':
                    $data[$field] = $input[$field];
                    array_push( $types, '%f' );
                    break;

                case 'string':
                    $data[$field] = $input[$field];
                    array_push( $types, '%s' );
                    break;

                default:
                    $data[$field] = (string) $input[$field]; // Converts all non integer, float and string to string. Is this a good idea?
                    array_push( $types, '%s' );
            }
        }

        return ['data' => $data, 'types' => $types];
    }
}