<?php

namespace dumbu\cls {
    require_once 'system_config.php';
    require_once 'Client.php';
    /**
     * class Day_client_work
     * 
     */
    class Day_client_work {
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * 
         * @access public
         */
        public $Client;

        /**
         * 
         * @access public
         */
        public $Ref_profile_follows;

        /**
         * 
         * @access public
         */
        public $unfollow_data;

        /**
         * 
         * @access public
         */
        public $follow_data;

        /**
         * Elapsed time since last access to this $Client
         * @access public
         */
        public $last_accesss;
        
        function __construct() {
            $this->Client = new Client();
        }

        /**
         * 
         *
         * @param system_config config 

         * @return bool
         * @access public
         */
        public function is_work_done($config) {
            
        }
        
        function get_unfollow_data() {
            // Get profiles to unfollow today for this Client... 
            // (i.e the last followed)
            
        }

// end of member function is_work_done
        
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

    // end of Day_client_work
}

?>
