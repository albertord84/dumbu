<?php

require_once '../class/DB.php';
require_once '../class/Worker.php';
require_once '../class/Robot.php';
require_once '../class/system_config.php';

echo "UNFOLLOW Inited...!<br>\n";
echo date("Y-m-d h:i:sa") . "<br>\n";

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();

$Robot = new \dumbu\cls\Robot();

$DB = new \dumbu\cls\DB();


$clients_data_db = $DB->get_unfollow_clients_data();

// Before
print '<br>\nBEFORE:<br>\n';
$CN = 0;
while ($clients_data[$CN] = $clients_data_db->fetch_object()) {
    $clients_data[$CN]->unfollows = 0;
    print "" . $clients_data[$CN]->login . '  |   ' . $clients_data[$CN]->id . '  |   ' . $clients_data[$CN]->insta_following . "<br>\n";
    $CN++;
}
//var_dump($clients_data);

for ($i = 0; $i < 100; $i++) {
    // Process all UNFOLLOW clients
    for ($ci = 0; $ci < $CN; $ci++) {
        $client_data = $clients_data[$ci];
        echo "<br>\nClient: $client_data->login ($client_data->id) <br>\n";
        $client_data->insta_follows_cursor = NULL;
        if ($client_data->cookies) {
            $login_data = json_decode($client_data->cookies);
            $json_response = $Robot->get_insta_follows(
                    $login_data, $client_data->insta_id, 10, $client_data->insta_follows_cursor
            );
            $json_response = $Robot->get_insta_follows(
                    $login_data, $client_data->insta_id, 10, $client_data->insta_follows_cursor
            );
//            var_dump($json_response);
            if (is_object($json_response) && $json_response->status == 'ok' && isset($json_response->follows->nodes)) { // if response is ok
                // Get Users 
                print '\n<br> Count: ' . count($json_response->follows->nodes) . '\n<br>';
                $Profiles = $json_response->follows->nodes;
                foreach ($Profiles as $Profile) {
                    // Do unfollow request
                    echo "Profil name: $Profile->username<br>\n";
                    $json_response2 = $Robot->make_insta_friendships_command($login_data, $Profile->id, 'unfollow');
                    var_dump($json_response2);
                    if (is_object($json_response2) && $json_response2->status == 'ok') { // if response is ok
                        $clients_data[$ci]->unfollows++;
                    } else
                        break;
                }
            }
        }
        else {
            print "NOT COOKIES!!!";
        }
    }
    // Wait some minutes
    sleep(10 * 60);
}


// Change status
for ($ci = 0; $ci < $CN; $ci++) {
    $client_data = $clients_data[$ci];
    $Client = new dumbu\cls\Client();
    if ($client_data->unfollows >= 500) { // TODO: Set to 900
        $Client->set_client_status($client_data->id, dumbu\cls\user_status::ACTIVE);
        echo "<br>\nNew Status ACTIVE: $client_data->login ($client_data->id) <br>\n";
    }
}

// AFTER
print 'AFTER:<br>\n';
print_r($clients_data);


//$clients_data = $DB->get_unfollow_clients_data();
//while ($client_data = $clients_data->fetch_object()) {
//    echo "<br>\nClient: $client_data->login ($client_data->id) <br>\n";
//    $client_data->insta_follows_cursor = NULL;
//    $error = FALSE;
//    $unfollows = 0;
//    while (!$error && $unfollows < 15) {
//        $login_data = json_decode($client_data->cookies);
//        $json_response = $Robot->get_insta_follows(
//                $login_data, $client_data->insta_id, 10, $client_data->insta_follows_cursor
//        );
//        if (is_object($json_response) && $json_response->status == 'ok' && isset($json_response->follows->nodes)) { // if response is ok
//            // Get Users 
//            $Profiles = $json_response->follows->nodes;
//            foreach ($Profiles as $Profile) {
//                // Do unfollow request
//                echo "Profil name: $Profile->username<br>\n";
//                $json_response = $Robot->make_insta_friendships_command($login_data, $Profile->id, 'unfollow');
//                var_dump($json_response);
//                if (is_object($json_response) && $json_response->status == 'ok') { // if response is ok
//                    $unfollows++;
//                } else {
//                    $error = TRUE;
//                    break;
//                }
//            }
//        } else {
//            $error = TRUE;
//        }
//    }
//}


print '\n<br>JOB DONE!!!<br>\n';
echo date("Y-m-d h:i:sa");