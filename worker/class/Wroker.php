<?php

namespace dumbu\cls {
    require_once 'system_config.php';
    require_once 'Day_client_work.php';
    require_once 'Reference_profile.php';
    require_once 'Client.php';
    
    /**
     * class Wroker
     * 
     */
    class Wroker {
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
        public $IP;

        /**
         * 
         * @access public
         */
        public $robots;

        /**
         * 
         * @access public
         */
        public $config;

        /**
         * Day_client_work queue
         * @access public
         */
        public $work_queue;

        /**
         * 
         * @access public
         */
        public $dir;

        /**
         * 
         *
         * @return system_config
         * @access public
         */
        public function get_worker_config() {
            
        }

// end of member function get_worker_config

        /**
         * 
         *
         * @return void
         * @access public
         */
        public function request_follow_unfollow_work() {
            // Connect to BD
            // Get Users Info
            $Clients = $this->get_clients();
            foreach ($Clients as $Client) {
                $DCW = new \dumbu\cls\Day_client_work();
                $DCW->Client = $Client;
                $DCW->Client->get_reference_profiles();
                $DCW->get_unfollow_data();
                array_push($DCW->Ref_profile_follows, 0);  // Follows by this Client today
                array_push($work_queue, $DCW);             // Queue this Client to Work with later
            }
            
            // Make a Queue of ussers
            $fruit = array_shift($stack);
            array_push($stack, "apple", "raspberry");
            
            // Send Queued users to Robots
        }

// end of member function request_follow_unfollow_work

        /**
         * 
         *
         * @param Day_client_work Client 

         * @param Reference_profile Pref_profile 

         * @return void
         * @access public
         */
        public function send_foollow_unfollow_work($Day_client_work, $Ref_profile) {
            $Robot = new Robot();
            $Robot->config = $this->config;
            $Robot->do_follow_unfollow_work($Day_client_work, $Ref_profile);
            $this->save_work($Day_client_work);
        }

// end of member function send_foollow_unfollow_work

        /**
         * 
         *
         * @param Client Client 

         * @return bool
         * @access public
         */
        public function send_check_insta_user_work($Client) {
            
        }

// end of member function send_check_insta_user_work

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function have_work() {
            return count($this->work_queue);
        }

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function do_work() {
            $this->request_follow_unfollow_work();
            $DCW = new Day_client_work();
            while ($this->have_work()) {
                $DCW = array_shift($this->work_queue);
//                $follows = array_shift($DCW->Ref_profile_follows);
                $now = time();
                if ($now - $DCW->last_accesss > $this->config->MIN_NEXT_ATTEND_TIME) {
                    // Get and set back the oldest Preference Profile
                    $Pref_profile = array_shift($DCW->Client->reference_profiles);
                    array_push($DCW->Client->reference_profiles, $Pref_profile);
                    // Send work to Robot
                    $this->send_foollow_unfollow_work($DCW, $Pref_profile);
                    // Update last access
                    $DCW->last_accesss = time();
                    array_push($this->work_queue, $DCW);
                }
            }
        }

// end of member function have_work
        
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

    // end of Wroker
}

?>
