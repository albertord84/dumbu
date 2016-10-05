<?php

namespace dumbu\cls {
    require_once 'system_config.php';
    require_once 'Day_client_work.php';
    require_once 'Reference_profile.php';
    
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
        protected $robots;

        /**
         * 
         * @access protected
         */
        protected $config;

        /**
         * 
         * @access protected
         */
        protected $Day_client_work;

        /**
         * 
         * @access protected
         */
        protected $work_queue;

        /**
         * 
         * @access protected
         */
        protected $dir;

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
        public function send_foollow_unfollow_work($Client, $Pref_profile) {
            
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
            
        }

// end of member function have_work
    }

    // end of Wroker
}

?>
