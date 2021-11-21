<?php

namespace ExpenseManager\Admin;

/**
 * Class Expenses
 */

class Expenses
{
    public $messages = [];
    public $categories;
    public $tablename;

    public function __construct()
    {
        $this->tablename = "expenses";

        $cat_obj = new ExpenseCategory();
        $this->categories = $cat_obj->get_categories();


        if (isset($_POST['submit_expense'])) {
            $this->form_handler();
        }


        $this->filter_submit();
    }

    public function render_page()
    {
        if ('expenses' != $_GET['page']) {
            return;
        }

        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'new':
                $template = __DIR__ . '/views/expenses/expense_new.php';
                break;

            case 'edit':
                $template = __DIR__ . '/views/expenses/expense_edit.php';
                break;

            case 'delete':
                $deleted = delete_data($this->tablename, $_GET['id']);
                if ($deleted) {
                    $this->messages['delete-success'] = 'Expense deleted successfully.';
                } else {
                    $this->messages['delete-error'] = 'Error deleting expense!';
                }
                $template = __DIR__ . '/views/expenses/expense_list.php';
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
        if (!wp_verify_nonce($_POST['_wpnonce'], 'new-expense')) {
            wp_die("You are not allowed to access this page!");
        }

        if (!current_user_can('manage_options')) {
            wp_die('You are not allowed to access this page!');
        }

        $expense_id = isset($_POST['expense_id']) ? intval($_POST['expense_id']) : '';
        $expense_date = isset($_POST['expense_date']) ? $_POST['expense_date'] : '';
        $expense_amount = isset($_POST['amount']) ? $_POST['amount'] : '';
        $expense_category = isset($_POST['purpose']) ? $_POST['purpose'] : '';
        $expense_paid_to = isset($_POST['paid_to']) ? $_POST['paid_to'] : '';

        if (empty($expense_amount)) {
            $this->messages['expense_amount_err'] = 'Expense amount is required.';
            return;
        }

        if (!empty($expense_category)) {
            if (count($expense_category) > 1) {
                $expense_category = implode(', ', $expense_category);
            } else {
                $expense_category = $expense_category[0];
            }
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
        $this->tablename = "{$wpdb->prefix}expenses";

        if (empty($expense_id)) {
            $inserted = insert_data($this->tablename, $expense_data);

            if (!$inserted) {
                return new \WP_Error('failed-to-insert', __('Failed to insert data', WP_EM_TXT_DOMAIN));
            } else {
                $redirected_to = admin_url('admin.php?page=expenses&inserted=true');
            }
        } else {
            $updated = update_data($this->tablename, $expense_data, ['id' => $expense_id]);
            if ($updated) {
                $redirected_to = admin_url('admin.php?page=expenses&updated=true');
            } else {
                $redirected_to = admin_url('admin.php?page=expenses&updated=false');
            }
        }

        wp_redirect($redirected_to);
        exit;
    }


    public function filter_submit()
    {
        if (isset($_GET['expense_filter'])) {
            wp_redirect("admin.php?page=expenses&category={$_GET['category']}&year={$_GET['year']}&month={$_GET['month']}");
            exit;
        }


        if (isset($_GET['expense_search'])) {
            wp_redirect("admin.php?page=expenses&search_expense={$_GET['search_expense']}");
            exit;
        }
    }

    public function filter_by_params($expenses)
    {
        //Filter By Category
        if (isset($_GET['category']) && $_GET['category'] != 0) {

            $cat_id = $_GET['category'];
            $expense_ids = '';

            foreach ($expenses as $key => $expense) {
                if ($cat_id == $expense->expense_category) {
                    $expense_ids .= $expense->id . ', ';
                }
                if (str_contains($expense->expense_category, ', ')) {
                    $cat_arr = explode(', ', $expense->expense_category);

                    if (in_array($cat_id, $cat_arr)) {
                        $expense_ids .= $expense->id . ', ';
                    }
                }
            }

            $expense_ids = rtrim($expense_ids, ', ');

            if (!empty($expense_ids)) {
                $expenses = get_data_by_id($this->tablename, $expense_ids);
            } else {
                $expenses = array();
            }
        }

        // Filter By Year
        if (isset($_GET['year']) && $_GET['year'] != 0) {
            $year = $_GET['year'];
            $expense_ids = '';

            foreach ($expenses as $key => $expense) {
                if (substr($expense->expense_date, 0, 4) == $year) {
                    $expense_ids .= $expense->id . ', ';
                }
            }

            $expense_ids = rtrim($expense_ids, ', ');

            if (!empty($expense_ids)) {
                $expenses = get_data_by_id($this->tablename, $expense_ids);
            } else {
                $expenses = array();
            }
        }

        // Filter By Month
        if (isset($_GET['month']) && $_GET['month'] != 0) {
            $choosen_month = $_GET['month'];
            $expense_ids = '';


            foreach ($expenses as $key => $expense) {
                $expense_month = substr($expense->expense_date, 5, 2);

                if ($expense_month == $choosen_month) {
                    $expense_ids .= $expense->id . ', ';
                }
            }

            $expense_ids = rtrim($expense_ids, ', ');

            if (!empty($expense_ids)) {
                $expenses = get_data_by_id($this->tablename, $expense_ids);
            } else {
                $expenses = array();
            }
        }

        return $expenses;
    }

    // Expense Search Function
    public function expense_search($expenses)
    {
        if (isset($_GET['search_expense']) && !empty($_GET['search_expense'])) {
            $search_term = strtolower($_GET['search_expense']);
            $expense_ids = '';

            foreach ($expenses as $key => $expense) {

                // Search By Category
                if (str_contains($expense->expense_category, ', ')) {
                    $cat_arr = explode(', ', $expense->expense_category);
                    foreach ($cat_arr as $cat) {
                        $cat = get_data('expenses_category', 'id=' . $cat);

                        if (str_contains(strtolower($cat[0]->category_name), $search_term)) {
                            $expense_ids .= $expense->id . ', ';
                        }
                    }
                } else {
                    $cat = get_data('expenses_category', 'id=' . $expense->expense_category);
                    if (str_contains(strtolower($cat[0]->category_name), $search_term)) {
                        $expense_ids .= $expense->id . ', ';
                    }
                }

                // Search By Amount, Paid To
                if (str_contains(strtolower($expense->expense_paid_to), $search_term) || str_contains($expense->expense_amount, $search_term)) {
                    $expense_ids .= $expense->id . ', ';
                }


                // Search By Date
                $date = date_format(date_create($expense->expense_date), 'j F, Y');
                if (str_contains(strtolower($date), $search_term)) {
                    $expense_ids .= $expense->id . ', ';
                }
            }

            $expense_ids = rtrim($expense_ids, ', ');

            if (!empty($expense_ids)) {
                $expenses = get_data_by_id($this->tablename, $expense_ids);
            } else {
                $expenses = array();
            }
        }

        return $expenses;
    }
}
