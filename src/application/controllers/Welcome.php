<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
//        require_once 'class/Client.php';
//        $Client = new dumbu\cls\Client();
//        $Client->sign_in();
//        $Client->credit_card_number = 7;
//        echo "God number is $Client->credit_card_number";
        $this->load->view('welcome_message');
        
    }
    
    
    public function user_do_login()
     {        
        require_once 'class/User.php';
        $GLOBALS['User']=new dumbu\cls\User();
        
        $user_name = $this->input->post('user_name');
        $user_pass = $this->input->post('user_pass');
        
        echo $user_name;
        
        $GLOBALS['User']->name = "pepe";
        echo $GLOBALS['User']->name;
        
        //perro viejo
        
       // $result=$GLOBALS['User']->do_login($user_name,$user_pass);
        /*if($result['success']) //if user is in database
          { 
            if($result[''])
              {
                
              }
          }
        else
         {
            //Message('Error: user not exist');
         }*/
         
     }


    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
   

//    public function get_reference_user($cookies, $reference_user_name) {
//        echo "-------Obtindo dados de perfil de referencia------------<br><br";
//        $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
//        $ds_user_id = $this->obtine_cookie_value($cookies, "ds_user_id");
//        $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
//        $url = "https://www.instagram.com/$reference_user_name'/?__a=1'";
//        $curl_str = "curl '$url' ";
//        $curl_str .= "-H 'Accept-Encoding: gzip, deflate, sdch' ";
//        $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
//        $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
//        $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
//        $curl_str .= "-H 'Accept: */*' ";
//        $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
//        $curl_str .= "-H 'Authority: www.instagram.com' ";
//        $curl_str .= "-H 'Cookie: mid=V9WouwAEAAEC24F7E7oIcleD-vkG; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
//        $curl_str .= "--compressed ";
//        exec($curl_str, $output, $status);
//        return json_decode($output[0]);
//    }
//
//    public function follow_not_followed($cookies, $Users, $N) {
//        foreach ($Users as $User) {
//            if (!$User->requested_by_viewer && !$User->followed_by_viewer) {
//                echo "<br><br><br>O seguidor " . $User->username . " foi requisitado. Resultado: ";
//                $this->make_insta_follow($cookies, $User->id);
//                //sleep(1);
//            }
//        }
//    }
//
//    public function make_insta_login($cookies) {
//        $curl_str = $this->make_curl_login_str('https://www.instagram.com/accounts/login/ajax/', $cookies, "albertoreyesd84", "alberto");
//        //    $curl_str = make_curl_str('https://www.instagram.com/accounts/login/ajax/', $webdriver->getAllCookies(), "josergm86", "joseramon");
//        //    print("<br><br>$curl_str<br><br>");
//        exec($curl_str, $output, $status);
//        print_r($output);
//        print_r($status);
//        print_r("-> $status<br><br>");
//        return $output;
//    }
//
//    public function make_curl_login_str($url, $cookies, $user, $pass) {
//        $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
//        $curl_str = "curl '$url' ";
//        $curl_str .= "-H 'Host: www.instagram.com' ";
//        $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
//        $curl_str .= "-H 'Accept: */*' ";
//        $curl_str .= "-H 'Accept-Language: en-US,en;q=0.5' ";
//        $curl_str .= "--compressed ";
//        $curl_str .= "-H 'Referer: https://www.instagram.com/accounts/login/' ";
//        $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
//        $curl_str .= "-H 'X-Instagram-AJAX: 1' ";
//        $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
//        $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
//        $curl_str .= "-H 'Cookie: csrftoken=$csrftoken; ig_pr=1; ig_vw=1280' ";
//        $curl_str .= "-H 'Connection: keep-alive' ";
//        $curl_str .= "--data 'username=$user&password=$pass'";
//        return $curl_str;
//    }
//
//    public function make_curl_follow_str($url, $cookies) {
//        $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
//        $ds_user_id = $this->obtine_cookie_value($cookies, "ds_user_id");
//        $sessionid = $this->obtine_cookie_value($cookies, "sessionid");
//        $curl_str = "curl '$url' ";
//        $curl_str .= "-X POST ";
//        $curl_str .= "-H 'Cookie: mid=V9WouwAEAAEC24F7E7oIcleD-vkG; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
//        $curl_str .= "-H 'Host: www.instagram.com' ";
//        $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
//        $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
//        $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
//        $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
//        $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
//        $curl_str .= "-H 'X-Instagram-Ajax: 1' ";
//        $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
//        $curl_str .= "-H 'Accept: */*' ";
//        $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
//        $curl_str .= "-H 'Authority: www.instagram.com' ";
//        $curl_str .= "-H 'Content-Length: 0' ";
//        $curl_str .= "--compressed ";
//        return $curl_str;
//    }
//
//    public function obtine_cookie_value($cookies, $name) {
//        foreach ($cookies as $key => $object) {
//            //print_r($object + "<br>");
//            if ($object->name == $name) {
//                return $object->value;
//            }
//        }
//        return null;
//    }
//
//    public function make_post($url) {
//        $session = curl_init();
//        //$headers['Accept-Encoding'] = 'gzip, deflate, br';
//        //$headers['Accept-Language'] = 'en-US,en;q=0.5';
//        //$headers['Content-Length'] = '37';
//        $headers['Accept'] = '*/*';
//        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
//        $headers['Cookie'] = "mid=V9xV2wAEAAGOzlo31h2_pyy1Huj5; sessionid=IGSC08fa8c584f5ca30ce171f701e318e8285610c3c1ffdac0a54ef0c4e43a6ec770%3Ad2m0Jq7dBiCBuUFEReJX6Pdg8TjmSKf4%3A%7B%22_token_ver%22%3A2%2C%22_auth_user_id%22%3A3858629065%2C%22_token%22%3A%223858629065%3Abz609jmb069TVeABWNYqpPxnNdWV0bxV%3Afd4a372b9561ade868a2eb39cc98f468da9c2053f34179d724c89ac52630e64c%22%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22last_refreshed%22%3A1474057695.099257%2C%22_platform%22%3A4%2C%22_auth_user_hash%22%3A%22%22%7D; csrftoken=bewWHKLF2xJq01xo98Aze2fOEnAcyiaX";
//        $headers['Origin'] = "https://www.instagram.com/";
//        $headers['Accept-Encoding'] = "gzip, deflate";
//        $headers['Accept-Language'] = "en-US,en; q=0.8";
//        $headers['User-Agent'] = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36';
//        $headers['X-Requested-With'] = 'XMLHttpRequest';
//        $headers['X-CSRFToken'] = 'bewWHKLF2xJq01xo98Aze2fOEnAcyiaX';
//        $headers['X-Instagram-AJAX'] = '1';
//        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
//        $headers['Referer'] = 'https://www.instagram.com/';
//        $headers['Authority'] = 'www.instagram.com';
//        $headers['Content-Length'] = '0';
//        $headers['Connection'] = 'keep-alive';
//
//        curl_setopt($session, CURLOPT_URL, $url);
//        curl_setopt($session, CURLOPT_POST, TRUE);
//        curl_setopt($session, CURLOPT_ENCODING, "gzip");
//        curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
//        //curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($session, CURLOPT_HEADER, 1);
//        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($session, CURLOPT_FOLLOWLOCATION, 1);
//
//        //curl_setopt($session, CURLOPT_POSTFIELDS, $data);
//
//        $response = curl_exec($session);
//        print_r($response);
//
//        curl_close($session);
//        echo "data posted....! <br>";
//    }

}