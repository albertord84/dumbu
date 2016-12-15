<?php

namespace dumbu\cls {
    require_once 'DB.php';
    require_once 'Day_client_work.php';
    require_once 'Reference_profile.php';
    require_once 'Client.php';
    require_once 'Robot.php';
    require_once 'Gmail.php';

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

        /**
         *
         * @var type 
         */
        public $Gmail;

        public function __construct() {
            $this->Robot = new Robot();
            $this->Robot->config = $GLOBALS['sistem_config'];
            $this->Gmail = new Gmail();
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
            $Clients = (new Client())->get_clients();
            $DB = new DB();
            $Client = new Client();
            foreach ($Clients as $Client) { // for each CLient
//                var_dump($Client);
// Log user with webdriver in istagram to get needed session data
                $login_data = $this->Robot->bot_login($Client->login, $Client->pass, $Client);
//                var_dump($Client);
                if (is_object($login_data) && isset($login_data->json_response->authenticated) && $login_data->json_response->authenticated) {
//                    var_dump($Client->login);
                    print("<br>\nAutenticated Client: $Client->login <br>\n<br>\n");
                    $Client->set_client_status($Client->id, user_status::ACTIVE);
// Distribute work between clients
                    $RPWC = $Client->rp_workable_count();
                    if ($RPWC > 0) {
                        $to_follow_unfollow = $GLOBALS['sistem_config']::DIALY_REQUESTS_BY_CLIENT / $RPWC;
                        // If User status = UNFOLLOW he do 0 follows
                        $to_follow = $Client->status_id != user_status::UNFOLLOW ? $to_follow_unfollow : 0;
                        $to_unfollow = $to_follow_unfollow;
                        foreach ($Client->reference_profiles as $Ref_Prof) { // For each reference profile
//$Ref_prof_data = $this->Robot->get_insta_ref_prof_data($Ref_Prof->insta_name);
                            $DB->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, json_encode($login_data));
                        }
                    } else {
                        echo "Not reference profiles: $Client->login <br>\n<br>\n";
                    }
                } else {
// TODO: do something in Client autentication error
                    // Send email to client and dumbu system
                    echo "<br>\n NOT Autenticated Client!!!: $Client->login <br>\n<br>\n";
                    // Chague client status
                    if (isset($login_data->json_response) && $login_data->json_response->status == 'fail' && $login_data->json_response->message == 'checkpoint_required') {
                        $Client->set_client_status($Client->id, user_status::VERIFY_ACCOUNT);
                    }
                    if (isset($login_data->json_response) && $login_data->json_response->status == 'ok' && !$login_data->json_response->authenticated) {
                        $Client->set_client_status($Client->id, user_status::BLOCKED_BY_INSTA);
                    }
                    // Send email to client
                    $now = new DateTime("now");
                    $status_date = new \DateTime();
                    $status_date->setTimestamp($Client->status_date? $Client->status_date : 0);
                    $diff_info = $status_date->diff($now);
                    var_dump($diff_info->days);
                    if ($diff_info->days <= 3) {
                        // TODO, UNCOMMENT
                        //$this->Gmail->send_client_login_error($Client->email, $Client->name, $Client->login, $Client->pass);
                    }
                }
            }
//            die("Loged all Clients");
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
                $unfollow_work = NULL;
                $Followeds_to_unfollow = array();
                if ($daily_work->to_unfollow > 0) {
                    $unfollow_work = $DB->get_unfollow_work($daily_work->client_id);
                    while ($Followed = $unfollow_work->fetch_object()) { //
                        $To_Unfollow = new \dumbu\cls\Followed();
// Update Ref Prof Data
                        $To_Unfollow->id = $Followed->id;
                        $To_Unfollow->followed_id = $Followed->followed_id;
                        array_push($Followeds_to_unfollow, $To_Unfollow);
                    }
                }
// Do the FOLLOW work
                $Ref_profile_follows = $this->Robot->do_follow_unfollow_work($Followeds_to_unfollow, $daily_work);
                $this->save_follow_unfollow_work($Followeds_to_unfollow, $Ref_profile_follows, $daily_work);
// Count unfollows
                $unfollows = 0;
                foreach ($Followeds_to_unfollow as $unfollowed) {
                    if ($unfollowed->unfollowed)
                        $unfollows++;
                }
                // TODO: foults
                $DB->update_daily_work($daily_work->reference_id, count($Ref_profile_follows), $unfollows);
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
                        if ($daily_work->login_data != NULL) {
                            // Calculate time to sleep    
//                        $last_access = DateTime::createFromFormat('U', $daily_work->last_access);
//                        $now = DateTime::createFromFormat('U', time());
//                        $diff_info = $last_access->diff($now);
//                        $elapsed_time = $diff_info->i; // In minutes
                            $elapsed_time = (time() - intval($daily_work->last_access)) / 60 % 60; // minutes
                            if ($elapsed_time < $GLOBALS['sistem_config']::MIN_NEXT_ATTEND_TIME) {
                                sleep(($GLOBALS['sistem_config']::MIN_NEXT_ATTEND_TIME - $elapsed_time) * 60); // secounds
                            }
                            $this->do_follow_unfollow_work($daily_work);
                        } else {
                            print "<br> Login data NULL!!!!!!!!!!!! <br>";
                        }
                    } else {
                        $has_work = FALSE;
                    }
                }
                echo "<br>\n<br>\nCongratulations!!! Job done...<br>\n";
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        function insert_daily_work($Ref_Prof, $to_follow, $to_unfollow, $login_data) {
            $DB = new \dumbu\cls\DB();
            $DB->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, json_encode($login_data));
        }

        function delete_daily_work($ref_prof_id) {
            $DB = new \dumbu\cls\DB();
            $DB->truncate_daily_work($ref_prof_id);
        }

        function truncate_daily_work() {
            $DB = new \dumbu\cls\DB();
            $DB->truncate_daily_work();
        }

    }

}