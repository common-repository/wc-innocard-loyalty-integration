<?php 
$fields = require WC_INNOCARD_PLUGIN_DIR . '/includes/checkout_form_fields.php';
$use_card = WC()->session->get('innocard_use');
?>
<div class="woocommerce-innocard-checkout-form">

    <h3>
        <?php if ( WC_Innocard_Options::get('show_loyalty_logo', 0 ) ): ?>
            <div class="wc-innocard-logo">
                <img src="<?php echo WC_INNOCARD_PLUGIN_DIR_URL ?>/img/loyalty.svg" alt="Innocard">
            </div>
        <?php endif ?>

        <?php echo esc_html( WC_Innocard_Options::get('form_title', __('Use your Innocard Loyalty', 'wc-innocard-integration'))) ?></h3>
    
    <div class="woocommerce-error error hidden"></div>
    <div class="woocommerce-message success hidden"></div>
    
    <p>
        <label>
            <input type="checkbox" name="wc-innocard-checkout-enable" value="1" class="wc-innocard-checkbox-use-card" <?php echo $use_card == 1 ? 'checked' : '' ?>>
            <?php echo esc_html(WC_Innocard_Options::get('checkbox_text', __('I have an Innocard Loyalty card and I want to use it to pay for my purchase', 'wc-innocard-integration'))) ?>
        </label>
    </p>

    <div class="wc-innocard-payment-fields <?php echo $use_card ? '' : 'hidden' ?>">
        <?php 
        
        woocommerce_form_field('wc_innocard_card_number', $fields['wc_innocard_card_number'], $fields['wc_innocard_card_number']['value'] );
        woocommerce_form_field('wc_innocard_pin', $fields['wc_innocard_pin'],  $fields['wc_innocard_pin']['value'] ); ?>
        
        <button type="button" data-action="<?php echo site_url('wp-json/wc-innocard/v1/card/balance') ?>" class="wc-innocard-button-check-balance <?php echo $use_card ? 'hidden' : '' ?>"><?php echo WC_Innocard_Options::get('label_check_my_balance', __('Check my balance', 'wc-innocard-integration')) ?></button>
        
        <?php woocommerce_form_field('wc_innocard_balance', $fields['wc_innocard_balance'], $fields['wc_innocard_balance']['value'] ); ?>

        <p>
            <button type="button" data-action="<?php echo site_url('wp-json/wc-innocard/v1/cart/discount') ?>" class="confirm-discount <?php echo $use_card ? '' : 'hidden' ?>">
                <?php echo  WC_Innocard_Options::get('label_confirm_discount', __('Confirm discount', 'wc-innocard-integration')) ?>
            </button>
            <br>
            <a href="<?php echo site_url('wp-json/wc-innocard/v1/card/discount/remove') ?>" class="remove-discount <?php echo $use_card ? '' : 'hidden' ?>"><?php echo  WC_Innocard_Options::get('label_remove_discount', __('Remove discount', 'wc-innocard-integration')) ?></a>
        </p>
    </div>

    <input type="hidden" id="wc-innocard-nonce" value="<?php echo wp_create_nonce( 'wp_rest' ) ?>">
    <input type="hidden" id="wc-innocard-wp-session" value="<?php echo wp_get_session_token() ?>">
    <input type="hidden" id="wc-innocard-wc-session" value="<?php echo (isset(WC()->session->get_session_cookie() [3]) ? WC()->session->get_session_cookie() [3] : '') ?>">
    <input type="hidden" id="wc-wordpress-api-endpoint" value="<?php echo site_url() . '/wp-json/' ?>">

</div>

<style>
input[disabled] {
    background-color: #f0f0f0;
}
</style>