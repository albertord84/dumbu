<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../class/DB.php';
require_once '../class/Worker.php';
require_once '../class/Robot.php';
require_once '../class/system_config.php';
require_once '../class/user_role.php';
require_once '../class/user_status.php';
require_once '../class/Gmail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/libraries/utils.php';

echo "RECOVERING Inited...!<br>\n";
echo date("Y-m-d h:i:sa") . "<br>\n";

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();

$Robot = new \dumbu\cls\Robot();

$DB = new \dumbu\cls\DB();

$Gmail = new \dumbu\cls\Gmail();

$Client = new dumbu\cls\Client();

$clients_data_db = $DB->get_client_with_white_list();

if(isset($clients_data_db))
{
   foreach($clients_data_db as $client_id)
   {
       $current_client = $DB->get_client_login_data($client_id);
       
       try{
           $login = $Robot->bot_login($current_client->login, $current_client->pass);
           if (isset($login->json_response->authenticated) && $login->json_response->authenticated) {
                $profile_data = $Robot->get_insta_ref_prof_data_from_client($login,$current_client->login);
                var_dump($profile_data);
                $following = $profile_data->following;
                $cnt = 0;
                $white_list = $DB->get_white_list($client_id);
                $insta_follows = $Robot->get_insta_follows(
                              $login, $current_client->insta_id, 15);
                var_dump($insta_follows);
                $cursor = $insta_follows->data->user->edge_follow->page_info->end_cursor;
                while($cnt < $following)
                {
                  $cnt = $cnt + 15;
                  $Profiles = $insta_follows->data->user->edge_follow->edges;
                  echo '<br>';
                  var_dump($Profiles);
                  echo '</br><br>';
                  var_dump($white_list);
                  echo '</br>';
                  $pos = 0;
                  foreach ($white_list as $followed)
                  {                     
                      if(insta_follows_search($followed, $Profiles))
                      {
                          echo '<br>Success 1</br>';
                          unset($white_list[$pos]);
                          var_dump($white_list);
                          $pos = $pos - 1;
                      } 
                      $pos = $pos+1;
                  }
                  
                  sleep(10);
                      
                  $insta_follows = $Robot->get_insta_follows(
                           $login, $current_client->insta_id, 15,$cursor);  
                  $cursor = $insta_follows->data->user->edge_follow->page_info->end_cursor;
                }
                
                foreach ($white_list as $followed)
                {
                    echo "<br>Profil id: $followed<\br>\n";
                    echo "<br>";
                    var_dump($login);
                    echo "<\br>\n";
                    $json_response = $Robot->make_insta_friendships_command($login, $followed, 'follow');
                    var_dump($json_response);
                    echo "<br>\n";
                    if (!is_object($json_response) || $json_response->status != 'ok') { 
                             $error = $Robot->process_follow_error($json_response);
                                    var_dump($json_response);
                                    $error = TRUE;                          
                    }
                    sleep(10);

                }
        } else {
            $Gmail->send_client_login_error($clients_data[$CN]->email, $clients_data[$CN]->name, $clients_data[$CN]->login);
            print "NOT AUTENTICATED!!!";
            echo "<br>\n Can not recover users for: " . $clients_data[$CN]->login . " (" . $clients_data[$CN]->id . ") <br>\n";
        }
       }
        catch (\Exception $exc){ echo $exc->getTraceAsString(); }
   }
}
//$clients_data_db = $Client->get_client(1);
//

