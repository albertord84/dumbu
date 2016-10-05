<?php

namespace dumbu\cls {

    /**
     * class User
     * 
     */
    class User {
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * Variable defined as setter and getter reference example,
         * study carefully:
         * If function with same variable name is defined, the magic getter 
         * and setter will called without (resp. with) the $value param, 
         * so it function can determine if should do a get or o set..
         * 
         * @access private
         */
        protected $id;       
        protected function id($value = NULL) {
            if (isset($value)) {
                $this->id = $value;
            }
            else {
                return $this->id;
            }
        }

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

    // end of User
}
?>
