<?php

set_time_limit(0);
date_default_timezone_set('UTC');

require __DIR__.'/../../../autoload.php';

/////// CONFIG ///////
$username = 'alberto_dreyes';
$password = 'albertord8';
$debug = false;
$truncatedDebug = false;
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $loginResponse = $ig->login($username, $password, true);
    var_dump($loginResponse);
    
    //var_dump($ig);

    if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
        $twoFactorIdentifier = $loginResponse->getTwoFactorInfo()->getTwoFactorIdentifier();

        // The "STDIN" lets you paste the code via terminal for testing.
        // You should replace this line with the logic you want.
        // The verification code will be sent by Instagram via SMS.
        $verificationCode = trim(fgets(STDIN));
        $ig->finishTwoFactorLogin($verificationCode, $twoFactorIdentifier);
    }
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
