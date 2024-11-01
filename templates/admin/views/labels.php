
<form action="?page=<?php echo $this->plugin_slug ?>&tab=labels" method="post">
    
    <input type="hidden" name="wc_innocard_integration_action" value="labels">

    <table class="form-table">
        
        <tr>
            <th>
                <label><?php echo __('Form Title', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_form_title"
                value="<?php echo isset($_POST['innocard_form_title']) 
                    ? esc_attr($_POST['innocard_form_title']) :
                    $this->getOption('form_title', __('Use your Innocard Loyalty', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Checkbox Text', 'wc-innocard-integration') ?></label>
            </th>

            <td>
            <input 
                class="form-control" 
                name="innocard_checkbox_text" 
                value="<?php echo isset($_POST['innocard_checkbox_text']) ? 
                esc_attr($_POST['innocard_checkbox_text']) : 
                esc_html($this->getOption(
                    'checkbox_text', 
                    __('I have an Innocard Loyalty card and I want to use it to pay for my purchase', 'wc-innocard-integration')
                )) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label for="show_loyalty_logo"><?php echo __('Show Loyalty icon', 'wc-innocard-integration') ?></label>
                <img src="<?php echo esc_attr(WC_INNOCARD_PLUGIN_DIR_URL) ?>/img/loyalty.svg" style="width: 20px; height: auto" alt="Innocard">

            </th>

            <td>
                <input 
                type="checkbox" 
                id="show_loyalty_logo"
                name="innocard_show_loyalty_logo"
                value="1" 
                <?php echo isset($_POST['innocard_show_loyalty_logo']) ? 'checked' : ($this->getOption('show_loyalty_logo') == 1 ? 'checked' : '') ?>>

            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Card Number', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_card_number"
                value="<?php echo isset($_POST['innocard_label_card_number']) 
                    ? esc_attr($_POST['innocard_label_card_number']) :
                    $this->getOption('label_card_number', __('Card Number', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Card Number', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_pin"
                value="<?php echo isset($_POST['innocard_label_pin']) 
                    ? esc_attr($_POST['innocard_label_pin']) :
                    $this->getOption('label_pin', __('PIN', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Balance to be used', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_balance_to_be_used"
                value="<?php echo isset($_POST['innocard_label_balance_to_be_used']) 
                    ? esc_attr($_POST['innocard_label_balance_to_be_used']) :
                    $this->getOption('label_balance_to_be_used', __('Balance to be used', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Balance legend', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_balance_legend"
                value="<?php echo isset($_POST['innocard_label_balance_legend']) 
                    ? esc_attr($_POST['innocard_label_balance_legend']) :
                    $this->getOption('label_balance_legend', __('You can use the full balance or a portion of the card balance', 'wc-innocard-integration'), 'wc-innocard-integration') ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Confirm Discount', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_confirm_discount"
                value="<?php echo isset($_POST['innocard_label_confirm_discount']) 
                    ? esc_attr($_POST['innocard_label_confirm_discount']) :
                    $this->getOption('label_confirm_discount', __('Confirm Discount', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Remove Discount', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_remove_discount"
                value="<?php echo isset($_POST['innocard_label_remove_discount']) 
                    ? esc_attr($_POST['innocard_label_remove_discount']) :
                    $this->getOption('label_remove_discount', __('Remove Discount', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Discount Title', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_discount_title"
                value="<?php echo isset($_POST['innocard_label_discount_title']) 
                    ? esc_attr($_POST['innocard_label_discount_title']) :
                    $this->getOption('label_discount_title', __('Innocard Loyalty Discount', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Check my Balance', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_check_my_balance"
                value="<?php echo isset($_POST['innocard_label_check_my_balance']) 
                    ? esc_attr($_POST['innocard_label_check_my_balance']) :
                    $this->getOption('label_check_my_balance', __('Check My Balance', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <label><?php echo __('Discount Applied', 'wc-innocard-integration') ?></label>
            </th>

            <td>
                <input 
                type="text" 
                class="form-control" 
                name="innocard_label_discount_applied"
                value="<?php echo isset($_POST['innocard_label_discount_applied']) 
                    ? esc_attr($_POST['innocard_label_discount_applied']) :
                    $this->getOption('label_discount_applied', __('Your discount has been applied', 'wc-innocard-integration') ) ?>">
            </td>
        </tr>

        <tr>
            <th>
                <button class="button-primary">
                    <?php echo __('Save Changes', 'wc-innocard-integration') ?>
                </button>
            </th>
        </tr>

    </table>
</form>