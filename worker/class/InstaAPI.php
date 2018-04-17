<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dumbu\cls {
//    ini_set('xdebug.var_display_max_depth', 17);
//    ini_set('xdebug.var_display_max_children', 256);
//    ini_set('xdebug.var_display_max_data', 1024);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/src/vendor/autoload.php';
    //require_once '../../src/vendor/autoload.php'; //asi noooo, cojone

    /**
     * Description of InstaAPI
     *
     * @author albertord
     */
    class InstaAPI {

        public $Cookies = null;

        public function login($username, $password) {
            /////// CONFIG ///////
            $debug = false;
            $truncatedDebug = true;
            //////////////////////

            try {
                $ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
        
                
//                $ig->setOutputInterface("191.252.110.140");
                
                $ig->setProxy(['proxy'=>'tcp://70.39.250.32:23128']);

                $loginResponse = $ig->login($username, $password, true);
                
                $ig->client->loadCookieJar();

                if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
                    $twoFactorIdentifier = $loginResponse->getTwoFactorInfo()->getTwoFactorIdentifier();

                    // The "STDIN" lets you paste the code via terminal for testing.
                    // You should replace this line with the logic you want.
                    // The verification code will be sent by Instagram via SMS.
                    $verificationCode = trim(fgets(STDIN));
                    $ig->finishTwoFactorLogin($verificationCode, $twoFactorIdentifier);
                }
                $loginResponse->Cookies = new \stdClass();
                $loginResponse->Cookies->sessionid =  $ig->client->getCookie('sessionid')->getValue();              
                $loginResponse->Cookies->csrftoken =  $ig->client->getCookie('csrftoken')->getValue();
                $loginResponse->Cookies->ds_user_id = $ig->client->getCookie('ds_user_id')->getValue();
                $loginResponse->Cookies->mid =  $ig->client->getCookie('mid')->getValue();
                
                return $loginResponse;
            } catch (\Exception $e) {
                throw $e;
//                echo 'Something went wrong: ' . $e->getMessage() . "\n";
            }
        }

    }

}