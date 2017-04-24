<?php

require_once '../class/Client.php';
require_once '../class/system_config.php';
require_once '../class/Payment.php';

echo "RETRY PAYMENT Inited...!<br>\n";
echo date("Y-m-d h:i:sa") . "<br>\n";

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();

$Client = new dumbu\cls\Client();


$DB = new \dumbu\cls\DB();
$clients_data = $DB->get_clients_by_status(2, 0);
//
//var_dump($clients_data->num_rows);
while ($client_data = $clients_data->fetch_object()) {
    var_dump($client_data->login);
    $initial_order_key = isset($client_data->initial_order_key) ? $client_data->initial_order_key : NULL;
    $Payment = new dumbu\cls\Payment();
    $order_key = isset($client_data->order_key) ? $client_data->order_key : NULL;
    // Check whether it have any payment done
    $OK_paied = $Payment->check_client_order_paied($order_key);
    //
    if ($order_key && $Payment->retry_payment($order_key)) {
        print "\nClient $client_data->login($client_data->user_id): NORMAL OrderKey CAPTURED!!!\n";
    } else if (!$OK_paied && $initial_order_key && retry_payment($initial_order_key)) {
        print "\nClient $client_data->login($client_data->user_id): INITIAL OrderKey CAPTURED!!!\n";
    }
}

function PaiedTrasRef($TransactionIdentifier, $SaleDataCollection) {
    foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
        if ($SaleData->TransactionIdentifier == $TransactionIdentifier && $SaleData->CapturedAmountInCents > 100) {
            return TRUE;
        }
    }
    return FALSE;
}

print '\n<br>JOB DONE!!!<br>\n';
echo date("Y-m-d h:i:sa");
