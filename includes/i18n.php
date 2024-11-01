<?php 

/**
 * i18n
 */
add_action( 'plugins_loaded', 'woocommerce_innocard_integration_plugin_load_text_domain' );
function woocommerce_innocard_integration_plugin_load_text_domain() {
    load_plugin_textdomain( 'wc-innocard-integration', false, WC_INNOCARD_PLUGIN_DIR . '/languages/' );
}

