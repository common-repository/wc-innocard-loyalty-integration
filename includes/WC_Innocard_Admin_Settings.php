<?php
/**
 * Creates the submenu page for the plugin.
 *
 * @package Custom_Admin_Settings
 */
 
/**
 * Creates the submenu page for the plugin.
 *
 * Provides the functionality necessary for rendering the page corresponding
 * to the submenu with which this page is associated.
 *
 * @package Custom_Admin_Settings
 */
class WC_Innocard_Admin_Settings {
 
    public $tab = null;

    public $plugin_slug = null;
    
    private $error_message = null;

    private $success_message = null;

    /**
     * This function renders the contents of the page associated with the Submenu
     * that invokes the render method. In the context of this plugin, this is the
     * Submenu class.
     */

    /**
     * ALL OPTION FIELDS IN THIS FILE ARE SANITIZED BY WC_Innocard_Options::set();
     */

    public function __construct($plugin_slug)
    {
        $this->plugin_slug = $plugin_slug;
        $this->tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : null;
    }

    public function render() 
    {
        /**
         * handle requests
         */
        $this->handleRequests();

        require_once WC_INNOCARD_PLUGIN_DIR . 'templates/admin/admin.php';
    }

    public function handleRequests()
    {
        if ( !isset( $_POST['wc_innocard_integration_action'] ) || empty($_POST['wc_innocard_integration_action']) ) return;
        
        switch ( $_POST['wc_innocard_integration_action'] ) {
            case 'settings':
                $this->handleSettings();
                break;
            case 'account':
                $this->handleAccountSettings();
                break;
            case 'labels':
                $this->handleCheckoutLabels();
                break;
        }
    }

    public function handleSettings()
    {
        try {
            $innocard = get_innocard_api_instance([
                'api_username' => isset($_POST['innocard_username']) ? sanitize_text_field($_POST['innocard_username']) : $this->getOption('api_username'), // can't use sanitize_email because username could be not email
                'api_password' => empty($_POST['innocard_password']) ? $this->getOption('api_password') : sanitize_text_field($_POST['innocard_password']),
                'api_address' => defined('WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS') ? WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS : 
                    (isset($_POST['innocard_api_address']) ? sanitize_text_field($_POST['innocard_api_address'] ) : $this->getOption('api_address')),
            ]);
            $innocard->auth();
            
        } catch (Exception $e) {
            $this->error_message = __('We could not connect to the server using this settings. Verify params and try again.', 'wc-innocard-integration');

            return;
        }

        $this->setOption('api_username', $_POST['innocard_username'] );
        
        if ( !defined( 'WOOCOMMERCE_INNOCARD_INTEGRATION_API_ADDRESS' ) ) {
            $this->setOption('api_address', $_POST['innocard_api_address'] );
        }

        if ( $this->getOption('api_password') != $_POST['innocard_password'] && !empty($_POST['innocard_password']) ) {
            $this->setOption('api_password', $_POST['innocard_password']);
        }

        $this->success_message = __('Your settings have been saved', 'wc-innocard-integration');
    }

    public function handleAccountSettings()
    {
        $this->setOption('terminal', $_POST['innocard_terminal']);

        $this->success_message = __('Your settings have been saved', 'wc-innocard-integration');

    }

    public function handleCheckoutLabels()
    {
        $this->setOption('form_title', $_POST['innocard_form_title']);
        $this->setOption('checkbox_text', $_POST['innocard_checkbox_text']);
        $this->setOption('label_card_number', $_POST['innocard_label_card_number']);
        $this->setOption('label_pin', $_POST['innocard_label_pin']);
        $this->setOption('label_balance_to_be_used', $_POST['innocard_label_balance_to_be_used']);
        $this->setOption('label_balance_legend', $_POST['innocard_label_balance_legend']);
        $this->setOption('label_confirm_discount', $_POST['innocard_label_confirm_discount']);
        $this->setOption('label_remove_discount', $_POST['innocard_label_remove_discount']);
        $this->setOption('label_discount_title', $_POST['innocard_label_discount_title']);
        $this->setOption('label_discount_applied', $_POST['innocard_label_discount_applied']);
        $this->setOption('label_check_my_balance', $_POST['innocard_label_check_my_balance']);
        $this->setOption('show_loyalty_logo', isset($_POST['innocard_show_loyalty_logo']) ? '1' : '0'); 

        $this->success_message = __('Your settings have been saved', 'wc-innocard-integration');

    }

    private function getOption($option, $default = null)
    {
        return WC_Innocard_Options::get($option, $default);
    }

    private function setOption($option, $value)
    {
        return WC_Innocard_Options::set($option, esc_html( sanitize_text_field( $value ) ) );
    }
}