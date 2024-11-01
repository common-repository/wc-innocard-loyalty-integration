<?php
/*
Plugin Name: Innocard Loyalty Integration for WooCommerce
Description: Extends WooCommerce with an Innocard Loyalty gateway.
Text Domain: wc-innocard-integration
Domain Path: /languages
Version: 6.3
Author: Varlik
Tested up to: 6.1
Author URI: https://varlik.com.br/
	Copyright: © 2022-2023 Varlik
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Prevent direct file execution
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Plugin contants
 */

if ( !defined( 'WC_INNOCARD_PLUGIN_DIR' ) ) {
	define( 'WC_INNOCARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined('WC_INNOCARD_PLUGIN_DIR_URL') ) {
	define( 'WC_INNOCARD_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

if (!defined('WC_INNOCARD_DB_OPTIONS_PREFIX')) {
    define( 'WC_INNOCARD_DB_OPTIONS_PREFIX', 'wc_innocard_integration_' );
}

if (!defined('WC_INNOCARD_DEFAULT_PRODUCTION_API')) {
    define( 'WC_INNOCARD_DEFAULT_PRODUCTION_API', 'https://rest.innocardloyalty.ch' );
}

if (!defined('WC_INNOCARD_DEFAULT_TRANSACTION_TYPE')) {
    define( 'WC_INNOCARD_DEFAULT_TRANSACTION_TYPE', '3' ); // Gift card and prepaid card purchase
}

/**
 * Plugin settings
 */
require WC_INNOCARD_PLUGIN_DIR . 'includes/WC_Innocard_Options.php';

$wc_innocard_integration_api_settings = [
    'api_username' => WC_Innocard_Options::get( 'api_username' ),
    'api_password' => WC_Innocard_Options::get( 'api_password' ),
    'api_address' => defined( 'WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS' ) ? WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS : WC_Innocard_Options::get( 'api_address', WC_INNOCARD_DEFAULT_PRODUCTION_API )
];

/**
 * Include components
 */

require WC_INNOCARD_PLUGIN_DIR . 'includes/functions.php';

require WC_INNOCARD_PLUGIN_DIR . 'includes/i18n.php';

require WC_INNOCARD_PLUGIN_DIR . 'includes/admin.php';

require WC_INNOCARD_PLUGIN_DIR . 'includes/api.php';

require WC_INNOCARD_PLUGIN_DIR . 'includes/assets.php';

require WC_INNOCARD_PLUGIN_DIR . 'includes/checkout.php';

require WC_INNOCARD_PLUGIN_DIR . 'includes/thankyou.php';
