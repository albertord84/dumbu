<?php

//require_once 'Reference_profile[].php';

namespace dumbu\cls {
    require_once 'User.php';
    require_once 'DB.php';

    /**
     * class Client
     * 
     */
    class Client extends User {
        /** Aggregations: */
        /** Compositions: */
        /** Attributes: */

        /**
         * 
         * @access public
         */
        public $credit_card_number;

        /**
         * 
         * @access public
         */
        public $credit_card_status_id;

        /**
         * 
         * @access public
         */
        public $credit_card_cvc;

        /**
         * 
         * @access public
         */
        public $credit_card_name;

        /**
         * 
         * @access public
         */
        public $pay_day;

        /**
         * 
         * @access public
         */
        public $insta_id;

        /**
         * 
         * @access public
         */
        public $insta_followers_ini;

        /**
         * 
         * @access public
         */
        public $insta_following;

        /**
         * 
         * @access public
         */
        public $HTTP_SERVER_VARS;

        /**
         * 
         * @access public
         */
        public $cookies;

        /**
         * 
         * @access public
         */
        public $reference_profiles = array();

        public function get_clients() {
            try {
                $Clients = array();
                $DB = new \dumbu\cls\DB();
                $clients_data = $DB->get_clients_data();
                while ($client_data = $clients_data->fetch_object()) {
                    $Client = $this->fill_client_data($client_data);
                    array_push($Clients, $Client);
                }
                return $Clients;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function fill_client_data($client_data) {
            $Client = NULL;
            if ($client_data) {
                $Client = new Client();
                //print_r($client_data);
                // Update Client Data
                $Client->id = $client_data->id;
                $Client->name = $client_data->name;
                $Client->login = $client_data->login;
                $Client->pass = $client_data->pass;
                $Client->email = $client_data->email;
                $Client->insta_id = $client_data->insta_id;
                $Client->status_id = $client_data->status_id;
                $Client->insta_following = $client_data->insta_following;
                $Client->cookies = $client_data->cookies;
                $Client->get_reference_profiles($Client->id);
            }
            return $Client;
        }

        public function get_client($client_id) {
            try {
                $DB = new DB();
                $client_data = $DB->get_client_data($client_id);
                $Client = $this->fill_client_data($client_data);
                return $Client;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function create_daily_work($client_id) {
            $DB = new DB();
            $Client = $this->get_client($client_id);
            if (count($Client->reference_profiles) > 0) {
                $to_follow_unfollow = $GLOBALS['sistem_config']::DIALY_REQUESTS_BY_CLIENT / count($Client->reference_profiles);
                // If User status = UNFOLLOW he do 0 follows
                $to_follow = $Client->status_id != user_status::UNFOLLOW ? $to_follow_unfollow : 0;
                $to_unfollow = $to_follow_unfollow;
                foreach ($Client->reference_profiles as $Ref_Prof) { // For each reference profile
//$Ref_prof_data = $this->Robot->get_insta_ref_prof_data($Ref_Prof->insta_name);
                    $DB->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, $Client->cookies);
                }
            } else {
                echo "Not reference profiles: $Client->login <br>\n<br>\n";
            }
        }

        public function delete_daily_work($client_id) {
            $DB = new DB();
            $DB->delete_daily_work_client($client_id);
        }

        /**
         * 
         */
        function __construct() {
            parent::__construct();
        }

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function sign_in() {
            echo("Do Client sign_in!!! <br>\n");
        }

// end of member function sign_in

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function check_insta_user() {
            
        }

        public function set_client_status($client_id, $status_id) {
            try {
                $client_id = $client_id ? $client_id : $this->id;
                $status_id = $status_id ? $status_id : $this->status_id;
                $DB = new \dumbu\cls\DB();
                $result = $DB->set_client_status($client_id, $status_id);
                if ($result) {
                    print "Client $client_id to status $status_id!!!";
                } else {
                    print "FAIL CHANGING Client $client_id to status $status_id!!!";
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

// end of member function sign_in

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function get_reference_profiles($client_id = NULL) {
            try {
                $client_id = $client_id ? $client_id : $this->id;
                $DB = new \dumbu\cls\DB();
                $ref_profs_data = $DB->get_reference_profiles_data($client_id);
                while ($prof_data = $ref_profs_data->fetch_object()) {
                    $Ref_Prof = new \dumbu\cls\Reference_profile();
                    //print_r($prof_data);
                    // Update Ref Prof Data if not privated
                    if ($Ref_Prof->is_private($prof_data->insta_name) === FALSE) {
                        $Ref_Prof->id = $prof_data->id;
                        $Ref_Prof->insta_id = $prof_data->insta_id;
                        $Ref_Prof->insta_name = $prof_data->insta_name;
                        $Ref_Prof->insta_follower_cursor = $prof_data->insta_follower_cursor;
                        array_push($this->reference_profiles, $Ref_Prof);
                    }
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

    }

    // end of Client
}
?>
