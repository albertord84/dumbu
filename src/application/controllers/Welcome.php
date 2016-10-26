<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        $data['content']=$this->load->view('my_views/init_page', '', true);
        $this->load->view('welcome_message',$data);
    }
    
    public function user_do_login() {
        $data_user=$this->input->post();
        $user_login = $data_user['user_login'];
        $user_pass = $data_user['user_pass'];
        $this->load->model('class/user_model');
        $user_role = $this->user_model->get_user_role($user_login, $user_pass);
        if (count($user_role)) {
            //session_start();            
            $this->load->model('class/user_role');
            if ($user_role['role_id'] == user_role::CLIENT) {
                $this->load->model('class/client_model');
                $GLOBALS['User'] = new Client_model();                
            } else if ($user_role['role_id'] == user_role::ATTENDET) {
                $this->load->model('class/attendent_model');
                $GLOBALS['User'] = new Attendent_model();
            } else if ($user_role['role_id'] == user_role::ADMIN) {
                $this->load->model('class/admin_model');
                $GLOBALS['User'] = new Admin_model();
            }            
            $GLOBALS['User']->login($user_login, $user_pass, $this->session);
            $result['success']=true;
            $result['message']='Usuario logueado';
        } else{
            $result['success']=false;
            $result['message']='Usuario ou senha incorretos';
        }
        echo json_encode($result);
    }    
    
    public function client_sing_in() { //con datos desde JSON
        $datas=$this->input->post();
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        $this->load->model('class/credit_card_status');
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        
        $data_user = array(
            'name'=>$datas['client_name'],
            'login'=>$datas['client_login'],
            'pass'=>$datas['client_pass'],
            'email'=>$datas['client_email'],            
            'telf'=>$datas['client_telf'],
            'role_id'=>user_role::CLIENT,
            'status_id'=> user_status::ACTIVE,            
            'languaje'=>$datas['client_languaje']
        );
        $data_client = array(
            'credit_card_number'=>$datas['client_credit_card_number'],
            'credit_card_status_id'=>credit_card_status::ACTIVE,
            'credit_card_cvc'=>$datas['client_credit_card_cvc'],
            'credit_card_name'=>$datas['client_credit_card_name'],
            'pay_day'=>$this->get_day_of_payment(),
            'insta_id'=>$datas['client_insta_id'],
            'insta_followers_ini'=>(int)$datas['client_insta_followers_ini'],                            
            'insta_following'=>(int)$datas['client_insta_following'],
            //'foults'=>0,
            //'last_access'=>0
        );        
        $a=$this->client_model->sign_in($data_user,$data_client);
        $this->user_model->login($data_user['login'], $data_user['pass'], $this->session);
        if($a){
            $jsondata['success'] = true;
        } else {
            $jsondata['success'] = false;
            $jsondata['message'] = 'O cadastro não foi executado. Tente depois';
        }
        echo json_encode($jsondata);        
    }
    
    public function client_update() { //con datos desde JSON
        if($this->session->userdata('name')){
            $datas=$this->input->post();
            $this->load->model('class/user_role');
            $this->load->model('class/user_status');
            $this->load->model('class/credit_card_status');
            $this->load->model('class/client_model');
            $data_user = array(
                'id'=>$this->session->userdata('id'),
                'name'=>$datas['client_name'],
                'login'=>$datas['client_login'],
                'pass'=>$datas['client_pass'],
                'email'=>$datas['client_email'],
                'telf'=>$datas['client_telf'],
                'role_id'=>user_role::CLIENT,
                'status_id'=> user_status::ACTIVE,
                'languaje'=>$datas['client_languaje']
            );
            
            $data_client = array(
                'user_id'=>$this->session->userdata('id'),
                'credit_card_number'=>$datas['client_credit_card_number'],
                'credit_card_status_id'=>credit_card_status::ACTIVE,
                'credit_card_cvc'=>$datas['client_credit_card_cvc'],
                'credit_card_name'=>$datas['client_credit_card_name'],
                'pay_day'=>$this->get_day_of_payment(),
                'insta_id'=>$datas['client_insta_id'],
                'insta_followers_ini'=>(int)$datas['client_insta_followers_ini'],                            
                'insta_following'=>(int)$datas['client_insta_following']
            );
            
            $a=$this->client_model->sign_update($data_user,$data_client);
            if($a){
                $jsondata['success'] = true;
                $jsondata['message'] = 'Actualização satisfactoria';
            } else {
                $jsondata['success'] = false;
                $jsondata['message'] = 'error';
            }
            echo json_encode($jsondata);
        }
    }
    
    public function client_insert_profile(){
        if($this->session->userdata('name')){
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/client_model');
            $profile=$this->input->post();
            $all_profiles_of_client=$this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N=count($all_profiles_of_client);
            $is_active_profile=false;
            $is_deleted_profile=false;
            for($i=0;$i<$N;$i++){
                if($all_profiles_of_client[$i]['insta_name']==$profile['profile']){
                    if($all_profiles_of_client[$i]['deleted']==false)
                        $is_active_profile=true;
                    else
                        $is_deleted_profile=true;
                    break;
                }
            }
            if(!$is_active_profile && !$is_deleted_profile){                
                if($N<dumbu_system_config::REFERENCE_PROFILE_AMOUNT){                    
                    $profile_insta_id=$this->check_insta_profile($profile['profile']);                    
                    if($profile_insta_id!=0){
                        $p=$this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['profile'], $profile_insta_id);
                        if($p){
                            $result['success']=true;
                            $result['message']='Perfil adicionado corretamente';
                        } else{
                            $result['success']=false;
                            $result['message']='Erro no sistema, tente depois';
                        }
                    } else{
                        $result['success']=false;
                        $result['message']='Não é um perfil do Instagram';
                    }
                } else{
                    $result['success']=false;
                    $result['message']='Já tem a quantidade máxima possível de perfis activos';
                }
            } else{
                if($is_active_profile){
                    $result['success']=false;
                    $result['message']='O perfil informado ja está activo';
                } elseif ($is_deleted_profile && $N<dumbu_system_config::REFERENCE_PROFILE_AMOUNT) {
                    $this->client_model->activate_profile($this->session->userdata('id'),$profile);
                }
            }
            echo json_encode($result);
        }
    }
    
    public function client_list_active_profiles(){
        if($this->session->userdata('name')){
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/client_model');
            $profile=$this->input->post();  
            $all_profiles_of_client=$this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N=count($all_profiles_of_client);   
            if($N>0){
                $result['success']=true;
                $result['num_row']=$N;
                $result['data']=$all_profiles_of_client;
            } else{
                $result['success']=false;
                $result['message']='Não tem perfis activos';
            }
            echo json_encode($result);
        }
    }    
    
    public function client_desactive_profiles(){
        if($this->session->userdata('name')){
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/client_model');
            $data=$this->input->post();
            $N=count($data['cbox']);
            for($i=0;$i<$N;$i++){
                $my_id_profiles[$i]=(int)$data['cbox'][$i];
            }
            if($this->client_model->desactive_profiles($this->session->userdata('id'), $my_id_profiles,$N)){
                $result['success']=true;
                $result['message']='Os perfis selecionados foram eliminados';
            } else{
                $result['success']=false;
                $result['message']='Erro no sistema, tente depois';
            }
            echo json_encode($result);
        }
    }
    
    public function client_update_profiles(){
        /*if($this->session->userdata('name')){
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/client_model');
            $data=$this->input->post();

            $old_profiles=$data[0];
            $new_profiles=$data[1];
            $N=count($new_profiles);

            $insta_id_profiles=$this->client_model->check_insta_profiles(dumbu_system_config::SYSTEM_USER_LOGIN,dumbu_system_config::SYSTEM_USER_PASS,$new_profiles, dumbu_system_config::NOT_INSTA_ID, $N);

            $flag=true;
            for($i=0;$i<$N;$i++) {
                if($insta_id_profiles[$i]==dumbu_system_config::NOT_INSTA_ID){
                    $result[$i]=false;
                    $flag=false;
                } else {
                    $result[$i]=true;
                }
            }
            if($flag){
                $a=$this->client_model->insert_insta_profiles($GLOBALS['User']->id, $new_profiles, $insta_id_profiles,$N);
                $b=$this->client_model->desactive_profiles($GLOBALS['User']->id, $old_profiles,$N);
                if($a && $b){
                    $result['success']=true;
                    $result['message']='Os perfis foram atualiçados';
                } else{
                    $result['success']=false;
                    $result['message']='Erro no sistema, tente depois';
                }
            } else{
                $result['success']=false;
                $result['message']='Alguns perfis não existem no Instagram';                
            }
            echo json_encode($result);
        }*/
    }    
    
    public function client_sing_up(){
        //if($this->session->userdata('name')){
            //1. almacenar la causa por la que el cliente esta cerrando su cuenta
            $cause = $this->input->post();
            //2. cambiar el estado del cliente a INACTIVO
            $this->load->model('class/client_model');
            $this->load->model('class/user_status');
            if($this->client_model->sign_up($GLOBALS['User']->login,$GLOBALS['User']->pass,user_status::INACTIVE)){
                $result['success']=true;
                $result['message']='Conta desativada com sucesso';
            } else{
                $result['success']=false;
                $result['message']='Sua solicitude não pode ser processada no momento. Tente depois';
            }
        //}
    }
    
        
    public function check_user() {
        $this->load->model('class/dumbu_system_config');
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $datas=$this->input->post();
        
        try {
            $data_insta= $this->is_insta_user($datas['client_login'],$datas['client_pass']);
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
        }
        if($data_insta['success']==true){
            if((count($this->client_model->get_client_by_ds_user_id($data_insta['insta_id']))==0) || $datas['updating']==true){
                $data_insta['success']=true;
                $data_insta['message']='siiiiiiiiiiiiiiiiiiiiiiiii';
                if($data_insta['insta_following']+dumbu_system_config::MIN_MARGIN_TO_INIT > dumbu_system_config::INSTA_MAX_FOLLOWING){
                    $data_insta['need_delete']=true;
                } else{
                    $data_insta['need_delete']=false;
                }
            } else{    
                $data_insta['success']=false;
                $data_insta['message'] = 'O usuario ja tem cadastro no sistema';
            }
        } else{
            $data_insta['success']=false;
            $data_insta['message'] = 'O usuario não existe no Instagram';
        }
        echo json_encode($data_insta);
    }
    
    public function check_insta_profile($profile) {
            require_once $_SERVER['DOCUMENT_ROOT'].'/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $data=$this->Robot->get_insta_ref_prof_data($profile);
            
            //$data=$this->get_insta_ref_prof_data($profile);
            if(is_object($data))
                return $data->pk;
            else
                return 0;
            return 123;
        }
    
    public function is_insta_user($client_login,$client_pass) {           
        require_once $_SERVER['DOCUMENT_ROOT'].'/dumbu/worker/class/Robot.php';
        $this->Robot = new \dumbu\cls\Robot();
        $login_data = $this->Robot->bot_login($client_login,$client_pass);             
        
        //$login_data = $this->bot_login($client_login,$client_pass);             
        if($login_data->json_response->authenticated){
            $data_insta['insta_id']=$login_data->ds_user_id;                
            $user_data=$this->Robot->get_insta_ref_prof_data($client_login);
            $data_insta['insta_followers_ini'] =$user_data->follower_count;
            $data_insta['insta_following'] = $user_data->following;  
            $data_insta['success']=true;
        } else{
            $data_insta['success']=false;
        }
        return $data_insta;
    }
    
    public function is_credit_card(){
        $this->load->model('class/client_model');
        $datas=$this->input->post();
        if($this->client_model->credit_card($datas['client_credit_card_number'],$datas['client_credit_card_cvc'],$datas['client_credit_card_name'])){
            $result['success']=true;
            $result['message']='Dados bancários confirmados corretamente';
        } else{
            $result['success']=false;
            $result['message']='Dados bancários incorretos';
        }
        echo json_encode($result);
    }
    
    public function get_day_of_payment(){        
        $promotion=false;
        if(!$promotion)
            return  (string)time();
    }
    
    
    
    public function how_function(){
        $data['content']=$this->load->view('my_views/how_function', '', true);
        $this->load->view('welcome_message',$data);
    }
    
    public function sing_in(){
        $data['content']=$this->load->view('my_views/sing_in', '', true);
        //$data['content']=$this->load->view('my_views/sing_in_new', '', true);
        $this->load->view('welcome_message',$data);
    }
    
    public function sing_up(){
        if($this->session->userdata('name')){
            $data['content']=$this->load->view('my_views/sing_up', '', true);
            $this->load->view('welcome_message',$data);
        }
    }
    
    public function log_in(){
        $data['content']=$this->load->view('my_views/log_in', '', true);
        $this->load->view('welcome_message',$data);
    }    
    
    public function log_out(){
        if($this->session->userdata('name')){
            $this->session->sess_destroy();
            header('Location: '.  base_url().'index.php/welcome/');
        }
    }
    
    public function update_client(){
        if($this->session->userdata('name')){
            $this->load->model('class/user_model');
            $this->load->model('class/client_model');            
            $user_data['personal_datas']=$this->user_model->load_user($this->session->userdata('login'),$this->session->userdata('pass'));
            $user_data['bank_datas']=$this->client_model->load_bank_datas_user($this->session->userdata('id'));            
            $datas['user_data']=$user_data;
            $data['content']=$this->load->view('my_views/update_client',$datas, true);
            $this->load->view('welcome_message',$data);
        }
    }
    
    
    public function panel_client(){
        if($this->session->userdata('name')){
            $data1['user_name']=$this->session->userdata('name');
            $data2['content']=$this->load->view('my_views/client_painel',$data1 , true);
            $this->load->view('welcome_message',$data2);
        }        
    }
    
    public function panel_atendent(){
        if($this->session->userdata('name')){
            
        }
    }
    
    public function panel_admin(){
        if($this->session->userdata('name')){
            
        }
    }
    
    
    
    /*
    public function bot_login($login, $pass) {
        $url = "https://www.instagram.com/";
        $ch = curl_init($url);
        $this->csrftoken = $this->get_insta_csrftoken($ch, $login, $pass);
        $result = $this->login_insta_with_csrftoken($ch, $login, $pass, $this->csrftoken);
        return $result;
    }
    
    public function get_insta_csrftoken($ch) {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLINFO_COOKIELIST, true);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
        global $cookies;
        $cookies = array();
        $response = curl_exec($ch);
        $csrftoken = explode("=", $cookies[1][1]);
        $csrftoken = $csrftoken[1];
        return $csrftoken;
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
        $html = curl_exec($ch);
        $info = curl_getinfo($ch);

        $start = strpos($html, "{");
        $json_str = substr($html, $start);
        $json_response = json_decode($json_str);
        $login_data = new \stdClass();
        $login_data->json_response = $json_response;
        if (curl_errno($ch)) {
            print curl_error($ch);
        } else if (count($cookies) >= 5) {
            $login_data->csrftoken = $csrftoken;
            $sessionid = explode("=", $cookies[1][1]);
            $sessionid = $sessionid[1];
            $login_data->sessionid = $sessionid;
            $ds_user_id = explode("=", $cookies[2][1]);
            $ds_user_id = $ds_user_id[1];
            $login_data->ds_user_id = $ds_user_id;
            $mid = explode("=", $cookies[4][1]);
            $mid = $mid[1];
            $login_data->mid = $mid;
        }
        curl_close($ch);
        return $login_data;
    }
    
    function curlResponseHeaderCallback($ch, $headerLine) {
            global $cookies;
            if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1)
                $cookies[] = $cookie;
            return strlen($headerLine); // Needed by curl
        }
    
     public function get_insta_ref_prof_data($ref_prof) {
        $content = file_get_contents("https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof");
        $users = json_decode($content)->users;
        $User = NULL;
        foreach ($users as $key => $user) {
            if ($user->user->username === $ref_prof) {
                $User = $user->user;
                $User->following = $this->get_insta_ref_prof_following($ref_prof);
                break;
            }
        }
        return $User;
    }
    
    public function get_insta_ref_prof_following($ref_prof) {
            $content = file_get_contents("https://www.instagram.com/$ref_prof/");
            $doc = new \DOMDocument();
            $doc->loadHTML($content);
            $search = "\"follows\": {\"count\": ";
            $start = strpos($doc->textContent, $search);
            $substr1 = substr($doc->textContent, $start, 100);
            $substr2 = substr($substr1, strlen($search), strpos($substr1, "}") - strlen($search));
            return intval($substr2)? intval($substr2) : NULL;            
        }
    */
}


