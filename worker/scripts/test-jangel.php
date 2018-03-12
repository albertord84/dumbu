<?PHP

require_once '../class/Worker.php';
require_once '../class/system_config.php';
require_once '../class/Gmail.php';
require_once '../class/Payment.php';
require_once '../class/Client.php';
require_once '../class/Reference_profile.php';
require_once '../class/PaymentCielo3.0.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/utils.php';

print('Hola Mundo');
$client_id = 26187;
var_dump(abs(5-9));
//$DB = new \dumbu\cls\DB();
//$DB->InsertEventToWashdog($client_id, 'Test Action', '1', 1, 'TEST action');

$DB = new \dumbu\cls\DB();

$Clients = (new \dumbu\cls\Client())->get_clients();
foreach ($Clients as $Client)
{
    if(isset($Client->cookies))
    {
        $cookies = \GuzzleHttp\json_decode($Client->cookies);
        if(isset($cookies->ds_user_id) && $Client->insta_id !== $cookies->ds_user_id)
        {
            $DB->set_client_cookies($Client->id);
        }
    }
}
/*try{
        //$Robot = new \dumbu\cls\Robot();
        //$val = $Robot->checkpoint_requested("ecr_nature", "ecr26020");
        //$val = $Robot->make_checkpoint("casazunzun", "327094");
        //var_dump($val);
        exec("curl 'https://www.google.com/'",$output,$status);
var_dump($output);
var_dump($status);
}catch(\Exception $exc)
{
        var_dump($exc);
}*/
/*
try{
        $Robot = new \dumbu\cls\Robot();
        $val = $Robot->checkpoint_requested("ky2oficial", "alejandropacho32");
        //$val = $Robot->make_checkpoint("casazunzun", "327094");
        var_dump($val);
}catch(\InstagramAPI\Exception\ChallengeRequiredException $exc)
{
        var_dump("yea");
}

*/
/*

$Client = (new \dumbu\cls\Client())->get_client(27405);
$Robot = new \dumbu\cls\Robot();
$DB = new \dumbu\cls\DB();
//var_dump($Client);
$json_response2 = $Robot->make_insta_friendships_command(json_decode($Client->cookies), '2023444583', 'unfollow', 'web/friendships', $Client);
var_dump($json_response2);
*/
echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
