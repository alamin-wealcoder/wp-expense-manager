<?php

namespace ExpenseManager\Admin;

/**
 * Class ExpenseCategory
 */

class ExpenseCategory
{
    public $messages = [];

    public function __construct()
    {
        $this->form_handler();
    }

    public function render_page()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case '':
                $template = __DIR__ . '/views/expense_category/category_new.php';
                break;

            case 'edit':
                $template = __DIR__ . '/views/expense_category/category_edit.php';
                break;

            default:
                $template = __DIR__ . '/views/expense_category/category_list.php';
                break;
        }

        if (file_exists($template)) {
            include_once $template;
        }
    }

    public function form_handler()
    {
        if (!isset($_POST['submit_expense_category'])) {return;}

        if (!wp_verify_nonce($_POST['_wpnonce'], 'new-expense-category')) {
            wp_die("You are not allowed to access this page!");
        }

        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to access this page!');
        }

        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';

        if (empty($name)) {
            $this->messages['name_err'] = 'Name is required.';
            return;
        }

        if (isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $this->update_category($id, $name);
            $redirected_to = admin_url('admin.php?page=expense-categories&action=edit&updated=true&id=' . $id);

        } else {
            $inserted = $this->insert_category($name);

            if (!$inserted) {
                return new \WP_Error('failed-to-insert', __('Failed to insert data', WP_EM_TXT_DOMAIN));
            } else {
                $this->messages['success'] = 'Category added successfully.';
                $redirected_to = admin_url('admin.php?page=expense-categories&inserted=true');
            }
        }

        wp_redirect($redirected_to);
        exit;
    }

    public function insert_category($name)
    {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'expenses_category',
            [
                'category_name' => $name,
            ],
            [
                '%s',
            ]
        );

        return $wpdb->insert_id;
    }

    public function update_category($id, $name)
    {

    }

    public function delete_category()
    {

    }
}
