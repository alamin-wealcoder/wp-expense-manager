<div class="wrap">

    <?php
    $name_err = (isset($this->messages['name_err'])) ? '<br><span style="color:#ca4a1f">' . $this->messages['name_err'] . '</span>' : '';
    ?>
    <h1 class="wp-heading-inline">
        <?php _e('Expense Categories', 'wp-expense-manager'); ?>
    </h1>
    <hr>

    <div id="col-container">
        <div id="col-left">
            <div class="col-wrap">
                <h3><?php _e('Add New Category', WP_EM_TXT_DOMAIN); ?></h3>
                <form action="" method="post">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th>
                                    <label for="name"><?php _e('Name', WP_EM_TXT_DOMAIN); ?></label> <br>
                                    <input type="text" name="name" id="name" class="regular-text" value="" required><?php echo $name_err; ?>
                                </th>
                            </tr>
                        </tbody>
                    </table>

                    <?php wp_nonce_field('new-expense-category'); ?>

                    <?php submit_button(__('Add Category', WP_EM_TXT_DOMAIN), 'primary', 'submit_expense_category'); ?>
                </form>
            </div>
        </div>

        <div id="col-right">
            <div class="col-wrap">
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <th><strong>Categories</strong></th>
                    </tr>

                    <?php
                    $categories = $this->get_categories();

                    if (count($categories) > 0) {
                        foreach ($categories as $expense_category) {
                    ?>
                            <tr>
                                <td><?php echo $expense_category->category_name; ?>

                                    <div class="row-actions">
                                        <span class="edit"><a href="">Edit</a> | </span>

                                        <span class="delete"><a href="" class="delete-tag">Delete</a> | </span>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td><?php _e('No expense categories found.', WP_EM_TXT_DOMAIN); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

</div>