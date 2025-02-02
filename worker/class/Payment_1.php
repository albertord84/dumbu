<?php

require_once(dirname(__FILE__) . '/libraries/mundipagg/init.php');
require_once(dirname(__FILE__) . '/class/system_config.php');

function queryOrder($order_key) {
    try {
// Define a url utilizada
        \Gateway\ApiClient::setBaseUrl("https://sandbox.mundipaggone.com");
//    \Gateway\ApiClient::setBaseUrl("https://transactionv2.mundipaggone.com");
// Define a chave da loja
//    \Gateway\ApiClient::setMerchantKey("85328786-8BA6-420F-9948-5352F5A183EB");
        \Gateway\ApiClient::setMerchantKey(\dumbu\cls\system_config::SYSTEM_MERCHANT_KEY);

//Cria um objeto ApiClient
        $client = new Gateway\ApiClient();

// Faz a chamada para criação
        $response = $client->searchSaleByOrderKey("e0c0954a-dbd5-4e79-b513-0769d89bb490");
//    $response = $client->searchSaleByOrderReference("42e24322-a797-4a52-9c2c-7a8585f71a26");
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
