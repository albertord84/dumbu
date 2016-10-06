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
            $this->bot_login();
            $Profile = new Profile();
            for ($i = 0; $i < $this->config->REQUESTS_AT_SAME_TIME; $i++) {
                $Profile = array_shift($this->Day_client_work->unfollow_data);
                $json_reslt = $this->make_insta_friendships_command(
                        $this->$webdriver->getAllCookies(),
                        $Profile->insta_id,
                        'unfollow');
                if ($json_reslt && $json_reslt.status == 'ok') {
                    // Do something
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
            $json_reslt = json_decode($output[0]);
            if ($json_reslt) {
                print_r($json_reslt->result);
            } else {
                print_r($output[0]);
            }
            //print_r($status);
            echo '<br>';
            //print("-> $status<br><br>");
            return $json_reslt;
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
