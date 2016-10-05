<?php

namespace dumbu\cls {
    const MAX_TIME = 7;

    /**
     * class User
     * 
     */
    class User {
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * 
         * @access protected
         */
        protected $id;

        /**
         * 
         * @access protected
         */
        protected $name;

        /**
         * 
         * @access protected
         */
        protected $login;

        /**
         * 
         * @access protected
         */
        protected $pass;

        /**
         * 
         * @access protected
         */
        protected $email;

        /**
         * 
         * @access protected
         */
        protected $telf;

        /**
         * 
         * @access protected
         */
        protected $role_id;

        /**
         * 
         * @access protected
         */
        protected $status_id;

        /**
         * 
         * @access protected
         */
        protected $languaje;

        /**
         * 
         */
        function __construct() {
            print "User constructor inited...  <br>";
        }

        /**
         * 
         *
         * @return unsigned short
         * @access public
         */
        public function do_login() {
            echo("Do User login!!! <br>");
        }

// end of member function do_login

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function update_user() {
            
        }

// end of member function update_user

        /**
         * 
         *
         * @param serial user_id 

         * @return User
         * @access public
         */
        public function load_user($user_id = 0) {
            
        }

// end of member function load_user

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function disable_account() {
            
        }

// end of member function disable_account
    }

    // end of User
}
?>
