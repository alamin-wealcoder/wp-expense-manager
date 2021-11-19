<?php

namespace ExpenseManager\Admin;

/**
 * Class Expenses
 */

class Expenses
{
    public $messages = [];
    public $categories;

    public function __construct()
    {
        $cat_obj = new ExpenseCategory();
        $this->categories = $cat_obj->get_categories();

        $this->form_handler();
    }

    public function render_page()
    {
        $tablename = "expenses";

        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'new':
                $template = __DIR__ . '/views/expenses/expense_new.php';
                break;

            case 'edit':
                $template = __DIR__ . '/views/expenses/expense_edit.php';
                break;

            case 'delete':
                $deleted = $this->delete_expense($_GET['id']);
                if ($deleted) {
                    $this->messages['delete-success'] = 'Expense deleted successfully.';
                } else {
                    $this->messages['delete-error'] = 'Error deleting expense!';
                }
                $template = __DIR__ . '/views/expenses/expense_new.php';
                break;

            default:
                $template = __DIR__ . '/views/expenses/expense_list.php';
                break;
        }

        if (file_exists($template)) {
            include_once $template;
        }
    }

    public function form_handler()
    {
        if (!isset($_POST['submit_expense'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['_wpnonce'], 'new-expense')) {
            wp_die("You are not allowed to access this page!");
        }

        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to access this page!');
        }

        $expense_id = isset($_POST['expense_id']) ? $_POST['expense_id'] : '';
        $expense_date = isset($_POST['expense_date']) ? $_POST['expense_date'] : '';
        $expense_amount = isset($_POST['amount']) ? $_POST['amount'] : '';
        $expense_category = isset($_POST['purpose']) ? $_POST['purpose'] : '';
        $expense_paid_to = isset($_POST['paid_to']) ? $_POST['paid_to'] : '';

        if (empty($expense_amount)) {
            $this->messages['expense_amount_err'] = 'Expense amount is required.';
            return;
        }

        if (count($expense_category) > 1) {
            $expense_category = implode(', ', $expense_category);
        } else {
            $expense_category = $expense_category[0];
        }


        // Insert / Update Expense
        $expense_data = [
            'expense_date' => $expense_date,
            'expense_amount' => $expense_amount,
            'expense_category' => $expense_category,
            'expense_paid_to' => $expense_paid_to,
            'expense_status' => 1,
        ];


        global $wpdb;
        $tablename = "{$wpdb->prefix}expenses";

        if (empty($expense_id)) {
            $inserted = insert_data($tablename, $expense_data);

            if (!$inserted) {
                return new \WP_Error('failed-to-insert', __('Failed to insert data', WP_EM_TXT_DOMAIN));
            } else {
                $redirected_to = admin_url('admin.php?page=expenses&inserted=true');
            }
        } else {
            $updated = update_data($tablename, $expense_data, ['id' => $expense_id]);
            if ($updated) {
                $redirected_to = admin_url('admin.php?page=expenses&updated=true');
            } else {
                $redirected_to = admin_url('admin.php?page=expenses&updated=false');
            }
        }

        wp_redirect($redirected_to);
        exit;
    }

    public function delete_expense()
    {
    }
}
