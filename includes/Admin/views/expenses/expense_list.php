<?php
$total_expense = 0;
$expenses = get_all($tablename);
if (count($expenses) > 0) {
    foreach ($expenses as $expense) {
        $total_expense += $expense->expense_amount;
    }
}
?>

<div class="wrap">
    <h2 class="wp-heading-inline"><?php _e('Expenses', WP_EM_TXT_DOMAIN); ?> <a href="admin.php?page=expenses&action=new" class="page-title-action">Add New Expense</a> </h2>

    <hr>

    <div class="total-expense">
        <h1>Total Expense: ৳<span><?php echo $total_expense; ?></span></h1>
    </div>
    <h3>Expense List</h3>
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
        ?>
                <tr>
                    <td>৳ <?php echo $expense->expense_amount; ?>
                        <div class="row-actions">
                            <span class="edit"><a href="admin.php?page=expenses&action=edit&id=<?php echo $expense->id; ?>">Edit</a> | </span>

                            <span class="delete"><a href="admin.php?page=expenses&action=delete&id=<?php echo $expense->id; ?>" onclick="return confirm('Are you sure you want to delete?');">Delete</a> </span>
                        </div>
                    </td>
                    <td><?php echo date_format(date_create($expense->expense_date), 'j M, Y - h:i A'); ?>
                    </td>
                    <td><?php echo $expense->expense_category; ?>
                    </td>
                    <td><?php echo $expense->expense_paid_to; ?>
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