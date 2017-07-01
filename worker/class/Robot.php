<?php

namespace dumbu\cls {
    require_once 'DB.php';
    require_once 'Gmail.php';
    require_once 'Reference_profile.php';
    require_once 'Day_client_work.php';
//    require_once '../libraries/webdriver/phpwebdriver/WebDriver.php';
//    echo $_SERVER['DOCUMENT_ROOT'];
//    require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/webdriver/phpwebdriver/WebDriver.php';

    /**
     * class Robot
     * 
     */
    class Robot {
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * 
         * @access public
         */
        public $id;

        /**
         * 
         * @access public
         */
        public $IP;

        /**
         * 
         * @access public
         */
        public $dir;

        /**
         * 
         * @access public
         */
        public $config;

        /**
         * 
         * @access public
         */
        public $daily_work;

        /**
         * 
         * @access public
         */
        public $Ref_profile;

        /**
         *
         * @var type 
         */
        public $csrftoken = NULL;

        function __construct($DB = NULL) {
            $this->Day_client_work = new Day_client_work();
            $this->Ref_profile = new Reference_profile();
            $this->DB = $DB ? $DB : new \dumbu\cls\DB();
        }

        /**
         * 
         *
         * @param Client Client 

         * @return bool
         * @access public
         */
        public function login_client($Client) {
            
        }

// end of member function login_client

        /**
         * 
         *
         * @param Day_client_work Day_client_work 

         * @param Reference_profile Ref_profile 

         * @return void
         * @access public
         */
        public function do_follow_unfollow_work($Followeds_to_unfollow, $daily_work) {
//            $this->Day_client_work = $Day_client_work;
//            $this->Ref_profile = $Ref_profile;
            //$DB = new DB();
            $this->daily_work = $daily_work;
            $login_data = $this->daily_work->login_data;
            // Unfollow same profiles quantity that we will follow
            $Profile = new Profile();
            // Do unfollow work
            $has_next = count($Followeds_to_unfollow);
            echo "<br>\nClient: $daily_work->client_id <br>\n";
            echo "<br>\nnRef Profil: $daily_work->insta_name<br>\n" . " Count: " . count($Followeds_to_unfollow) . " Hasnext: $has_next - ";
            echo date("Y-m-d h:i:sa");
            echo "<br>\n make_insta_friendships_command UNFOLLOW <br>\n";
            $error = FALSE;
            for ($i = 0; $i < $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME && ($has_next); $i++) {
                $error = FALSE;
                // Next profile to unfollow, not yet unfollwed
                $Profile = array_shift($Followeds_to_unfollow);
                $Profile->unfollowed = FALSE;
                $json_response = $this->make_insta_friendships_command(
                        $login_data, $Profile->followed_id, 'unfollow'
                );
                if (is_object($json_response) && $json_response->status == 'ok') { // if unfollowed 
                    $Profile->unfollowed = TRUE;
                    var_dump(json_encode($json_response));
                    echo "Followed ID: $Profile->followed_id<br>\n";
                    // Mark it unfollowed and send back to queue
                    // If have some Profile to unfollow
                    $has_next = count($Followeds_to_unfollow) && !$Followeds_to_unfollow[0]->unfollowed;
                } else {
                    echo "ID: $Profile->followed_id<br>\n";
//                    var_dump($json_response);
                    $error = $this->process_follow_error($json_response);
                    // TODO: Class for error messages
                    if ($error == 6) {// Just empty message:
                        $error = FALSE;
                        $Profile->unfollowed = TRUE;
                    } else if ($error == 7 || $error == 9) { // To much request response string only
                        $error = FALSE;
                        break;
                    } else {
                        break;
                    }
                }
                array_push($Followeds_to_unfollow, $Profile);
            }
            // Do follow work
            //daily work: cookies   reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
            $Ref_profile_follows = array();
            $follows = 0;
            echo "<br>\nmake_insta_friendships_command FOLLOW (like firsts = $daily_work->like_first): $daily_work->to_follow <br>\n";
            if (!$error && $daily_work->to_follow > 0) { // If has to follow
                $get_followers_count = 0;
                $error = FALSE;
                while (!$error && $follows < $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME && $get_followers_count < $GLOBALS['sistem_config']->MAX_GET_FOLLOWERS_REQUESTS) {
                    // Get next insta followers of Ref_profile
                    $get_followers_count++;
                    echo "<br>\nRef Profil: $daily_work->insta_name (id: $daily_work->rp_id | type: $daily_work->type)<br>\n";
                    // Get Users 
                    $page_info = NULL;
                    $Profiles = $this->get_profiles_to_follow($daily_work, $error, $page_info);
                    foreach ($Profiles as $Profile) {
                        $Profile = $Profile->node;
                        echo "Profil name: $Profile->username ";
                        $null_picture = strpos($Profile->profile_pic_url, '11906329_960233084022564_1448528159_a');
                        // Check if its a valid profile
//                            $valid_profile = FALSE;
//                            if (!$is_private) {
//                                // Check the post amount from this profile
//                                $MIN_FOLLOWER_POSTS = $GLOBALS['sistem_config']->MIN_FOLLOWER_POSTS;
//                                $posts = $this->get_insta_chaining($login_data, $Profile->id, $MIN_FOLLOWER_POSTS);
//                                $valid_profile = count($posts) >= $MIN_FOLLOWER_POSTS;
//                            } else {
//                                $valid_profile = TRUE;
//                            }
//                            if (!$Profile->requested_by_viewer && !$Profile->followed_by_viewer && $valid_profile) { // If user not requested or follwed by Client
                        if (!$Profile->requested_by_viewer && !$Profile->followed_by_viewer && !$null_picture) { // If profile not requested or follwed by Client
                            $Profile_data = $this->get_reference_user($login_data, $Profile->username);
                            $is_private = isset($Profile_data->user->is_private) ? $Profile_data->user->is_private : false;
                            $posts_count = isset($Profile_data->user->media->count) ? $Profile_data->user->media->count : 0;
                            $MIN_FOLLOWER_POSTS = $GLOBALS['sistem_config']->MIN_FOLLOWER_POSTS;
                            $valid_profile = $posts_count >= $MIN_FOLLOWER_POSTS;
                            $following_me = (isset($Profile_data->user->follows_viewer)) ? $Profile_data->user->follows_viewer : false;
                            // TODO: BUSCAR EN BD QUE NO HALLA SEGUIDO ESA PERSONA
//                            $followed_in_db = $this->DB->is_profile_followed($daily_work->client_id, $Profile->id);
                            $followed_in_db = NULL;
                            if (!$followed_in_db && !$following_me && $valid_profile) { // Si no lo he seguido en BD y no me está siguiendo
                                // Do follow request
                                echo "FOLLOWING <br>\n";
                                $json_response2 = $this->make_insta_friendships_command($login_data, $Profile->id, 'follow');
                                if ($daily_work->like_first && count($Profile_data->user->media->nodes)) {
//                                    $this->make_insta_friendships_command($login_data, $Profile_data->user->media->nodes[0]->id, 'like', 'web/likes');
//                                    $this->like_fist_post($login_data, $Profile->id);
                                }
                                if (is_object($json_response2) && $json_response2->status == 'ok') { // if response is ok
                                    array_push($Ref_profile_follows, $Profile);
                                    $follows++;
                                    if ($follows >= $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME)
                                        break;
                                } else {
                                    $error = $this->process_follow_error($json_response2);
                                    var_dump($json_response2);
                                    $error = TRUE;
                                    break;
                                }
                                // Sleep up to proper delay between request
                                sleep($GLOBALS['sistem_config']->DELAY_BETWEEN_REQUESTS);
                            } else {
                                echo "NOT FOLLOWING: followed_in_db($followed_in_db) following_me($following_me) valid_profile($valid_profile)<br>\n";
                            }
                        } else {
                            echo "NOT FOLLOWING: requested_by_viewer($Profile->requested_by_viewer) followed_by_viewer($Profile->followed_by_viewer) null_picture($null_picture)<br>\n";
                        }
                    }
                    // Update cursor
                    if ($page_info && isset($page_info->end_cursor)) {
                        $this->daily_work->insta_follower_cursor = $page_info->end_cursor;
                        $this->DB->update_reference_cursor($this->daily_work->reference_id, $page_info->end_cursor);
                        if (!$page_info->has_next_page)
                            break;
                    } else {
                        break;
                    }
                }
            }
            return $Ref_profile_follows;
        }

        function get_profiles_to_follow($daily_work, &$error, &$page_info) {
            $Profiles = array();
            $error = TRUE;
            $login_data = json_decode($daily_work->cookies);
            $quantity = min(array($daily_work->to_follow, $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME));
            $page_info = new \stdClass();
            if ($daily_work->rp_type == 0) {
                $json_response = $this->get_insta_followers(
                        $login_data, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor
                );
                echo "<br>\nRef Profil: $daily_work->insta_name<br>\n";
                if (is_object($json_response) && $json_response->status == 'ok') {
                    if (isset($json_response->data->user->edge_followed_by)) { // if response is ok
                        echo "Nodes: " . count($json_response->data->user->edge_followed_by->edges) . " <br>\n";
                        $page_info = $json_response->data->user->edge_followed_by->page_info;
                        $Profiles = $json_response->data->user->edge_followed_by->edges;
                        //$DB = new DB();
                        if ($page_info->has_next_page === FALSE && $page_info->end_cursor != NULL) { // Solo qdo es <> de null es que llego al final
                            $this->DB->update_reference_cursor($daily_work->reference_id, NULL);
                            echo ("<br>\n Updated Reference Cursor to NULL!!");
                            $result = $this->DB->delete_daily_work($daily_work->reference_id);
                            if ($result) {
                                echo ("<br>\n Deleted Daily work!!");
                            }
                        } else if ($page_info->has_next_page === FALSE && $page_info->end_cursor === NULL) {
                            $Client = new Client();
                            $Client = $Client->get_client($daily_work->user_id);
                            $login_result = $Client->sign_in($Client);
                            $result = $this->DB->delete_daily_work($daily_work->reference_id);
                            if ($result) {
                                echo ("<br>\n Deleted Daily work!!");
                            }
                        }
                        $error = FALSE;
                    } else {
                        $page_info->end_cursor = NULL;
                        $page_info->has_next_page = false;
                    }
                }
            } else {
                $json_response = $this->get_insta_geomedia($login_data, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor);
                if (is_object($json_response) && $json_response->status == 'ok') {
                    if (isset($json_response->data->location->edge_location_to_media)) { // if response is ok
                        echo "Nodes: " . count($json_response->data->location->edge_location_to_media->edges) . " <br>\n";
                        $page_info = $json_response->data->location->edge_location_to_media->page_info;
                        foreach ($json_response->data->location->edge_location_to_media->edges as $Edge) {
                            $profile = new \stdClass();
                            $profile->node = $this->get_geo_post_user_info($login_data, $daily_work->rp_insta_id, $Edge->node->shortcode);
                            array_push($Profiles, $profile);
                        }
                        $error = FALSE;
                    } else {
                        $page_info->end_cursor = NULL;
                        $page_info->has_next_page = false;
                    }
                }
            }
            if ($error) {
                $error = $this->process_follow_error($json_response);
            }
            return $Profiles;
        }

// end of member function do_follow_unfollow_work

        function process_follow_error($json_response) {
            //$DB = new DB();
            $Profile = new Profile();
            $ref_prof_id = $this->daily_work->rp_id;
            $client_id = $this->daily_work->client_id;
            $error = $Profile->parse_profile_follow_errors($json_response);
            switch ($error) {
                case 1: // "Com base no uso anterior deste recurso, sua conta foi impedida temporariamente de executar essa ação. Esse bloqueio expirará em há 23 horas."
                    $result = $this->DB->delete_daily_work_client($client_id);
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    var_dump($result);
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_TIME!!! <br>\n";
//                    print "<br>\n Unautorized Client (id: $client_id) STUDING set it to BLOCKED_BY_TIME!!! <br>\n";
                    // Alert when insta block by IP
                    $result = $this->DB->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                    $rows_count = $result->num_rows;
                    if ($rows_count == 100 || $rows_count == 150 || ($rows_count >= 200 && $rows_count <= 205)) {
                        $Gmail = new Gmail();
                        $Gmail->send_client_login_error("josergm86@gmail.com", "Jose!!!!!!! BLOQUEADOS 1= " . $rows_count, "Jose");
                        $Gmail->send_client_login_error("albertord84@gmail.com", "Alberto!!!!!!! BLOQUEADOS 1= " . $rows_count, "Alberto");
                    }
                    break;

                case 2: // "Você atingiu o limite máximo de contas para seguir. É necessário deixar de seguir algumas para começar a seguir outras."
                    $result = $this->DB->delete_daily_work_client($client_id);
                    var_dump($result);
//                    $this->DB->set_client_status($client_id, user_status::UNFOLLOW);
//                    print "<br>\n Client (id: $client_id) set to UNFOLLOW!!! <br>\n";
                    print "<br>\n Client (id: $client_id) MUST set to UNFOLLOW!!! <br>\n";
                    break;

                case 3: // "Unautorized"
                    $result = $this->DB->delete_daily_work_client($client_id);
                    var_dump($result);
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_INSTA);
                    $this->DB->set_client_cookies($client_id, NULL);
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_INSTA!!! <br>\n";
                    break;

                case 4: // "Parece que você estava usando este recurso de forma indevida"
                    $result = $this->DB->delete_daily_work_client($client_id);
                    var_dump($result);
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_TIME!!! <br>\n";
                    // Alert when insta block by IP
                    $result = $this->DB->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                    $rows_count = $result->num_rows;
                    if ($rows_count == 100 || $rows_count == 150 || ($rows_count >= 200 && $rows_count <= 210)) {
                        $Gmail = new Gmail();
                        $Gmail->send_client_login_error("josergm86@gmail.com", "Jose!!!!!!! BLOQUEADOS 4= " . $rows_count, "Jose");
                        $Gmail->send_client_login_error("albertord84@gmail.com", "Alberto!!!!!!! BLOQUEADOS 4= " . $rows_count, "Alberto");
                    }
                    print "<br>\n BLOCKED_BY_TIME!!! number($rows_count) <br>\n";
                    break;

                case 5: // "checkpoint_required"
                    $result = $this->DB->delete_daily_work_client($client_id);
                    var_dump($result);
                    $this->DB->set_client_status($client_id, user_status::VERIFY_ACCOUNT);
                    $this->DB->set_client_cookies($client_id, NULL);
                    print "<br>\n Unautorized Client (id: $client_id) set to VERIFY_ACCOUNT!!! <br>\n";
                    break;

                case 6: // "" Empty message
                    print "<br>\n Empty message (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;

                case 7: // "Há solicitações demais. Tente novamente mais tarde." "Aguarde alguns minutos antes de tentar novamente."
                    print "<br>\n Há solicitações demais. Tente novamente mais tarde. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;

                case 8: // "Esta mensagem contém conteúdo que foi bloqueado pelos nossos sistemas de segurança." 
                    $result = $this->DB->delete_daily_work_client($client_id);
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    //var_dump($result);
                    print "<br>\n Esta mensagem contém conteúdo que foi bloqueado pelos nossos sistemas de segurança. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;

                case 9: // "Ocorreu um erro ao processar essa solicitação. Tente novamente mais tarde." 
                    print "<br>\n Ocorreu um erro ao processar essa solicitação. Tente novamente mais tarde. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;

                default:
                    print "<br>\n Client (id: $client_id) not error code found ($error)!!! <br>\n";
                    $error = FALSE;
                    break;
            }
            return $error;
        }

        /**
         * Friendships API commands, normally used to 'follow' and 'unfollow'.
         * @param type $login_data
         * @param type $resource_id {ex: Profile Id}
         * @param type $command {follow, unfollow, ... }
         * @return type
         */
        public function make_insta_friendships_command($login_data, $resource_id, $command = 'follow', $objetive_url = 'web/friendships') {
            $curl_str = $this->make_curl_friendships_command_str("'https://www.instagram.com/$objetive_url/$resource_id/$command/'", $login_data);
            //print("<br><br>$curl_str<br><br>");
            //echo "<br><br><br>O seguidor ".$user." foi requisitado. Resultado: ";
            exec($curl_str, $output, $status);
            if (is_array($output) && count($output)) {
                $json_response = json_decode($output[0]);
                if ($json_response && (isset($json_response->result) || isset($json_response->status))) {
                    return $json_response;
                }
            }

            return $output;
            //print_r($status);
            //print("-> $status<br><br>");
//            return $json_response;
        }

        public function make_curl_friendships_command_str($url, $login_data) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            $curl_str = "curl '$url' ";
            $curl_str .= "-X POST ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Host: www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-Ajax: 1' ";
            $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Content-Length: 0' ";
            $curl_str .= "--compressed ";
            return $curl_str;
        }

        public function get_insta_chaining($login_data, $user, $N = 1, $cursor = NULL) {
            try {
                $url = "https://www.instagram.com/graphql/query/";
                $curl_str = $this->make_curl_chaining_str("$url", $login_data, $user, $N, $cursor);
                //print("<br><br>$curl_str<br><br>");
                exec($curl_str, $output, $status);
                //print_r($output);
                //print("-> $status<br><br>");
                $json = json_decode($output[0]);
                if (isset($json->data->user->edge_owner_to_timeline_media) && isset($json->data->user->edge_owner_to_timeline_media->edges) && count($json->data->user->edge_owner_to_timeline_media->edges)) {
                    return $json->data->user->edge_owner_to_timeline_media->edges;
                }
                return FALSE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_insta_followers($login_data, $user, $N, $cursor = NULL) {
            try {
                $url = "https://www.instagram.com/graphql/query/";
                $curl_str = $this->make_curl_followers_str("$url", $login_data, $user, $N, $cursor);
                //print("<br><br>$curl_str<br><br>");
                exec($curl_str, $output, $status);
                //print_r($output);
                //print("-> $status<br><br>");
                $json = json_decode($output[0]);
                //var_dump($output);
                if (isset($json->data->user->edge_followed_by) && isset($json->data->user->edge_followed_by->page_info)) {
                    if ($json->data->user->edge_followed_by->page_info->has_next_page === false) {
                        echo ("<br>\n END Cursor empty!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                        var_dump(json_encode($json));
                    }
                } else {
                    var_dump($output);
                    print_r($curl_str);
                    if (isset($json->data) && ($json->data->user == null)) {
                        //$this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                        //echo ("<br>\n Updated Reference Cursor to NULL!!");
                        $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                        if ($result) {
                            echo ("<br>\n Deleted Daily work!!");
                        } else {
                            var_dump($result);
                        }
                    }
                }
                return $json;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        /**
         * Unfollow Total
         * @param type $login_data
         * @param type $user
         * @param type $N
         * @param type $cursor
         * @return type
         */
        public function get_insta_follows($login_data, $user, $N, &$cursor = NULL) {
            try {
                $url = "https://www.instagram.com/graphql/query/";
                $curl_str = $this->make_curl_follows_str("$url", $login_data, $user, $N, $cursor);
                exec($curl_str, $output, $status);
                $json = json_decode($output[0]);
                //var_dump($output);
                if (isset($json->data->user->edge_follow) && isset($json->data->user->edge_follow->page_info)) {
                    $cursor = $json->data->user->edge_follow->page_info->end_cursor;
                    if (count($json->data->user->edge_follow->edges) == 0) {
                        var_dump($json);
//                        var_dump($curl_str);
                        echo ("No nodes!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                    }
                } else {
                    //var_dump($output);
                    var_dump($curl_str);
                    //$this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                }
                return $json;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_insta_geomedia($login_data, $location, $N, &$cursor = NULL) {
            try {
                $url = "https://www.instagram.com/graphql/query/";
                $curl_str = $this->make_curl_geomedia_str("$url", $login_data, $location, $N, $cursor);
                exec($curl_str, $output, $status);
                $json = json_decode($output[0]);
                //var_dump($output);
                if (isset($json->data->location->edge_location_to_media) && isset($json->data->location->edge_location_to_media->page_info)) {
                    $cursor = $json->data->location->edge_location_to_media->page_info->end_cursor;
                    if (count($json->data->location->edge_location_to_media->edges) == 0) {
                        //echo '<pre>'.json_encode($json, JSON_PRETTY_PRINT).'</pre>';
                        //var_dump($json);
//                        var_dump($curl_str);
                        echo ("<br>\n No nodes!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                        $this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                        $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                        echo ("<br>\n Set end cursor to NULL!!!!!!!! Deleted daily work!!!!!!!!!!!!");
                    }
                } else {
                    var_dump($output);
                    print_r($curl_str);
                    //$this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                }
                return $json;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function make_curl_followers_str($url, $login_data, $user, $N, $cursor = NULL) {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            $url .= "?query_id=17851374694183129&id=$user&first=$N";
            if ($cursor) {
                $url .= "&after=$cursor";
            }
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            //$curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
//            }
        }

        public function make_curl_geomedia_str($url, $login_data, $location, $N, $cursor = NULL) {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            $url .= "?query_id=17881432870018455&id=$location&first=$N";
            if ($cursor) {
                $url .= "&after=$cursor";
            }
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            //$curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/explore/locations/$location/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
//            }
        }

        public function make_curl_chaining_str($url, $login_data, $user, $N, $cursor = NULL) {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            $url .= "?query_id=17880160963012870&id=$ds_user_id&first=$N";
            if ($cursor) {
                $url .= "&after=$cursor";
            }
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
//            if ($cursor === NULL || $cursor === '') {
//                $curl_str .= "--data "
//                        . "'q=ig_user($user)+%7B+media.first($N)+%7B%0A++count%2C%0A++nodes+%7B%0A++++__typename%2C%0A++++caption%2C%0A++++code%2C%0A++++comments+%7B%0A++++++count%0A++++%7D%2C%0A++++comments_disabled%2C%0A++++date%2C%0A++++dimensions+%7B%0A++++++height%2C%0A++++++width%0A++++%7D%2C%0A++++display_src%2C%0A++++id%2C%0A++++is_video%2C%0A++++likes+%7B%0A++++++count%0A++++%7D%2C%0A++++owner+%7B%0A++++++id%0A++++%7D%2C%0A++++thumbnail_src%2C%0A++++video_views%0A++%7D%2C%0A++page_info%0A%7D%0A+%7D' ";
//            }
//            else {
////                $curl_str .= "--data "
////                        . "'q=ig_user($user)+%7B%0A++followed_by.after($cursor, $N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list&query_id=17851938028087704' ";
//                $curl_str .= "--data "
//                        . "'q=ig_user($user)+%7B%0A++followed_by.after($cursor, $N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++profile_pic_url%2C%0A++++++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list' ";
////                "page_info": {"has_previous_page": true, "start_cursor": "AQCofdJPzGRljplmFndRzUK17kcV3cD2clwRHYSHInAWcmxn5fhtZVGZyHs1pLUafOMOw8SYZnM4UB-4WO8vM9oTjdAuvL14DmH87kZDJE2kmaW_sA-K6_yqP6pzsyC-6RE", "end_cursor": "AQDsGU9PY7SKUFVzb4g-9hUAqmN3AVn7WKa38BTEayApyPavBw6RqRriVD46_LamE1GllxTSdsFsbD3IQ7C5aEx2n7rRIaIegPoTWxPZg34SWMwLxJfI5I6ivcZ0wOZg7a4", "has_next_page": true}
//            }
            $curl_str .= "--compressed ";
            return $curl_str;
//            }
        }

        public function make_curl_follows_str($url, $login_data, $user, $N, $cursor = NULL) {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            $url .= "?query_id=17874545323001329&id=$ds_user_id&first=$N";
            if ($cursor) {
                $url .= "&after=$cursor";
            }
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
        }

        public function make_insta_login($cookies) {
            $curl_str = $this->make_curl_login_str('https://www.instagram.com/accounts/login/ajax/', $cookies, "albertoreyesd84", "alberto");
            //    $curl_str = make_curl_str('https://www.instagram.com/accounts/login/ajax/', $webdriver->getAllCookies(), "josergm86", "joseramon");
            //    print("<br><br>$curl_str<br><br>");
            exec($curl_str, $output, $status);
            print_r($output);
            print_r($status);
            print_r("-> $status<br>\n<br>\n");
            return $output;
        }

        public function make_curl_login_str($url, $cookies, $user, $pass) {
            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Host: www.instagram.com' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Accept-Language: en-US,en;q=0.5' ";
            $curl_str .= "--compressed ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/accounts/login/' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-AJAX: 1' ";
            $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Cookie: csrftoken=$csrftoken; ig_pr=1; ig_vw=1280' ";
            $curl_str .= "-H 'Connection: keep-alive' ";
            $curl_str .= "--data 'username=$user&password=$pass'";
            return $curl_str;
        }

        public function obtine_cookie_value($cookies, $name) {
            foreach ($cookies as $key => $object) {
                //print_r($object + "<br>");
                if ($object->name == $name) {
                    return $object->value;
                }
            }
            return null;
        }

        public function make_post($url) {
            $session = curl_init();
            //$headers['Accept-Encoding'] = 'gzip, deflate, br';
            //$headers['Accept-Language'] = 'en-US,en;q=0.5';
            //$headers['Content-Length'] = '37';
            $headers['Accept'] = '*/*';
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $headers['Cookie'] = "mid=V9xV2wAEAAGOzlo31h2_pyy1Huj5; sessionid=IGSC08fa8c584f5ca30ce171f701e318e8285610c3c1ffdac0a54ef0c4e43a6ec770%3Ad2m0Jq7dBiCBuUFEReJX6Pdg8TjmSKf4%3A%7B%22_token_ver%22%3A2%2C%22_auth_user_id%22%3A3858629065%2C%22_token%22%3A%223858629065%3Abz609jmb069TVeABWNYqpPxnNdWV0bxV%3Afd4a372b9561ade868a2eb39cc98f468da9c2053f34179d724c89ac52630e64c%22%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22last_refreshed%22%3A1474057695.099257%2C%22_platform%22%3A4%2C%22_auth_user_hash%22%3A%22%22%7D; csrftoken=bewWHKLF2xJq01xo98Aze2fOEnAcyiaX";
            $headers['Origin'] = "https://www.instagram.com/";
            $headers['Accept-Encoding'] = "gzip, deflate";
            $headers['Accept-Language'] = "en-US,en; q=0.8";
            $headers['User-Agent'] = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36';
            $headers['X-Requested-With'] = 'XMLHttpRequest';
            $headers['X-CSRFToken'] = 'bewWHKLF2xJq01xo98Aze2fOEnAcyiaX';
            $headers['X-Instagram-AJAX'] = '1';
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $headers['Referer'] = 'https://www.instagram.com/';
            $headers['Authority'] = 'www.instagram.com';
            $headers['Content-Length'] = '0';
            $headers['Connection'] = 'keep-alive';

            curl_setopt($session, CURLOPT_URL, $url);
            curl_setopt($session, CURLOPT_POST, TRUE);
            curl_setopt($session, CURLOPT_ENCODING, "gzip");
            curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($session, CURLOPT_HEADER, 1);
            curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($session, CURLOPT_FOLLOWLOCATION, 1);

            //curl_setopt($session, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($session);
            print_r($response);

            curl_close($session);
            echo "data posted....! <br>\n";
        }

        public function get_insta_csrftoken($ch) {
            curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/");
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            //curl_setopt($ch, CURLOPT_CAINFO, "curl-ca-bundle.crt");
            //curl_setopt ($ch, CURLOPT_CAINFO,"cacert.pem");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLINFO_COOKIELIST, true);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
            global $cookies;
            $cookies = array();
            $response = curl_exec($ch);

            $csrftoken = $this->get_cookies_value("csrftoken");

            return $csrftoken;
        }

        public function login_insta_with_csrftoken($ch, $login, $pass, $csrftoken, $Client = NULL) {
            $pass = urlencode($pass);
            $postinfo = "username=$login&password=$pass";

            $headers = array();
            $headers[] = "Host: www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
            //            $headers[] = "Accept: application/json";
            $headers[] = "Accept: */*";
            $headers[] = "Accept-Language: en-US,en;q=0.5, ";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Referer: https://www.instagram.com/";
            $headers[] = "X-CSRFToken: $csrftoken";
            $headers[] = "X-Instagram-AJAX: 1";

            $ip = $_SERVER['REMOTE_ADDR'];
            if ($Client != NULL && $Client->HTTP_SERVER_VARS != NULL) { // if 
                $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);
                $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
            }
            $ip = "127.0.0.1";
            $headers[] = "REMOTE_ADDR: $ip";
            $headers[] = "HTTP_X_FORWARDED_FOR: $ip";

            $headers[] = "Content-Type: application/x-www-form-urlencoded";
//            $headers[] = "Content-Type: application/json";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Cookie: csrftoken=$csrftoken";
            $url = "https://www.instagram.com/accounts/login/ajax/";
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
            //curl_setopt($ch, CURLOPT_POST, true);
            //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));

            global $cookies;
            $cookies = array();
            $html = curl_exec($ch);
            $info = curl_getinfo($ch);

            // LOGIN WITH CURL TO TEST
            // Parse html response
            $start = strpos($html, "{");
            $json_str = substr($html, $start);
            $json_response = json_decode($json_str);
            //
            $login_data = new \stdClass();
            $login_data->json_response = $json_response;
            if (curl_errno($ch)) {
                //print curl_error($ch);
            } else if (count($cookies) >= 5) {
                $login_data->csrftoken = $csrftoken;
                // Get sessionid from cookies
                $login_data->sessionid = $this->get_cookies_value("sessionid");
                // Get ds_user_id from cookies
                $login_data->ds_user_id = $this->get_cookies_value("ds_user_id");
                // Get mid from cookies
                $login_data->mid = $this->get_cookies_value("mid");
            }
            curl_close($ch);
//            var_dump($login_data);
            return $login_data;
        }

        public function get_cookies_value($key) {
            $value = NULL;
            global $cookies;
            foreach ($cookies as $index => $cookie) {
                $pos = strpos($cookie[1], $key);
                if ($pos !== FALSE && $pos === 0) {
                    $value = explode("=", $cookie[1]);
                    $value = $value[1];
                }
            }
//            array(5) (
//                [0] => array(2) (
//                    [0] => (string) Set-Cookie: target = ""
//                    [1] => (string) target = ""
//                )
//                [1] => array(2) (
//                    [0] => (string) Set-Cookie: sessionid = IGSCe1aaf9cbd92bdb97f6392541718f0f1cc3c9f104fa582781747eea41f45feab6%3AaWt6gfw3qwDWgZ4pm5z3KJdHi97IhFXj%3A%7B%22_token%22%3A%223858629065%3ASaCRKRRXkW6bOn1hABewWJMkpIjPJnVH%3A02085c8afdf6bccc4e3aeda68d33cf4d9d24fd52c778bb5ef68d9055e3de38d8%22%2C%22_auth_user_id%22%3A3858629065%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_token_ver%22%3A2%2C%22_platform%22%3A4%2C%22last_refreshed%22%3A1481805181.8183546%2C%22_auth_user_hash%22%3A%22%22%7D
//                    [1] => (string) sessionid = IGSCe1aaf9cbd92bdb97f6392541718f0f1cc3c9f104fa582781747eea41f45feab6%3AaWt6gfw3qwDWgZ4pm5z3KJdHi97IhFXj%3A%7B%22_token%22%3A%223858629065%3ASaCRKRRXkW6bOn1hABewWJMkpIjPJnVH%3A02085c8afdf6bccc4e3aeda68d33cf4d9d24fd52c778bb5ef68d9055e3de38d8%22%2C%22_auth_user_id%22%3A3858629065%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_token_ver%22%3A2%2C%22_platform%22%3A4%2C%22last_refreshed%22%3A1481805181.8183546%2C%22_auth_user_hash%22%3A%22%22%7D
//                )
//                [2] => array(2) (
//                    [0] => (string) Set-Cookie: csrftoken = LRroVq0dMCKrMf3ZEHHxlK4096vWjS4L
//                    [1] => (string) csrftoken = LRroVq0dMCKrMf3ZEHHxlK4096vWjS4L
//                )
//                [3] => array(2) (
//                    [0] => (string) Set-Cookie: ds_user_id = 3858629065
//                    [1] => (string) ds_user_id = 3858629065
//                )
//                [4] => array(2) (
//                    [0] => (string) Set-Cookie: mid = WFKNfQAEAAFmshFCfCuZHStSf0Ou
//                    [1] => (string) mid = WFKNfQAEAAFmshFCfCuZHStSf0Ou
//                )
//            )
            return $value;
        }

        public function get_insta_ref_prof_data($ref_prof, $ref_prof_id = NULL) {
            try {
                $Profile = NULL;
                if ($ref_prof != "") {
//                    $content = @file_get_contents("https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof", FALSE);
//                    exec("curl 'https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof'", $output, $status);
//                    $content = json_decode($output[0]);
                    $ch = curl_init("https://www.instagram.com/");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_POST, FALSE);
                    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof");
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    $html = curl_exec($ch);
                    $string = curl_error($ch);
                    $content = json_decode($html);
                    $Profile = $this->process_get_insta_ref_prof_data($content, $ref_prof, $ref_prof_id);
                    curl_close($ch);
                }
                return $Profile;
            } catch (Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        public function get_insta_geolocalization_data($ref_prof, $ref_prof_id = NULL) {
            try {
                $Profile = NULL;
                if ($ref_prof != "") {
//                    $content = @file_get_contents("https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof", FALSE);
//                    exec("curl 'https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof'", $output, $status);
//                    $content = json_decode($output[0]);
                    $ch = curl_init("https://www.instagram.com/");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_POST, FALSE);
                    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof");
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    $html = curl_exec($ch);
                    $string = curl_error($ch);
                    $content = json_decode($html);
                    $Profile = $this->process_get_insta_geolocalization_data($content, $ref_prof, $ref_prof_id);
                    curl_close($ch);
                }
                return $Profile;
            } catch (Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        public function get_insta_geolocalization_data_from_client($cookies, $ref_prof, $ref_prof_id = NULL) {
            try {
                $Profile = NULL;
                if ($ref_prof != "") {
                    $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
                    $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
                    $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
                    $mid = isset($cookies->mid) ? $cookies->mid : 0;
                    $headers = array();
                    $headers[] = "Host: www.instagram.com";
                    $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
//                                $headers[] = "Accept: application/json";
                    $headers[] = "Accept: */*";
                    $headers[] = "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4";

                    $headers[] = "Accept-Encoding: deflate, sdch";
                    $headers[] = "Referer: https://www.instagram.com/";
                    $headers[] = "X-CSRFToken: $csrftoken";
                    $ip = "127.0.0.1";
                    $headers[] = "REMOTE_ADDR: $ip";
                    $headers[] = "HTTP_X_FORWARDED_FOR: $ip";

                    $headers[] = "Content-Type: application/x-www-form-urlencoded";
//                    $headers[] = "Content-Type: application/json";
                    $headers[] = "X-Requested-With: XMLHttpRequest";
                    $headers[] = "Authority: www.instagram.com";
                    $headers[] = "Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id";
                    $url = "https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof";
                    $ch = curl_init("https://www.instagram.com/");
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    $output = curl_exec($ch);
                    $string = curl_error($ch);
                    $content = json_decode($output);
                    $Profile = $this->process_get_insta_geolocalization_data($content, $ref_prof, $ref_prof_id);
                    //var_dump($content);
                }
                return $Profile;
            } catch (Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        public function get_insta_ref_prof_data_from_client($cookies, $ref_prof, $ref_prof_id = NULL) {
            try {
                $Profile = NULL;
                if ($ref_prof != "") {
                    $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
                    $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
                    $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
                    $mid = isset($cookies->mid) ? $cookies->mid : 0;
                    $headers = array();
                    $headers[] = "Host: www.instagram.com";
                    $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
//                                $headers[] = "Accept: application/json";
                    $headers[] = "Accept: */*";
                    $headers[] = "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4";

                    $headers[] = "Accept-Encoding: deflate, sdch";
                    $headers[] = "Referer: https://www.instagram.com/";
                    $headers[] = "X-CSRFToken: $csrftoken";
                    $ip = "127.0.0.1";
                    $headers[] = "REMOTE_ADDR: $ip";
                    $headers[] = "HTTP_X_FORWARDED_FOR: $ip";

                    $headers[] = "Content-Type: application/x-www-form-urlencoded";
//                    $headers[] = "Content-Type: application/json";
                    $headers[] = "X-Requested-With: XMLHttpRequest";
                    $headers[] = "Authority: www.instagram.com";
                    $headers[] = "Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id";
                    $url = "https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof";
                    $ch = curl_init("https://www.instagram.com/");
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    $output = curl_exec($ch);
                    $string = curl_error($ch);
                    $content = json_decode($output);
                    //var_dump($content);
                    $Profile = $this->process_get_insta_ref_prof_data($content, $ref_prof, $ref_prof_id);
                }
                return $Profile;
            } catch (Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        function process_get_insta_ref_prof_data($content, $ref_prof, $ref_prof_id) {
            $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $users = $content->users;
                // Get user with $ref_prof name over all matchs 
                if (is_array($users)) {
                    for ($i = 0; $i < count($users); $i++) {
                        if ($users[$i]->user->username === $ref_prof) {
                            $Profile = $users[$i]->user;
                            $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
                            $Profile->following = $this->get_insta_ref_prof_following($ref_prof);
                            if (!isset($Profile->follower_count)) {
                                $Profile->follower_count = isset($Profile->byline) ? $this->parse_follow_count($Profile->byline) : 0;
                            }
                            break;
                        }
                    }
                }
            } else {
                //var_dump($content);
                //var_dump("null reference profile!!!");
            }
            return $Profile;
        }

        function process_get_insta_geolocalization_data($content, $ref_prof, $ref_prof_id) {
            $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $places = $content->places;
                // Get user with $ref_prof name over all matchs 
                if (is_array($places)) {
                    for ($i = 0; $i < count($places); $i++) {
                        if ($places[$i]->place->slug === $ref_prof) {
                            $Profile = $places[$i]->place;
                            $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
//                            $Profile->following = $this->get_insta_ref_prof_following($ref_prof);
                            break;
                        }
                    }
                }
            } else {
                //var_dump($content);
                //var_dump("null reference profile!!!");
            }
            return $Profile;
        }

        public function parse_follow_count($follow_count_str) {
            $search = " followers";
            $start = strpos($follow_count_str, $search);

            $letter = substr($follow_count_str, $start - 1, 1);
            $decimals = 1;
            $substr = substr($follow_count_str, 0, strlen($follow_count_str) - strlen($search));
            if ($letter === 'k' || $letter === 'm') { // If not integer its a 10 power
                $substr = substr($follow_count_str, 0, strlen($follow_count_str) - strlen($search) - 1);
                $decimals = $letter === 'k' ? 1000 : $decimals;
                $decimals = $letter === 'm' ? 1000000 : $decimals;
            }
            $followers = floatval($substr) * $decimals;

            return $followers;
        }

        public function get_insta_ref_prof_follows($ref_prof_id) {
            $follows = $ref_prof_id ? Reference_profile::static_get_follows($ref_prof_id) : 0;
            return $follows;
        }

        public function get_insta_ref_prof_following($ref_prof) {
            $content = @file_get_contents("https://www.instagram.com/$ref_prof/", false);

            $doc = new \DOMDocument();
//$doc->loadXML($content);
            $substr2 = NULL;
            $loaded = @$doc->loadHTML('<?xml encoding="UTF-8">' . $content);
            if ($loaded) {

                $search = "\"follows\": {\"count\": ";
                $start = strpos($doc->textContent, $search);

                $substr1 = substr($doc->textContent, $start, 100);
                $substr2 = substr($substr1, strlen($search), strpos($substr1, "}") - strlen($search));
            } else {
                print "<br>\nProblem parsing document:<br>\n";
                var_dump($doc);
            }
            return intval($substr2) ? intval($substr2) : 0;
        }

        public function bot_login($login, $pass, $Client = NULL) {
            $result = NULL;
            $url = "https://www.instagram.com/";
//            $cookie = "/home/albertord/cookies.txt";
            $login_response = false;
            $try_count = 0;
            while (!$login_response && $try_count < 4) {
                $ch = curl_init($url);
                $this->csrftoken = $this->get_insta_csrftoken($ch);
                if ($this->csrftoken != NULL && $this->csrftoken != "") {
                    $result = $this->login_insta_with_csrftoken($ch, $login, $pass, $this->csrftoken, $Client);
                    $login_response = is_object($result->json_response);
                }
                $try_count++;
//                if (isset($result->json_response->message) && $result->json_response->message == "checkpoint_required") {
//                    $this->DB->set_client_status_by_login($login, user_status::VERIFY_ACCOUNT);
//                }
//                else if (isset($result->json_response->authenticated) && $result->json_response->authenticated == FALSE) {
//                    $this->DB->set_client_status_by_login($login, user_status::BLOCKED_BY_INSTA);
//                }
//                if (!$login_response)
//                    print "LOGIN NULL ISSUE ($login)!!! Trying $try_count of 3";
            }
            //var_dump($result);
            //die("<br><br>Debug Finish!");
            return $result;
        }

        public function like_fist_post($client_cookies, $client_insta_id) {
            $result = $this->get_insta_chaining($client_cookies, $client_insta_id);
            //print_r($result);
            if ($result) {
                $result = $this->make_insta_friendships_command($client_cookies, $result[0]->node->id, 'like', 'web/likes');
//              print_r($result);
            }
        }

//  end of member function bot_login
// get cookie
// multi-cookie variant contributed by @Combuster in comments
        function curlResponseHeaderCallback($ch, $headerLine) {
            global $cookies;
            if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1)
                $cookies[] = $cookie;
//        $cookies[] = $headerLine;
            return strlen($headerLine); // Needed by curl
        }

        public function get_reference_user($cookies, $reference_user_name) {
            //echo " -------Obtindo dados de perfil de referencia------------<br>\n<br>\n";
            $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
            $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
            $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
            $mid = isset($cookies->mid) ? $cookies->mid : 0;
            $url = "https://www.instagram.com/$reference_user_name/?__a=1";
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "--compressed ";
            $result = exec($curl_str, $output, $status);
            return json_decode($output[0]);
        }

        public function get_geo_post_user_info($cookies, $location_id, $post_reference) {
            //echo " -------Obtindo dados de perfil que postou na geolocalizacao------------<br>\n<br>\n";
            $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
            $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
            $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
            $mid = isset($cookies->mid) ? $cookies->mid : 0;
            $url = "https://www.instagram.com/p/$post_reference/?taken-at=$location_id&__a=1";
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "--compressed ";
            $result = exec($curl_str, $output, $status);
            $object = json_decode($output[0]);
            if (is_object($object) && isset($object->graphql->shortcode_media->owner)) {
                return $object->graphql->shortcode_media->owner;
            }
            return NULL;
        }

        public function str_login($csrftoken, $user, $pass) {
            $url = "https://www.instagram.com/accounts/login/ajax/";
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'Cookie: csrftoken=$csrftoken' ";
            $curl_str .= "-H 'Host: www.instagram.com' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-AJAX: 1' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'REMOTE_ADDR: 127.0.0.1' -H 'HTTP_X_FORWARDED_FOR: 127.0.0.1'";
            $curl_str .= " --data 'username=$user&password=$pass' ";
            exec($curl_str, $output, $status);
            return json_decode($output[0]);
        }

    }

// end of Robot
}

?>
