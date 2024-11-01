<?php 

$use_card = WC()->session->get('innocard_use');

return [
    'wc_innocard_card_number' => [
        'label' =>  WC_Innocard_Options::get('label_card_number',__('Card Number', 'wc-innocard-integration')),
        'required' => false,
        'clear' => false,
        'type' => 'text',
        'custom_attributes' => ['disabled' => $use_card ],
        'class' => [ 'wc-innocard-field-card-number'],
        'value' => $use_card ? WC()->session->get('innocard_card_number') : null
    ],
    'wc_innocard_pin' => [
        'label' => WC_Innocard_Options::get('label_pin', __('PIN', 'wc-innocard-integration') ),
        'required' => false,
        'clear' => false,
        'type' => 'password',
        'custom_attributes' => ['disabled' => $use_card ],
        'class' => [ 'wc-innocard-field-pin' ],
        'value' => $use_card ? WC()->session->get('innocard_pin') : null
    ],
    'wc_innocard_balance' => [
        'label' => WC_Innocard_Options::get('label_balance_to_be_used', __('Balance to be used', 'wc-innocard-integration')),
        'required' => false,
        'clear' => true,
        'type' => 'number',
        'class' => ['wc-innocard-field-balance' . ( ( $use_card ) ? '' : ' hidden' )],
        'description' => WC_Innocard_Options::get('label_balance_legend', __('You can use the full balance or a portion of the card balance', 'wc-innocard-integration')),
        'value' => $use_card ? WC()->session->get('innocard_balance') : null
    ]
];