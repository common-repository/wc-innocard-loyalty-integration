<?php

/**
 * Add proof to thankyou page
 */
add_action( 'woocommerce_thankyou', function( $order_id ) {

	$order = wc_get_order($order_id);
	try {

		$transaction_id = $order->get_meta('wc_innocard_transaction_id'); // saved sanitized in session
		if (!$transaction_id) return;
		
		$receiptFile = $order->get_meta('wc_innocard_proof');
		if ( empty( $receiptFile )) {
			$innocard = get_innocard_api_instance();
			$innocard->auth();
			$proof = $innocard->proof( $transaction_id, $order->get_billing_email() );
			
			$receiptFile = '';
			if ( isset($proof->LOINTransactionReceipt, $proof->LOINTransactionReceipt->ReceiptFile)) {
				$receiptFile = $proof->LOINTransactionReceipt->ReceiptFile;
			}
			$order->update_meta_data('wc_innocard_proof', $receiptFile);
			$order->save();
		}
		
		wc_innocard_clear_session_data();

		echo '<iframe style="border: 0; width: 100%; height: auto; min-height: 650px; margin-bottom: 20px" 
			src="data:application/pdf;base64,', esc_attr($receiptFile), '"></iframe>'; 

	} catch (Exception|Error $e) {
		// silently ignore
		wc_innocard_debug_log($e->getMessage());
	}
});