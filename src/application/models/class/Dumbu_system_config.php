<?php

class dumbu_system_config {

    const DIALY_REQUESTS_BY_CLIENT = 5; // Change to 480 in production mode
    const REQUESTS_AT_SAME_TIME = 10;     // Reference Profile Followers amoun by request. Change to 10  in production mode   
    const DELAY_BETWEEN_REQUESTS = 0;
    const INSTA_MAX_FOLLOWING =7000;    
   
    const MIN_NEXT_ATTEND_TIME = 3; //1000 * 60; //5 * 1000 * 60; // 5 min
    const REFERENCE_PROFILE_AMOUNT = 3; // By Client
    const UNFOLLOW_ELAPSED_TIME_LIMIT = 24; // 48; // In hours
    const MAX_GET_FOLLOWERS_REQUESTS = 5; // Max of get followers request to complete REQUESTS_AT_SAME_TIME for a client work
    const MAX_CLIENT_FAUTL_TRIES = 1; // Quantity max of failures with this client
    CONST MIN_MARGIN_TO_INIT = 1000;  //margen inicial requerido para trabajar con un cliente
    CONST SYSTEM_EMAIL = 'dumbu.system@gmail.com';
    CONST SYSTEM_USER_LOGIN = 'dumbu.system';
    CONST SYSTEM_USER_PASS = 'sorvete69@';
    
    CONST PROMOTION_N_FREE_DAYS= 7;    // N days free promotion
    CONST PROMOTION_PERCENTAGEM_DISCOUNT_BY_ONE_MONTH= 7;    // promotion of percentegem discount by one month
    
    CONST PAYMENT_VALUE=1; //quantity to payment in cents
    
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
