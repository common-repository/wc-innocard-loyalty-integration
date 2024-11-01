<?php 

function register_innocard_api_endpoints()
{
    register_rest_route('wc-innocard/v1', 'card/balance', [
        'methods'  => 'GET',
        'callback' => 'wc_innocard_api_get_card',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('wc-innocard/v1', 'cart/discount', [
        'methods'  => 'POST',
        'callback' => 'wc_innocard_api_set_discount',
        'permission_callback' => '__return_true',
        'args' => []
    ]);

    register_rest_route('wc-innocard/v1', 'card/discount/remove', [
        'methods'  => 'GET',
        'callback' => 'wc_innocard_api_remove_discount',
        'permission_callback' => '__return_true'
    ]);

}

add_action('rest_api_init', 'register_innocard_api_endpoints');

/**
 * Callbacks
 */
function wc_innocard_api_get_card($data)
{
    if ( ! isset( $data['card_number'], $data['pin']) || !$data['card_number'] || !$data['pin'] ) {
        return new WP_Error(
            'missing_parameters',
            __('The Card Number and PIN must be provided', 'wc-innocard-integration')
        );
    }

    $card = wc_innocard_get_card( sanitize_text_field( $data['card_number'] ), sanitize_text_field( $data['pin'] ) );

    if ( $card instanceof WP_Error ) return $card;

    return [
        'message' => sprintf( __('Your balance is %s', 'wc-innocard-integration'), sanitize_text_field( $card->Balance ) ),
        'balance' => sanitize_text_field( $card->Balance ),
        'customer' => sanitize_text_field( $card->CustomerID )
    ];
}

function wc_innocard_api_set_discount()
{
    $cart = wc_innocard_store_api_client('store/v1/cart');
    wc_innocard_rest_session_init();

    $data  = $_POST;
    $total = intval($cart->totals->total_items) + 
        intval($cart->totals->total_shipping) + 
        //intval($cart->totals->total_items_tax) + 
        intval($cart->totals->total_tax) +
        intval($cart->totals->total_discount);

    $total = substr($total, 0, -2) . '.' . substr($total, -2);
    // ( $cart->totals->total_price / 100 ); 

    if ( $total <= 0 ) {
        wc_innocard_clear_session_data();
        return new WP_Error( 'unexpected_error', __('Unexpected error with your session. Please reload page and try again.', 'wc-innocard-integration'), ['status' => 500 ]);
    }

    $card = wc_innocard_get_card( sanitize_text_field( $data['card_number'] ), sanitize_text_field( $data['pin'] ) );
    if ( $card instanceof WP_Error ) return $card;

    /**
     * 
     */
    if ( $card->Balance <= 0 ) {
        return new WP_Error( 'insufficient_balance', __('You card does not have sufficient balance', 'wc-innocard-integration'), [ 'status' => 422 ]);
    }

    if ( $data['balance'] > $total ) {
        return new WP_Error( 'balance_greater_than_total', sprintf( __('You can\'t set a discount greater than cart total (%s)', 'wc-innocard-integration'), esc_html( $total ) ), [ 'status' => 422 ]);
    }

    if ( $data['balance'] < 0.01 ) {
        return new WP_Error( 'balance_negative', sprintf( __('You can\'t set a discount less than 0.01 (-%s)', 'wc-innocard-integration'), esc_html( $total ) ), [ 'status' => 422 ]);
    }

    WC()->session->set('innocard_use', 1);
    WC()->session->set('innocard_card_number', sanitize_text_field( $data['card_number'] ) );
    WC()->session->set('innocard_pin', sanitize_text_field( $data['pin'] ) );
    WC()->session->set('innocard_balance', sanitize_text_field( $data['balance'] ) );

    $label = WC_Innocard_Options::get('label_discount_applied');
    return [
        'message' => $label ? $label :  __('Your discount has been applied', 'wc-innocard-integration')
    ];
}

function wc_innocard_api_remove_discount()
{
    wc_innocard_rest_session_init();
    wc_innocard_clear_session_data();
    
    return [
        'action' => true
    ];
}

// function custom_add_metadata_to_rest_order_response($response, $order, $request)
// {

//     if (!empty($custom_metadata)) {
//         $response->data['innocard_use'] = $order->get_meta('innocard_use');
//         $response->data['innocard_card_number'] = $order->get_meta('innocard_card_number');
//         $response->data['innocard_pin'] = $order->get_meta('innocard_pin');
//         $response->data['innocard_balance'] = $order->get_meta('innocard_balance');
//     }

//     return $response;
// }

// add_filter('woocommerce_rest_prepare_shop_order', 'custom_add_metadata_to_rest_order_response', 10, 3);