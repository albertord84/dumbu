<?PHP

require_once 'class/Worker.php';
require_once 'class/system_config.php';
require_once 'class/Gmail.php';
require_once 'class/Payment.php';

echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa");

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();




// MUNDIPAGG
//$Payment = new dumbu\cls\Payment();
//
//$payment_data['credit_card_number'] = '5133680051852331';
//$payment_data['credit_card_name'] = 'THIAGO A L BARBALHO';
//$payment_data['credit_card_exp_month'] = '09';
//$payment_data['credit_card_exp_year'] = '2021';
//$payment_data['credit_card_cvc'] = '062';
//$payment_data['amount_in_cents'] = 9990;
//$payment_data['pay_day'] = '1482693376';
//        
//$Payment->create_recurrency_payment($payment_data);

//----------------------------------------------------------------

// GMAIL
// 
//$Gmail = new dumbu\cls\Gmail();
//$result = $Gmail->send_client_contact_form("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg NEW2!", "DUMBU", "555-777-777");
//$Gmail->send_client_payment_error("albertord84@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_client_login_error("josergm86@gmail.com", "albertord", "alberto", "Alberto Reyes");
//var_dump($result);

//$Robot = new dumbu\cls\Robot();

//$Robot->bot_login("albertoreyesd1984", "alberto");

//----------------------------------------------------------------

// WORKER

$Worker = new dumbu\cls\Worker();
//
////$Worker->check_daily_work();
//$Worker->truncate_daily_work();
//$Worker->prepare_daily_work();
$Worker->do_work();

//----------------------------------------------------------------

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
