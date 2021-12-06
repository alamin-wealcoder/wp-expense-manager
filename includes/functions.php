<?php

if ( !function_exists( 'dpr' ) ) {
    function dpr( $data ) {
        echo '<pre>';
        print_r( $data );
        echo '</pre>';
        die();
    }
}
if ( !function_exists( 'pr' ) ) {
    function pr( $data ) {
        echo '<pre>';
        print_r( $data );
        echo '</pre>';
    }
}

// Insert Data
if ( !function_exists( 'insert_data' ) ) {
    function insert_data( $table, $data ) {
        global $wpdb;
        $wpdb->insert( $table, $data );
        return $wpdb->insert_id;
    }
}

// Update Data
if ( !function_exists( 'update_data' ) ) {
    function update_data( $table, $data, $where ) {
        global $wpdb;
        $wpdb->update( $table, $data, $where );
    }
}

// Get All Data
if ( !function_exists( 'get_all' ) ) {
    function get_all( $table ) {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$table" );

        return $result;
    }
}

// Get Data By Condition
if ( !function_exists( 'get_data' ) ) {
    function get_data( $table, $condition ) {
        global $wpdb;

        $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$table WHERE $condition" );

        return $result;
    }
}

// Get Data By ID
if ( !function_exists( 'get_data_by_id' ) ) {
    function get_data_by_id( $table, $id ) {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$table WHERE id IN ( $id )" );

        return $result;
    }
}

// Get Data by Category
function get_data_by_categories( $table, $ids ) {
    global $wpdb;
    $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$table WHERE expense_category IN ( $ids )" );

    return $result;
}

//Get Data By Search
function get_data_by_search( $table, $search ) {
    global $wpdb;

    $search_ids = '';

    $search_cats = $wpdb->get_results( "SELECT `id` FROM {$wpdb->prefix}expenses_category WHERE category_name LIKE '%$search%'" );

    foreach ( $search_cats as $key => $value ) {
        $search_ids .= $value->id . ', ';
    }

    $search_ids = rtrim( $search_ids, ', ' );

    $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$table WHERE expense_category LIKE '%$search_ids%'" );

    return $result;
}

// Delete Data
if ( !function_exists( 'delete_data' ) ) {
    function delete_data( $table, $id ) {
        global $wpdb;

        $id = intval( $id );

        $deleted = $wpdb->delete(
            $wpdb->prefix . $table,
            [
                'id' => $id,
            ]
        );

        return $deleted;
    }
}
