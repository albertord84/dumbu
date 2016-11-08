<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        if ($this->session->userdata('id'))            
            $data['user_active'] = true;
        else
            $data['user_active'] = false;
        $data['content'] = $this->load->view('my_views/init_painel', '', true);
        $data['content_header'] = $this->load->view('my_views/users_header', '', true);        
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);        
        $this->load->view('welcome_message', $data);
    }
    
    
    public function user_do_login() {
        $datas = $this->input->post();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        $user_data = $this->user_model->load_user($datas['user_login'], $datas['user_pass']);
        if (count($user_data)) {
            if($user_data['role_id'] == user_role::ADMIN){
                $this->user_model->set_sesion($datas['user_login'], $datas['user_pass'], $this->session);
                $result['resource'] = 'panel_admin';                
                $result['message'] = 'Administador '.$datas['user_login'].' logueado';
                $result['authenticated'] = true;
            } else
            if($user_data['role_id'] == user_role::ATTENDET) {
                $this->user_model->set_sesion($datas['user_login'], $datas['user_pass'], $this->session);
                $result['resource'] = 'panel_atendent';                 
                $result['message'] = 'Atendente '.$datas['user_login'].' logueado';
                $result['authenticated'] = true;
            } else
            if($user_data['role_id'] == user_role::CLIENT) {
                $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass']);
                if($data_insta['status']==='ok' && $data_insta['authenticated']){
                    $client = $this->client_model->get_client_by_ds_user_id($data_insta['insta_id']);
                    if (count($client)) { //si ademas el ds_user_id de INSTAG coincide con el que esta en DUMBU                                                                        
                        if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_INSTA) {
                            $this->user_model->update_user($this->session->userdata('id'), array('status_id' => user_status::ACTIVE));
                        }
                        $this->user_model->set_sesion($datas['user_login'], $datas['user_pass'], $this->session);
                        $result['resource'] = 'panel_client';
                        $result['message'] = 'Usuário '.$datas['user_login'].' logueado';
                        $result['authenticated'] = true;
                    } else{
                        /*Antiguo usuario de Instagram que elimino su cuenta de Instagram y volvio a crear una nueva y todavia es cliente de nuestro sistema */
                    }
                } else
                if($data_insta['status']==='ok' && !$data_insta['authenticated']){
                    $result['resource'] = 'index';
                    $result['message'] = 'Faça login com credenciais de Instagram';
                    $result['cause']='credentials_update_required'; 
                    $result['authenticated'] = false;
                } else
                if($data_insta['status']==='fail' && $data_insta['message']=='checkpoint_required'){
                    $result['resource'] = 'verify_account';
                    $result['verify_link'] = $data_insta['verify_account_url'];
                    $result['return_link'] ='index';
                    $result['message'] = 'Sua conta precisa ser verificada no Instagram';
                    $result['cause']='checkpoint_required';
                    $result['authenticated']=false;
                }                
            }
        } else{
            $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass']);
            if($data_insta['status']==='ok' && $data_insta['authenticated']){
                $client = $this->client_model->get_client_by_ds_user_id($data_insta['insta_id']);
                if (count($client)) { //si ademas el ds_user_id de INSTAG coincide con el que esta en DUMBU                                                                    
                    $client=$client[0];
                    $this->user_model->update_user($client['user_id'], array('login' => $datas['user_login'],'pass' => $datas['user_pass']));
                    $this->user_model->set_sesion($datas['user_login'], $datas['user_pass'], $this->session);
                    if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_INSTA) {
                        $this->user_model->update_user($this->session->userdata('id'), array('status_id' => user_status::ACTIVE));
                    }                    
                    $result['resource'] = 'panel_client';
                    $result['message'] = 'Usuário '.$datas['user_login'].' logueado';
                    $result['authenticated'] = true;
                } else{
                    $result['resource'] = 'sign_in';
                    $result['message'] = 'Você deve assinar para recever o serviço';
                    $result['cause']='signin_required';
                    $result['authenticated'] = false;
                }
            } else
            if($data_insta['status']==='ok' && !$data_insta['authenticated']){
                $result['resource'] = 'index';
                $result['message'] = 'Nome de usuário uo senha incorretos';
                $result['cause']='real_credentials_required'; 
                $result['authenticated'] = false;
            } else
            if($data_insta['status']==='fail' && $data_insta['message']=='checkpoint_required'){
                $result['resource'] = 'verify_account';
                $result['verify_link'] = $data_insta['verify_account_url'];
                $result['return_link'] ='index';
                $result['message'] = 'Voce precisa verificar sua conta no Instagram e depois assinar no DUMBUS';
                $result['cause']='checkpoint_required'; 
                $result['authenticated'] = false;
            }    
        }
        echo json_encode($result);
    }
        
    public function re_login() {
        $data_user = $this->input->get();
        $user_login = $data_user['user_login'];
        $user_pass = $data_user['user_pass'];
        $this->load->model('class/user_model');
        $user_role = $this->user_model->get_user_role($user_login, $user_pass);
        if (count($user_role)) {
            $this->user_model->set_sesion($user_login, $user_pass, $this->session);
            $this->panel_client();
        } else {
            $this->index();
        }
    }

    
    //functions for client signature (in, update)
    public function check_user_for_sing_in() {
        $this->load->model('class/dumbu_system_config');
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');
        $datas = $this->input->post();
        $data_insta = $this->is_insta_user($datas['client_login'], $datas['client_pass']);
        if ($data_insta['status']==='ok' && $data_insta['authenticated']) {
            $client = $this->client_model->get_client_by_ds_user_id($data_insta['insta_id']);
            $N = count($client);
            if ($N == 0) { //si no existe em dumbus
                $datas['role_id'] = user_role::CLIENT;
                $datas['status_id'] = user_status::BEGINNER;
                $id_user = $this->client_model->insert_client($datas, $data_insta);
                $response['pk'] = $id_user;
                $response['success'] = true;
                //TODO: enviar para el navegador los datos del usuario logueado en las cookies para chequearlas en los PASSOS 2 y 3
            } else { //si existe en dumbus chequear si el estado es BEGINNER                
                $client = $client[0];
                $user = $this->user_model->get_user_by_id($client['user_id'])[0];
                if ($user['status_id'] == user_status::BEGINNER) {
                    $response['success'] = true;
                    $response['pk'] = $client['user_id'];
                } else {
                    $response['success'] = false;
                    $response['message'] = 'O usuario ja tem cadastro no sistema';
                }
            }
            if ($response['success'] == true) {
                $response['need_delete'] = (dumbu_system_config::INSTA_MAX_FOLLOWING - $data_insta['insta_following']);
                //TODO: guardar esta cantidad en las cookies para trabajar con lo que este en la cookie
                $response['MIN_MARGIN_TO_INIT'] = dumbu_system_config::MIN_MARGIN_TO_INIT;
            }
        } else
        if($data_insta['status']==='ok' && !$data_insta['authenticated']){
            $response['success'] = false;
            $response['cause'] = 'missing_user';
            $response['message'] = 'A conta informada não existe no Instagram';
        } else
        if($data_insta['status']==='fail' && $data_insta['message']==='checkpoint_required'){
            $response['resource'] = 'verify_account';
            $response['verify_link'] = $data_insta['verify_account_url'];
            $response['return_link'] ='sign_in';            
            $response['message'] = 'Voce precisa verificar sua conta no Instagram e depois assinar no DUMBUS';
            $response['cause']='checkpoint_required'; 
            $response['success'] = false;
        }
            
        echo json_encode($response);
    }

    public function check_client_data_bank() {
        //1.TODO: recibir los datos que vienen en las cookies desde el navegador y verificar que sea el mismo usuario que se logueo en PASSO 1
        //---despues de verificar datos bancarios correctos, pasar as user_status::UNFOLLOW o a ACTIVE
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/dumbu_system_config');
        $this->load->model('class/credit_card_status');
        $datas = $this->input->post();
        if ($this->validate_post_credit_card_datas($datas)) {
            if ($this->client_model->check_mundipagg_credit_card($datas)) {
                if ($datas['need_delete'] < dumbu_system_config::MIN_MARGIN_TO_INIT)
                    $datas['status_id'] = user_status::UNFOLLOW;
                else
                    $datas['status_id'] = user_status::ACTIVE;
                $datas['pay_day'] = $this->get_day_of_payment();
                $a = $this->user_model->update_user($datas['pk'], array(
                    'status_id' => $datas['status_id'],
                    'email' => $datas['client_email']));
                $b = $this->client_model->update_client($datas['pk'], array(
                    'credit_card_number' => $datas['client_credit_card_number'],
                    'credit_card_cvc' => $datas['client_credit_card_cvv'],
                    'credit_card_name' => $datas['client_credit_card_name'],
                    'credit_card_exp_month' => $datas['client_credit_card_validate_month'],
                    'credit_card_exp_year' => $datas['client_credit_card_validate_year'],
                    'credit_card_status_id' => credit_card_status::ACTIVE,
                    'pay_day' => $datas['pay_day']));
                if ($a && $b) {
                    $result['success'] = true;
                    $result['message'] = 'Dados bancários confirmados corretamente';
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Error actualizando en base de datos';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Dados bancários incorretos';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Violação, accesso não permitido';
        }
        echo json_encode($result);
    }

    public function update_client_datas() {
        if ($this->session->userdata('id')) {
            //1.TODO: recibir los datos que vienen en las cookies desde el navegador y verificar que sea el mismo usuario que se logueo en PASSO 1
            //---despues de verificar datos bancarios correctos, pasar as user_status::UNFOLLOW o a ACTIVE
            $this->load->model('class/client_model');
            $this->load->model('class/user_model');
            $this->load->model('class/user_status');
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/credit_card_status');
            $datas = $this->input->post();
            if (/* TODO */$this->validate_post_credit_card_datas($datas)) {
                if ($this->client_model->check_mundipagg_credit_card($datas)) {
                    //-------------------------------------------------------------------------
                    //Opcion 1. Si el cliente estava pendiente o bloqueado por pagamento, hacer el 
                    //pagamento y activarlo inmediatamente
                    /* if($this->session->userdata('status_id')==user_status::BLOCKED_BY_PAYMENT || $this->session->userdata('status_id')==user_status::PENDING){
                      $this->do_payment($this->session->userdata('id'))
                      $datas['status_id']=user_status::ACTIVE;
                      } */
                    //-------------------------------------------------------------------------
                    //Opcion 2. Si cliente esta bloqueado por pagamento, pasarlo al estado de pendiente
                    //para que el sistema de pagamento sea el lo active despues de
                    //hacer el pagamento (posiblemente al otro dia)
                    if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT) {
                        $datas['status_id'] = user_status::PENDING;
                    }
                    //-------------------------------------------------------------------------
                    else
                        $datas['status_id'] = $this->session->userdata('status_id');
                    $a = $this->user_model->update_user($this->session->userdata('id'), array(
                        'status_id' => $datas['status_id'],
                        'email' => $datas['client_email']));
                    $b = $this->client_model->update_client($this->session->userdata('id'), array(
                        'credit_card_number' => $datas['client_credit_card_number'],
                        'credit_card_cvc' => $datas['client_credit_card_cvv'],
                        'credit_card_name' => $datas['client_credit_card_name'],
                        'credit_card_exp_month' => $datas['client_credit_card_validate_month'],
                        'credit_card_exp_year' => $datas['client_credit_card_validate_year'],
                        'credit_card_status_id' => credit_card_status::ACTIVE));
                    if ($a && $b) {
                        $result['success'] = true;
                        $result['message'] = 'Dados atualizados corretamente';
                    } else {
                        $result['success'] = false;
                        $result['message'] = 'Error actualizando en base de datos';
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Dados bancários incorretos';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Violação, accesso não permitido';
            }
            echo json_encode($result);
        }
    }

    public function client_sing_up() { //usada por administradores
        $this->load->model('class/user_role');
        if ($this->session->userdata('role_id') == user_role::ADMIN) {
            //1. almacenar la causa por la que el cliente esta cerrando su cuenta
            $cause = $this->input->post();
            //2. cambiar el estado del cliente a INACTIVO
            $this->load->model('class/client_model');
            $this->load->model('class/user_status');
            if ($this->client_model->sign_up($GLOBALS['User']->login, $GLOBALS['User']->pass, user_status::INACTIVE)) {
                //3. Enviar email con mensaje de cuenta desactivada
                $result['success'] = true;
                $result['message'] = 'Conta desativada com sucesso';
            } else {
                $result['success'] = false;
                $result['message'] = 'Sua solicitude não pode ser processada no momento. Tente depois';
            }
        }
    }

    
    //functions for reference profiles
    public function client_insert_profile() {
        if ($this->session->userdata('name')) {
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            $all_profiles_of_client = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($all_profiles_of_client);
            $is_active_profile = false;
            $is_deleted_profile = false;
            for ($i = 0; $i < $N; $i++) {
                if ($all_profiles_of_client[$i]['insta_name'] == $profile['profile']) {
                    if ($all_profiles_of_client[$i]['deleted'] == false)
                        $is_active_profile = true;
                    else
                        $is_deleted_profile = true;
                    break;
                }
            }
            if (!$is_active_profile && !$is_deleted_profile) {
                if ($N < dumbu_system_config::REFERENCE_PROFILE_AMOUNT) {
                    $profile_datas = $this->check_insta_profile($profile['profile']);
                    if ($profile_datas) {
                        $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['profile'], $profile_datas->pk);
                        if ($p) {
                            $result['success'] = true;
                            $result['message'] = 'Perfil adicionado corretamente';
                            $result['img_url'] = $profile_datas->profile_pic_url;
                            $result['profile'] = $profile['profile'];
                        } else {
                            $result['success'] = false;
                            $result['message'] = 'Erro no sistema, tente depois';
                        }
                    } else {
                        $result['success'] = false;
                        $result['message'] = 'Não é um perfil do Instagram';
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Já tem a quantidade máxima possível de perfis activos';
                }
            } else {
                if ($is_active_profile) {
                    $result['success'] = false;
                    $result['message'] = 'O perfil informado ja está activo';
                } elseif ($is_deleted_profile && $N < dumbu_system_config::REFERENCE_PROFILE_AMOUNT) {
                    // $this->client_model->activate_profile($this->session->userdata('id'),$profile);
                }
            }
            echo json_encode($result);
        }
    }

    public function client_desactive_profiles() {
        if ($this->session->userdata('name')) {
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['profile'])) {
                $result['success'] = true;
                $result['message'] = 'Perfil eliminado';
            } else {
                $result['success'] = false;
                $result['message'] = 'Erro no sistema, tente depois';
            }
            echo json_encode($result);
        }
    }

    public function check_insta_profile($profile) {
        if ($this->session->userdata('name')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $data=$this->Robot->get_insta_ref_prof_data($profile);
            if (is_object($data)) {
                return $data;
            } else {
                return NULL;
            }
        }
    }

    public function message() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \dumbu\cls\system_config();
        $this->Gmail = new \dumbu\cls\Gmail();
        $datas = $this->input->post();
        $result = $this->Gmail->send_client_contact_form($datas['name'], $datas['email'], $datas['message'], $datas['company'], $datas['telf']);
        if ($result['success']) {
            $result['message'] = 'Mensagem enviada, agradecemos seu contato ...';
        }
        echo json_encode($result);
    }

    
    //auxiliar function
    public function validate_post_credit_card_datas($datas) {
        //TODO: validate emial and datas of credit card using regular expresions
        /* if (preg_match('^[0-9]{16,16}$',$datas['client_credit_card_number']) &&
          preg_match('^[0-9 ]{3,3}$',$datas['client_credit_card_cvv']) &&
          preg_match('^[A-Z ]{4,50}$',$datas['client_credit_card_name']) &&
          preg_match('^[0-10-9]{2,2}$',$datas['client_credit_card_validate_month']) &&
          preg_match('^[2-20-01-20-9]{4,4}$',$datas['client_credit_card_validate_year']) &&
          preg_match('^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$',$datas['client_email']))
          return true;
          else
          return false; */
        return true;
    }

    public function is_insta_user($client_login, $client_pass) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
        $this->Robot = new \dumbu\cls\Robot();
        $login_data = $this->Robot->bot_login($client_login, $client_pass);        
        if ($login_data->json_response->status==="ok"){
            $data_insta['status']=$login_data->json_response->status;
            if ($login_data->json_response->authenticated) {                
                $data_insta['authenticated']=true;
                $data_insta['insta_id'] = $login_data->ds_user_id;
                $user_data = $this->Robot->get_insta_ref_prof_data($client_login);
                $data_insta['insta_followers_ini'] = $user_data->follower_count;
                $data_insta['insta_following'] = $user_data->following;
                $data_insta['insta_name'] = $user_data->full_name;
                $data_insta['insta_login_response'] = $login_data;                
            } else {
                $data_insta['authenticated']=false;                
            }
        } else{
            if($login_data->json_response->status==="fail"){
                $data_insta['status']=$login_data->json_response->status;
                if($login_data->json_response->message==="checkpoint_required"){
                    $data_insta['message']=$login_data->json_response->message;
                    $data_insta['verify_account_url']=$login_data->json_response->checkpoint_url;
                }
            }
        }
        return $data_insta;
    }

    public function get_day_of_payment() {
        $this->load->model('class/dumbu_system_config');
        $ndays = dumbu_system_config::PROMOTION_N_FREE_DAYS;
        return (string) time() + ($ndays * 24 * 60 * 60);
    }

    
    //functions for load ad dispay the diferent funtionalities views
    public function how_function() {
        if ($this->session->userdata('id'))
            $data['user_active'] = true;
        else
            $data['user_active'] = false;
        $data['content_header'] = $this->load->view('my_views/users_header', '', true);
        $data['content'] = $this->load->view('my_views/howfunction_painel', '', true);
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
        $this->load->view('welcome_message', $data);
    }

    public function sign_in() {
        if ($this->session->userdata('id'))
            $data['user_active'] = true;
        else
            $data['user_active'] = false;
        $data['content_header'] = $this->load->view('my_views/users_header', '', true);
        $data['content'] = $this->load->view('my_views/singin_painel', '', true);
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
        $this->load->view('welcome_message', $data);
    }

    public function sign_client_update() {
        if ($this->session->userdata('name')) {
            $data['user_active'] = true;
            $this->load->model('class/user_model');
            $this->load->model('class/client_model');
            $user_data = $this->user_model->get_user_by_id($this->session->userdata('id'))[0];
            $client_data = $this->client_model->get_client_by_id($this->session->userdata('id'))[0];
            $datas['upgradable_datas'] = array('email' => $user_data['email'],
                'credit_card_number' => $client_data['credit_card_number'],
                'credit_card_cvc' => $client_data['credit_card_cvc'],
                'credit_card_name' => $client_data['credit_card_name'],
                'credit_card_exp_month' => $client_data['credit_card_exp_month'],
                'credit_card_exp_year' => $client_data['credit_card_exp_year']);
            $data['content_header'] = $this->load->view('my_views/users_header', '', true);
            $data['content'] = $this->load->view('my_views/client_update_painel', $datas, true);
            $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
            $this->load->view('welcome_message', $data);
        } else
            $data['user_active'] = false;
    }

    public function sign_out() {
        if ($this->session->userdata('name')) {
            $data['user_active'] = true;
            $data['content'] = $this->load->view('my_views/sing_up', '', true);
            $this->load->view('welcome_message', $data);
        } else
            $data['user_active'] = false;
    }

    public function log_in() {
        if ($this->session->userdata('id'))
            $data['user_active'] = true;
        else
            $data['user_active'] = false;
        $data['content_header'] = $this->load->view('my_views/users_header', '', true);
        $data['content'] = $this->load->view('my_views/log_in', '', true);
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
        $this->load->view('welcome_message', $data);
    }

    public function log_out() {
        if ($this->session->userdata('name')) {
            $data['user_active'] = true;
            $this->session->sess_destroy();
            header('Location: ' . base_url() . 'index.php/welcome/');
        } else
            $data['user_active'] = false;
    }

    public function talk_me() {
        if ($this->session->userdata('id'))
            $data['user_active'] = true;
        else
            $data['user_active'] = false;
        $data['content_header'] = $this->load->view('my_views/users_header', '', true);
        $data['content'] = $this->load->view('my_views/talkme_painel', '', true);
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
        $this->load->view('welcome_message', $data);
    }
    
    public function verify_account() {
        if ($this->session->userdata('id'))
            $data['user_active'] = true;
        else
            $data['user_active'] = false;
        $data_user = $this->input->get();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
        $this->Robot = new \dumbu\cls\Robot();
        $insta_datas=$this->Robot->get_insta_ref_prof_data($data_user['user_login']);
        $data_user['profile_pic_url']=$insta_datas->profile_pic_url;        
        $data_user['full_name']=$insta_datas->full_name;
        $data['content_header'] = $this->load->view('my_views/users_header', '', true);
        $data['content'] = $this->load->view('my_views/verify_account_painel', $data_user, true);
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
        $this->load->view('welcome_message', $data);
    }    

    
    //functions for load ad dispay the user views
    public function panel_client() {
        if ($this->session->userdata('name')) {
            $this->load->model('class/dumbu_system_config');
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $datas1['MAX_NUM_PROFILES'] = dumbu_system_config::REFERENCE_PROFILE_AMOUNT;
            $datas1['my_img_profile'] = $this->Robot->get_insta_ref_prof_data($this->session->userdata('login'))->profile_pic_url;
            $datas1['my_login_profile'] = $this->session->userdata('login');
            $datas1['profiles'] = $this->create_profiles_datas_to_display();
            $datas2['user_active'] = true;
            $data2['content_header'] = $this->load->view('my_views/users_header', '', true);
            $datas2['content'] = $this->load->view('my_views/client_painel', $datas1, true);
            $datas2['content_footer'] = $this->load->view('my_views/client_footer', '', true);
            $this->session->set_userdata('datas1',$datas1);
            $this->load->view('welcome_message', $datas2);
        }
    }
    
    public function reload_panel_client() {
        if ($this->session->userdata('name')) {
            $datas2['user_active'] = true;
            $data2['content_header'] = $this->load->view('my_views/users_header', '', true);
            $datas2['content'] = $this->load->view('my_views/client_painel', $this->session->userdata('datas1'), true);
            $datas2['content_footer'] = $this->load->view('my_views/client_footer', '', true);            
            $this->load->view('welcome_message', $datas2);
        }
    }

    public function panel_atendent() {
        if ($this->session->userdata('name')) {
            $data['user_active'] = true;
        } else
            $data['user_active'] = false;
    }

    public function panel_admin() {
        if ($this->session->userdata('name')) {
            $data['user_active'] = true;
        } else
            $data['user_active'] = false;
        $data['content_header'] = $this->load->view('my_views/admin_header', '', true);
        $data['content'] = $this->load->view('my_views/admin_painel', '', true);
        $data['content_footer'] = $this->load->view('my_views/admin_footer', '', true);
        $this->load->view('layout_admin', $data);
        
    }

    public function create_profiles_datas_to_display() {
        if ($this->session->userdata('login')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $this->load->model('class/client_model');
            $client_active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($client_active_profiles);
            if ($N > 0) {
                for ($i = 0; $i < $N; $i++) {
                    $name_profile = $client_active_profiles[$i]['insta_name'];
                    $datas_of_profile = $this->Robot->get_insta_ref_prof_data($name_profile);
                    $array_profiles[$i]['login_profile'] = $name_profile;
                    if ($datas_of_profile)
                        $array_profiles[$i]['img_profile'] = $datas_of_profile->profile_pic_url;
                    else
                        $array_profiles[$i]['img_profile'] = base_url() . 'assets/img/profile_missing.png';
                }
                $response['array_profiles'] = $array_profiles;
            } else {
                $response['array_profiles'] = NULL;
            }
            $response['N'] = $N;
            return json_encode($response);
        }
    }

    public function create_profiles_datas_to_display_as_json() {
        echo($this->create_profiles_datas_to_display());
    }

}
