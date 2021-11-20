<?php
$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

$expense = get_data_by_id($tablename, $id);

$purpose = (!empty($expense->expense_category)) ? $expense->expense_category : '';

$multi_purpose = explode(', ', $purpose);

$paid_to = (!empty($expense->expense_paid_to)) ? $expense->expense_paid_to : '';

?>

<div class="wrap">
    <h3><?php _e('Update Expense', WP_EM_TXT_DOMAIN); ?></h3>
    <hr>

    <form action="" method="post" multiple="multiple">
        <table class="form-table">
            <tbody>
                <tr>
                    <td>
                        <label for="name"><?php _e('Date', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <input type="datetime-local" name="expense_date" id="date" class="regular-text" value="<?php echo $expense->expense_date; ?>">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="amount"><?php _e('Amount', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <input type="number" name="amount" id="amount" class="regular-text" value="<?php echo $expense->expense_amount; ?>" min="0" required>

                        <input type="hidden" name="expense_id" value="<?php echo $expense->id; ?>">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="purpose"><?php _e('Purpose', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <select name="purpose[]" id="purpose" multiple="multiple">
                            <?php
                            foreach ($this->categories as $key => $category) {
                                if ($purpose == $category->category_name || in_array($category->category_name, $multi_purpose)) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo '<option value="' . $category->category_name . '" ' . $selected . '>' . $category->category_name . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="paid_to"><?php _e('Paid To', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <input type="text" name="paid_to" id="paid_to" class="regular-text" value="<?php echo $paid_to; ?>">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field('new-expense'); ?>

        <?php submit_button(__('Update Expense', WP_EM_TXT_DOMAIN), 'primary', 'submit_expense'); ?>
    </form>
</div>