<?php

namespace dumbu\cls {
    require_once 'Reference_profile.php';
//    require_once '../libraries/webdriver/phpwebdriver/WebDriver.php';
    echo $_SERVER['DOCUMENT_ROOT'];
    require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/webdriver/phpwebdriver/WebDriver.php';

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
         * @access public
         */
        public $webdriver = null;

        function __construct() {
            $this->Day_client_work = new Day_client_work();
            $this->Ref_profile = new Reference_profile();
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
            $this->daily_work = $daily_work;
            $cookies = $this->daily_work->cookies;
            // Unfollow same profiles quantity that we will follow
            $Profile = new Profile();
            // Do unfollow work
            $has_next = count($Followeds_to_unfollow) && !$Followeds_to_unfollow[0]->unfollowed;
            echo "<br>Ref Profil: $daily_work->insta_name<br>";
            echo time();
            echo "<br> make_insta_friendships_command UNFOLLOW <br>";
            for ($i = 0; $i < $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME && ($has_next); $i++) {
                // Next profile to unfollow, not yet unfollwed
                $Profile = array_shift($Followeds_to_unfollow);
                $json_response = $this->make_insta_friendships_command(
                        $cookies, $Profile->followed_id, 'unfollow'
                );
                if (is_object($json_response) && $json_response->status == 'ok') { // if unfollowed 
                    var_dump(json_encode($json_response));
                    echo "Followed ID: $Profile->followed_id<br>";
                    // Mark it unfollowed and send back to queue
                    $Profile->unfollowed = TRUE;
                    array_push($Followeds_to_unfollow, $Profile);
                    // If have some Profile to unfollow
                    $has_next = !$Followeds_to_unfollow[0]->unfollowed;
                } else {
                    var_dump($json_response);
                    break;
                    // TODO: if response is "Há solicitações demais. Tente novamente mais tarde." then
                    // stop this user work flow...
//                    throw new \Exception(json_encode($json_response), 1003);
                }
            }
            // Do follow work
            //daily work: cookies   reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
            echo "<br>make_insta_friendships_command FOLLOW <br>";
            $follows = 0;
            $get_followers_count = 0;
            $Ref_profile_follows = array();
            while ($follows < $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME && $get_followers_count < $GLOBALS['sistem_config']::MAX_GET_FOLLOWERS_REQUESTS) {
                // Get next insta followers of Ref_profile
                $quantity = min(array($daily_work->to_follow, $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME));
                $json_response = $this->get_insta_followers(
                        $cookies, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor
                );
                //var_dump($json_response);
                echo "<br>Ref Profil: $daily_work->insta_name     ------>   End Cursor: $daily_work->insta_follower_cursor<br>";
                $get_followers_count++;
                if (is_object($json_response) && $json_response->status == 'ok' && isset($json_response->followed_by->nodes)) { // if response is ok
                    // Get Users 
                    $Profiles = $json_response->followed_by->nodes;
                    foreach ($Profiles as $Profile) {
                        if (!$Profile->requested_by_viewer && !$Profile->followed_by_viewer) { // If user not requested or follwed by Client
                            // Do follow request
                            $json_response = $this->make_insta_friendships_command($cookies, $Profile->id, 'follow');
                            var_dump($json_response);
                            echo "Profil name: $Profile->username<br>";
                            if (is_object($json_response) && $json_response->status == 'ok') { // if response is ok
                                array_push($Ref_profile_follows, $Profile);
                                $follows++;
                                if ($follows >= $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME)
                                    break;
                            } else {
                                var_dump($json_response);
//                                throw new \Exception(json_encode($json_response), 1001);
                            }
                            //echo "<br><br><br>O .seguidor " . $User->username . " foi requisitado. Resultado: ";
                            // Sleep up to proper delay between request
                            sleep($GLOBALS['sistem_config']::DELAY_BETWEEN_REQUESTS);
                        }
                    }
                }
//                else {
//                    var_dump($json_response);
//                    throw new \Exception(json_encode($json_response), 1002);
//                }
            }
            //$this->webdriver->close();
            return $Ref_profile_follows;
        }

// end of member function do_follow_unfollow_work

        /**
         * Friendships API commands, normally used to 'follow' and 'unfollow'.
         * @param type $cookies
         * @param type $user_id
         * @param type $command {follow, unfollow, ... }
         * @return type
         */
        public function make_insta_friendships_command($cookies, $user_id, $command = 'follow') {
            $curl_str = $this->make_curl_follow_str("'https://www.instagram.com/web/friendships/$user_id/$command/'", $cookies);
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

        public function make_curl_follow_str($url, $cookies) {
            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
            $ds_user_id = $this->obtine_cookie_value($cookies, "ds_user_id");
            $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
            $curl_str = "curl '$url' ";
            $curl_str .= "-X POST ";
            $curl_str .= "-H 'Cookie: mid=V9WouwAEAAEC24F7E7oIcleD-vkG; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
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

        public function make_curl_friendships_str($url, $cookies) {
            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
            $ds_user_id = $this->obtine_cookie_value($cookies, "ds_user_id");
            $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
            $curl_str = "curl '$url' ";
            $curl_str .= "-X POST ";
            $curl_str .= "-H 'Cookie: mid=V9WouwAEAAEC24F7E7oIcleD-vkG; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
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

        public function get_insta_followers($cookies, $user, $N, $cursor = NULL) {
            try {
                $curl_str = $this->make_curl_followers_str("'https://www.instagram.com/query/'", $cookies, $user, $N, $cursor);
                //print("<br><br>$curl_str<br><br>");
                exec($curl_str, $output, $status);
                //print_r($output);
                //print("-> $status<br><br>");
                $json = json_decode($output[0]);
                var_dump($output);
                if (isset($json->followed_by) && isset($json->followed_by->page_info)) {
                    $DB = new \dumbu\cls\DB();
                    $DB->update_reference_cursor($this->daily_work->reference_id, $json->followed_by->page_info->end_cursor);
                    $this->daily_work->insta_follower_cursor = $json->followed_by->page_info->end_cursor;
                    if ($json->followed_by->page_info->end_cursor == '') {
                        var_dump(json_encode($json));
                        echo ("END Cursor empty!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                    }
                }
                return $json;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function make_curl_followers_str($url, $cookies, $user, $N, $cursor = NULL) {
            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
            $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
            $curl_str = "curl '$url' ";
            // TODO: automatizate mid
            $curl_str .= "-H 'Cookie: mid=V9WouwAEAAEC24F7E7oIcleD-vkG; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$user' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            if ($cursor === NULL || $cursor === '') {
//                $curl_str .= "--data "
//                        . "'q=ig_user($user)+%7B%0A++followed_by.first($N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list&query_id=17851938028087704' ";
                $curl_str .= "--data "
                        . "'q=ig_user($user)+%7B%0A++followed_by.first($N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list' ";
            } else {
//                $curl_str .= "--data "
//                        . "'q=ig_user($user)+%7B%0A++followed_by.after($cursor, $N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list&query_id=17851938028087704' ";
                $curl_str .= "--data "
                        . "'q=ig_user($user)+%7B%0A++followed_by.after($cursor, $N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list' ";
//                "page_info": {"has_previous_page": true, "start_cursor": "AQCofdJPzGRljplmFndRzUK17kcV3cD2clwRHYSHInAWcmxn5fhtZVGZyHs1pLUafOMOw8SYZnM4UB-4WO8vM9oTjdAuvL14DmH87kZDJE2kmaW_sA-K6_yqP6pzsyC-6RE", "end_cursor": "AQDsGU9PY7SKUFVzb4g-9hUAqmN3AVn7WKa38BTEayApyPavBw6RqRriVD46_LamE1GllxTSdsFsbD3IQ7C5aEx2n7rRIaIegPoTWxPZg34SWMwLxJfI5I6ivcZ0wOZg7a4", "has_next_page": true}
            }
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
            print_r("-> $status<br><br>");
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
            echo "data posted....! <br>";
        }

        public function bot_login($login, $pass) {
            $IUser = $login;
            $IPass = $pass;
            //$Ref_Pprof = $this->Ref_profile->insta_name;
//            if ($this->webdriver == NULL) {
            $this->webdriver = new \WebDriver("localhost", "4444");
            $this->webdriver->connect("firefox");
            //$this->webdriver->windowMaximize();
            $this->webdriver->get("https://www.instagram.com/accounts/login/");
            sleep(2);

            $username = $this->webdriver->findElementBy(\LocatorStrategy::name, "username");
            $password = $this->webdriver->findElementBy(\LocatorStrategy::name, "password");
            if ($username) {
                $username->sendKeys(array($IUser));
                $password->sendKeys(array($IPass));
                $username->submit();
                sleep(1.5);
                echo "<br>-------Fazendo login no Instagram do user " . $IUser . "------------<br><br";

                //sleep(2);
                //$cookies = $webdriver->getAllCookies();
                // Get data for Reference User
//                $reference_user_name = $Ref_Pprof->name;
                //$webdriver->get("https://www.instagram.com/$reference_user_name/");
                //sleep(2);
                //$reference_user = $this->get_reference_user($cookies, $reference_user_name);
                // Get insta follower for reference user
                //echo "-------Obtendo os " . $N . " seguidores do prerfil " . $Ref_Pprof->insta_name . "------------<br><br>";
                //$response = $this->get_insta_followers($cookies, $Ref_Pprof->insta_id, $N);
                // Follow $N user not followed before
                //echo "-------Comecando a fazer a requisicao de follow------------<br><br>";
                //$this->follow_not_followed($cookies, $response->followed_by->nodes, $N);
                //make_insta_follow($cookies, "3491366569");
                //$webdriver->close();
            }
//            }
        }

//  end of member function bot_login

        public function get_reference_user($cookies, $reference_user_name) {
            echo "-------Obtindo dados de perfil de referencia------------<br><br";
            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
            $ds_user_id = $this->obtine_cookie_value($cookies, "ds_user_id");
            $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
            $url = "https://www.instagram.com/$reference_user_name'/?__a=1'";
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, sdch' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Cookie: mid=V9WouwAEAAEC24F7E7oIcleD-vkG; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "--compressed ";
            exec($curl_str, $output, $status);
            return json_decode($output[0]);
        }

        public function bot_logout() {
            $this->webdriver->close();
        }
    }

    // end of Robot
}

?>
