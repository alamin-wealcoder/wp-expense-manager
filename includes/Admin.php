<?php

namespace ExpenseManager;

// Admin handler class
class Admin
{
    public function __construct()
    {
        new Admin\Menu();

        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }

    public function admin_enqueue_scripts()
    {
        wp_enqueue_style('expense-manager-admin', plugins_url('/assets/css/admin-style.css', WP_EM_FILE));
    }
}
