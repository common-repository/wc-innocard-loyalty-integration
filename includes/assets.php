<?php 

/**
 * Import css
 */
add_action('admin_init', 'wc_innocard_integration_admin_assets' );

function wc_innocard_integration_admin_assets()
{
    wp_enqueue_style('wc_innocard_integration_admin.css', WC_INNOCARD_PLUGIN_DIR_URL . 'css/admin.css', null, false );
}

add_action('wp_enqueue_scripts', 'wc_innocard_integration_checkout_assets');

function wc_innocard_integration_checkout_assets()
{
    wp_enqueue_script( 'wc_innocard_checkout_js', WC_INNOCARD_PLUGIN_DIR_URL . 'js/checkout.js');
    wp_enqueue_style('wc_innocard_checkout_css', WC_INNOCARD_PLUGIN_DIR_URL .'css/checkout.css');

    wp_localize_script('wc_innocard_checkout_js', 'acme_ajax_object', [
        'security'  => wp_create_nonce( 'acme-security-nonce' ),
    ]);
}