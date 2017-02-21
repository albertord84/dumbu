<?PHP

require_once '../class/Worker.php';
require_once '../class/system_config.php';
require_once '../class/Gmail.php';
require_once '../class/Payment.php';
require_once '../class/Client.php';
require_once '../class/Reference_profile.php';

//echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa") . "<br>\n";

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();
//print $GLOBALS['sistem_config']->SYSTEM_EMAIL . "<br>";
//print $GLOBALS['sistem_config']->SYSTEM_USER_LOGIN . "<br>";
//print $GLOBALS['sistem_config']->SYSTEM_USER_PASS . "<br>";
//dumbu\cls\system_config():: 
// Ref Prof
//$RP = new \dumbu\cls\Reference_profile();
//$ref_prof = "santatemakeria";
//$response = $RP->get_insta_ref_prof_data($ref_prof);
//var_dump($response);
//$follows_count = \dumbu\cls\Reference_profile::static_get_follows(2);
//var_dump($follows_count);
//$follows_count = \dumbu\cls\Reference_profile::static_get_follows(20);
//var_dump($follows_count);
//$Worker = new dumbu\cls\Worker();
//$Robot = new dumbu\cls\Robot();
//$DB = new \dumbu\cls\DB();
//$DB->delete_daily_work_client(13);
//$daily_work = $DB->get_follow_work();
//$daily_work->login_data = json_decode($daily_work->cookies);
//var_dump($daily_work);
////$Worker->do_follow_unfollow_work($daily_work);
//$Ref_profile_follows = $Robot->do_follow_unfollow_work(NULL, $daily_work);
//var_dump($Ref_profile_follows);

$Client = new dumbu\cls\Client();

//$result = $Client->insert_clients_daily_report();

//$profile_data = (new dumbu\cls\Reference_profile())->get_insta_ref_prof_data('ftthiagomonteiro');
//$DB = new \dumbu\cls\DB();
//$result = $DB->insert_client_daily_report(1624, $profile_data);

//$Client->create_daily_work(234);
//var_dump(date('d-m-Y',time()));
//$a=strtotime("+" .'7'. " days", time());
//var_dump($a);
//var_dump(date('d-m-Y',1483449391));
//var_dump(date('d-m-Y',1486247776));
//$Client->set_client_status(1, dumbu\cls\user_status::BLOCKED_BY_INSTA);
//var_dump(date('d-m-Y',1482951226));
// MUNDIPAGG
//$Payment = new dumbu\cls\Payment();
//$response=$Payment->delete_payment('0b0759c7-2c28-4c3c-aee9-07d1aae581a9');
//$a=json_decode($response);
//var_dump($a->success);
//
//
//
//
// MUNDIPAGG
$Payment = new dumbu\cls\Payment();
//
//$order_key = "f853c228-aa35-4bb0-9ef6-18da7dd33d70";
//$result = $Payment->check_payment($order_key);
//$now = DateTime::createFromFormat('U', time());
//if (is_object($result) && $result->isSuccess()) {
//    $data = $result->getData();
//    //var_dump($data);
//    $SaleDataCollection = $data->SaleDataCollection[0];
//    $SaledData = NULL;
//    // Get last client payment
//    foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
//        $SaleDataDate = new DateTime($SaleData->DueDate);
//        if ($SaleData->CapturedAmountInCents == NULL && $SaleDataDate < $now) {
//            break;
//        }
//    }
//}
//
//if ($SaleData) {
//    var_dump($SaleData->TransactionKey);
//    $result = $Payment->retry_payment_recurrency($order_key, $SaleData->TransactionKey);
//    $result = $result->getData();
//    print "<pre>";
//    print json_encode($result, JSON_PRETTY_PRINT);
//    print "</pre>";
//} else {
//    print 'NOT SALE DATA CAPTURED!!!';
//}
//$pd = strtotime('02/22/2017 04:33:32');
//var_dump($pd);
//var_dump(date("d-m-Y", $pd));
//
//$pd = strtotime("-3 days", 1487487807);
//$pd = strtotime("-1 month", $pd);
//var_dump($pd);
//var_dump(date("d-m-Y", $pd));
//
//
//$pay_day = strtotime('09/02/2017 14:04:49');
//var_dump($pay_day);
//
//
//$pay_day = strtotime('11/03/2017 14:04:49');
//var_dump($pay_day);
//
//$d_today = date("j", $now);
//$m_today = date("n", $now);
//$y_today = date("Y", $now);
//$d_pay_day = date("j", $pay_day);
//$m_pay_day = date("n", $pay_day);
//$y_pay_day = date("Y", $pay_day);
//
//$data = strtotime("+5 days", time());
//var_dump($data);
//var_dump(date('d-m-Y', $data));
//$pay_day = strtotime('02/22/2017 04:33:32');
//$pay_day = strtotime("+30 days", $pay_day);
//$pay_day = time();
//$strdate = date("d-m-Y", $pay_day);
//
//////
//$payment_data['credit_card_number'] = '5399832641710235';
//$payment_data['credit_card_name'] = 'DANIEL ONOJA';
//$payment_data['credit_card_exp_month'] = '02';
//$payment_data['credit_card_exp_year'] = '2018';
//$payment_data['credit_card_cvc'] = '657';
//$payment_data['amount_in_cents'] = 2990;
//$payment_data['pay_day'] = $pay_day;
//$resul = $Payment->create_payment($payment_data);
//var_dump($resul);
//$resul = $Payment->create_recurrency_payment($payment_data, 0);
//var_dump($resul);
////----------------------------------------------------------------
//$result = $Payment->check_payment(NULL);
//$result = $Payment->delete_payment(NULL);
//header('Content-Type: application/json');
//print_r($resul);
//$order_key = "4942e0ac-fb5b-41fa-87a8-cb1f80d81d32";
//$transaction_key = "79c28bd0-d0c8-47aa-be07-67d81202ed6dd";
//$result = $Payment->retry_payment_recurrency($order_key, $transaction_key);
//var_dump($result);
//header('Content-Type: application/json');
////print_r($result->getData());
//print_r(json_encode($result->getData(), JSON_PRETTY_PRINT));
//var_dump($result->isSuccess());
//$result = $Payment->check_payment("3d66ccd9-9e66-44ed-bd2a-13e4d7a388e1");
//print_r(json_encode($result->getData(), JSON_PRETTY_PRINT));
// GMAIL
$Gmail = new dumbu\cls\Gmail();
//$useremail, $username, $instaname, $instapass
//$result = $Gmail->send_client_payment_error("marinsmarcelo@gmail.comm", "marcelomarins.art", "marcelomarins.art", "");
//var_dump($result);
//$result = $Gmail->send_client_payment_success("fashionactone@gmail.com", "fashionact", "fashionactcc", "fash1234");
//var_dump($result);
//$Gmail->send_client_payment_error("albertord84@gmail.com", "Alberto R", "albertord84", "albertord");
//var_dump($result)
//$result = $Gmail->send_client_not_rps("albertord84@gmail.com", "Alberto R", Raphael PH & Pedrinho Lima"albertord84", "albertord");
//print_r($result);
//        ("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg NEW2!", "DUMBU", "555-777-777");
//$Gmail = new dumbu\cls\Gmail();
//$result = $Gmail->send_client_contact_form("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg NEW2!", "DUMBU", "555-777-777");
//$result = $Gmail->send_client_login_error("albertord85@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_new_client_payment_done("Alberto Reyes", "albertord84@gmail.com", 4);
//var_dump($result);

$Robot = new dumbu\cls\Robot();

//$client = $Client->get_client(1);
//$result = $Robot->get_insta_chaining(json_decode($client->cookies), 1420916955, 10);
//print_r($result);
//
//print_r($result->media->nodes[0]->id);
//$result = $Robot->make_insta_friendships_command(json_decode($client->cookies), $result->media->nodes[0]->id, 'unlike', 'web/likes');
//print_r($result);
//$result = $Robot->like_fist_post(json_decode($client->cookies), $client->insta_id);
//$result = $Robot->bot_login("iclothesbsb", "brasilusa87");
//print_r(json_encode($result));
//$result = $Robot->bot_login('abrfuncional','treinoabr');  //'julianabaraldi83','tininha1712'   'guilfontes','persian'
//print_r(json_encode($result));
//$result = $Robot->bot_login("urpia", "romeus33");
//var_dump($result);
//$Gmail->send_client_login_error("ronefilho@gmail.com", 'Rone', "ronefilho", "renivalfilho");
//$result = $Robot->bot_login("vaniapetti", "202020");
//var_dump($result);
//$result = $Robot->bot_login("dona_fina", "aquarell2016");
//var_dump($result);
//$result = $Robot->bot_login("abrfuncional", "treinoabr");
//var_dump($result);
//$result = $Robot->bot_login("jeff_need", "24549088");
//var_dump($result);
//$result = $Robot->bot_login("baladauberlandia", "calypso");
//var_dump($result);
//$result = $Robot->bot_login("alberto_dreyes", "albertord4");
//var_dump($result);
//$result = $Robot->bot_login("josergm86", "josergm1");
//var_dump($result);
//$result = $Robot->bot_login("smartsushidelivery", "838485");
//var_dump($result);
//$result = $Robot->bot_login("pedropetti", "Pp106020946");
//var_dump($result);
//----------------------------------------------------------------
//
// WORKER
$Worker = new dumbu\cls\Worker();
//
////$Worker->check_daily_work();
//$Worker->truncate_daily_work();
//$Worker->prepare_daily_work();
//$Worker->do_work();
//----------------------------------------------------------------

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
