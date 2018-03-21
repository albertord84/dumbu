<?PHP

require_once '../class/Worker.php';
require_once '../class/system_config.php';
require_once '../class/Gmail.php';
require_once '../class/Payment.php';
require_once '../class/Client.php';
require_once '../class/Reference_profile.php';
require_once '../class/PaymentCielo3.0.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/utils.php';

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();
print('Hola Mundo');

/*$Client = (new \dumbu\cls\Client())->get_client(65045);
$Robot = new \dumbu\cls\Robot();
$DB = new \dumbu\cls\DB();
//var_dump($Client);
$json_response2 = $Robot->get_insta_geolocalization_data('havana-cuba');
var_dump($json_response2);
$json_response2 = $Robot->get_insta_geolocalization_data('cutrasddaa');
var_dump($json_response2);
$json_response2 = $Robot->get_insta_tag_data('cuba');
var_dump($json_response2);
$json_response2 = $Robot->get_insta_geolocalization_data_from_client($Client->cookies, 'havana-cuba');
var_dump($json_response2);
$json_response2 = $Robot->get_insta_geolocalization_data_from_client($Client->cookies, 'cuba');
var_dump($json_response2);
$json_response2 = $Robot->get_insta_tag_data_from_client($Client->cookies, 'cuba');
var_dump($json_response2);
*/
$res = (new \dumbu\cls\Gmail())->send_link_ticket_bank_and_access_link("Jose Angel", "jangel.riveaux@gmail.com", "https://dumbu.one/dumbu/src/index.php","https://dumbu.pro/dumbu/src/index.php");

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
