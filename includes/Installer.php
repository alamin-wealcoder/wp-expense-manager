<?php

namespace ExpenseManager;

class Installer
{

    public function install()
    {
        $this->create_tables();
    }

    public function create_tables()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $em_table_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}expenses (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            expense_date varchar(255) NOT NULL,
            expense_amount float NOT NULL,
            expense_category varchar(255) NOT NULL,
            expense_paid_to varchar(255) NOT NULL,
            expense_status varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        $em_category_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}expenses_category (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            category_name varchar(255) NOT NULL,
            date_created datetime NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta($em_table_sql);
        dbDelta($em_category_sql);
    }
}
