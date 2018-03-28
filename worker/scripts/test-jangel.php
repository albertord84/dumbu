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
//print('Hola Mundo');

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


$Robot = new \dumbu\cls\Robot();
$json_response = new \stdClass();
$Client = (new \dumbu\cls\Client())->get_client(81875);
$daily_work = new \stdClass();
$daily_work->rp_type = 1;
$daily_work->cookies = $Client->cookies; 
$daily_work->to_follow = 10;
$daily_work->insta_follower_cursor = NULL;
$daily_work->insta_name = 'cuba';
$daily_work->rp_insta_id = 220021938;
$daily_work->client_id = 81875;

$res = $Robot->get_insta_ref_prof_data('daylipadron');
var_dump($res);

//$json_response->message = "unauthorized";
//$json_response->status = 'fail';
//$Robot->daily_work = $daily_work;
//$Robot->id = 1;
//$Robot->process_follow_error($json_response);

/*$Client = (new \dumbu\cls\Client())->get_client(27063);
$daily_work = new \stdClass();
$daily_work->rp_type = 1;
$daily_work->cookies = $Client->cookies; 
$daily_work->to_follow = 10;
$daily_work->insta_follower_cursor = NULL;
$daily_work->insta_name = 'cuba';
$daily_work->rp_insta_id = 220021938;



$query_hash_tag = '298b92c8d7cad703f7565aa892ede943';
$query_hash_loc = '951c979213d7e7a1cf1d73e2f661cbd1';
$query_hash_people = '37479f2b8209594dde7facb0d904896a';

$variables_loc = '{"id":"220021938","first":5,"after":"1742734290348619057"}';
$variables_tag = '{"tag_name":"casa","first":2,"after":"AQDtqk6w08rRUwIh171RaVDS0IPYVbYaQ2T0QDmgUcp42VjDyumZ2a3kLSzgwiDqmvLhv5VJXX0xXr1lwmf2f4EMj1znzGKFHxH_U0gqrpEdmw"}';
$variables_people = '{"id":"2023444583","first":5}';

$Robot = new \dumbu\cls\Robot();
$error = FALSE;
$res = $Robot->get_profiles_to_follow($daily_work, $error, $page_info);
echo json_encode($res);
$cnt = count($res);
echo "<br></br><br>Peoples: $cnt</br><br></br>";

$daily_work->rp_type = 0;
$daily_work->rp_insta_id = 2023444583;
$daily_work->insta_follower_cursor = NULL;
$res = $Robot->get_profiles_to_follow($daily_work, $error, $page_info);
var_dump($res);
echo json_encode($res);
$cnt = count($res);
echo "<br></br><br>Peoples: $cnt</br><br></br>";

$daily_work->rp_type = 2;
$daily_work->insta_follower_cursor = NULL;
$res = $Robot->get_profiles_to_follow($daily_work, $error, $page_info);
echo json_encode($res);
var_dump($res);
echo "<br></br><br>Peoples: $cnt</br><br></br>";*/


/*$result_people =  $Robot->make_curl_followers_query($query_hash_people, $variables_people, json_decode($daily_work->cookies));
$json_response = json_decode(exec($result_people));
$cnt = count($json_response->data->user->edge_followed_by->edges);
echo "<br></br><br>Follows: $cnt </br><br></br>";
echo json_encode($json_response);

$result_loc =  $Robot->make_curl_followers_query($query_hash_loc, $variables_loc);
$json_response = json_decode(exec($result_loc));
$cnt = count($json_response->data->location->edge_location_to_media->edges);
echo "<br></br><br>Peoples: $cnt</br><br></br>";
echo json_encode($json_response);

$result_tag =  $Robot->make_curl_followers_query($query_hash_tag, $variables_tag);
$json_response = json_decode(exec($result_tag));
$cnt = count($json_response->data->hashtag->edge_hashtag_to_media->edges);
echo "<br></br><br>Peoples: $cnt</br><br></br>";
echo json_encode($json_response);
*/

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
