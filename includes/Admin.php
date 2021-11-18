<?php

namespace ExpenseManager;

// Admin handler class
class Admin
{
    // Constructor
    public function __construct()
    {

        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Add admin scripts
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Expense Manager',
            'Expense Manager',
            'manage_options',
            'expense-manager',
            array($this, 'expense_manager_page'),
            'dashicons-money-alt',
            6
        );
    }

    public function expense_manager_page()
    {
        echo '<h1>Expense Manager</h1>';
    }

}
