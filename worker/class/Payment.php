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
         * @param type $$paymentMethodCode (20) | 5 Cielo -> 1.5 | 32 -> eRede | 20 -> Stone | 42 -> Cielo 3.0 | 0 -> Auto;
         * @return string
         */
        public function create_recurrency_payment($payment_data, $recurrence = 0, $paymentMethodCode = 20) {
            try {
                $card_bloqued = [
                    "5178057308185854",
                    "5178057258138580",
                    "4500040041538532",
                    "4984537159084527"
                ];
                $name_bloqued = [
                    "JUNIOR SUMA",
                    "JUNIOR LIMA",
                    "JUNIOR SANTOS",
                    "LUCAS BORSATTO22",
                    "LUCAS BORSATTO",
                    "GABRIEL CASTELLI",
                    "ANA SURIA",
                    "HENDRYO SOUZA",
                    "JOAO ANAKIM",
                    "JUNIOR FRANCO",
                    "FENANDO SOUZA",
                    "CARLOS SANTOS",
                    "DANIEL SOUZA",
                    "SKYLE JUNIOR",
                    "EDEDMUEDEDMUNDOEDEDMUEDEDMUNDO",
                    "EDEMUNDO LOPPES",
                    "JUNIOR KARLOS",
                    "ZULMIRA FERNANDES",
                    'JUNIOR FREITAS'
                ];
                if (in_array($payment_data['credit_card_number'], $card_bloqued) || in_array($payment_data['credit_card_name'], $name_bloqued)) {
                    throw new \Exception('Credit Card Number Blocked by Hacking! Sending profile and navigation data to police...');
                }

// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey($GLOBALS['sistem_config']->SYSTEM_MERCHANT_KEY);

                // Cria objeto requisição
                $createSaleRequest = new \Gateway\One\DataContract\Request\CreateSaleRequest();

                // Cria objeto do cartão de crédito
                $creditCard = \Gateway\One\Helper\CreditCardHelper::createCreditCard(
                                $payment_data['credit_card_number'], $payment_data['credit_card_name'], $payment_data['credit_card_exp_month'] . "/" . $payment_data['credit_card_exp_year'], $payment_data['credit_card_cvc']
                );

                // Dados da transação de cartão de crédito
                //$paymentMethodCode = 5; // 5 Cielo -> 1.5 | 32 -> eRede | 20 -> Stone | 42 -> Cielo 3.0 | 0 -> Auto;
                $creditCardTransaction = new \Gateway\One\DataContract\Request\CreateSaleRequestData\CreditCardTransaction();
                $creditCardTransaction
                        ->setPaymentMethodCode($paymentMethodCode)
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
                $response = array("message" => $ex->getMessage());
            } finally {
                return $response;
            }
        }

        /**
         * 
         * @param type $payment_data
         * @param type $recurrence
         * @return string
         */
        
        public function create_debit_payment($payment_data) {
            try {
                // Define a url utilizada
                \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);

// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey($GLOBALS['sistem_config']->SYSTEM_MERCHANT_KEY);

                // Cria objeto requisição
                $createSaleRequest = new \Gateway\One\DataContract\Request\CreateSaleRequest();

                // Define dados da transação
                $CreditCardBrand = Payment::detectCardType($payment_data['credit_card_number']);
//                print_r($CreditCardBrand);
                $createSaleRequest->addCreditCardTransaction()
                        ->setAmountInCents($payment_data['amount_in_cents'])
                        ->setPaymentMethodCode(\Gateway\One\DataContract\Enum\PaymentMethodEnum::AUTO)
                        ->setCreditCardOperation(\Gateway\One\DataContract\Enum\CreditCardOperationEnum::AUTH_AND_CAPTURE)
                        ->getCreditCard()
                        ->setCreditCardBrand($CreditCardBrand)
//                        ->setCreditCardBrand(\Gateway\One\DataContract\Enum\CreditCardBrandEnum::VISA)
                        ->setCreditCardNumber($payment_data['credit_card_number'])
                        ->setExpMonth($payment_data['credit_card_exp_month'])
                        ->setExpYear($payment_data['credit_card_exp_year'])
                        ->setHolderName($payment_data['credit_card_name'])
                        ->setSecurityCode($payment_data['credit_card_cvc']);

                //Define dados do pedido
                $createSaleRequest->getOrder()
                        ->setOrderReference($payment_data['pay_day']);

                // Cria um objeto ApiClient
                $apiClient = new \Gateway\ApiClient();

                // Faz a chamada para criação
                $response = $apiClient->createSale($createSaleRequest);

                // Mapeia resposta
                $httpStatusCode = $response->isSuccess() ? 201 : 401;
            } catch (\Gateway\One\DataContract\Report\CreditCardError $error) {
                $httpStatusCode = 400;
                $response = array("message" => $error->getMessage());
            } catch (\Gateway\One\DataContract\Report\ApiError $error) {
                $httpStatusCode = $error->errorCollection->ErrorItemCollection[0]->ErrorCode;
                $response = array("message" => $error->errorCollection->ErrorItemCollection[0]->Description);
            } catch (\Exception $ex) {
                $httpStatusCode = 500;
                $response = array("message" => $ex->getMessage());
            } finally {
                // Devolve resposta
//                http_response_code($httpStatusCode);
//                header('Content-Type: application/json');
                return $response;
            }
        }
        
        
        public function create_payment($payment_data) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey($GLOBALS['sistem_config']->SYSTEM_MERCHANT_KEY);

                // Cria objeto requisição
                $createSaleRequest = new \Gateway\One\DataContract\Request\CreateSaleRequest();

                // Define dados da transação
                $CreditCardBrand = Payment::detectCardType($payment_data['credit_card_number']);
//                print_r($CreditCardBrand);
                $createSaleRequest->addCreditCardTransaction()
                        ->setAmountInCents($payment_data['amount_in_cents'])
                        ->setPaymentMethodCode(\Gateway\One\DataContract\Enum\PaymentMethodEnum::AUTO)
                        ->setCreditCardOperation(\Gateway\One\DataContract\Enum\CreditCardOperationEnum::AUTH_AND_CAPTURE)
                        ->getCreditCard()
                        ->setCreditCardBrand($CreditCardBrand)
//                        ->setCreditCardBrand(\Gateway\One\DataContract\Enum\CreditCardBrandEnum::VISA)
                        ->setCreditCardNumber($payment_data['credit_card_number'])
                        ->setExpMonth($payment_data['credit_card_exp_month'])
                        ->setExpYear($payment_data['credit_card_exp_year'])
                        ->setHolderName($payment_data['credit_card_name'])
                        ->setSecurityCode($payment_data['credit_card_cvc']);

                //Define dados do pedido
                $createSaleRequest->getOrder()
                        ->setOrderReference($payment_data['pay_day']);

                // Cria um objeto ApiClient
                $apiClient = new \Gateway\ApiClient();

                // Faz a chamada para criação
                $response = $apiClient->createSale($createSaleRequest);

                // Mapeia resposta
                $httpStatusCode = $response->isSuccess() ? 201 : 401;
            } catch (\Gateway\One\DataContract\Report\CreditCardError $error) {
                $httpStatusCode = 400;
                $response = array("message" => $error->getMessage());
            } catch (\Gateway\One\DataContract\Report\ApiError $error) {
                $httpStatusCode = $error->errorCollection->ErrorItemCollection[0]->ErrorCode;
                $response = array("message" => $error->errorCollection->ErrorItemCollection[0]->Description);
            } catch (\Exception $ex) {
                $httpStatusCode = 500;
                $response = array("message" => $ex->getMessage());
            } finally {
                // Devolve resposta
//                http_response_code($httpStatusCode);
//                header('Content-Type: application/json');
                return $response;
            }
        }

        // end of member function add_payment

        public static function detectCardType($num) {
            $re = array(
                "visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
                "mastercard" => "/^5[1-5][0-9]{14}$/",
                "amex" => "/^3[47][0-9]{13}$/",
                "discover" => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
                "diners" => "/^3[068]\d{12}$/",
                "elo" => "/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/",
                "hipercard" => "/^(606282\d{10}(\d{3})?)|(3841\d{15})$/",
            );

            if (preg_match($re['visa'], $num)) {
                return 'Visa';
            } else if (preg_match($re['mastercard'], $num)) {
                return 'Mastercard';
            } else if (preg_match($re['amex'], $num)) {
                return 'Amex';
            } else if (preg_match($re['discover'], $num)) {
                return 'Discover';
            } else if (preg_match($re['diners'], $num)) {
                return 'Diners';
            } else if (preg_match($re['hipercard'], $num)) {
                return 'Hipercard';
            } else {
                return false;
            }
        }

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function delete_payment($order_key) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey($GLOBALS['sistem_config']->SYSTEM_MERCHANT_KEY);

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
                // print "<pre>";
                return json_encode(array('success' => $response->isSuccess(), 'data' => $response->getData()), JSON_PRETTY_PRINT);
                //print "</pre>";
            } catch (\Gateway\One\DataContract\Report\ApiError $error) {
                // Imprime json
                //print "<pre>";
                return json_encode($error, JSON_PRETTY_PRINT);
                //print "</pre>";
            } catch (Exception $ex) {
                // Imprime json
                //print "<pre>";
                return json_encode($ex, JSON_PRETTY_PRINT);
                //print "</pre>";
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
        
        public function get_paymment_data($order_key) {
            if ($order_key) {
                $result = $this->queryOrder($order_key);
                if (is_object($result) && $result->isSuccess())
                {
                     $data = $result->getData();
                    //var_dump($data);
                    $SaleDataCollection = $data->SaleDataCollection[0];
                    $LastSaledData = NULL;
                    // Get last client payment
                    $now = DateTime::createFromFormat('U', time());
                    foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                        return  new DateTime($SaleData->CreateDate);
                    }                    
                }
            }
            return null;
        }
        
        public function get_last_paymment_data($order_key) {
            if ($order_key) {
                $result = $this->queryOrder($order_key);
                if (is_object($result) && $result->isSuccess())
                {
                     $data = $result->getData();
                    //var_dump($data);
                    $SaleDataCollection = $data->SaleDataCollection[0];
                    $LastSaledData = NULL;
                    // Get last client payment
                    $now = DateTime::createFromFormat('U', time());
                    foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                        $SaleDataDate = new DateTime($SaleData->DueDate);
        //                $LastSaleDataDate = new DateTime($LastSaledData->DueDate);
                        //$last_payed_date = DateTime($LastSaledData->DueDate);
                        if ($SaleData->CapturedAmountInCents != NULL && ($LastSaledData == NULL || $SaleDataDate > new DateTime($LastSaledData->DueDate))) {
                            $LastSaledData = $SaleData;
                        }
                     }                    
                }
            }
            return null;
        }

        // end of member function update_payment

        function queryOrder($order_key) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey($GLOBALS['sistem_config']->SYSTEM_MERCHANT_KEY);

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
            return NULL;
        }

        function retry_payment($order_key, $request_key = NULL) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey($GLOBALS['sistem_config']->SYSTEM_MERCHANT_KEY);

                // Create request object
                $request = new \Gateway\One\DataContract\Request\RetryRequest();

                // Define all request data
                $request->setOrderKey($order_key);
                $request->setRequestKey($request_key);
//                var_dump($order_key);
                // Create new ApiClient object
                $client = new \Gateway\ApiClient();

                // Make the call
                $response = $client->Retry($request);
            } catch (\Gateway\One\DataContract\Report\ApiError $error) {
                $httpStatusCode = $error->errorCollection->ErrorItemCollection[0]->ErrorCode;
                $response = array("message" => $error->errorCollection->ErrorItemCollection[0]->Description);
            } catch (Exception $ex) {
                $httpStatusCode = 500;
                $response = array("message" => "Ocorreu um erro inesperado.");
            } finally {
                return $response;
            }
        }

        function retry_payment_recurrency($order_key, $transaction_key, $cvc = NULL) {
            try {
// Define a url utilizada
                \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
//    \Gateway\ApiClient::setBaseUrl($GLOBALS['sistem_config']->MUNDIPAGG_BASE_URL);
// Define a chave da loja
                \Gateway\ApiClient::setMerchantKey($GLOBALS['sistem_config']->SYSTEM_MERCHANT_KEY);

                // Create request object
                $request = new \Gateway\One\DataContract\Request\RetryRequest();

                // Create request object
                $request = new \Gateway\One\DataContract\Request\RetryRequest();

                // Define all request data
                $request->setOrderKey($order_key);
                $creditCardTransaction = new \Gateway\One\DataContract\Request\RetryRequestData\RetrySaleCreditCardTransaction();
                if ($cvc) {
                    $creditCardTransaction->setSecurityCode($cvc);
                }
                $creditCardTransaction->setTransactionKey($transaction_key);
                print_r($creditCardTransaction->getData());

                $request->addRetrySaleCreditCardTransactionCollection($creditCardTransaction);

                // Create new ApiClient object
                $client = new \Gateway\ApiClient();

                // Make the call
                $response = $client->Retry($request);
            } catch (\Gateway\One\DataContract\Report\ApiError $error) {
                var_dump($error);
                $httpStatusCode = $error->errorCollection->ErrorItemCollection[0]->ErrorCode;
                $response = array("message" => $error->errorCollection->ErrorItemCollection[0]->Description);
            } catch (Exception $ex) {
                $httpStatusCode = 500;
                $response = array("message" => "Ocorreu um erro inesperado.");
            } finally {
                return $response;
            }
        }

        /**
         * Check whether the $order_key have any payment done
         * @param type $order_key
         * @return boolean
         */
        public function check_client_order_paied($order_key) {
            $result = $this->check_payment($order_key);
            if (is_object($result) && $result->isSuccess()) {
                $data = $result->getData();
                //var_dump($data);
                $SaleDataCollection = $data->SaleDataCollection[0];
                foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                    // Get last client payment
                    //$SaleData = $SaleDataCollection->CreditCardTransactionDataCollection[0];
                    $SaleDataDate = new \DateTime($SaleData->DueDate);
                    if ($SaleData->CapturedAmountInCents != NULL) {
                        return TRUE;
                    }
                    //var_dump($SaleData);
                }
            }
            return FALSE;
        }

        // end of Payment
    }

}

?>
