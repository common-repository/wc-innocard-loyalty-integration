<?php 

if ( !class_exists('WC_Innocard_Locales') ) {
    class WC_Innocard_Locales {

        /**
         * Convert common locales to API available locales
         */

        private $locales = [
            'EN'    => 'EN',
            'EN_US' => 'EN',
            'EN_GB' => 'EN',
            'EN_CA' => 'EN',
            'EN_AU' => 'EN',
            'FR'    => 'FR',
            'FR_BR' => 'FR',
            'FR_FR' => 'FR',
            'DE_DE' => 'DE',
            'DE_CH' => 'DE',
            'IT'    => 'IT',
            'IT_IT' => 'IT'
        ];

        private $locale = 'EN';

        public function get($locale = null)
        {
            if ( $locale )
                $locale = strtoupper($locale);
            else 
                $locale = $this->locale;

            if ( isset( $this->locales[ $locale ] ) ) return $this->locales[ $locale ];

            return $locale;
        }

        public function set($locale)
        {
            $this->locale = $this->get($locale);
        }
    }
}