<?php

namespace dumbu\cls {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/mundipagg/init.php';
    require_once 'system_config.php';
//    require_once('libraries/mundipagg/init.php');
//    require_once('class/system_config.php');


    /**
     * class Payment
     * 
     */
    class Payment {
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * 
         * @access public
         */
        public $id;

        /**
         * 
         * @access public
         */
        public $value;

        /**
         * 
         * @access public
         */
        public $date;

        /**
         * 
         *
         * @return Payment
         * @access public
         */
        public function add_payment() {
            
        }

// end of member function add_payment

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function delete_payment() {
            
        }

// end of member function delete_payment

        /**
         * 
         *
         * @return Payment
         * @access public
         */
        public function update_payment() {
            
        }

// end of member function update_payment

        /**
         * 
         *
         * @return Payment
         * @access public
         */
        public function check_payment($order_key) {
           $result = $this->queryOrder($order_key);
           return $result;
        }

// end of member function update_payment

        function queryOrder($order_key) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl(system_config::MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl(system_config::MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey(system_config::SYSTEM_MERCHANT_KEY);

//Cria um objeto ApiClient
                $client = new \Gateway\ApiClient();

// Faz a chamada para criação
                $response = $client->searchSaleByOrderKey($order_key);
//                $response = $client->searchSaleByOrderKey("e0c0954a-dbd5-4e79-b513-0769d89bb490");
// Imprime resposta
                print "<pre>";
                print json_encode(array('success' => $response->isSuccess(), 'data' => $response->getData()), JSON_PRETTY_PRINT);
                print "</pre>";
            } catch (\Gateway\One\DataContract\Report\ApiError $error) {
// Imprime json
                print "<pre>";
                print json_encode($error, JSON_PRETTY_PRINT);
                print "</pre>";
            } catch (Exception $ex) {
// Imprime json
                print "<pre>";
                print json_encode($ex, JSON_PRETTY_PRINT);
                print "</pre>";
            }
        }

    }
    // end of Payment
}

?>
