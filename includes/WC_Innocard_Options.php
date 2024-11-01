<?php 

class WC_Innocard_Options {

    public static function get($key, $default = null)
    {
        $option = get_option(WC_INNOCARD_DB_OPTIONS_PREFIX . $key);

        if ( empty ($option)) return $default;

        return $option;
    }

    public static function set($key, $value)
    {
        return update_option(WC_INNOCARD_DB_OPTIONS_PREFIX . $key, strip_tags( sanitize_text_field( $value ) ) );
    }
}