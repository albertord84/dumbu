<?PHP
require_once '../class/Worker.php';
require_once '../class/system_config.php';
require_once '../class/Gmail.php';
require_once '../class/Payment.php';
require_once '../class/Client.php';
require_once '../class/Reference_profile.php';
require_once '../class/PaymentCielo3.0.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/utils.php';

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
 
//$Gmail = new dumbu\cls\Gmail();
//$result = $Gmail->send_client_contact_form("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg NEW2!", "DUMBU", "555-777-777");
//$Gmail->send_client_payment_error("albertord84@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_client_login_error("josergm86@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_new_client_payment_done("Test test", "test@email");
//var_dump($result);
//$Robot = new dumbu\cls\Robot();
 	  		 	
 	 	
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
//testing binary_search
/*$array = array("011234","1020390","1123980","345532","354676","5646754","5765456","6565453");
if(str_binary_search("1123980",$array))
    { echo "\n<br>  true </br>" ;}
else{ echo "\n<br> false </br>"; }
if(str_binary_search("1123935",$array))
    {  echo "\n<br> true </br>"; }
else{ echo "\n<br> false </br>"; }
if(str_binary_search("5765456",$array))
    { echo "\n<br> true </br>"; }
else{ echo "\n<br> false </br>" ;
}
*/
//Testing search om black list
/*$DB = new \dumbu\cls\DB();
$array2 = $DB->get_white_list('20481');
if(str_binary_search("204205888",$array2))
  { echo "\n<br>  DB search true </br>"; }
else{ echo "\n<br> DB search  false </br>" ;
}
if(str_binary_search("410556064",$array2))
  { echo "\n<br> DB search true </br>"; }
else{ echo "\n<br> DB search false </br>" ;
}*/
$DB = new \dumbu\cls\DB();
$result = $DB->InsertEventToWashdog(19356,'Error yo testando',1);
var_dump($result);


/*
$white_list = $DB->get_white_list('45769');

$Profiles[0] = 47711036;
$Profiles[1] = 1342090624;
$Profiles[2] = 27708066;
foreach ($Profiles as $id_insta) {
    // If profile is not in white list then do unfollow request
    if(!(isset($white_list) && str_binary_search($id_insta, $white_list)))
    {   
        print '<br> sucess"' . $id_insta .'</br>';
    }
}*/
    // Wait 20 minutes

// AFTER
print 'AFTER:<br>\n';
//print_r($clients_data);

//Testing reports
//$Client = new dumbu\cls\Client();

//$result = $Client->insert_clients_daily_report();

//print '<br>report : ' . $cnt->fetch_object() . '<\br>';



//Testing Worker with black list
/*$worker = new \dumbu\cls\Worker();

$worker->prepare_daily_work();
$daily_work = $DB->get_follow_work();
$daily_work->login_data = json_decode($daily_work->cookies);
var_dump($daily_work);
$worker->do_follow_unfollow_work($daily_work);
*/
//Testing white and black list 
//    $DB = new \dumbu\cls\DB();
//$result = $DB->insert_in_white_list(1,"407964088");
//$result2 = $DB->insert_in_white_list(1,"407964098");
//$result3 = $DB->insert_in_white_list(1,"407964078");
//$white_list = $DB->get_white_list(1);
//$result2 = $DB->insert_in_white_list(2,"407964088");
//$result3 = $DB->delete_from_white_list(1,"407964088");
//$result4 = $DB->insert_in_black_list(1,"407964088",true);
//$result5 = $DB->insert_in_black_list(2,"407964088",0);
//$result6 = $DB->delete_from_black_list(1,"407964088");
echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
