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
        public $reference_profiles;

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
            echo("Do Client sign_in!!! <br>");
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
        public function get_reference_profiles() {
            
        }

// end of member function check_insta_user
        
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

    // end of Client
}
?>
