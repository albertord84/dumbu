<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

//            public function index(){
//                require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
//                $this->Robot = new \dumbu\cls\Robot();
//                $this->load->model('class/client_model');
//                $datas_of_profile = $this->Robot->get_insta_ref_prof_data('nike',28);
//                var_dump($datas_of_profile);
//                $datas_of_profile = $this->Robot->get_insta_ref_prof_data('josergm86');
//                var_dump($datas_of_profile);
//            }
    
    public function index() {// responsive
        //$data['head_section1'] = $this->load->view('responsive_views/users_header_painel_black_friday','', true);        
        $data['body_section1'] = $this->load->view('responsive_views/users_body_painel_black_friday', '', true);        
        $data['footer_section1'] = $this->load->view('responsive_views/users_footer_painel', '', true);
        $data['body_section2'] = $this->load->view('responsive_views/users_howfunction_painel', '', true);
        $data['body_section3'] = $this->load->view('responsive_views/users_singin_painel_black_friday', '', true);
        $data['body_section4'] = $this->load->view('responsive_views/users_talkme_painel', '', true);
        $data['body_section5'] = $this->load->view('responsive_views/users_final_footer_painel', '', true);
        $this->load->view('view',$data);
    }
    
    public function verify_account() {
        $data_user = $this->input->get();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
        $this->Robot = new \dumbu\cls\Robot();
        $insta_datas = $this->Robot->get_insta_ref_prof_data($data_user['user_login']);
        $data_user['profile_pic_url'] = $insta_datas->profile_pic_url;
        $data_user['full_name'] = $insta_datas->full_name;        
        $data['head_section1'] = $this->load->view('my_views/users_header_painel','', true);
        $data['body_section1'] = $this->load->view('my_views/verify_account_painel', $data_user, true); 
        $data['footer_section1'] = $this->load->view('my_views/users_footer_painel', '', true);
        $data['body_section2'] = $this->load->view('my_views/users_howfunction_painel', '', true);
        $data['body_section3'] = $this->load->view('my_views/users_singin_painel', '', true);
        $data['body_section4'] = $this->load->view('my_views/users_talkme_painel', '', true);
        $data['body_section5'] = $this->load->view('my_views/users_final_footer_painel', '', true);
        $this->load->view('welcome_message', $data);
    }
    
    public function client() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('role_id')==user_role::CLIENT) {            
            $this->load->model('class/dumbu_system_config');
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $datas1['MAX_NUM_PROFILES'] = dumbu_system_config::REFERENCE_PROFILE_AMOUNT;
            $datas1['my_img_profile'] = $this->Robot->get_insta_ref_prof_data($this->session->userdata('login'))->profile_pic_url;
            $datas1['my_login_profile'] = $this->session->userdata('login');
            $datas1['status'] = $this->client_status_description();
            if($datas1['status']['status_id']==9){
                $insta_login=$this->is_insta_user($this->session->userdata('login'), $this->session->userdata('pass'));
                if($insta_login['status'] === 'fail' && $insta_login['message'] == 'checkpoint_required')
                    $datas1['verify_account_datas']=$insta_login;
                else
                if($insta_login['status'] === 'ok' &&  $insta_login['authenticated']){
                    $this->load->model('class/user_model');
                    $this->load->model('class/user_status');
                    $this->user_model->update_user($this->session->userdata('role_id'),array('status_id'=>user_status::ACTIVE));
                    $datas1['status'] = $this->client_status_description();
                }
            }
            $datas1['messages'] = $this->client_status_messages();
            $datas1['profiles'] = $this->create_profiles_datas_to_display();
            
            $data['head_section1'] = $this->load->view('responsive_views/client_header_painel','', true);
            $data['body_section1'] = $this->load->view('responsive_views/client_body_painel', $datas1, true);                         
            
            //$data['body_section2'] = $this->load->view('my_views/client_statistic_painel', '', true);            
            
            $this->load->model('class/user_model');
            $this->load->model('class/client_model');
            $user_data = $this->user_model->get_user_by_id($this->session->userdata('id'))[0];
            $client_data = $this->client_model->get_client_by_id($this->session->userdata('id'))[0];           
            //$data['body_section3'] = $this->load->view('responsive_views/client_update_painel', $datas, true);
            $data['body_section4'] = $this->load->view('responsive_views/users_talkme_painel', '', true);            
            $data['body_section5'] = $this->load->view('responsive_views/users_final_footer_painel', '', true);            
            $this->load->view('view_client', $data);
        } else{
            $this->display_access_error();
        }        
    }
            
    public function user_do_login() {
        $datas = $this->input->post();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        
        //Is an active Administrator?
        $query='SELECT * FROM users'.
                ' WHERE login="'.$datas['user_login'].'" AND pass="'.$datas['user_pass'].
                '" AND role_id='.user_role::ADMIN.' AND status_id='.user_status::ACTIVE ;
        $user= $this->user_model->execute_sql_query($query);
        if(count($user)){
            $result['role'] = 'ADMIN';
            $result['str'] ='login='.urlencode($datas['user_login']).'&pass='.urlencode($datas['user_pass']);
            $result['authenticated'] = true;
        } else{
            //Is an active Attendent?
            $query='SELECT * FROM users'.
                ' WHERE login="'.$datas['user_login'].'" AND pass="'.$datas['user_pass'].
                '" AND role_id='.user_role::ATTENDET.' AND status_id='.user_status::ACTIVE ;            
            $user= $this->user_model->execute_sql_query($query);
            if(count($user)){
                $result['role'] = 'ATTENDET';
                $result['str'] = urlencode('login='.$datas['user_login'].'&pass='.$datas['user_pass']);             
                $result['authenticated'] = true;
            } else{
                //Is an actually Instagram user?
                $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass']);
                if($data_insta['status'] === 'ok' &&  $data_insta['authenticated']) {
                    //Is a DUMBU Client by Insta ds_user_id?
                    $query='SELECT * FROM users,clients'.
                           ' WHERE clients.insta_id="'.$data_insta['insta_id'].'" AND clients.user_id=users.id';
                    $user= $this->user_model->execute_sql_query($query);
                    
                    $N=count($user);
                    $real_status=0; //No existe, eliminado o inactivo
                    $index=0;
                    for($i=0;$i<$N;$i++){
                        if($user[$i]['status_id']==user_status::BEGINNER ){
                            $real_status=1; //Beginner
                            $index=$i;
                            break;
                        } else
                        if($user[$i]['status_id']!=user_status::DELETED && $user[$i]['status_id']!=user_status::INACTIVE){
                            $real_status=2; //cualquier otro estado
                            $index=$i;
                            break;
                        }
                    }
                    if($real_status>0){
                        $st=(int)$user[$index]['status_id'];                 
                        if($st==user_status::ACTIVE || $st==user_status::BLOCKED_BY_INSTA || $st==user_status::VERYFY_ACCOUNT){
                            $this->user_model->update_user($user[$index]['id'], array(
                                        'name' => $data_insta['insta_name'],
                                        'login' =>$datas['user_login'],
                                        'pass' =>$datas['user_pass'],
                                        'status_id' => user_status::ACTIVE));
                            $this->client_model->update_client($user[$index]['id'], array(
                                        'cookies' => json_encode($data_insta['insta_login_response'])));
                            $this->user_model->set_sesion($user[$index]['id'], $this->session, $data_insta['insta_login_response']);
                            $result['resource'] = 'client';
                            $result['message'] = 'Usuário '.$datas['user_login'].' logueado';
                            $result['role'] = 'CLIENT';
                            $result['authenticated'] = true;
                        } else
                        if($st==user_status::BLOCKED_BY_PAYMENT || $st==user_status::PENDING || $st==user_status::UNFOLLOW){
                            $this->user_model->update_user($user[$index]['id'], array(
                                        'name' => $data_insta['insta_name'],
                                        'login' =>$datas['user_login'],
                                        'pass' =>$datas['user_pass']));
                            $this->client_model->update_client($user[$index]['id'], array(                                        
                                        'cookies' => json_encode($data_insta['insta_login_response'])));
                            $this->user_model->set_sesion($user[$index]['id'], $this->session,  $data_insta['insta_login_response']);
                            $result['resource'] = 'client';
                            $result['message'] = 'Usuário '.$datas['user_login'].' logueado';
                            $result['role'] = 'CLIENT';
                            $result['authenticated'] = true;
                        } else
                        if($st==user_status::BEGINNER){
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = 'Falha no login! Seu cadastro esta incompleto. Por favor, termine a sua assinatura.';
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        } else
                        if($st==user_status::DELETED || $st==user_status::INACTIVE){
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = 'Falha no login! Você deve assinar novamente para recever o serviço';
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        }                        
                    } else{
                        $result['resource'] = 'index#lnk_sign_in_now';
                        $result['message'] = 'Falha no login! Você deve assinar para recever o serviço';
                        $result['cause'] = 'signin_required';
                        $result['authenticated'] = false;
                    }
                } else
                if($data_insta['status'] === 'ok' && !$data_insta['authenticated']){                    
                    //Is a client with oldest Instagram credentials?
                    //Buscarlo en BD por el nombre y senha
                    $query='SELECT * FROM users'.
                           ' WHERE users.login="'.$datas['user_login'].
                                   '" AND users.pass="'.$datas['user_pass'].
                                   '" AND users.role_id="'.user_role::CLIENT.'"';
                    $user= $this->user_model->execute_sql_query($query);
                    $N=count($user);
                    $real_status=0; //No existe, eliminado o inactivo
                    $index=0;
                    for($i=0;$i<$N;$i++){
                        if($user[$i]['status_id']==user_status::BEGINNER ){
                            $real_status=1; //Beginner
                            $index=$i;
                            break;
                        } else
                        if($user[$i]['status_id']!=user_status::DELETED && $user[$i]['status_id']!=user_status::INACTIVE){
                            $real_status=2; //cualquier otro estado
                            $index=$i;
                            break;
                        }
                    }                      
                    if($real_status>0){
                        if($user[$index]['status_id']!=user_status::DELETED && $user[$index]['status_id']!=user_status::INACTIVE){
                            $result['resource'] = 'index';
                            $result['message'] = 'Falha no login! Entre com as suas credenciais do Instagram.';
                            $result['cause'] = 'credentials_update_required';
                            $result['authenticated'] = false;
                        } else{
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = 'Você deve assinar novamente para recever o serviço.';
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        }
                    } else{
                        //Verificar que el userLogin y respectivo ds_user_id pueden pertenecer a un usuario que
                        //esta intentando entrar por 3 o mas veces con senha antigua
                        //Buscarlo en BD por pk obtenido por el nombre de usuario informado
                        $data_profile= $this->check_insta_profile($datas['user_login']);
                        if($data_profile){
                            $query='SELECT * FROM users,clients'.
                                    ' WHERE clients.insta_id="'.$data_profile->pk.'" AND clients.user_id=users.id';
                            $user= $this->user_model->execute_sql_query($query);
                            $N=count($user);
                            $real_status=0; //No existe, eliminado o inactivo
                            $index=0;
                            for($i=0;$i<$N;$i++){
                                if($user[$i]['status_id']==user_status::BEGINNER ){
                                    $real_status=1; //Beginner
                                    $index=$i;
                                    break;
                                } else
                                if($user[$i]['status_id']!=user_status::DELETED && $user[$i]['status_id']!=user_status::INACTIVE){
                                    $real_status=2; //cualquier otro estado
                                    $index=$i;
                                    break;
                                }
                            }
                            if($real_status>0){
                                //perfil exite en instagram y en la base de datos, senha incorrecta           
                                $result['message'] = 'Senha incorreta!. Entre com a sua senha de Instagram.';
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;                                
                            } else{
                                //el perfil existe en instagram pero no en la base de datos
                                $result['message'] = 'Falha no login! Certifique-se de que possui uma assinatura antes de entrar.';
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;  
                            }
                        }   else{
                            //nombre de usuario informado no existe en instagram
                            $result['message'] = 'Falha no login! O nome de usuário fornecido não existe no Instagram.';
                            $result['cause'] = 'error_login';
                            $result['authenticated'] = false;  
                        }                              
                        /*$result['resource'] = 'index';
                        $result['message'] = 'Usuário ou senha incorretos';
                        $result['cause'] = 'credentials_required';
                        $result['authenticated'] = false;*/
                    } 
                }else
                if ($data_insta['status'] === 'fail' && $data_insta['message'] == 'checkpoint_required'){                      
                    $data_profile=$this->check_insta_profile($datas['user_login']);
                    $query='SELECT * FROM users,clients'.
                           ' WHERE clients.insta_id="'.$data_profile->pk.'" AND clients.user_id=users.id';
                    $user= $this->user_model->execute_sql_query($query);
                    $N=count($user);
                    $real_status=0; //No existe, eliminado o inactivo
                    $index=0;
                    for($i=0;$i<$N;$i++){
                        if($user[$i]['status_id']==user_status::BEGINNER ){
                            $real_status=1; //Beginner
                            $index=$i;
                            break;
                        } else
                        if($user[$i]['status_id']!=user_status::DELETED && $user[$i]['status_id']!=user_status::INACTIVE){
                            $real_status=2; //cualquier otro estado
                            $index=$i;
                            break;
                        }
                    }      
                    if($real_status>1){
                        $this->user_model->set_sesion($user[$index]['id'], $this->session);
                        if($user[$index]['status_id']!=user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id']!=user_status::PENDING){
                           $this->user_model->update_user($user[$index]['id'], array(
                                'status_id' => user_status::VERYFY_ACCOUNT));
                        }/* else
                            $this->session->set_userdata('login_status')=user_status::VERYFY_ACCOUNT;*/                                            
                        $result['resource'] = 'client';
                        $result['verify_link'] = $data_insta['verify_account_url'];
                        $result['return_link'] = 'client';
                        $result['message'] = 'Sua conta precisa ser verificada no Instagram';
                        $result['cause'] = 'checkpoint_required';
                        $result['authenticated'] = true;
                    } else{
                        //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                        $result['message'] = 'Falha no login! Certifique-se de que possui uma assinatura antes de entrar.';
                        $result['cause'] = 'error_login';
                        $result['authenticated'] = false; 
                    }
                }
            }
        }        
        echo json_encode($result);
    }

        
    public function check_user_for_sing_in() { //sign in with passive instagram profile verification
        $this->load->model('class/dumbu_system_config');
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');
        $datas = $this->input->post();
        $data_insta = $this->check_insta_profile($datas['client_login']);
        if($data_insta) {
            if(!$data_insta->following)
                $data_insta->following=0;
            $query='SELECT * FROM users,clients WHERE clients.insta_id="'.$data_insta->pk.'"'. 
                            'AND clients.user_id=users.id';// AND (users.status_id='.user_status::DELETED.' OR users.status_id='.user_status::INACTIVE.')';                                                                              
            $client=$this->user_model->execute_sql_query($query);
            $N=count($client);
            $real_status=0; //No existe, eliminado o inactivo
            $index=0;
            for($i=0;$i<$N;$i++){
                if($client[$i]['status_id']==user_status::BEGINNER ){
                    $real_status=1; //Beginner
                    $index=$i;
                    break;
                } else
                if($client[$i]['status_id']!=user_status::DELETED && $client[$i]['status_id']!=user_status::INACTIVE){
                    $real_status=2; //cualquier otro estado
                    break;
                }
            }
            if($real_status==0){
                //var_dump($datas);
                $datas['role_id'] = user_role::CLIENT;
                $datas['status_id'] = user_status::BEGINNER;
                $id_user = $this->client_model->insert_client($datas, $data_insta);
                $response['pk'] = $id_user;
                $response['datas'] = json_encode($data_insta);
                $response['success'] = true;
                //TODO: enviar para el navegador los datos del usuario logueado en las cookies para chequearlas en los PASSOS 2 y 3
            } else {
            if($real_status==1){
                    $this->user_model->update_user($client[$i]['id'], array(
                        'name' => $data_insta->full_name,
                        'email' => $datas['client_email'],
                        'login' => $datas['client_login'],
                        'pass' => $datas['client_pass']));
                    $this->client_model->update_client($client[$i]['id'], array(
                        'insta_followers_ini' => $data_insta->follower_count,                        
                        'insta_following' => $data_insta->following,
                        'HTTP_SERVER_VARS' => json_encode($_SERVER)));
                    $response['datas'] = json_encode($data_insta);
                    $response['pk'] = $client[$index]['user_id'];
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['message'] = 'O usuario informado já tem cadastro no sistema.';
                }
            }
            
            if ($response['success'] == true) {
                $response['need_delete'] = (dumbu_system_config::INSTA_MAX_FOLLOWING - $data_insta->following);
                //TODO: guardar esta cantidad en las cookies para trabajar con lo que este en la cookie
                $response['MIN_MARGIN_TO_INIT'] = dumbu_system_config::MIN_MARGIN_TO_INIT;
            }
        } else{
            $response['success'] = false;
            $response['cause'] = 'missing_user';
            $response['message'] = 'O nome de usuario informado não é um perfil do Instagram.';
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
            $this->load->model('class/dumbu_system_config');
            $day_plus = strtotime("+".dumbu_system_config::PROMOTION_N_FREE_DAYS." days", time());
            $datas['pay_day'] = $day_plus;
            $datas['amount_in_cents']=dumbu_system_config::PAYMENT_VALUE; 
            try {                 
                $this->client_model->update_client($datas['pk'], array(
                    'credit_card_number' => $datas['client_credit_card_number'],
                    'credit_card_cvc' => $datas['client_credit_card_cvv'],
                    'credit_card_name' => $datas['client_credit_card_name'],
                    'credit_card_exp_month' => $datas['client_credit_card_validate_month'],
                    'credit_card_exp_year' => $datas['client_credit_card_validate_year'],      
                    'pay_day' => $datas['pay_day']));               
            } catch (Exception $exc) {
                $result['success'] = false;
                $result['exception'] = $exc->getTraceAsString();
                $result['message'] = 'Error actualizando en base de datos';
            } finally {
                if(true){
                //$resp=$this->check_mundipagg_credit_card($datas);
                //if (is_object($resp)&& $resp->isSuccess() ) {
                    try {
                        //$this->client_model->update_client($datas['pk'], array(
                        //    'order_key'=>$resp->getData()->OrderResult->OrderKey));                        
                    } catch (Exception $exc) {
                        $this->user_model->update_user($datas['pk'], array(
                            'status_id' => user_status::BEGINNER));
                        $this->client_model->update_client($datas['pk'], array(
                            'order_key'=>NULL));
                        //deshacer el pagamento en Mundipagg
                        $result['success'] = false;
                        $result['exception'] = $exc->getTraceAsString();                
                        $result['message'] = 'Error actualizando en base de datos';                        
                    } finally {
                        //passo 3: login con instagram para saber el estado
                        $data_insta=$this->is_insta_user($datas['user_login'],$datas['user_pass'] );
                        if($data_insta['status'] === 'ok' &&  $data_insta['authenticated']){                            
                            if ($datas['need_delete'] < dumbu_system_config::MIN_MARGIN_TO_INIT)
                                $datas['status_id'] = user_status::UNFOLLOW;
                            else
                                $datas['status_id'] = user_status::ACTIVE;                                                
                            $this->user_model->update_user($datas['pk'], array(
                                'status_id' => $datas['status_id']));
                            $this->client_model->update_client($datas['pk'], array(                                  
                                'cookies' => json_encode($data_insta['insta_login_response'])));
                            $this->user_model->set_sesion($datas['pk'], $this->session, $data_insta['insta_login_response']);
                        }else
                        if($data_insta['status'] === 'ok' && !$data_insta['authenticated']){
                            $this->user_model->update_user($datas['pk'], array(
                                'status_id' => user_status::BLOCKED_BY_INSTA));
                            $this->user_model->set_sesion($datas['pk'], $this->session);
                        }else
                        if ($data_insta['status'] === 'fail' && $data_insta['message'] == 'checkpoint_required'){
                            $this->user_model->update_user($datas['pk'], array(
                                'status_id' => user_status::VERYFY_ACCOUNT));
                            $result['resource'] = 'client';
                            $result['verify_link'] = $data_insta['verify_account_url'];
                            $result['return_link'] = 'client';
                            $result['message'] = 'Sua conta precisa ser verificada no Instagram';
                            $result['cause'] = 'checkpoint_required';
                            $this->user_model->set_sesion($datas['pk'], $this->session);
                        }      
                        $this->success_buy_to_atendiment($datas['user_login'],$datas['user_email'] );
                        $result['success'] = true;
                        $result['message'] = 'Usuário cadastrado satisfatóriamente';
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Dados bancários incorretos';
                }
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Violação, accesso não permitido';
        }
        echo json_encode($result);
    }      

    public function check_mundipagg_credit_card($datas) {
        $payment_data['credit_card_number'] = $datas['client_credit_card_number'];
        $payment_data['credit_card_name'] = $datas['client_credit_card_name'];
        $payment_data['credit_card_exp_month'] = $datas['client_credit_card_validate_month'];
        $payment_data['credit_card_exp_year'] =$datas['client_credit_card_validate_year'] ;
        $payment_data['credit_card_cvc'] = $datas['client_credit_card_cvv'];
        $payment_data['amount_in_cents'] = $datas['amount_in_cents'];
        $payment_data['pay_day'] = $datas['pay_day'];
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        // Check client payment in mundipagg
        $Payment = new \dumbu\cls\Payment();
        $response = $Payment->create_recurrency_payment($payment_data);
        return $response;
    }
    
    public function delete_recurrency_payment($order_key) {        
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';        
        $Payment = new \dumbu\cls\Payment();
        $response = $Payment->delete_payment($order_key);
        return $response;
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
                $client_data=$this->client_model->get_client_by_id($this->session->userdata('id'))[0];                    
                $datas['pay_day'] = $client_data['pay_day'];
                $datas['amount_in_cents']=dumbu_system_config::PAYMENT_VALUE;
                try {
                    $this->user_model->update_user($this->session->userdata('id'), array(
                        'email' => $datas['client_email']));
                    $this->client_model->update_client($this->session->userdata('id'), array(
                        'credit_card_number' => $datas['client_credit_card_number'],
                        'credit_card_cvc' => $datas['client_credit_card_cvv'],
                        'credit_card_name' => $datas['client_credit_card_name'],
                        'credit_card_exp_month' => $datas['client_credit_card_validate_month'],
                        'credit_card_exp_year' => $datas['client_credit_card_validate_year'],                        
                       ));                    
                } catch (Exception $exc) {
                    $result['success'] = false;
                    $result['exception'] = $exc->getTraceAsString();                
                    $result['message'] = 'Error actualizando en base de datos';
                } finally {
                    $response_delete_early_payment='';
                    $resp=$this->check_mundipagg_credit_card($datas);
                    if(is_object($resp)&& $resp->isSuccess()) {
                        try {
                            $this->client_model->update_client($this->session->userdata('id'), array(
                                'order_key'=>$resp->getData()->OrderResult->OrderKey));                            
                            
                            $response_delete_early_payment=$this->delete_recurrency_payment($client_data['order_key']);                            
                            
                            if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT) {
                                $datas['status_id'] = user_status::PENDING; //para que Payment intente hacer el pagamento y si ok entonces lo active y le ponga trabajo
                            }
                            else
                                $datas['status_id'] = $this->session->userdata('status_id');
                            $this->user_model->update_user($this->session->userdata('id'), array(
                                'status_id' => $datas['status_id']));                            
                        } catch (Exception $exc) {
                            $this->user_model->update_user($datas['pk'], array(
                                'status_id' => $this->session->userdata('status_id'))); //the previous
                            $this->client_model->update_client($datas['pk'], array(
                                'order_key'=>$client_data['order_key'])); //the previous
                            $result['success'] = false;
                            $result['exception'] = $exc->getTraceAsString();
                            $result['message'] = 'Error actualizando en base de datos';                        
                        } finally {
                            $result['success'] = true;
                            $result['message'] = 'Dados bancários confirmados corretamente';
                            $result['response_delete_early_payment']=$response_delete_early_payment;
                        }
                    } else {
                        //restablecer en la base de datos los datos anteriores
                        $this->client_model->update_client($this->session->userdata('id'), array(
                            'credit_card_number' => $client_data['credit_card_number'],
                            'credit_card_cvc' => $client_data['credit_card_cvc'],
                            'credit_card_name' => $client_data['credit_card_name'],
                            'credit_card_exp_month' => $client_data['credit_card_exp_month'],
                            'credit_card_exp_year' => $client_data['credit_card_exp_year'],
                            'order_key'=>$client_data['order_key']
                           ));
                        $result['success'] = false;
                        $result['message'] = 'Dados bancários incorretos';
                    }
                }                  
            } else {
                $result['success'] = false;
                $result['message'] = 'Violação, accesso não permitido';
            }
            echo json_encode($result);
        }
    }
    
    public function client_sing_up() { //a ser usada por administradores
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
            $this->load->model('class/user_status');
            $profile = $this->input->post();
            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($active_profiles);
            $is_active_profile = false;
            $is_deleted_profile = false;
            for ($i = 0; $i < $N; $i++) {
                if ($active_profiles[$i]['insta_name'] == $profile['profile']) {
                    if ($active_profiles[$i]['deleted'] == false)
                        $is_active_profile = true;
                    else
                        $is_deleted_profile = true;
                    break;
                }
            }
            if (!$is_active_profile && !$is_deleted_profile) {
                if ($N < dumbu_system_config::REFERENCE_PROFILE_AMOUNT) {
                    $profile_datas = $this->check_insta_profile($profile['profile']);
                    if($profile_datas) {
                        if(!$profile_datas->is_private){
                            $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['profile'], $profile_datas->pk);                                                        
                            if ($p) {
                                if ($this->session->userdata('status_id')==user_status::ACTIVE && $this->session->userdata('insta_datas'))
                                    $q = $this->client_model->insert_profile_in_daily_work($p,$this->session->userdata('insta_datas'), $N, $active_profiles, dumbu_system_config::DIALY_REQUESTS_BY_CLIENT);
                                else
                                    $q=true;
                                
                                $profile_datas = $this->check_insta_profile($profile['profile'],$p);
                                $result['success'] = true;
                                $result['img_url'] = $profile_datas->profile_pic_url;
                                $result['profile'] = $profile['profile'];   
                                $result['follows_from_profile'] = $profile_datas->follows;
                                if ($q) {
                                    $result['message'] = 'Perfil adicionado corretamente';                                    
                                } else{                                    
                                    $result['message'] = 'O trabalho com o perfil começara depois';            
                                } 
                            } else {
                                $result['success'] = false;
                                $result['message'] = 'Erro no sistema, tente depois';
                            }
                        }
                        else{
                            $result['success'] = false;
                            $result['message'] = 'O perfil '.$profile['profile'].' é um perfil privado' ;                            
                        }
                    } else {
                        $result['success'] = false;
                        $result['message'] = $profile['profile'].' não é um perfil do Instagram';
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
//        if ($this->session->userdata('name')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $data = $this->Robot->get_insta_ref_prof_data($profile);
            if (is_object($data)) {
                return $data;
            } else {
                return NULL;
            }
//        }
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
    
    public function success_buy_to_atendiment($username, $useremail) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \dumbu\cls\system_config();
        $this->Gmail = new \dumbu\cls\Gmail();
        $datas = $this->input->post();
        $result = $this->Gmail->send_new_client_payment_done($username, $useremail);
        if ($result['success']) 
            return TRUE;
        return false;            
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
        $data_insta = NULL;
        $login_data = $this->Robot->bot_login($client_login, $client_pass);
        if (isset($login_data->json_response->status) && $login_data->json_response->status === "ok") {
            $data_insta['status'] = $login_data->json_response->status;
            if ($login_data->json_response->authenticated) {
                $data_insta['authenticated'] = true;
                $data_insta['insta_id'] = $login_data->ds_user_id;
                $user_data = $this->Robot->get_insta_ref_prof_data($client_login);
                $data_insta['insta_followers_ini'] = $user_data->follower_count;
                $data_insta['insta_following'] = $user_data->following;
                $data_insta['insta_name'] = $user_data->full_name;
                $data_insta['insta_login_response'] = $login_data;                
            } else {
                $data_insta['authenticated'] = false;
            }
        } else {
            if ($login_data->json_response->status === "fail") {
                $data_insta['status'] = $login_data->json_response->status;
                if ($login_data->json_response->message === "checkpoint_required") {
                    $data_insta['message'] = $login_data->json_response->message;
                    $data_insta['verify_account_url'] = $login_data->json_response->checkpoint_url;
                }
            }
        }        
        return $data_insta;
    }

    
    //functions for load ad dispay the diferent funtionalities views 
    public function sign_client_update() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('role_id')==user_role::CLIENT) {
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
            //$data['content_header'] = $this->load->view('my_views/users_header', '', true);
            $data['content'] = $this->load->view('my_views/client_update_painel', $datas, true);
            $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
            $this->load->view('welcome_message', $data);
        } else{
            $this->display_access_error();
        }
        
    }

    public function log_out() {        
        $data['user_active'] = false;
        $this->session->sess_destroy();
        header('Location: ' . base_url() . 'index.php/welcome/');        
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
                    $id_profile = $client_active_profiles[$i]['id'];
                    $datas_of_profile = $this->Robot->get_insta_ref_prof_data($name_profile,$id_profile);
                    $array_profiles[$i]['login_profile'] = $name_profile;
                    $array_profiles[$i]['follows_from_profile'] = $datas_of_profile->follows;
                    if (!$datas_of_profile){
                        $array_profiles[$i]['status_profile']='deleted';
                        $array_profiles[$i]['img_profile'] = base_url() . 'assets/img/profile_deleted.jpg';
                    }                        
                    else{
                        if($datas_of_profile->is_private){
                            $array_profiles[$i]['status_profile']='privated';
                            $array_profiles[$i]['img_profile'] = base_url() . 'assets/img/profile_privated.jpg';
                        }                            
                        else{
                            $array_profiles[$i]['status_profile']='active';
                            $array_profiles[$i]['img_profile'] = $datas_of_profile->profile_pic_url;
                        }
                    }
                }
                $response['array_profiles'] = $array_profiles;
            } else {
                $response['array_profiles'] = NULL;
            }
            $response['N'] = $N;
            return json_encode($response);
        } else{
            $this->display_access_error();
        }
    }

    public function create_profiles_datas_to_display_as_json() {
        echo($this->create_profiles_datas_to_display());
    }
    
    public function display_access_error(){
        $this->session->sess_destroy();
        header('Location: '. base_url().'index.php/welcome/');
    }
    
    public function client_status_description(){
        $st=$this->session->userdata('status_id');
          switch ($st){
            case 1:
                return array('status_id'=>$st, 'status_name'=>'ATIVO', 'status_message'=>'');
            case 2:
                return array('status_id'=>$st, 'status_name'=>'DESABILITADO', 'status_message'=>'Sua conta encontre-se deshabilitado porque não foi possível fazer o pagamento no prazo estabelecido, deve <a style="font-size:1em; color:blue" href="#lnk_update">atualizar</a> seus dados.');
            case 3:
                return array('status_id'=>$st, 'status_name'=>'INATIVO', 'status_message'=>'');
            case 6:
                return array('status_id'=>$st, 'status_name'=>'PENDENTE', 'status_message'=>'Ainda não foi possível realizar o pagamento do serviço devido a problemas com seu cartão de crédito, <a style="font-size:1em; color:blue" href="#lnk_update">atualice</a> seus dados bancarios para evitar deshabilitar o serviço.');
            case 7:
                return array('status_id'=>$st, 'status_name'=>'NÂO INICIADO', 'status_message'=>'Precisamos que você siga máximo 6000 perfis para poder iniciar a ferramenta');
            case 9:
                return array('status_id'=>$st, 'status_name'=>'INATIVO', 'status_message'=>'Verificação de conta');
        }
    }
    
    public function client_status_messages(){
        $st=$this->session->userdata('status_id');        
        $status_message=array(
            'danger'=>array(0=>0),
            'warning'=>array(0=>0),
            'info'=>array(0=>0)
        ); 
        $status_message['danger'][1]='';
        $status_message['warning'][1]='';
        
        $status_message['info'][1]='O Instagram só permite que você siga em média 7000 perfis. Precisamos que você siga no máximo 6000 perfis para iniciar a ferramenta em seu perfil;';
        $status_message['info'][2]='Nossa ferramenta é interligada ao Instagram, por isso, pode sofrer variações no desempenho a cada atualização feita pelo Instagram;';
        $status_message['info'][3]='Caso altere seu nome de usuário ou senha no Instagram, o seviço será desconectado temporariamente. Para ativar novamente a ferramenta, você precisa fazer login em nosso site para atualizar sua conta e continuar o serviço;';
        $status_message['info'][4]='Nunca utilize outras ferramentas junto a Dumbu.';
        $status_message['info'][0]=4;
        
        switch ($st){
            /*case 1:
                $status_message['info'][0]=4;
                $status_message['info'][1]='O Instagram só permite que você siga alredor de 7000 perfis. Precisamos que você siga máximo 6000 perfis para iniciar a ferramenta;';
                break;*/
            case 2:
                $status_message['danger'][0]=1;
                $status_message['danger'][1]='O serviço que você receve com Dumbu encontre-se deshabilitado devido a que não foi possível fazer o pagamento no plazo estabelecido, deve <a style=\"font-size:1em; color:blue\" href=\"#lnk_update\">atualizar</a> seus dados;';                
                break;
            case 6:
                $status_message['warning'][0]=1;
                $status_message['warning'][1]='Informamos que ainda não foi possível realizar o pagamento do serviço devido a problemas com seu cartão de crédito, <a style=\"font-size:1em; color:blue\" href=\"#lnk_update\">atualice</a> seus dados bancarios para evitar deshabilitar o serviço;';                
                break;
            case 7:
                $status_message['info'][0]=3;
                $status_message['info'][1]='Nossa ferramenta é interligada ao Instagram, por isso, pode sofrer variações no desempenho a cada atualização feita pelo Instagram;;';
                $status_message['info'][2]='Casso altere seu nome de usuário ou senha no Instagram, o seviço de Dumbu será desconetado temporáriamente. Somente precisa fazer login no Dumbu para atualizar as suas credenciais e continuar recevendo o serviço;;';
                $status_message['info'][3]='Nunca utilice outras ferramentas junto a Dumbu.';                
                $status_message['warning'][0]=1;
                $status_message['warning'][1]='Precisamos que você siga máximo 6000 perfis para poder iniciar a ferramenta';
                break;                
        }        
        return $status_message;
    }
    
    

}
