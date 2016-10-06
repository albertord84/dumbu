<?php

namespace dumbu\cls {
    require_once 'Reference_profile.php';
    require_once "../libraries/webdriver/phpwebdriver/WebDriver.php";

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
        public $Day_client_work;

        /**
         * 
         * @access public
         */
        public $Ref_profile;

        /**
         * 
         * @access public
         */
        public $webdriver;

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
         * @param Client Client 

         * @param Reference_profile Ref_profile 

         * @return void
         * @access public
         */
        public function do_follow_unfollow_work($Day_client_work, $Ref_profile) {
            $this->Day_client_work = $Day_client_work;
            $this->Ref_profile = $Ref_profile;
            // Log user with webdriver in istagram
            $this->bot_login();
            // Unfollow same profiles quantity that we will follow
            $Profile = new Profile();
            $cookies = $this->$webdriver->getAllCookies();
            for ($i = 0; $i < $this->config->REQUESTS_AT_SAME_TIME; $i++) {
                // Next profile to unfollow, not yet unfollwed
                $Profile = array_shift($this->Day_client_work->unfollow_data);
                $json_response = $this->make_insta_friendships_command(
                        $cookies, $Profile->insta_id, 'unfollow'
                );
                if (is_object($json_response) && $json_response->status == 'ok') { // if unfollowed 
                    // Mark it unfollowed and send back to queue
                    $Profile->unfollowed = TRUE;
                    array_push($this->Day_client_work->unfollow_data, $Profile);
                } else {
                    throw new Exception($json_response, 1003);
                }
            }
            // Do follow work
            while ($follows < $this->config->REQUESTS_AT_SAME_TIME) {
                // Get next insta followers of Ref_profile
                $json_response = $this->get_insta_followers(
                        $cookies, $Profile->insta_id, $this->config->REQUESTS_AT_SAME_TIME, $this->Ref_profile->$insta_follower_cursor
                );
                if (is_object($json_response) && $json_response->status == 'ok') { // if response is ok
                    // Get Users 
                    $Users = $json_response->followed_by->nodes;
                    foreach ($Users as $User) {
                        if (!$User->requested_by_viewer && !$User->followed_by_viewer) { // If user not requested or follwed by Client
                            // Do follow request
                            $json_response = $this->make_insta_friendships_command($cookies, $User->id, 'follow');
                            if (is_object($json_response) && $json_response->status == 'ok') { // if response is ok
                                array_push($this->Day_client_work->follow_data, $User);
                            } else {
                                throw new Exception($json_response, 1001);
                            }
                            //echo "<br><br><br>O .seguidor " . $User->username . " foi requisitado. Resultado: ";
                            // Sleep up to proper delay between request
                            sleep($this->config->DELAY_BETWEEN_REQUESTS);
                        }
                    }
                } else {
                    throw new Exception($json_response, 1002);
                }
            }
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
            $json_response = json_decode($output[0]);
            if ($json_response) {
                print_r($json_response->result);
            } else {
                print_r($output[0]);
            }
            //print_r($status);
            echo '<br>';
            //print("-> $status<br><br>");
            return $json_response;
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
            $curl_str = $this->make_curl_followers_str("'https://www.instagram.com/query/'", $cookies, $user, $N, $cursor);
            //print("<br><br>$curl_str<br><br>");
            exec($curl_str, $output, $status);
            //print_r($output);
            //print("-> $status<br><br>");
            $json = json_decode($output[0]);
            return $json;
        }

        public function make_curl_followers_str($url, $cookies, $user, $N, $cursor = NULL) {
            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
            $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
            $curl_str = "curl '$url' ";
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
            if ($cursor === NULL) {
                $curl_str .= "--data 'q=ig_user($user)+%7B%0A++followed_by.first($N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list' ";
            } else {
                $curl_str .= "--data 'q=ig_user($user)+%7B%0A++followed_by.after($cursor, $N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list' ";
                //"page_info": {"has_previous_page": true, "start_cursor": "AQCofdJPzGRljplmFndRzUK17kcV3cD2clwRHYSHInAWcmxn5fhtZVGZyHs1pLUafOMOw8SYZnM4UB-4WO8vM9oTjdAuvL14DmH87kZDJE2kmaW_sA-K6_yqP6pzsyC-6RE", "end_cursor": "AQDsGU9PY7SKUFVzb4g-9hUAqmN3AVn7WKa38BTEayApyPavBw6RqRriVD46_LamE1GllxTSdsFsbD3IQ7C5aEx2n7rRIaIegPoTWxPZg34SWMwLxJfI5I6ivcZ0wOZg7a4", "has_next_page": true}
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

        public function bot_login() {
            $IUser = $this->Day_client_work->Client->login;
            $IPass = $this->Day_client_work->Client->pass;
            $Perfil = $this->Ref_profile->insta_name;
            $Q = $_POST["Num"];

            echo "La cantidad es " . $Q . "<BR><BR>";

            $N = (int) $Q;

            $webdriver = new WebDriver("localhost", "4444");
            $webdriver->connect("firefox");
            $webdriver->windowMaximize();
            $webdriver->get("https://www.instagram.com/accounts/login/");
            sleep(1);

            $username = $webdriver->findElementBy(LocatorStrategy::name, "username");
            $password = $webdriver->findElementBy(LocatorStrategy::name, "password");
            if ($username) {
                $username->sendKeys(array($IUser));
                $password->sendKeys(array($IPass));
                $username->submit();
                echo "-------Fazendo login no Instagram do user " . $IUser . "------------<br><br";

                sleep(2);
                $cookies = $webdriver->getAllCookies();

                // Get data for Reference User
                $reference_user_name = $Perfil;
                $webdriver->get("https://www.instagram.com/$reference_user_name/");
                sleep(2);
                $reference_user = $this->get_reference_user($cookies, $reference_user_name);

                // Get insta follower for reference user
                echo "-------Obtendo os " . $N . " seguidores do prerfil " . $reference_user_name . "------------<br><br>";
                $response = $this->get_insta_followers($cookies, $reference_user->user->id, $N);


                // Follow $N user not followed before
                echo "-------Comecando a fazer a requisicao de follow------------<br><br>";
                $this->follow_not_followed($cookies, $response->followed_by->nodes, $N);

                //make_insta_follow($cookies, "3491366569");
                //$webdriver->close();
            }
        }

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

//  end of member function bot_login


        public function bot_logout() {
            $webdriver->close();
        }

        function __set($name, $value) {
            if (method_exists($this, $name)) {
                $this->$name($value);
            } else {
                // Getter/Setter not defined so set as property of object
                $this->$name = $value;
            }
        }

        function __get($name) {
            if (method_exists($this, $name)) {
                return $this->$name();
            } elseif (property_exists($this, $name)) {
                // Getter/Setter not defined so return property if it exists
                return $this->$name;
            }
            return null;
        }

        // end of generic setter an getter definition
    }

    // end of Robot
}

?>
