<?PHP

require_once 'class/Worker.php';
require_once 'class/system_config.php';
require_once 'class/Gmail.php';
require_once 'class/Payment.php';
require_once 'class/Client.php';
require_once 'class/Reference_profile.php';

echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa");

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();

// Ref Prof

//$RP = new \dumbu\cls\Reference_profile();
//$RP->get_insta_ref_prof_data($ref_prof);
//$follows_count = \dumbu\cls\Reference_profile::static_get_follows(10);
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


//$Client = new dumbu\cls\Client();
//
//$Client->set_client_status(1, dumbu\cls\user_status::BLOCKED_BY_INSTA);


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
 
$Gmail = new dumbu\cls\Gmail();
//$result = $Gmail->send_client_contact_form("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg NEW2!", "DUMBU", "555-777-777");
//$Gmail->send_client_payment_error("albertord84@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_client_login_error("josergm86@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_new_client_payment_done("Test test", "test@email");
//var_dump($result);

$Robot = new dumbu\cls\Robot();
 	  		 	
 	 	 	
//$result = $Robot->bot_login("urpia", "romeus33");
//print_r(json_encode($result));
//$result = $Robot->bot_login("iclothesbsb", "brasilusa87");
//print_r(json_encode($result));
//$result = $Robot->bot_login("urpia", "romeus33");
//var_dump($result);
 	 	
//$Gmail->send_client_login_error("ronefilho@gmail.com", 'Rone', "ronefilho", "renivalfilho");
//$result = $Robot->bot_login("vaniapetti", "202020");
//var_dump($result);
//$result = $Robot->bot_login("dona_fina", "aquarell2016");
//var_dump($result);
//$result = $Robot->bot_login("noivaemforma", "noivaemforma2016");
//var_dump($result);
//$result = $Robot->bot_login("jeff_need", "24549088");
//var_dump($result);
//$result = $Robot->bot_login("baladauberlandia", "calypso");
//var_dump($result);
//$result = $Robot->bot_login("albertoreyesd1984", "albertord");
//var_dump($result);
//$result = $Robot->bot_login("josergm86", "josergm");
//var_dump($result);
//$result = $Robot->bot_login("smartsushidelivery", "838485");
//var_dump($result);
//$result = $Robot->bot_login("pedropetti", "Pp106020946");
//var_dump($result);

//----------------------------------------------------------------

// WORKER

//$Worker = new dumbu\cls\Worker();
//
////$Worker->check_daily_work();
//$Worker->truncate_daily_work();
//$Worker->prepare_daily_work();
//$Worker->do_work();

//----------------------------------------------------------------

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
