<?php

//require_once 'Reference_profile[].php';

//namespace dumbu\cls {
    require_once 'user_model.php';
    //require_once '.php';

    /**
     * class Client
     * 
     */
    class Client_model extends User_model {
        /** Aggregations: */
        /** Compositions: */
        /** Attributes: */

        /**
         * 
         * @access protected
         */
        protected $credit_card_number;

        /**
         * 
         * @access protected
         */
        protected $credit_card_status_id;

        /**
         * 
         * @access protected
         */
        protected $credit_card_cvc;

        /**
         * 
         * @access protected
         */
        protected $credit_card_name;

        /**
         * 
         * @access protected
         */
        protected $pay_day;

        /**
         * 
         * @access protected
         */
        protected $insta_id;

        /**
         * 
         * @access protected
         */
        protected $insta_followers_ini;

        /**
         * 
         * @access protected
         */
        protected $insta_following;

        /**
         * 
         * @access protected
         */
        protected $reference_profiles;

        /**
         * 
         */
        function __construct() {
            parent::__construct();            
        }

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function sign_in($data_user,$data_client) {
            try {
                $this->db->insert('users',$data_user);
                $id=$this->db->insert_id();
                $data_client['user_id']=$id;
                $this->db->insert('clients',$data_client);
                $id=$this->db->insert_id();
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function sign_up($client_login,$client_pass,$status_id) {
            try {
                $this->db->select('*');
                $this->db->from('users');
                $this->db->where('login', $client_login);
                $this->db->where('pass', $user_pass);
                $user_data = $this->db->get()->row_array();
                $user_data['status_id']=$status_id;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function sign_update($data_user,$data_client) {
            try {
                $this->db->update('users',$data_user);
                $this->db->update('clients',$data_client);
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function get_client_by_ds_user_id($insta_id) {
            try {    
                $this->db->select('*');
                $this->db->from('clients');        
                $this->db->where('insta_id', $insta_id);
                return $this->db->get()->result_array();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function get_client_active_profiles($user_id){
            try {    
                $this->db->select('*');
                $this->db->from('reference_profile');        
                $this->db->where('client_id', $user_id);
                $this->db->where('deleted', '0');
                return $this->db->get()->result_array();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function activate_profile($client_id,$profile){
            try {        
                $this->db->select('*');
                $this->db->from('reference_profile');        
                $this->db->where('client_id', $client_id);
                $this->db->where('insta_name', $profile);
                $datas=$this->db->get()->row_array();
                $datas['deleted']=false;
                return $this->db->update('reference_profile', $datas, array('client_id' => $client_id, 'insta_name' => $profile));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function check_insta_profile111111111111111($profile) {
            /*require_once $_SERVER['DOCUMENT_ROOT'].'/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $user_data=$this->Robot->get_insta_ref_prof_data($profile);*/  
            $insta_id='1112223334';
            
            if($perfil_is_correct)
                return $insta_id;
            else
                return 0;
        }
        
        public function insert_insta_profile($clien_id, $profile, $insta_id_profile){       
            try {
                $data['client_id']=$clien_id;        
                $data['insta_name']=$profile;
                $data['insta_id']=$insta_id_profile;
                $data['deleted']=false;
                $this->db->insert('reference_profile',$data);
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function desactive_profiles($clien_id, $profiles,$N){
            try {
                for($i=0;$i<$N;$i++){
                    $this->db->select('*');
                    $this->db->from('reference_profile');
                    $this->db->where('id',$profiles[$i]);
                    $profile_data = $this->db->get()->row_array();            
                    $profile_data['deleted']='1';
                    
                    $this->db->where("id",$profiles[$i]);
                    $this->db->update('reference_profile',$profile_data);
                }
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function credit_card($client_credit_card_number, $client_credit_card_cvc, $client_credit_card_name){
        
            return true;
        }
        
        public function load_bank_datas_user($user_id){            
            try {
                $this->db->select('credit_card_number,credit_card_cvc,credit_card_name');
                $this->db->from('clients');        
                $this->db->where('user_id', $user_id);
                return $this->db->get()->row_array();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function bot_login($login, $pass) {
            $url = "https://www.instagram.com/";
            $ch = curl_init($url);
            $csrftoken = $this->get_insta_csrftoken($ch, $login, $pass);
            /*$result = $this->login_insta_with_csrftoken($ch, $login, $pass, $csrftoken);
            var_dump($result);
            return $result;*/
         echo 'fggdfg';
        }
        
        public function get_insta_csrftoken($ch) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLINFO_COOKIELIST, true);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
            global $cookies;
            $cookies = array();
            $response = curl_exec($ch);
            echo $cookies[1][1];
            /*$csrftoken = explode("=", $cookies[1][1]);
            $csrftoken = $csrftoken[1];
            return $csrftoken;*/
        }
        
        public function login_insta_with_csrftoken($ch, $login, $pass, $csrftoken) {
            $postinfo = "username=$login&password=$pass";
            $headers = array();
            $headers[] = "Host: www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
            $headers[] = "Accept: application/json";
            $headers[] = "Accept-Language: en-US,en;q=0.5, ";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            //$headers[] = "--compressed ";
            $headers[] = "Referer: https://www.instagram.com/";
            $headers[] = "X-CSRFToken: $csrftoken";
            $headers[] = "X-Instagram-AJAX: 1";
            //$headers[] = "Content-Type: application/x-www-form-urlencoded";
            $headers[] = "Content-Type: application/json";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Cookie: csrftoken=$csrftoken";
            $url = "https://www.instagram.com/accounts/login/ajax/";
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
            //curl_setopt($ch, CURLOPT_POST, true);
            //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
            global $cookies;
            $cookies = array();
            //            var_dump($cookies);
            $html = curl_exec($ch);
            $info = curl_getinfo($ch);
//            print_r($html);

            $start = strpos($html, "{");
            $json_str = substr($html, $start);
            $json_response = json_decode($json_str);
            $login_data = new \stdClass();
            $login_data->json_response = $json_response;
            if (curl_errno($ch)) {
                print curl_error($ch);
            } else if (count($cookies) >= 5) {
                $login_data->csrftoken = $csrftoken;
                // Get sessionid from cookies
                $sessionid = explode("=", $cookies[1][1]);
                $sessionid = $sessionid[1];
                $login_data->sessionid = $sessionid;
                // Get ds_user_id from cookies
                $ds_user_id = explode("=", $cookies[2][1]);
                $ds_user_id = $ds_user_id[1];
                $login_data->ds_user_id = $ds_user_id;
                // Get mid from cookies
                $mid = explode("=", $cookies[4][1]);
                $mid = $mid[1];
                $login_data->mid = $mid;
            }
            curl_close($ch);
            return $login_data;
        }
        
    }

    // end of Client
//}
    

?>
