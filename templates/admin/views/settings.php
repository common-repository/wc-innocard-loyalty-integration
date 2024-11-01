<form action="?page=<?php echo $this->plugin_slug ?>" method="post">
    
    <input type="hidden" name="wc_innocard_integration_action" value="settings">

    <table class="form-table">
        <tr>
            <th>
                <label><?php echo __('Innocard Loyalty API Address', 'wc-innocard-integration') ?></label>
            </th>
            <td>
                <?php if ( defined('WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS') ): ?>
                    <input type="text" class="regular-text" disabled value="<?php echo WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS ?>">
                    <br><sub><?php echo __('Address defined on WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS constant config', 'wc-innocard-integration') ?></sub>
                <?php else: ?>
                    <input 
                        type="text" 
                        class="form-control regular-text" 
                        name="innocard_api_address" 
                        value="<?php echo isset($_POST['innocard_api_address']) ? esc_attr($_POST['innocard_api_address']) : esc_html($this->getOption('api_address', WC_INNOCARD_DEFAULT_PRODUCTION_API)) ?>">
                        <br>
                        <sub><?php echo __('Don\'t modify unless you know what you\'re doing', 'wc-innocard-integration') ?></sub>
                <?php endif ?>
            </div>
        </tr>
        <tr>
            <th>
                <label><?php echo __('Loyalty Username', 'wc-innocard-integration') ?></label>
            </th>
            <td>
                <input type="text" class="form-control regular-text" name="innocard_username" value="<?php echo isset($_POST['innocard_username']) ? esc_attr($_POST['innocard_username']) : esc_html($this->getOption('api_username')) ?>">
            </div>
        </tr>
        <tr>
            <th>
                <label><?php echo __('Loyalty Password', 'wc-innocard-integration') ?></label>
            </th>
            <td>
                <input type="password" class="form-control regular-text" name="innocard_password" value="<?php echo isset($_POST['innocard_password']) ? esc_attr($_POST['innocard_password']) : '' ?>">
                <?php if ($this->getOption('api_password')): ?>
                    <br>
                    <sub><?php echo __('Fill this input only if you need to modify current password') ?></sub>
                <?php endif ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                * <?php echo __('We need this information to be able to connect to Innocard Loyalty API service', 'wc-innocard-integration') ?>
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