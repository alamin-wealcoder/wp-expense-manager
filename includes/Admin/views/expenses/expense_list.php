<?php
$total_expense = 0;

if (isset($_GET['category']) && $_GET['category'] != 0) {
    $expenses = get_all($tablename);

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

    $expenses = get_data_by_id($tablename, $expense_ids);
} else {
    $expenses = get_all($tablename);
}

if (count($expenses) > 0) {
    foreach ($expenses as $expense) {
        $total_expense += $expense->expense_amount;
    }
}
?>

<div class="wrap">
    <h2 class="wp-heading-inline"><?php _e('Expenses', WP_EM_TXT_DOMAIN); ?> <a href="admin.php?page=expenses&action=new" class="page-title-action">Add New Expense</a> </h2>

    <hr>

    <br>
    <div class="total-expense">
        <h1>Total Expense: ৳<span><?php echo $total_expense; ?></span></h1>
    </div>
    <br>

    <div class="filter-container">
        <div class="col-left">
            <h3>Expense List</h3>
        </div>
        <div class="col-right">
            <form action="#" method="get">
                <label for="category"> </label>
                <select name="category" id="category" class="postform">
                    <option value="0">All Categories</option>
                    <?php
                    $cat_id = (isset($_GET['category'])) ? $_GET['category'] : 0;
                    foreach ($this->categories as $key => $category) {
                        $selected = ($cat_id == $category->id) ? 'selected' : '';
                        echo '<option value="' . $category->id . '" ' . $selected . '>' . $category->category_name . '</option>';
                    }
                    ?>
                </select>


                <label for="date"> </label>
                <select name="date" id="date" class="postform">
                    <option value="0">All Date</option>
                    <?php
                    foreach ($expenses as $key => $expense) {
                        echo '<option value="' . $expense->expense_date . '">' . $expense->category_date . '</option>';
                    }
                    ?>
                </select>

                <?php
                submit_button('Filter', 'submit', 'expense_filter', false, null);
                ?>

            </form>
        </div>
    </div>


    <table class="wp-list-table widefat fixed striped">
        <tr>
            <th><strong>Amount</strong></th>
            <th><strong>Date</strong></th>
            <th><strong>Category</strong></th>
            <th><strong>Paid To</strong></th>
        </tr>

        <?php
        if (count($expenses) > 0) {

            foreach ($expenses as $expense) {
                $category_name = '';


                $category = get_data_by_id('expenses_category', $expense->expense_category);

                if (!empty($category)) {
                    foreach ($category as $key => $value) {
                        $category_name .= $value->category_name . ', ';
                    }

                    $category_name = rtrim($category_name, ', ');
                }

        ?>
                <tr>
                    <td>৳ <?php echo $expense->expense_amount; ?>
                        <div class="row-actions">
                            <span class="edit"><a href="admin.php?page=expenses&action=edit&id=<?php echo $expense->id; ?>">Edit</a> | </span>

                            <span class="delete"><a href="admin.php?page=expenses&action=delete&id=<?php echo $expense->id; ?>" onclick="return confirm('Are you sure you want to delete?');">Delete</a> </span>
                        </div>
                    </td>
                    <td><?php echo (!empty($expense->expense_date)) ? date_format(date_create($expense->expense_date), 'j M, Y - h:i A') : ''; ?>
                    </td>
                    <td><?php echo (!empty($expense->expense_category)) ? $category_name : ''; ?>
                    </td>
                    <td><?php echo (!empty($expense->expense_paid_to)) ? $expense->expense_paid_to : ''; ?>
                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td><?php _e('No Expenses Found.', WP_EM_TXT_DOMAIN); ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>