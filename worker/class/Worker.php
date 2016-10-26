<?php

namespace dumbu\cls {
    require_once 'DB.php';
    require_once 'Day_client_work.php';
    require_once 'Reference_profile.php';
    require_once 'Client.php';
    require_once 'Robot.php';

    /**
     * class Wroker
     * 
     */
    class Worker {
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
        public $robots;

        /**
         * 
         * @access public
         */
        public $config;

        /**
         * Day_client_work queue
         * @access public
         */
        public $work_queue = array();

        /**
         * 
         * @access public
         */
        public $dir;

        /**
         *
         * @var type 
         */
        public $Robot;

        public function __construct() {
            $this->Robot = new Robot();
            $this->Robot->config = $GLOBALS['sistem_config'];
        }

        /**
         * 
         *
         * @return system_config
         * @access public
         */
        public function get_worker_config() {
            
        }

        function prepare_daily_work() {
            // Get Users Info
            $Clients = \dumbu\cls\Client::get_clients();
            $DB = new \dumbu\cls\DB();
            $Client = new \dumbu\cls\Client();
            foreach ($Clients as $Client) { // for each CLient
                // Log user with webdriver in istagram to get needed session data
                $login_data = $this->Robot->bot_login($Client->login, $Client->pass);
                if ($login_data && $login_data->json_response->authenticated) {
                    echo "<br>Autenticated Client: $Client->login <br><br>";
                    // Distribute work between clients
                    $to_follow = $GLOBALS['sistem_config']::DIALY_REQUESTS_BY_CLIENT / count($Client->reference_profiles);
                    foreach ($Client->reference_profiles as $Ref_Prof) { // For each reference profile
                        //$Ref_prof_data = $this->Robot->get_insta_ref_prof_data($Ref_Prof->insta_name);
                        $DB->insert_daily_work($Ref_Prof->id, $to_follow, json_encode($login_data));
                    }
                }
                else {
                    // TODO: do something in Client autentication error
                }
            }
            die("Loged all Clients");
            //
            //$DB->reset_preference_profile_cursors();
        }

// end of member function prepare_daily_work

        /**
         * 
         *
         * @return void
         * @access public
         */
        public function request_follow_unfollow_work() {
            
        }

// end of member function request_follow_unfollow_work

        /**
         * 
         *
         * @param Day_client_work Client 

         * @param Reference_profile Pref_profile 

         * @return void
         * @access public
         */
        public function do_follow_unfollow_work($daily_work) {
            if ($daily_work) {
                // Get new follows
                $DB = new \dumbu\cls\DB();
                $unfollow_work = $DB->get_unfollow_work($daily_work->client_id);
                $Followeds_to_unfollow = array();
                while ($Followed = $unfollow_work->fetch_object()) { //
                    $To_Unfollow = new \dumbu\cls\Followed();
                    // Update Ref Prof Data
                    $To_Unfollow->id = $Followed->id;
                    $To_Unfollow->followed_id = $Followed->followed_id;
                    array_push($Followeds_to_unfollow, $To_Unfollow);
                }
                // Do the FOLLOW work
                $Ref_profile_follows = $this->Robot->do_follow_unfollow_work($Followeds_to_unfollow, $daily_work);
                $this->save_follow_unfollow_work($Followeds_to_unfollow, $Ref_profile_follows, $daily_work);
                $DB->update_daily_work($daily_work->reference_id, count($Ref_profile_follows));
                return TRUE;
            }
            return FALSE;
        }

        function save_follow_unfollow_work($Followeds_to_unfollow, $Ref_profile_follows, $daily_work) {
            try {
                $DB = new \dumbu\cls\DB();
                $DB->save_unfollow_work($Followeds_to_unfollow);
                $DB->save_follow_work($Ref_profile_follows, $daily_work);
                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
                return FALSE;
            }
        }

// end of member function send_foollow_unfollow_work

        /**
         * 
         *
         * @param Client Client 

         * @return bool
         * @access public
         */
        public function send_check_insta_user_work($Client) {
            
        }

// end of member function send_check_insta_user_work

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function have_work() {
            //return count($this->work_queue);
        }

// end of member function have_work

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function do_work() {
            try {
                $has_work = TRUE;
                while ($has_work) {
                    $DB = new \dumbu\cls\DB();
                    //daily work: cookies   reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
                    $daily_work = $DB->get_follow_work();
                    if ($daily_work) {
                        $daily_work->login_data = json_decode($daily_work->cookies);
                        $elapsed_time = (time() - strtotime($daily_work->last_access)) / 60 % 60; // minutes
                        if ($elapsed_time < $GLOBALS['sistem_config']::MIN_NEXT_ATTEND_TIME) {
                            sleep(($GLOBALS['sistem_config']::MIN_NEXT_ATTEND_TIME - $elapsed_time) * 60); // secounds
                        }
                        $this->do_follow_unfollow_work($daily_work);
                    } else {
                        $has_work = FALSE;
                    }
                }
                echo "<br><br>Congratulations!!! Job done...<br>";
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        function delete_daily_work() {
            $DB = new \dumbu\cls\DB();
            $DB->delete_daily_work();
        }

//
//        function __set($name, $value) {
//            if (method_exists($this, $name)) {
//                $this->$name($value);
//            } else {
//                // Getter/Setter not defined so set as property of object
//                $this->$name = $value;
//            }
//        }
//
//        function __get($name) {
//            if (method_exists($this, $name)) {
//                return $this->$name();
//            } elseif (property_exists($this, $name)) {
//                // Getter/Setter not defined so return property if it exists
//                return $this->$name;
//            }
//            return null;
//        }
        // end of generic setter an getter definition
    }

    // end of Wroker
//        /**
//         * 
//         *
//         * @return void
//         * @access public
//         */
//        public function request_follow_unfollow_work() {
//            // Connect to BD
//            // Get Users Info
//            $Clients = \dumbu\cls\Client::get_clients();
//            foreach ($Clients as $Client) {
//                $DCW = new \dumbu\cls\Day_client_work();
//                $DCW->Client = $Client;
//                // Get profiles to unfollow today
//                $DCW->get_unfollow_work($DCW->Client->id);
////                for ($i = 0; $i < count($DCW->Client->reference_profiles); $i++) {
////                    array_push($DCW->Ref_profile_follows, 0);  // Follows by this Client today
////                }
//                // Make a Queue of ussers
//                array_push($this->work_queue, $DCW);             // Queue this Client to Work with later
//            }
//        }
}

?>
