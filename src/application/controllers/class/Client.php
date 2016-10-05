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
         * @access protected
         */
        protected $credit_card_number;

        /**
         * 
         * @access protected
         */
        protected $credit_card_status_id;

        /**
         * 
         * @access protected
         */
        protected $credit_card_cvc;

        /**
         * 
         * @access protected
         */
        protected $credit_card_name;

        /**
         * 
         * @access protected
         */
        protected $pay_day;

        /**
         * 
         * @access protected
         */
        protected $insta_id;

        /**
         * 
         * @access protected
         */
        protected $insta_followers_ini;

        /**
         * 
         * @access protected
         */
        protected $insta_following;

        /**
         * 
         * @access protected
         */
        protected $reference_profiles;

        /**
         * 
         */
        function __construct() {
            parent::__construct();
            print "Client constructor inited...  <br>";
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

// end of member function check_insta_user
    }

    // end of Client
}
?>
