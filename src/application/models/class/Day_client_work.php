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
         * @access protected
         */
        protected $Client;

        /**
         * 
         * @access protected
         */
        protected $Ref_profile_follows;

        /**
         * 
         *
         * @param system_config config 

         * @return bool
         * @access public
         */
        public function is_work_done($config) {
            
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
