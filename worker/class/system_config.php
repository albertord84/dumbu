<?php

namespace dumbu\cls {

    class system_config {

        const DIALY_REQUESTS_BY_CLIENT = 480; // Change to 480 in production mode
        const REQUESTS_AT_SAME_TIME = 10;     // Reference Profile Followers amoun by request. Change to 10  in production mode   
        const DELAY_BETWEEN_REQUESTS = 0; 
        const MIN_NEXT_ATTEND_TIME = 5; //1000 * 60; //5 * 1000 * 60; // 5 min
        const REFERENCE_PROFILE_AMOUNT = 3; // By Client
        const UNFOLLOW_ELAPSED_TIME_LIMIT = 24; // 48; // In hours
        const MAX_GET_FOLLOWERS_REQUESTS = 5; // Max of get followers request to complete REQUESTS_AT_SAME_TIME for a client work
        const MAX_CLIENT_FAUTL_TRIES = 3; // Quantity max of failures with this client

        static public function Defines($const) {
            $cls = new ReflectionClass(__CLASS__);
            foreach ($cls->getConstants() as $key => $value) {
                if ($value == $const) {
                    return true;
                }
            }

            return false;
        }

    }

}