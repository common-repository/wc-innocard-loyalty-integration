<?php 

require_once WC_INNOCARD_PLUGIN_DIR . 'includes/WC_Innocard_Admin_Settings.php';

require_once WC_INNOCARD_PLUGIN_DIR . 'includes/WC_Innocard_Admin_Menus.php';

/** Admin settings */

add_action('plugins_loaded', 'wc_innocard_admin_settings_init' );
function wc_innocard_admin_settings_init()
{
    $plugin = new WC_Innocard_Admin_Menus();
    $plugin->init();
}

// Display field value on the admin order edit page
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'wc_innocard_admin_checkout_field_display_order_meta', 10, 1 );
function wc_innocard_admin_checkout_field_display_order_meta( $order ){
    
    $innocard_use = $order->get_meta('wc_innocard_use');

    if ( !$innocard_use ) return;
    
    $innocard_balance = $order->get_meta('wc_innocard_balance');
    $innocard_proof = $order->get_meta('wc_innocard_proof');
    $label = WC_Innocard_Options::get('label_discount_title', __('Innocard Loyalty Discount', 'wc-innocard-integration')); ?>

    <h3><?php echo esc_html($label) ?></h3>
    <p>
        <?php echo wc_price($innocard_balance) ?>

        <?php if ( $innocard_proof ) { 
            $receipt = "<html>
            <head>
                <title>" . esc_html($label) . "</title>
                <style> * { padding: 0; margin: 0;box-sizing: border-box; --webkit-box-sizing: border-box; --moz-box-sizing: border-box;} iframe { width: 100%; min-height: 100%; height: auto; border: 0} </style>
            <body>
                <iframe 
                    type=\\\"application/pdf\\\" 
                    title=\\\"" . esc_html($label) . "\\\" 
                    src=\\\"data:application/pdf;base64," . esc_attr($innocard_proof) . "\\\"
                    download=\\\"" .esc_html(strtolower(str_replace(' ', '-', $label))).".pdf\\\"></iframe>
            </body></html>";
            ?>
            <script>
            const receiptHtml = "<?php echo str_replace(["    ", "\n", "\r", "\t"], "", $receipt) ?>";
            function wc_innocard_open_proof() {
                const open = window.open('', '_blank');
                open.document.write(receiptHtml);
                return false;
            }
            </script>
            <a href="#" onclick="return wc_innocard_open_proof()">
                <?php echo __('Receipt', 'wc-innocard-integration') ?>
            </a>
        <?php } ?>
    </p>
<?php
}