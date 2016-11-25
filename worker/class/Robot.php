<?php

namespace dumbu\cls {
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
            $login_data = $this->daily_work->login_data;
            // Unfollow same profiles quantity that we will follow
            $Profile = new Profile();
            // Do unfollow work
            $has_next = count($Followeds_to_unfollow) && !$Followeds_to_unfollow[0]->unfollowed;
            echo "<br>\n<br>\n<br>\nRef Profil: $daily_work->insta_name<br>\n" . " Count: " . count($Followeds_to_unfollow) . " Hasnext: " . $has_next;
            echo date("Y-m-d h:i:sa");
            echo "<br>\n make_insta_friendships_command UNFOLLOW <br>\n";
            for ($i = 0; $i < $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME && ($has_next); $i++) {
                // Next profile to unfollow, not yet unfollwed
                $Profile = array_shift($Followeds_to_unfollow);
                $json_response = $this->make_insta_friendships_command(
                        $login_data, $Profile->followed_id, 'unfollow'
                );
                if (is_object($json_response) && $json_response->status == 'ok') { // if unfollowed 
                    var_dump(json_encode($json_response));
                    echo "Followed ID: $Profile->followed_id<br>\n";
                    // Mark it unfollowed and send back to queue
                    $Profile->unfollowed = TRUE;
                    array_push($Followeds_to_unfollow, $Profile);
                    // If have some Profile to unfollow
                    $has_next = !$Followeds_to_unfollow[0]->unfollowed;
                } else {
                    var_dump($json_response);
                    if (is_array($json_response) && count($json_response)) {
                        break;
                    }
                    //var_dump($json_response);
                    //break;
                    // TODO: if response is "Há solicitações demais. Tente novamente mais tarde." then
                    // stop this user work flow...
//                    throw new \Exception(json_encode($json_response), 1003);
                }
            }
            // Do follow work
            //daily work: cookies   reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
            $Ref_profile_follows = array();
            $follows = 0;
            if ($daily_work->to_follow > 0) { // If has to follow
                echo "<br>\nmake_insta_friendships_command FOLLOW <br>\n";
                $get_followers_count = 0;
                while ($follows < $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME && $get_followers_count < $GLOBALS['sistem_config']::MAX_GET_FOLLOWERS_REQUESTS) {
                    // Get next insta followers of Ref_profile
                    $quantity = min(array($daily_work->to_follow, $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME));
                    $json_response = $this->get_insta_followers(
                            $login_data, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor
                    );
                    //var_dump($json_response);
                    echo "<br>\nRef Profil: $daily_work->insta_name     ------>   End Cursor: $daily_work->insta_follower_cursor<br>\n";
                    $get_followers_count++;
                    if (is_object($json_response) && $json_response->status == 'ok' && isset($json_response->followed_by->nodes)) { // if response is ok
                        // Get Users 
                        $Profiles = $json_response->followed_by->nodes;
                        foreach ($Profiles as $Profile) {
                            if (!$Profile->requested_by_viewer && !$Profile->followed_by_viewer) { // If user not requested or follwed by Client
                                // Do follow request
                                $json_response = $this->make_insta_friendships_command($login_data, $Profile->id, 'follow');
                                var_dump($json_response);
                                echo "Profil name: $Profile->username<br>\n";
                                if (is_object($json_response) && $json_response->status == 'ok') { // if response is ok
                                    array_push($Ref_profile_follows, $Profile);
                                    $follows++;
                                    if ($follows >= $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME)
                                        break;
                                } else {
                                    var_dump($json_response);
                                    break;
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
            }
            //$this->webdriver->close();
            return $Ref_profile_follows;
        }

// end of member function do_follow_unfollow_work

        /**
         * Friendships API commands, normally used to 'follow' and 'unfollow'.
         * @param type $login_data
         * @param type $user_id
         * @param type $command {follow, unfollow, ... }
         * @return type
         */
        public function make_insta_friendships_command($login_data, $user_id, $command = 'follow') {
            $curl_str = $this->make_curl_follow_str("'https://www.instagram.com/web/friendships/$user_id/$command/'", $login_data);
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

        public function make_curl_follow_str($url, $login_data) {
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

        // TODO: Remove if not use
//        public function make_curl_friendships_str($url, $cookies) {
//            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
//            $ds_user_id = $this->obtine_cookie_value($cookies, "ds_user_id");
//            $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
//            $curl_str = "curl '$url' ";
//            $curl_str .= "-X POST ";
//            $curl_str .= "-H 'Cookie: mid=V9WouwAEAAEC24F7E7oIcleD-vkG; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
//            $curl_str .= "-H 'Host: www.instagram.com' ";
//            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
//            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
//            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
//            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
//            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
//            $curl_str .= "-H 'X-Instagram-Ajax: 1' ";
//            $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
//            $curl_str .= "-H 'Accept: */*' ";
//            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
//            $curl_str .= "-H 'Authority: www.instagram.com' ";
//            $curl_str .= "-H 'Content-Length: 0' ";
//            $curl_str .= "--compressed ";
//            return $curl_str;
//        }

        public function get_insta_followers($login_data, $user, $N, $cursor = NULL) {
            try {
                $curl_str = $this->make_curl_followers_str("'https://www.instagram.com/query/'", $login_data, $user, $N, $cursor);
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

        public function make_curl_followers_str($url, $login_data, $user, $N, $cursor = NULL) {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            $curl_str = "curl '$url' ";
            // TODO: automatizate mid
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
//            }
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
//curl_setopt($ch, CURLOPT_URL, $url);
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
//var_dump($cookies);
//TODO: recursive function to load cookie variavels properly
//            $cur_info = curl_getinfo($ch);
            //echo $cookies[1][1];
            $csrftoken = explode("=", $cookies[1][1]);
            $csrftoken = $csrftoken[1];
//            var_dump($csrftoken);
            return $csrftoken;

//var_dump($cur_info);
//if (curl_errno($ch)) die(curl_error($ch));
//$dom = new DomDocument();
//$dom->loadHTML($response);
//print_r($dom);
        }

        public function login_insta_with_csrftoken($ch, $login, $pass, $csrftoken, $Client = NULL) {
            $postinfo = "username=$login&password=$pass";

            $headers = array();
            $headers[] = "Host: www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
            $headers[] = "Accept: application/json";
            $headers[] = "Accept-Language: en-US,en;q=0.5, ";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
//$headers[] = "--compressed ";
            $headers[] = "Referer: https://www.instagram.com/";
            $headers[] = "X-CSRFToken: $csrftoken";
            $headers[] = "X-Instagram-AJAX: 1";

            $ip = $_SERVER['REMOTE_ADDR'];
            if ($Client != NULL && $Client->HTTP_SERVER_VARS != NULL) { // if 
                $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);              
                $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
            }
            $headers[] = "REMOTE_ADDR: $ip";
            $headers[] = "HTTP_X_FORWARDED_FOR: $ip";
            
//$headers[] = "Content-Type: application/x-www-form-urlencoded";
            $headers[] = "Content-Type: application/json";
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
//            var_dump($cookies);
            $html = curl_exec($ch);
            $info = curl_getinfo($ch);
            //var_dump($html);

            $start = strpos($html, "{");
//            var_dump($start);
            $json_str = substr($html, $start);
//            var_dump($json_str);
            $json_response = json_decode($json_str);
            $login_data = new \stdClass();
            $login_data->json_response = $json_response;
//            var_dump($json);
//            print_r($info);
//            echo "<br><br>";
//            var_dump($html);
            if (curl_errno($ch)) {
                //print curl_error($ch);
            } else if (count($cookies) >= 5) {
                $login_data->csrftoken = $csrftoken;
                // Get sessionid from cookies
                $sessionid = explode("=", $cookies[1][1]);
                $sessionid = $sessionid[1];
                $login_data->sessionid = $sessionid;
                // Get ds_user_id from cookies
                $ds_user_id = explode("=", $cookies[2][1]);
                $ds_user_id = $ds_user_id[1];
                $login_data->ds_user_id = $ds_user_id;
                // Get mid from cookies
                $mid = explode("=", $cookies[4][1]);
                $mid = $mid[1];
                $login_data->mid = $mid;
            }
            curl_close($ch);
//            var_dump($login_data);
            return $login_data;
        }

        public function get_insta_ref_prof_data($ref_prof) {
            $content = file_get_contents("https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof");
            $users = json_decode($content)->users;

            // Get user with $ref_prof name over all matchs 
            $User = NULL;
            foreach ($users as $key => $user) {
                if ($user->user->username === $ref_prof) {
                    $User = $user->user;
                    $User->following = $this->get_insta_ref_prof_following($ref_prof);
                    break;
                }
            }

            //var_dump($User);
            return $User;
        }

        public function get_insta_ref_prof_following($ref_prof) {
            $content = file_get_contents("https://www.instagram.com/$ref_prof/");

            $doc = new \DOMDocument();
//$doc->loadXML($content);
            $doc->loadHTML($content);
//var_dump($doc);

            $search = "\"follows\": {\"count\": ";
            $start = strpos($doc->textContent, $search);

            $substr1 = substr($doc->textContent, $start, 100);
            $substr2 = substr($substr1, strlen($search), strpos($substr1, "}") - strlen($search));
            return intval($substr2) ? intval($substr2) : NULL;
        }

        public function bot_login($login, $pass, $Client = NULL) {
            $result = NULL;
            $url = "https://www.instagram.com/";
//            $cookie = "/home/albertord/cookies.txt";
            $ch = curl_init($url);
            $this->csrftoken = $this->get_insta_csrftoken($ch);
            if ($this->csrftoken != NULL && $this->csrftoken != "") {
                $result = $this->login_insta_with_csrftoken($ch, $login, $pass, $this->csrftoken, $Client);
            }
            //var_dump($result);
            //die("<br><br>Debug Finish!");
            return $result;
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
            echo "-------Obtindo dados de perfil de referencia------------<br>\n<br>\n";
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

    }

    // end of Robot
}

?>
