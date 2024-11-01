<?php 

/**
 * Creates the submenu item for the plugin.
 *
 * @package Custom_Admin_Settings
 */
 
/**
 * Creates the admin menus for the plugin.
 */
class WC_Innocard_Admin_Menus {
 
    /**
     * A reference the class responsible for rendering the submenu page.
     *
     * @access private
     */
 
    public function __construct() {
        $this->init();
    }

    public function init() {
        /**
         * Add main menu
         */
        add_action( 'admin_menu', array( $this, 'add_main_innocard_menu' ) );
    }
 
    /**
     * Creates the submenu item and calls on the Submenu Page object to render
     * the actual contents of the page.
     */
    public function add_main_innocard_menu() 
    {
        add_menu_page(
            __('Loyalty Settings', 'wc-innocard-integration'),
            __('Loyalty Settings', 'wc-innocard-integration'),
            'manage_options',
            'loyalty-settings',
            [ new WC_Innocard_Admin_Settings('loyalty-settings'), 'render' ],
            WC_INNOCARD_PLUGIN_DIR_URL . 'img/loyalty.svg',
            20
        );
    }
}