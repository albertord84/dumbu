<?php

namespace dumbu\cls {
    require_once 'Reference_profile.php';

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
         * @access protected
         */
        protected $id;

        /**
         * 
         * @access protected
         */
        protected $IP;

        /**
         * 
         * @access protected
         */
        protected $dir;

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
        public function do_follow_unfollow_work($Client, $Ref_profile) {
            
        }

// end of member function do_follow_unfollow_work
        
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
