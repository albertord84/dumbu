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
//$Payment->delete_payment("5f4ef87d-cf0d-4da1-91f6-5a394924c308");

//----------------------------------------------------------------

// GMAIL
// 
$Gmail = new dumbu\cls\Gmail();
$result = $Gmail->send_client_contact_form("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg NEW!", "DUMBU", "555-777-777");
//$Gmail->send_client_payment_error("pedro@seiva.pro", "pedropetti", "pedropetti", "pedropetti");
//var_dump($result);

//$Robot = new dumbu\cls\Robot();
//
//$Robot->bot_login("josergm86", "joseramon");

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
