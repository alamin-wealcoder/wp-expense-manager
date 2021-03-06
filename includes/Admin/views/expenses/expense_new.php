<div class="wrap">
    <h3><?php _e('Add New Expense', WP_EM_TXT_DOMAIN); ?></h3>
    <hr>

    <form action="" method="post" multiple="multiple">
        <table class="form-table">
            <tbody>
                <tr>
                    <td>
                        <label for="name"><?php _e('Date', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <input type="datetime-local" name="expense_date" id="date" class="regular-text" value="">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="amount"><?php _e('Amount', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <input type="number" name="amount" id="amount" class="regular-text" value="" min="0" required>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="purpose"><?php _e('Purpose', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <select name="purpose[]" id="purpose" multiple="multiple">
                            <?php
                            foreach ($this->categories as $key => $category) {
                                echo '<option value="' . $category->id . '">' . $category->category_name . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="paid_to"><?php _e('Paid To', WP_EM_TXT_DOMAIN); ?></label> <br>
                        <input type="text" name="paid_to" id="paid_to" class="regular-text" value="">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field('new-expense'); ?>

        <?php submit_button(__('Add Expense', WP_EM_TXT_DOMAIN), 'primary', 'submit_expense'); ?>
    </form>
</div>