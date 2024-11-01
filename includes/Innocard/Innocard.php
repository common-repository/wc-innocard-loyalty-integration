<?php 

require __DIR__ . '/Request.php';
require __DIR__ . '/Locales.php';

if (!class_exists('WC_Innocard')) {

    class WC_Innocard {

        /**
         * Initial config
         */
        private $config;

        /**
         * Request class
         */
        private $request;

        /**
         * Auth UserSessionID
         */
        private $UserSessionID = null;

        /**
         * Auth SessionIDHash
         */
        private $SessionIDHash = null;

        /**
         * Locales
         */
        public $locales;
        
        public function __construct($config = array())
        {
            $this->config = $config;

            if ( empty( $config['api_username']) || empty($config['api_password']) || empty($config['api_address']) ) {
                
                error_log('Innocard settings are incomplete. API username, password or address not provided in settings page');

                throw new Exception(__('An internal error was ocurred. Try again later', 'wc-innocard-integration'));
            }

            $this->request = new WC_Innocard_Request($this->config['api_address']);

            $this->locales = new WC_Innocard_Locales;
        }

        private function getDefaultArguments()
        {
            return [
                'UserSessionID' => $this->UserSessionID,
                'SessionIDHash' => $this->SessionIDHash,
                'LanguageCode' => $this->locales->get()
            ];
        }

        public function auth()
        {
            $auth = $this->request->get('LOIN/User/Signon', [
                'UserName' => $this->config['api_username'],
                'Password' => $this->config['api_password']
            ]);

            if ( isset( $auth->LOINUserSignin->MessageCategory ) && $auth->LOINUserSignin->MessageCategory == 'E' ) {
                error_log($auth->LOINUserSignin->StatusText );
                throw new Exception(__('An internal error was ocurred. Try again later', 'wc-innocard-integration'));
            }

            $this->UserSessionID = esc_html( $auth->LOINUserSignin->UserSessionID );
            $this->SessionIDHash = sha1($auth->LOINUserSignin->UserSessionID . $this->config['api_password']);

            return $this;
        }

        public function contracts()
        {
            return $this->request->get('LOIN/Contract', $this->getDefaultArguments() );
        }

        public function terminals()
        {
            return $this->request->get('LOIN/Terminal', $this->getDefaultArguments() );
        }

        public function transaction($params)
        {
            $params = array_merge( $this->getDefaultArguments(), $params);

            return $this->request->put('LOIN/Transaction', $params);
        }

        public function proof($transaction_id, $mail = null)
        {
            $params = array_merge( $this->getDefaultArguments(), [
                'TransactionID' => $transaction_id,
                'ReceiptTo' => $mail
            ]);

            return $this->request->put('LOIN/TransactionReceipt', $params);
        }

        public function card($card_number, $pin)
        {
            $params = array_merge( $this->getDefaultArguments(), [
                'Card-Id' => $card_number,
                'Pan' => $pin
            ]);

            return $this->request->get('LOIN/Card', $params);
        }
    }
}