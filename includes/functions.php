<?php

function dpr($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}


// Insert Data
function insert_data($table, $data)
{
    global $wpdb;
    $wpdb->insert($table, $data);
    return $wpdb->insert_id;
}


// Update Data
function update_data($table, $data, $where)
{
    global $wpdb;
    $wpdb->update($table, $data, $where);
}


// Get All Data
function get_all($table)
{
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$table");

    return $result;
}

// Get Data By ID
function get_data_by_id($table, $id)
{
    global $wpdb;
    $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}$table WHERE id = $id");

    return $result;
}

// Delete Data
function delete_data($table, $id)
{
    global $wpdb;

    $id = intval($id);

    $deleted = $wpdb->delete(
        $wpdb->prefix . $table,
        [
            'id' => $id,
        ]
    );

    return $deleted;
}
