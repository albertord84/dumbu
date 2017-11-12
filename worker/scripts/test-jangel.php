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
//$result = $Gmail->send_client_contact_form("Jose Angel", "jangel.riveaux@gmail.com", "Test contact formm msg NEW2!", "DUMBU", "555-777-777");
//$Gmail->send_client_payment_error("albertord84@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_client_login_error("josergm86@gmail.com", "albertord", "alberto", "Alberto Reyes");
//$Gmail->send_new_client_payment_done("Test test", "test@email");
//var_dump(time());
//var_dump(!0);
//$Robot = new dumbu\cls\Robot();
 
$Gmail = new dumbu\cls\Gmail();
$Robot = new dumbu\cls\Robot();
$DB = new \dumbu\cls\DB();
 $Clients = (new Client())->get_begginer_clients();
//$DB = new DB();
$Client = new Client();
foreach ($Clients as $Client) { // for each CLient
        if (!$Client->cookies) {
        // Log user with curl in istagram to get needed session data
        $login_data = $Client->sign_in($Client);
        if ($login_data !== NULL) {
            $Client->cookies = json_encode($login_data);
        }
    }
    if ($Client->cookies && !$Client->paused) {
//                    var_dump($Client->login);
        print("<br>\nAutenticated Client: $Client->login <br>\n<br>\n");
        $Client->set_client_status($Client->id, user_status::ACTIVE);
// Distribute work between clients
        $RPWC = $Client->rp_workable_count();
        $DIALY_REQUESTS_BY_CLIENT = $Client->to_follow;
        if ($RPWC > 0) {
            $to_follow_unfollow = $DIALY_REQUESTS_BY_CLIENT / $RPWC;
//                        $to_follow_unfollow = $GLOBALS['sistem_config']->DIALY_REQUESTS_BY_CLIENT / $RPWC;
            // If User status = UNFOLLOW he do 0 follows
            $to_follow = $Client->status_id != user_status::DUMBU_UNFOLLOW ? $to_follow_unfollow : 0;
            $to_unfollow = $to_follow_unfollow;
            foreach ($Client->reference_profiles as $Ref_Prof) { // For each reference profile
//$Ref_prof_data = $this->Robot->get_insta_ref_prof_data($Ref_Prof->insta_name);
                if (!$Ref_Prof->deleted && $Ref_Prof->end_date == NULL) {
                    $valid_geo = ($Ref_Prof->type == 1 && ($Client->plane_id == 1 || $Client->plane_id > 3));
                    if ($Ref_Prof->type == 0 || $valid_geo) {
                        $DB->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, $Client->cookies);
                    }
                }
            }
        } else {
            echo "Not reference profiles: $Client->login <br>\n<br>\n";
            if (count($Client->reference_profiles)) { // To keep unfollow
                $DB->insert_daily_work($Client->reference_profiles[0]->id, 0, $DIALY_REQUESTS_BY_CLIENT, $Client->cookies);
            }
            $Gmail->send_client_not_rps($Client->email, $Client->name, $Client->login, $Client->pass);
        }
    } elseif(!$Client->paused){
// TODO: do something in Client autentication error
        // Send email to client
        $now = new \DateTime("now");
        $status_date = new \DateTime();
        $status_date->setTimestamp($Client->status_date ? $Client->status_date : 0);
        $diff_info = $status_date->diff($now);
        var_dump($diff_info->days);
//                    if ($diff_info->days <= 3) {
        // TODO, UNCOMMENT
        $Gmail->send_client_login_error($Client->email, $Client->name, $Client->login, $Client->pass);
//                    }
    }
}

 	 	
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
}*//*
$DB = new \dumbu\cls\DB();
$result = $DB->InsertEventToWashdog(19356,'Error yo testando',1);
var_dump($result);*/
/*$client = (new \dumbu\cls\Client())->get_client(1);
$daily_work = new \dumbu\cls\Day_client_work();
$daily_work->rp_id = 2;
$daily_work->client_id = 1;
$Robot = new \dumbu\cls\Robot();
$Robot->daily_work = $daily_work;
$json_object = $obj = new stdClass();
$json_object->message = 'unauthorized';
$Robot-> process_follow_error($json_object);
*//*
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
