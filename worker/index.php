<?PHP

require_once 'class/Worker.php';
require_once 'class/system_config.php';
require_once 'class/Gmail.php';

echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa");

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();

$Gmail = new dumbu\cls\Gmail();
$Gmail->send_client_contact_form("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg", "DUMBU", "555-777-777");

//$Robot = new dumbu\cls\Robot();
//
//$Robot->bot_login("josergm86", "joseramon");

$Worker = new dumbu\cls\Worker();

//$Worker->check_daily_work();
//$Worker->delete_daily_work();
//$Worker->prepare_daily_work();
//$Worker->do_work();

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";