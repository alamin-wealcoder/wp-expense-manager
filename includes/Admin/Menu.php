<?php

namespace ExpenseManager\Admin;

// Menu handler class.
class Menu
{
    public $expense;
    public $expense_category;

    // Constructor
    public function __construct()
    {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menus'));

        $this->expense = new Expenses();
        $this->expense_category = new ExpenseCategory();
    }

    public function add_admin_menus()
    {
        $parent_slug = 'expense-list';
        $capability = 'manage_options';

        add_menu_page(
            'Expense Manager',
            'Expense Manager',
            'manage_options',
            'expense-list',
            array($this->expense, 'render_page'),
            'dashicons-money-alt',
            6
        );

        add_submenu_page($parent_slug, 'Expenses', 'Expenses', $capability, 'expense-list', array($this->expense, 'render_page'), null);

        add_submenu_page($parent_slug, 'Categories', 'Categories', $capability, 'expense-categories', array($this->expense_category, 'render_page'), null);
    }
}
