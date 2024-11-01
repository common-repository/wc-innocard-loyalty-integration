<?php

/**
 * Additional checkout fields
 */
add_filter('woocommerce_after_order_notes', 'custom_woocommerce_billing_fields');

function custom_woocommerce_billing_fields($fields)
{
    require WC_INNOCARD_PLUGIN_DIR . '/templates/form.php';
}

/**
 * Apply discount to cart after taxes and fees
 */
add_filter('woocommerce_calculated_total', 'wc_innocard_apply_discount_to_cart', 10, 2);
function wc_innocard_apply_discount_to_cart($total, $cart) {

    
    if (WC()->session->get('innocard_use') != 1) {
        return $total;
    }
    
    $fee_amount = floatval(WC()->session->get('innocard_balance'));
    if ( $fee_amount > $total) {
        // adjust transaction amount if cart was reduced
        // prevent innocard transaction value greater than cart total
        WC()->session->set('innocard_balance', number_format($total, 2, '.', ''));
        $fee_amount = $total;
    }

    return $total - $fee_amount;
}

/**
 * Add Innocard discount information to review
 */
add_action('woocommerce_review_order_before_order_total', 'add_custom_item_subtotal');
function add_custom_item_subtotal() {
    if (WC()->session->get('innocard_use') != 1)
        return;
    
    $fee_amount = floatval(WC()->session->get('innocard_balance'));
    $label = WC_Innocard_Options::get('label_discount_title', __('Innocard Loyalty Discount', 'wc-innocard-integration'));
    ?>
    <tr class="custom-subtotal">
        <th><?php echo esc_html($label); ?></th>
        <td data-title="<?php esc_attr_e($label); ?>"><?php echo wc_price(-$fee_amount); ?></td>
    </tr>
    <?php
}

/**
 * Hook after order created and default payment is started
 */
add_action(
    'woocommerce_after_checkout_validation',
    'wc_innocard_after_checkout_validation',
    10,
    2
);
function wc_innocard_after_checkout_validation($fields, $errors)
{
    if (WC()->session->get('innocard_use') != 1 || false == empty($errors->errors) ) {
        return;
    }


    if (WC()->session->get('wc_innocard_transaction_id')) {
        // Transaction already made for order
        return;
    }

    $innocard = get_innocard_api_instance();
    $innocard->auth();

    $card_number = WC()->session->get('innocard_card_number');
    $pin = WC()->session->get('innocard_pin');
    $card = wc_innocard_get_card($card_number, $pin);

    if ($card instanceof WP_Error) {
        return $errors->add('validation', esc_html($card->get_error_message()));
    }

    $generic_order_id = uniqid();

    $transaction = $innocard->transaction([
        'Amount' => floatval(WC()->session->get('innocard_balance')) * 100,
        // API receives value without floating point
        'TerminalID' => WC_Innocard_Options::get('terminal'),
        'OrderID' => $generic_order_id,
        'TransactionType' => WC_INNOCARD_DEFAULT_TRANSACTION_TYPE,
        'LanguageCode' => 'DE',
        'Timestamp' => date('YmdHis'),
        'Card-Id' => $card_number,
        'PIN' => $pin,
        // 'ReceiptTo' => $order->get_billing_email()
    ]);

    if (!isset($transaction->LOINTransaction)) {
        return $errors->add('validation', __('We are unable to connect to Innocard service. Try again later', 'wc-innocard-integration'));
    }

    if ($transaction->LOINTransaction->MessageCategory == 'E') {
        return $errors->add('validation', $transaction->LOINTransaction->UserMessage);
    }

    if ($transaction->LOINTransaction->StatusText == 'Error 123: No additional information') {
        return $errors->add('validation', __('Unable to complete payment with the provided card information. Verify card number and PIN and try again.', 'wc-innocard-integration'));
    }

    /**
     * Store metadata
     */
    WC()->session->set('innocard_generic_order_id', $generic_order_id);
    WC()->session->set('innocard_transaction_id', sanitize_text_field($transaction->LOINTransaction->TransactionID));
}

add_action('woocommerce_checkout_create_order', 'store_custom_order_metadata');
function store_custom_order_metadata($order) {
    // Get the Innocard use status from the session or any other source
    $innocard_use = WC()->session->get('innocard_use');
    
    // Store the custom metadata in the order
    if ($innocard_use) {

        $transaction_id = WC()->session->get('innocard_transaction_id'); // saved sanitized in session
		$generic_order_id = WC()->session->get('innocard_generic_order_id'); // saved sanitized in session
		$fee_amount = WC()->session->get('innocard_balance'); // saved sanitized in session

        $order->update_meta_data('wc_innocard_use', 1);
		$order->update_meta_data('wc_innocard_transaction_id', $transaction_id);
		$order->update_meta_data('wc_innocard_generic_order_id', $generic_order_id);
		$order->update_meta_data('wc_innocard_balance', $fee_amount);

        $order->save();

        wc_innocard_clear_session_data();
    }
}

add_filter('woocommerce_get_order_item_totals', 'add_innocard_discount_to_email', 10, 3);
function add_innocard_discount_to_email($rows, $order, $tax_display) {

    $innocard_use = $order->get_meta('wc_innocard_use');
    $innocard_balance = $order->get_meta('wc_innocard_balance');

    if ( empty ($innocard_use)) {
        $innocard_use = WC()->session->get('innocard_use');
        $innocard_balance = WC()->session->get('innocard_balance');
    }

    // Verify if discount was applied
    if ($innocard_use) {
        
        $item = [
            'label' => WC_Innocard_Options::get('label_discount_title', __('Innocard Loyalty Discount', 'wc-innocard-integration')),
            'value' => wc_price( - $innocard_balance )
        ];

        $output = [];
        foreach ( $rows as $key => $row ) 
        {
            // try to push before subtotal
            if ( $key == 'cart_subtotal' ) {
                $output['innocard_loyalty_discount'] = $item;
            }

            $output[ $key ] = $row;
        }

        // if someting wrong push to end of table
        if ( count( $rows ) == count( $output ) ) $output['innocard_loyalty_discount'] = $item;

        return $output;
    }

    return $rows;
}