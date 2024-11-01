<?php 

if ( !class_exists('WC_Innocard_Request')) {

    class WC_Innocard_Request {

        private $base_url;

        public function __construct(
            $base_url = null
        ) {
            $this->setBaseUrl( $base_url );    
        }

        public function setBaseUrl($url)
        {
            if (substr($url, -1) != '/' && $url ) {
                $url .= '/';
            }

            $this->base_url = $url;
            return $this;
        }

        public function getURL( $path, $params = [] )
        {
            if ( is_array($path) ) $path = implode('/', $path);
            
            if ( !empty( $params ) ) {
                $path .= '?' . http_build_query($params);
            }
            
            $url =  $this->base_url  . $path;
            if (preg_match('/https?:\/\//', $path)) {
                $url = $path;
            }

            return $url;
        }

        public function get($path, $params = [])
        {
            //return $this->request( 'GET', $this->getURL( $path, $params )); 
            $response = wp_remote_get( 
                esc_url_raw( $this->getURL( $path, $params ) ), 
                [
                    'headers' => [
                        'Content-Type:application/x-www-form-urlencoded'
                    ],
                ]
            );
            
            return json_decode( wp_remote_retrieve_body($response) );
        }

        public function put( $path, $params )
        {
            //return $this->request( 'PUT', $this->getURL( $path, $params ) );
            $response = wp_remote_request(
                esc_url_raw( $this->getURL( $path, $params ) ),
                [
                    'method' => 'PUT',
                    'headers' => [
                        'Content-Type:application/x-www-form-urlencoded'
                    ],
                ]
            );

            return json_decode( wp_remote_retrieve_body( $response ) );
        }
    }
}