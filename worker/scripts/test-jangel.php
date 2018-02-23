<?PHP
require_once '../class/Worker.php';
require_once '../class/system_config.php';
require_once '../class/Gmail.php';
require_once '../class/Payment.php';
require_once '../class/Client.php';
require_once '../class/Reference_profile.php';
require_once '../class/PaymentCielo3.0.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/utils.php';


$Client = (new \dumbu\cls\Client())->get_client(27405);
$Robot = new \dumbu\cls\Robot();
$DB = new \dumbu\cls\DB();
//var_dump($Client);
$json_response2 = $Robot->make_insta_friendships_command(json_decode($Client->cookies), '2023444583', 'unfollow', 'web/friendships', $Client);
var_dump($json_response2);


echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
