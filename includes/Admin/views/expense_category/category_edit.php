<div class="wrap">
    <?php
    $name_err = (isset($this->messages['name_err'])) ? '<br><span style="color:#ca4a1f">' . $this->messages['name_err'] . '</span>' : '';

    $id = isset($_GET['id']) ? $_GET['id'] : '';

    $category = ('' == $id) ? '' : $this->get_category($id)->category_name;

    ?>
    <div class="col-container">
        <div id="col-left">
            <div class="col-wrap">
                <h3><?php _e('Update Category', WP_EM_TXT_DOMAIN); ?></h3>
                <form action="" method="post">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <td>
                                    <label for="name"><?php _e('Name', WP_EM_TXT_DOMAIN); ?></label> <br>
                                    <input type="text" name="name" id="name" class="regular-text" value="<?php echo $category; ?>" required><?php echo $name_err; ?>

                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?php
                                    wp_nonce_field('new-expense-category');
                                    submit_button(__('Update Category', WP_EM_TXT_DOMAIN), 'primary', 'submit_expense_category');
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>