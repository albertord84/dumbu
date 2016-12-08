<?php

namespace dumbu\cls {

    /**
     * class Profile
     * 
     */
    class Profile {
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
        public $insta_id;

        /**
         * 
         * @access public
         */
        public $insta_name;

        /**
         * 
         * @access public
         */
        public $unfollowed;

        public function get_insta_ref_prof_data($ref_prof) {
            $Robot = new Robot();
            return $Robot->get_insta_ref_prof_data($ref_prof);
        }

        public function is_private($ref_prof) {
            $ref_prof_data = $this->get_insta_ref_prof_data($ref_prof);
            return $ref_prof_data ? $ref_prof_data->is_private : NULL;
        }

        /**
         * Code or FALSE if error occur
         * @param type $response
         * @return boolean
         */
        public function parse_profile_follow_errors($response) {
            $error = FALSE;
            if (is_object($response) && isset($response->message)) {
                if (strpos($response->message, 'Com base no uso anterior deste recurso, sua conta foi impedida temporariamente de executar essa ação.') !== FALSE) {
                    $error = 1;
                } else if (strpos($response->message, 'Você atingiu o limite máximo de contas para seguir.') !== FALSE) {
                    $error = 2;
                } else if (strpos($response->message, 'unauthorized') !== FALSE) {
                    $error = 3;
                } else if (strpos($response->message, 'Parece que você estava usando este recurso de forma indevida') !== FALSE) {
                    $error = 4;
                }
            } else {
                var_dump($response);
                print 'Not error found!';
            }
            return $error;
        }

//
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

    // end of Profile
}

?>
