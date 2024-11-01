<?php 

/**
 * Fetch an instance of Innocard API Class
 */
function get_innocard_api_instance($config = array())
{
    require WC_INNOCARD_PLUGIN_DIR . 'includes/Innocard/Innocard.php';

    global $wc_innocard_integration_api_settings;
    
    if ( empty($config) ) $config = $wc_innocard_integration_api_settings;

    $innocard = new WC_Innocard( $config );
    $innocard->locales->set(get_locale());
    
    return $innocard;
}

function wc_innocard_get_card($card_number, $pin)
{
    $innocard = get_innocard_api_instance();
    $innocard->auth();
    
    $cards = $innocard->card( $card_number, $pin );

    if ( !isset($cards->LOINCardSelect->MessageCategory ) ) {
        return new WP_Error(
            'internal_server_error', 
            __('Error communicating to Innocard Service. Try again later or contact support', 'wc-innocard-integration') 
        );
    }
    
    if ( strpos( $cards->LOINCardSelect->StatusText, 'Card Select: Error' ) !== false ) {
        return new WP_Error(
            'card_data_error', 
            __('The card is not valid. Verify informations and try again later.', 'wc-innocard-integration'),
            ['status' => 422],
        );
    }

    $card = $cards->LOINCardSelect->Cards[0]->Card;
    
    if ( floatval($card->Balance) == '0' ) {
        return new WP_Error(
            'card_without_balance',
            __('The card has no balance', 'wc-innocard-integration'),
            ['status' => 422],
        );
    }

    return $card;
}

function wc_innocard_rest_session_init()
{
    if ( defined( 'WC_ABSPATH' ) ) {
        include WC_ABSPATH . 'includes/wc-cart-functions.php';
        include WC_ABSPATH . 'includes/wc-notice-functions.php';
        include WC_ABSPATH . 'includes/wc-template-hooks.php';
    }
    
    
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
    
    WC()->cart = new WC_Cart();
    WC()->cart_session = new WC_Cart_Session(WC()->cart);
    WC()->cart_session->set_session();
    WC()->cart_session->maybe_set_cart_cookies();
}

function wc_innocard_clear_session_data()
{
    WC()->session->__unset('innocard_use', null);
    WC()->session->__unset('innocard_card_number', null);
    WC()->session->__unset('innocard_pin', null);
    WC()->session->__unset('innocard_customer', null);
    WC()->session->__unset('innocard_balance', null);
    WC()->session->__unset('innocard_generic_order_id', null);
    WC()->session->__unset('innocard_transaction_id', null);
}

function wc_innocard_store_api_client($path)
{

    $cookies = [];
    foreach ( $_COOKIE as $key => $val )
    {
        $cookies[] = new WP_Http_Cookie([
            'name' => $key,
            'value' => $val
        ]);
    }

    $response = json_decode(
        wp_remote_retrieve_body(
            wp_remote_get( site_url( 'wp-json/wc/' . $path ), [ 'cookies' => $cookies ] )
        )
    );

    return $response;
}
 
function wc_innocard_debug_log( $log ) {
    if ( WP_DEBUG_LOG ) {
        error_log( is_array( $log ) || is_object( $log ) ? print_r( $log, true ) : $log );
    }
}
