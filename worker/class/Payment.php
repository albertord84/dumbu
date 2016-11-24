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
         * @param type $payment_data
         * @param type $recurrence Default to infinite (0)
         * @return string
         */
        public function create_recurrency_payment($payment_data, $recurrence = 0) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl(system_config::MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl(system_config::MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey(system_config::SYSTEM_MERCHANT_KEY);

                // Cria objeto requisição
                $createSaleRequest = new \Gateway\One\DataContract\Request\CreateSaleRequest();

                // Cria objeto do cartão de crédito
                $creditCard = \Gateway\One\Helper\CreditCardHelper::createCreditCard(
                                $payment_data['credit_card_number'], $payment_data['credit_card_name'], $payment_data['credit_card_exp_month'] . "/" . $payment_data['credit_card_exp_year'], $payment_data['credit_card_cvc']
                );

                // Dados da transação de cartão de crédito
                $creditCardTransaction = new \Gateway\One\DataContract\Request\CreateSaleRequestData\CreditCardTransaction();
                $creditCardTransaction
                        ->setAmountInCents($payment_data['amount_in_cents'])
                        ->setInstallmentCount(1)
                        ->setCreditCard($creditCard)
//                        ->setIsOneDollarAuthEnabled(true)
                ;

                // Dados da recorrência
                $creditCardTransaction->getRecurrency()
                        ->setDateToStartBilling(\DateTime::createFromFormat('U', $payment_data['pay_day']))
                        ->setFrequency(\Gateway\One\DataContract\Enum\FrequencyEnum::MONTHLY)
                        ->setInterval(1)
                        ->setRecurrences($recurrence);

                // Define dados da transação
                $createSaleRequest->addCreditCardTransaction($creditCardTransaction);

//                //Define dados do pedido
//                $createSaleRequest->getOrder()
//                        ->setOrderReference('NumeroDoPedido');
                // Cria um objeto ApiClient
                $apiClient = new \Gateway\ApiClient();

                // Faz a chamada para criação
                $response = $apiClient->createSale($createSaleRequest);

                // Mapeia resposta
                $httpStatusCode = $response->isSuccess() ? 201 : 401;
            } catch (\Gateway\One\DataContract\Report\CreditCardError $error) {
                $response = array("message" => $error->getMessage());
            } catch (\Gateway\One\DataContract\Report\ApiError $error) {
                $response = array("message" => $error->errorCollection->ErrorItemCollection[0]->Description);
            } catch (\Exception $ex) {
                $response = array("message" => "Ocorreu um erro inesperado.");
            } finally {
                return $response;
            }
        }

        // end of member function add_payment

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function delete_payment($order_key) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl(system_config::MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl(system_config::MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey(system_config::SYSTEM_MERCHANT_KEY);

                // Cria objeto requisição
                $request = new \Gateway\One\DataContract\Request\CancelRequest();

                // Define dados da requisição
//                $request->setOrderKey("5f4ef87d-cf0d-4da1-91f6-5a394924c308");
                $request->setOrderKey($order_key);

                //Cria um objeto ApiClient
                $client = new \Gateway\ApiClient();

                // Faz a chamada para criação
                $response = $client->cancel($request);

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
            if ($order_key) {
                $result = $this->queryOrder($order_key);
            }
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
                return $response;
//                $response = $client->searchSaleByOrderKey("e0c0954a-dbd5-4e79-b513-0769d89bb490");
// Imprime resposta
//                print "<pre>";
//                print json_encode(array('success' => $response->isSuccess(), 'data' => $response->getData()), JSON_PRETTY_PRINT);
//                print "</pre>";
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
