<?php

//require_once 'Reference_profile[].php';

namespace dumbu\cls {
    require_once 'User.php';

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
        public $reference_profiles = array();

        static function get_clients() {
            try {
                $Clients = array();
                $DB = new \dumbu\cls\DB();
                $clients_data = $DB->get_clients_data();
                while ($client_data = $clients_data->fetch_object()) {
                    $Client = new \dumbu\cls\Client();
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
                    $Client->get_reference_profiles($Client->id);
                    array_push($Clients, $Client);
                }
                return $Clients;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
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
                    // Update Ref Prof Data
                    $Ref_Prof->id                    = $prof_data->id;
                    $Ref_Prof->insta_id              = $prof_data->insta_id;
                    $Ref_Prof->insta_name            = $prof_data->insta_name;
                    $Ref_Prof->insta_follower_cursor = $prof_data->insta_follower_cursor;
                    array_push($this->reference_profiles, $Ref_Prof);
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

// end of member function check_insta_user
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

    // end of Client
}
?>
