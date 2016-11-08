<?php

namespace dumbu\cls {

    class system_config {

        const DIALY_REQUESTS_BY_CLIENT = 480; // Change to 480 in production mode
        const REQUESTS_AT_SAME_TIME = 10;     // Reference Profile Followers amoun by request. Change to 10  in production mode   
        const DELAY_BETWEEN_REQUESTS = 0;
        const MIN_NEXT_ATTEND_TIME = 5; // 5 min --->>> not //5 * 1000 * 60; 
        const REFERENCE_PROFILE_AMOUNT = 3; // By Client
        const UNFOLLOW_ELAPSED_TIME_LIMIT = 48; // 48; // In hours
        const MAX_GET_FOLLOWERS_REQUESTS = 3; // Max of get followers request to complete REQUESTS_AT_SAME_TIME for a client work
        const MAX_CLIENT_FAUTL_TRIES = 1; // Quantity max of failures with this client
        CONST MIN_MARGIN_TO_INIT = 1000;  //margen inicial requerido para trabajar con un cliente        
        
        CONST SYSTEM_EMAIL = 'dumbu.system@gmail.com';
        CONST SYSTEM_USER_LOGIN = 'dumbu.system';
        CONST SYSTEM_USER_PASS = 'sorvete69@';
        
//        CONST MUNDIPAGG_BASE_URL = 'https://transactionv2.mundipaggone.com';  // PRODUCTION
//        CONST SYSTEM_MERCHANT_KEY = 'BCB45AC4-7EDB-49DF-98D1-69FD37F4E1D6';   // DUMBU Producition
        
        CONST MUNDIPAGG_BASE_URL = 'https://sandbox.mundipaggone.com';        // Sandbox
        CONST SYSTEM_MERCHANT_KEY = 'b3ef5018-6653-4ce0-b515-51ac26ccdcb1';   // DUMBU sandbox
//        CONST SYSTEM_MERCHANT_KEY = 'bcb45ac4-7edb-49df-98d1-69fd37f4e1d6';  // API Example

        // Payment Recurrency 
        
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


/*
7777 xxxx xxxx 7777
01/2020
ALBERTO
777
Visa
R$ 1,00 À vista
Autorizar e capturar

Buyer key
6bdce42e-a018-41e2-aa1f-c779b832ad64

InstantBuyKey
97066a19-fee2-4ff5-86ec-e201a0f12419"
 */