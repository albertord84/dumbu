<?php

set_time_limit(0);
date_default_timezone_set('UTC');

require __DIR__.'/../vendor/autoload.php';

/////// CONFIG ///////
//$username = 'alberto_dreyes';
//$password = 'albertord8';
$username = 'medina.marcosp';
$password = 'Medina241357+-*/123';
//$username = 'josergm86';
//$password = 'josergm2';
$debug = false; //true;
$truncatedDebug = true; //false;
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}

try {
    // Get the UserPK ID for "natgeo" (National Geographic).
    $userId = $ig->people->getUserIdForName('natgeo');
    die('loggin true');
    // Starting at "null" means starting at the first page.
    $maxId = null;
    do {
        // Request the page corresponding to maxId.
//        $response = $ig->timeline->getUserFeed($userId, $maxId);
        
        
        $response = $ig->people->getFollowers($userId, null, $maxId);

        // In this example we're simply printing the IDs of this page's items.
        foreach ($response->getUsers() as $item) {
            var_dump($item->biography);
            //printf("[%s] https://instagram.com/p/%s/\n", $item->getId(), $item->getCode());
        }

        // Now we must update the maxId variable to the "next page".
        // This will be a null value again when we've reached the last page!
        // And we will stop looping through pages as soon as maxId becomes null.
        $maxId = $response->getNextMaxId();

        // Sleep for 5 seconds before requesting the next page. This is just an
        // example of an okay sleep time. It is very important that your scripts
        // always pause between requests that may run very rapidly, otherwise
        // Instagram will throttle you temporarily for abusing their API!
        echo "Sleeping for 5s...\n";
        sleep(5);
    } while ($maxId !== null); // Must use "!==" for comparison instead of "!=".
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
