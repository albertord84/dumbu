<?php

require_once '../class/DB.php';
require_once '../class/Worker.php';
require_once '../class/Robot.php';
require_once '../class/system_config.php';
require_once '../class/user_role.php';
require_once '../class/user_status.php';

echo "UNFOLLOW Inited...!<br>\n";
echo date("Y-m-d h:i:sa") . "<br>\n";

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();

$Robot = new \dumbu\cls\Robot();

$DB = new \dumbu\cls\DB();


$clients_data_db = $DB->get_unfollow_clients_data();

// Before
print '<br>\nBEFORE:<br>\n';
$CN = 0;
while ($clients_data_db && $clients_data[$CN] = $clients_data_db->fetch_object()) {
    $clients_data[$CN]->unfollows = 0;
    print "" . $clients_data[$CN]->login . '  |   ' . $clients_data[$CN]->id . '  |   ' . $clients_data[$CN]->insta_following . "<br>\n";
    $CN++;
}
//var_dump($clients_data);

for ($i = 0; $i < 100 && $CN; $i++) {
    // Process all UNFOLLOW clients
    foreach ($clients_data as $ckey => $client_data) {
        if ($client_data) {
            echo "<br>\nClient: $client_data->login ($client_data->id)   " . date("Y-m-d h:i:sa") . "<br>\n";
            // Verify Profile Following
//            $RP = new \dumbu\cls\Reference_profile();
//            $RP_data = $RP->get_insta_ref_prof_data($client_data->login);
//            if (is_object($RP_data) && $RP_data->following > $GLOBALS['sistem_config']->INSTA_MAX_FOLLOWING - $GLOBALS['sistem_config']->MIN_MARGIN_TO_INIT) {
                if ($client_data->cookies) {
                    $login_data = json_decode($client_data->cookies);
                    $json_response = $Robot->get_insta_follows(
                            $login_data, $client_data->insta_id, 15
                    );
                    $json_response = $Robot->get_insta_follows(
                            $login_data, $client_data->insta_id, 15
                    );
//            var_dump($json_response);
                    if (is_object($json_response) && $json_response->status == 'ok' && isset($json_response->follows->nodes)) { // if response is ok
                        // Get Users 
                        print '\n<br> Count: ' . count($json_response->follows->nodes) . '\n<br>';
                        $Profiles = $json_response->follows->nodes;
                        foreach ($Profiles as $rpkey => $Profile) {
                            // Do unfollow request
                            echo "Profil name: $Profile->username<br>\n";
                            $json_response2 = $Robot->make_insta_friendships_command($login_data, $Profile->id, 'unfollow');
                            var_dump($json_response2);
                            echo "<br>\n";
                            if (is_object($json_response2) && $json_response2->status == 'ok') { // if response is ok
                                $clients_data[$ckey]->unfollows++;
                            } else { // Porcess error
                                $Profile = new \dumbu\cls\Profile();
                                $error = $Profile->parse_profile_follow_errors($json_response2); // TODO: Class for error messages
                                if ($error == 6) {// Just empty message:
                                    $error = FALSE;
                                } else if ($error == 7) { // To much request response string only
                                    $error = FALSE;
                                    break;
                                } else if ($error) {
                                    unset($clients_data[$ckey]);
                                    break;
                                }
                                break;
                            }
                        }
                    } else {
                        //unset($clients_data[$ckey]);
                        //echo "<br>\n DELETED FROM UNFOLLOW: $client_data->login ($client_data->id) <br>\n";
                    }
                } else {
                    unset($clients_data[$ckey]);
                    print "NOT COOKIES!!!";
                    echo "<br>\n DELETED FROM UNFOLLOW: $client_data->login ($client_data->id) <br>\n";
                }
//          } else {
//                $DB = new \dumbu\cls\DB();
//                $result = $DB->set_client_status($client_data->id, \dumbu\cls\user_status::ACTIVE);
//                if ($result) {
//                    echo "<br>\nNew Status ACTIVE: $client_data->login ($client_data->id) <br>\n";
//                    unset($clients_data[$ckey]);
//                }
        }
    }
    // Wait 20 minutes
    sleep(20 * 60);
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
