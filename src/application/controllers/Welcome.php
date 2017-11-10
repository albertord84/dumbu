<?php

class Welcome extends CI_Controller {
    
    private $security_purchase_code; //random number in [100000;999999] interval and coded by md5 crypted to antihacker control
    public $language =NULL;

    public function index() {
        $language=$this->input->get();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        if(isset($language['language']))
            $param['language']=$language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
        $GLOBALS['language']=$param['language'];
        $this->load->library('recaptcha');
        $this->load->view('user_view', $param);
    }
    
    public function language() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $this->load->library('recaptcha');
        $this->load->view('user_view', $param);
    }
    
    public function purchase() {
        if ($this->session->userdata('id')) {
            $datas = $this->input->get();
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'),'SUCCESSFUL PURCHASE');
            
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $datas['user_id'] = $this->session->userdata('id');
            $datas['profiles'] = $this->create_profiles_datas_to_display();            
            $datas['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;            
            if(isset($datas['language'])&& $datas['language']!=''){
                 $GLOBALS['language'] =  $datas['language'];
            }
            else{
                $datas['language'] = $GLOBALS['sistem_config']->LANGUAGE;
                 $GLOBALS['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            }
            $datas['Afilio_UNIQUE_ID'] = $this->session->userdata('id');
            $query='SELECT * FROM plane WHERE id='.$this->session->userdata('plane_id');
            $result = $this->user_model->execute_sql_query($query);
            $datas['Afilio_order_price']=$result[0]['initial_val'];
            $datas['Afilio_total_value']=$result[0]['normal_val'];
            $datas['Afilio_product_id']= $this->session->userdata('plane_id');
            
            $datas['client_email']= $this->session->userdata('email');            
            
            $this->load->view('purchase_view', $datas);
        }else
            echo 'Access error';
    }

    public function client() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/user_role');
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_status');
        $status_description = array(1 => 'ATIVO', 2 => 'DESABILITADO', 3 => 'INATIVO', 4 => '', 5 => '', 6 => 'ATIVO'/* 'PENDENTE' */, 7 => 'NÃ‚O INICIADO', 8 => '', 9 => 'INATIVO', 10 => 'LIMITADO');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $language=$this->input->get();           
            if(isset($language['language'])){
                 $GLOBALS['language']=$language['language'];
                $this->user_model->set_language_of_client($this->session->userdata('id'),$language);
            }
            else
                 $GLOBALS['language']=$this->user_model->get_language_of_client($this->session->userdata('id'))['language'];
            $datas1['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $datas1['WHATSAPP_PHONE'] = $GLOBALS['sistem_config']->WHATSAPP_PHONE;
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $datas1['MAX_NUM_PROFILES'] = $GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT;
            //$my_profile_datas = $this->Robot->get_insta_ref_prof_data($this->session->userdata('login'));
            $my_profile_datas = $this->Robot->get_insta_ref_prof_data_from_client(json_decode($this->session->userdata('cookies')), $this->session->userdata('login'));
            if(isset($my_profile_datas->profile_pic_url))
                $datas1['my_img_profile'] = $my_profile_datas->profile_pic_url;
            else
                $datas1['my_img_profile']="Blocked";
            //$datas1['dumbu_id'] = $this->session->userdata('id');

                        
            $sql = "SELECT * FROM clients WHERE clients.user_id='" . $this->session->userdata('id') . "'";
            $init_client_datas = $this->user_model->execute_sql_query($sql);

            $sql = "SELECT * FROM reference_profile WHERE client_id='" . $this->session->userdata('id') . "' AND type='0'";
            $reference_profile_used= $this->user_model->execute_sql_query($sql);
            $datas1['reference_profile_used'] =count($reference_profile_used);
            
            $sql = "SELECT * FROM reference_profile WHERE client_id='" . $this->session->userdata('id') . "' AND type='1'";
            $geolocalization_used= $this->user_model->execute_sql_query($sql);
            $datas1['geolocalization_used'] =count($geolocalization_used);
           
            $sql = "SELECT SUM(follows) as followeds FROM reference_profile WHERE client_id = " . $this->session->userdata('id')." AND type='0'";
            $amount_followers_by_reference_profiles = $this->user_model->execute_sql_query($sql);
            $amount_followers_by_reference_profiles =(string)$amount_followers_by_reference_profiles[0]["followeds"];
            $datas1['amount_followers_by_reference_profiles'] = $amount_followers_by_reference_profiles;
            
            $sql = "SELECT SUM(follows) as followeds FROM reference_profile WHERE client_id = " . $this->session->userdata('id')." AND type='1'";
            $amount_followers_by_geolocalization = $this->user_model->execute_sql_query($sql);
            $amount_followers_by_geolocalization =(string)$amount_followers_by_geolocalization[0]["followeds"];
            $datas1['amount_followers_by_geolocalization'] = $amount_followers_by_geolocalization;

             
            if(isset($my_profile_datas->follower_count))
                $datas1['my_actual_followers'] = $my_profile_datas->follower_count;
            else
                $datas1['my_actual_followers']="Blocked";            
             
            if(isset($my_profile_datas->following))
               $datas1['my_actual_followings'] = $my_profile_datas->following;
            else
                $datas1['my_actual_followings']="Blocked";
            
            $datas1['my_sigin_date'] = $this->session->userdata('init_date');
            date_default_timezone_set('Etc/UTC');
            $datas1['today'] = date('d-m-Y', time());
            $datas1['my_initial_followers'] = $init_client_datas[0]['insta_followers_ini'];
            $datas1['my_initial_followings'] = $init_client_datas[0]['insta_following'];            
            
            $datas1['my_login_profile'] = $this->session->userdata('login');
            $datas1['unfollow_total'] = $this->session->userdata('unfollow_total');
            $datas1['autolike'] = $this->session->userdata('autolike');
            $datas1['play_pause'] = (int) $init_client_datas[0]['paused'];
            $datas1['plane_id'] = $this->session->userdata('plane_id');
            $datas1['all_planes'] = $this->client_model->get_all_planes();
            $datas1['currency'] = $GLOBALS['sistem_config']->CURRENCY;
            $datas1['language'] =  $GLOBALS['language'];

            $daily_report = $this->get_daily_report($this->session->userdata('id'));
            $datas1['followings'] = $daily_report['followings'];
            $datas1['followers']  = $daily_report['followers'];

            if ($this->session->userdata('status_id') == user_status::VERIFY_ACCOUNT || $this->session->userdata('status_id') == user_status::BLOCKED_BY_INSTA) {
                $insta_login = $this->is_insta_user($this->session->userdata('login'), $this->session->userdata('pass'));
                if ($insta_login['status'] === 'ok') {
                    if ($insta_login['authenticated']) {
                        //1. actualizar estado a ACTIVO
                        $this->user_model->update_user($this->session->userdata('id'), array(
                            'status_id' => user_status::ACTIVE));
                        //2. actualizar la cookies
                        if ($insta_login['insta_login_response']) {
                            $this->client_model->update_client($this->session->userdata('id'), array(
                                'cookies' => json_encode($insta_login['insta_login_response'])));
                            //3. crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
                            $N = count($active_profiles);
                            for ($i = 0; $i < $N; $i++) {
                                $sql = 'SELECT * FROM daily_work WHERE reference_id=' . $active_profiles[$i]['id'];
                                $response = count($this->user_model->execute_sql_query($sql));
                                if (!$response && $active_profiles[$i]['end_date']!=='NULL')
                                    $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $insta_login['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                            }
                        }
                        //4. actualizar la sesion
                        $this->user_model->set_sesion($this->session->userdata('id'), $this->session, $insta_login['insta_login_response']);
                    } else {
                        $this->user_model->update_user($this->session->userdata('id'), array(
                            'status_id' => user_status::BLOCKED_BY_INSTA));
                        $this->user_model->set_sesion($this->session->userdata('id'), $this->session);
                    }
                } else
                if ($insta_login['status'] === 'fail' && ($insta_login['message'] == 'checkpoint_required' || $insta_login['message'] == '')) {
                    //actualizo su estado
                    $this->user_model->update_user($this->session->userdata('id'), array(
                        'status_id' => user_status::VERIFY_ACCOUNT));
                    //eliminar su trabajo si contrasenhas son diferentes
                    $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
                    $N = count($active_profiles);
                    for ($i = 0; $i < $N; $i++) {
                        $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                    }
                    //establezco la sesion
                    $this->user_model->set_sesion($this->session->userdata('id'), $this->session);
                    $datas1['verify_account_datas'] = $insta_login;
                }
            }
            $datas1['status'] = array('status_id' => $this->session->userdata('status_id'), 'status_name' => $status_description[$this->session->userdata('status_id')]);
            $datas1['profiles'] = $this->create_profiles_datas_to_display();
            $data['head_section1'] = $this->load->view('responsive_views/client/client_header_painel', '', true);
            $data['body_section1'] = $this->load->view('responsive_views/client/client_body_painel', $datas1, true);
            $data['body_section4'] = $this->load->view('responsive_views/user/users_talkme_painel', '', true);
            $data['body_section_cancel'] = $this->load->view('responsive_views/client/client_cancel_painel', '', true);
            $data['body_section5'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
            $this->load->view('client_view', $data);
        } else {
            $this->display_access_error();
        }
    }

    public function user_do_login($datas=NULL) {
        $login_by_client=false;
        if(!isset($datas)){
            $datas = $this->input->post();
            $language=$this->input->get();
            $login_by_client=true;
        }
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        if(isset($language['language']))
            $param['language']=$language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;    
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
        $GLOBALS['language']=$param['language'];
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        //Is an active Administrator?
        $query = 'SELECT * FROM users' .
                ' WHERE login="' . $datas['user_login'] . '" AND pass="' . $datas['user_pass'] .
                '" AND role_id=' . user_role::ADMIN.' AND status_id=' . user_status::ACTIVE;
        $user = $this->user_model->execute_sql_query($query);
        if (count($user)) {
            $result['role'] = 'ADMIN';
            $result['str'] = 'login=' . urlencode($datas['user_login']) . '&pass=' . urlencode($datas['user_pass']);
            $result['authenticated'] = true;
        } else {
            //Is an active Attendent?
            $query = 'SELECT * FROM users' .
                    ' WHERE login="' . $datas['user_login'] . '" AND pass="' . $datas['user_pass'] .
                    '" AND role_id=' . user_role::ATTENDET . ' AND status_id=' . user_status::ACTIVE;
            $user = $this->user_model->execute_sql_query($query);
            if (count($user)) {
                $result['role'] = 'ATTENDET';
                $result['str'] = urlencode('login=' . $datas['user_login'] . '&pass=' . $datas['user_pass']);
                $result['authenticated'] = true;
            } else {
                //Is an actually Instagram user?
                $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass']);
                if($data_insta==NULL){
                    $result['message'] = $this->T('NÃ£o foi possÃ­vel conferir suas credencias com o Instagram', array(), $GLOBALS['language']);
                    $result['cause'] = 'error_login';
                    $result['authenticated'] = false;
                } else
                if ($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                    //Is a DUMBU Client by Insta ds_user_id?
                    $query = 'SELECT * FROM users,clients' .
                            ' WHERE clients.insta_id="' . $data_insta['insta_id'] . '" AND clients.user_id=users.id';
                    $user = $this->user_model->execute_sql_query($query);

                    $N = count($user);
                    $real_status = 0; //No existe, eliminado o inactivo
                    $index = 0;
                    for ($i = 0; $i < $N; $i++) {
                        if ($user[$i]['status_id'] == user_status::BEGINNER) {
                            $real_status = 1; //Beginner
                            $index = $i;
                            break;
                        } else
                        if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE) {
                            $real_status = 2; //cualquier otro estado
                            $index = $i;
                            break;
                        }
                    }
                    if ($real_status > 1) {
                        $st = (int) $user[$index]['status_id'];
                        if ($st == user_status::BLOCKED_BY_INSTA || $st == user_status::VERIFY_ACCOUNT) {
                            $this->user_model->update_user($user[$index]['id'], array(
                                'name' => $data_insta['insta_name'],
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => user_status::ACTIVE));                                                        
                            if ($data_insta['insta_login_response']) {
                                $this->client_model->update_client($user[$index]['id'], array(
                                    'cookies' => json_encode($data_insta['insta_login_response'])));
                                $this->user_model->set_sesion($user[$index]['id'], $this->session, $data_insta['insta_login_response']);
                            }
                            if($st!=user_status::ACTIVE)
                                $this->user_model->insert_washdog($user[$index]['id'],'FOR ACTIVE STATUS');                            
                            //quitar trabajo si contrasenhas son diferentes
                            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
                            if ($user[$index]['pass'] != $datas['user_pass']) {
                                $N = count($active_profiles);
                                //quitar trabajo si contrasenhas son diferentes
                                for ($i = 0; $i < $N; $i++) {
                                    $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                                }
                            }
                            //crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                            //$active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));                                
                            if($data_insta['insta_login_response']) {
                                $N = count($active_profiles);
                                for ($i = 0; $i < $N; $i++) {
                                    $sql = 'SELECT * FROM daily_work WHERE reference_id=' . $active_profiles[$i]['id'];
                                    $response = count($this->user_model->execute_sql_query($sql));
                                    if (!$response && $active_profiles[$i]['end_date']!=='NULL')
                                        $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                                }
                            }
                            $result['resource'] = 'client';
                            $result['message'] = $this->T('UsuÃ¡rio @1 logueado', array(0 => $datas['user_login']), $GLOBALS['language']);
                            $result['role'] = 'CLIENT';
                            $result['authenticated'] = true;
                        } else
                        if ($st == user_status::ACTIVE || $st == user_status::BLOCKED_BY_PAYMENT || $st == user_status::PENDING || $st == user_status::UNFOLLOW || user_status::BLOCKED_BY_TIME) {
                            if ($st == user_status::ACTIVE) {
                                if ($user[$index]['pass'] != $datas['user_pass']) {
                                    $active_profiles = $this->client_model->get_client_active_profiles($user[$index]['id']);
                                    $N = count($active_profiles);
                                    //quitar trabajo si contrasenhas son diferentes
                                    for ($i = 0; $i < $N; $i++) {
                                        $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                                    }
                                    //crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                                    for ($i = 0; $i < $N; $i++) {
                                        if($active_profiles[$i]['end_date']!=='NULL')
                                            $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                                    }
                                }
                            }

                            if ($st == user_status::UNFOLLOW && $data_insta['insta_following'] < $GLOBALS['sistem_config']->INSTA_MAX_FOLLOWING - $GLOBALS['sistem_config']->MIN_MARGIN_TO_INIT) {
                                $st = user_status::ACTIVE;
                                $active_profiles = $this->client_model->get_client_active_profiles($user[$index]['id']);
                                $N = count($active_profiles);
                                //crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                                for ($i = 0; $i < $N; $i++) {
                                    if($active_profiles[$i]['end_date']!=='NULL')
                                        $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                                }
                            }

                            $this->user_model->update_user($user[$index]['id'], array(
                                'name' => $data_insta['insta_name'],
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => $st));
                            $cad=$this->user_model->get_status_by_id($st)['name'];
                            if ($data_insta['insta_login_response']) {
                                $this->client_model->update_client($user[$index]['id'], array(
                                    'cookies' => json_encode($data_insta['insta_login_response'])));
                            }
                            $this->user_model->set_sesion($user[$index]['id'], $this->session, $data_insta['insta_login_response']);
                            if($st!=user_status::ACTIVE)
                                $this->user_model->insert_washdog($this->session->userdata('id'),'FOR STATUS '.$cad);
                            $result['resource'] = 'client';
                            $result['message'] = $this->T('UsuÃ¡rio @1 logueado', array(0 => $datas['user_login']), $GLOBALS['language']);                            
                            $result['role'] = 'CLIENT';
                            $result['authenticated'] = true;
                        } else
                        if ($st == user_status::BEGINNER) {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = $this->T('Falha no login! Seu cadastro esta incompleto. Por favor, termine sua assinatura.', array(), $GLOBALS['language']);
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        } else
                        if ($st == user_status::DELETED || $st == user_status::INACTIVE) {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = $this->T('Falha no login! VocÃª deve assinar novamente para receber o serviÃ§o', array(), $GLOBALS['language']);
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        }
                    } else {
                        $result['resource'] = 'index#lnk_sign_in_now';
                        $result['message'] = $this->T('Falha no login! VocÃª deve assinar para receber o serviÃ§o', array(), $GLOBALS['language']);
                        $result['cause'] = 'signin_required';
                        $result['authenticated'] = false;
                    }
                } else
                if ($data_insta['status'] === 'ok' && !$data_insta['authenticated']) {
                    //Is a client with oldest Instagram credentials?
                    //Buscarlo en BD por el nombre y senha
                    $query = 'SELECT * FROM users' .
                            ' WHERE users.login="' . $datas['user_login'] .
                            '" AND users.pass="' . $datas['user_pass'] .
                            '" AND users.role_id="' . user_role::CLIENT . '"';
                    $user = $this->user_model->execute_sql_query($query);
                    $N = count($user);
                    $real_status = 0; //No existe, eliminado o inactivo
                    $index = 0;
                    for ($i = 0; $i < $N; $i++) {
                        if ($user[$i]['status_id'] == user_status::BEGINNER) {
                            $real_status = 1; //Beginner
                            $index = $i;
                            break;
                        } else
                        if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE) {
                            $real_status = 2; //cualquier otro estado
                            $index = $i;
                            break;
                        }
                    }
                    if ($real_status > 0) {
                        if ($user[$index]['status_id'] != user_status::DELETED && $user[$index]['status_id'] != user_status::INACTIVE) {
                            $result['resource'] = 'index';
                            $result['message'] = $this->T('Falha no login! Entre com suas credenciais do Instagram.', array(), $GLOBALS['language']);
                            $result['cause'] = 'credentials_update_required';
                            $result['authenticated'] = false;
                        } else {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = $this->T('VocÃª deve assinar novamente para receber o serviÃ§o.', array(), $GLOBALS['language']);
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        }
                    } else {
                        //Verificar que el userLogin y respectivo ds_user_id pueden pertenecer a un usuario que
                        //esta intentando entrar por 3 o mas veces con senha antigua
                        //Buscarlo en BD por pk obtenido por el nombre de usuario informado
                        $data_profile = $this->check_insta_profile($datas['user_login']);
                        if ($data_profile) {
                            $query = 'SELECT * FROM users,clients' .
                                    ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                            $user = $this->user_model->execute_sql_query($query);
                            $N = count($user);
                            $real_status = 0; //No existe, eliminado o inactivo
                            $index = 0;
                            for ($i = 0; $i < $N; $i++) {
                                if ($user[$i]['status_id'] == user_status::BEGINNER) {
                                    $real_status = 1; //Beginner
                                    $index = $i;
                                    break;
                                } else
                                if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE) {
                                    $real_status = 2; //cualquier otro estado
                                    $index = $i;
                                    break;
                                }
                            }
                            if ($real_status > 0) {
                                //perfil exite en instagram y en la base de datos, senha incorrecta           
                                $result['message'] = $this->T('Senha incorreta!. Entre com sua senha de Instagram.', array(), $GLOBALS['language']);
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;
                            } else {
                                //el perfil existe en instagram pero no en la base de datos
                                $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;
                            }
                        } else {
                            //nombre de usuario informado no existe en instagram
                            $result['message'] = $this->T('Falha no login! O nome de usuÃ¡rio fornecido nÃ£o existe no Instagram.', array(), $GLOBALS['language']);
                            $result['cause'] = 'error_login';
                            $result['authenticated'] = false;
                        }
                    }
                } else
                if ($data_insta['status'] === 'fail' && $data_insta['message'] == 'checkpoint_required') {
                    $data_profile = $this->check_insta_profile($datas['user_login']);
                    $query = 'SELECT * FROM users,clients' .
                            ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                    $user = $this->user_model->execute_sql_query($query);
                    $N = count($user);
                    $real_status = 0; //No existe, eliminado o inactivo
                    $index = 0;
                    for ($i = 0; $i < $N; $i++) {
                        if ($user[$i]['status_id'] == user_status::BEGINNER) {
                            $real_status = 1; //Beginner
                            $index = $i;
                            break;
                        } else
                        if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE) {
                            $real_status = 2; //cualquier otro estado
                            $index = $i;
                            break;
                        }
                    }
                    if ($real_status == 2) {
                        $status_id = $user[$index]['status_id'];
                        if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                            $status_id = user_status::VERIFY_ACCOUNT;
                            $this->user_model->insert_washdog($user[$index]['id'],'FOR VERIFY ACCOUNT STATUS');
                        }
                        $this->user_model->update_user($user[$index]['id'], array(
                            'login' => $datas['user_login'],
                            'pass' => $datas['user_pass'],
                            'status_id' => $status_id
                        ));
                        $cad=$this->user_model->get_status_by_id($status_id)['name'];                        
                        $this->user_model->set_sesion($user[$index]['id'], $this->session);
                        if($st!=user_status::ACTIVE)
                            $this->user_model->insert_washdog($this->session->userdata('id'),'FOR STATUS '.$cad);
                        $result['resource'] = 'client';                        
                        $result['verify_link'] = $data_insta['verify_account_url'];
                        $result['return_link'] = 'client';
                        $result['message'] = $this->T('Sua conta precisa ser verificada no Instagram', array(), $GLOBALS['language']);
                        $result['cause'] = 'checkpoint_required';
                        $result['authenticated'] = true;
                    } else {
                        //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                        $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                        $result['cause'] = 'error_login';
                        $result['authenticated'] = false;
                    }
                } else
                if ($data_insta['status'] === 'fail' && ($data_insta['message'] == '' || $data_insta['message'] == 'phone_verification_settings')) {
                    if (isset($data_insta['obfuscated_phone_number'])) {
                        $data_profile = $this->check_insta_profile($datas['user_login']);
                        $query = 'SELECT * FROM users,clients' .
                                ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                        $user = $this->user_model->execute_sql_query($query);
                        $N = count($user);
                        $real_status = 0; //No existe, eliminado o inactivo
                        $index = 0;
                        for ($i = 0; $i < $N; $i++) {
                            if ($user[$i]['status_id'] == user_status::BEGINNER) {
                                $real_status = 1; //Beginner
                                $index = $i;
                                break;
                            } else
                            if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE) {
                                $real_status = 2; //cualquier otro estado
                                $index = $i;
                                break;
                            }
                        }
                        if ($real_status == 2) {
                            $status_id = $user[$index]['status_id'];
                            if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                                $status_id = user_status::VERIFY_ACCOUNT;
                                $this->user_model->insert_washdog($user[$index]['id'],'FOR VERIFY ACCOUNT STATUS');
                            }
                            $this->user_model->update_user($user[$index]['id'], array(
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => $status_id
                            ));
                            $cad=$this->user_model->get_status_by_id($status_id)['name'];
                            $this->user_model->set_sesion($user[$index]['id'], $this->session);
                            $this->user_model->insert_washdog($this->session->userdata('id'),'FOR STATUS '.$cad);
                            $result['return_link'] = 'index';
                            $result['message'] = $this->T('Sua conta precisa ser verificada no Instagram com cÃ³digo enviado ao numero de telefone que comtÃªnm os digitos ', array(0 => $data_insta['obfuscated_phone_number']), $GLOBALS['language']);
                            $result['cause'] = 'phone_verification_settings';
                            $result['verify_link'] = '';
                            $result['obfuscated_phone_number'] = $data_insta['obfuscated_phone_number'];
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                            $result['cause'] = 'error_login';
                            $result['authenticated'] = false;
                        }
                    } else
                    if ($data_insta['message'] === 'empty_message') {
                        $data_profile = $this->check_insta_profile($datas['user_login']);
                        $query = 'SELECT * FROM users,clients' .
                                ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                        $user = $this->user_model->execute_sql_query($query);
                        $N = count($user);
                        $real_status = 0; //No existe, eliminado o inactivo
                        $index = 0;
                        for ($i = 0; $i < $N; $i++) {
                            if ($user[$i]['status_id'] == user_status::BEGINNER) {
                                $real_status = 1; //Beginner
                                $index = $i;
                                break;
                            } else
                            if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE) {
                                $real_status = 2; //cualquier otro estado
                                $index = $i;
                                break;
                            }
                        }
                        if ($real_status == 2) {
                            $status_id = $user[$index]['status_id'];
                            if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                                $status_id = user_status::VERIFY_ACCOUNT;
                                $this->user_model->insert_washdog($user[$index]['id'],'FOR VERIFY ACCOUNT STATUS');
                            }
                            $this->user_model->update_user($user[$index]['id'], array(
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => $status_id
                            ));
                            $cad=$this->user_model->get_status_by_id($status_id)['name'];
                            $this->user_model->set_sesion($user[$index]['id'], $this->session);
                            $this->user_model->insert_washdog($this->session->userdata('id'),'FOR STATUS '.$cad);
                            $result['resource'] = 'client';
                            $result['return_link'] = 'index';
                            $result['verify_link'] = '';
                            $result['message'] = $this->T('Sua conta esta presentando problemas temporalmente no Instagram. Entre em contato conosco para resolver o problema', array(), $GLOBALS['language']);
                            $result['cause'] = 'empty_message';
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                            $result['cause'] = 'error_login';
                            $result['authenticated'] = false;
                        }
                    } else
                    if ($data_insta['message'] = 'unknow_message') {
                        $data_profile = $this->check_insta_profile($datas['user_login']);
                        $query = 'SELECT * FROM users,clients' .
                                ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                        $user = $this->user_model->execute_sql_query($query);
                        $N = count($user);
                        $real_status = 0; //No existe, eliminado o inactivo
                        $index = 0;
                        for ($i = 0; $i < $N; $i++) {
                            if ($user[$i]['status_id'] == user_status::BEGINNER) {
                                $real_status = 1; //Beginner
                                $index = $i;
                                break;
                            } else
                            if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE) {
                                $real_status = 2; //cualquier otro estado
                                $index = $i;
                                break;
                            }
                        }
                        if ($real_status == 2) {
                            $status_id = $user[$index]['status_id'];
                            if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                                $status_id = user_status::VERIFY_ACCOUNT;
                                $this->user_model->insert_washdog($user[$index]['id'],'FOR VERIFY ACCOUNT STATUS');
                            }
                            $this->user_model->update_user($user[$index]['id'], array(
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => $status_id
                            ));
                            $cad=$this->user_model->get_status_by_id($status_id)['name'];
                            if($st!=user_status::ACTIVE)
                                $this->user_model->insert_washdog($user[$index]['id'],'FOR STATUS '.$cad);
                            $result['resource'] = 'client';
                            $result['return_link'] = 'index';
                            $result['verify_link'] = '';
                            $result['message'] = $data_insta['unknow_message'];
                            $result['cause'] = 'unknow_message';
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                            $result['cause'] = 'error_login';
                            $result['authenticated'] = false;
                        }
                    }
                } else {
                    $result['message'] = $this->T('Se o problema no login continua, por favor entre em contato com o Atendimento', array(), $GLOBALS['language']);
                    $result['cause'] = 'error_login';
                    $result['authenticated'] = false;
                }
            }
        }
        if($result['authenticated'] == true){
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'),'DID LOGIN ');
        }
        if($login_by_client)
            echo json_encode($result);
        else
            return $result;
    }

    public function check_ticket_peixe_urbano() {
        $this->load->model('class/client_model');
        $datas = $this->input->post();
        if(true){
            $this->client_model->update_client($datas['pk'], array(
                'ticket_peixe_urbano'=>$datas['cupao_number']));
            $result['success'] = true;
            $result['message'] = 'CUPOM de desconto verificado corretamennte';
        } else{
            $result['success'] = false;
            $result['message'] = 'CUPOM de desconto incorreto';
        }
        echo json_encode($result);
    }
    
       
    //Sign-in functions
    //Passo 1. Chequeando usuario em IG
    public function check_user_for_sing_in($datas=NULL) { //sign in with passive instagram profile verification
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');
        $origin_datas=$datas;
        if(!$datas){
            $datas = $this->input->post();
             $GLOBALS['language']=$datas['language'];
        }

        $datas['utm_source'] = isset($datas['utm_source']) ? urldecode($datas['utm_source']) : "NULL";
        
        $data_insta = $this->check_insta_profile($datas['client_login']);
        if ($data_insta) {
            if (!isset($data_insta->following))
                $data_insta->following = 0;
            $query = 'SELECT * FROM users,clients WHERE clients.insta_id="' . $data_insta->pk . '"' .
                    'AND clients.user_id=users.id';
            $client = $this->user_model->execute_sql_query($query);
            $N = count($client);
            $real_status = -1; //No existe
            $early_client_canceled = false;
            $index = 0;
            for ($i = 0; $i < $N; $i++) {
                if ($client[$i]['status_id'] == user_status::DELETED || $client[$i]['status_id'] == user_status::INACTIVE) {
                    $real_status = 0; //cancelado o inactivo
                    $early_client_canceled = true;
                    $index = $i;
                    //break;
                } else
                if ($client[$i]['status_id'] == user_status::BEGINNER) {
                    $real_status = 1; //Beginner
                    $index = $i;
                    break;
                } else
                if ($client[$i]['status_id'] != user_status::DELETED && $client[$i]['status_id'] != user_status::INACTIVE) {
                    $real_status = 2; //cualquier otro estado
                    break;
                }
            }
            if ($real_status == -1 || $real_status == 0) {
                $datas['role_id'] = user_role::CLIENT;
                $datas['status_id'] = user_status::BEGINNER;
                $datas['HTTP_SERVER_VARS'] = json_encode($_SERVER);
                $datas['purchase_counter'] =$GLOBALS['sistem_config']->MAX_PURCHASE_RETRY;
                $id_user = $this->client_model->insert_client($datas, $data_insta);
                $response['pk'] = $id_user;
                if ($real_status == 0 || $early_client_canceled)
                    $response['early_client_canceled'] = true;
                else
                    $response['early_client_canceled'] = false;
                $response['datas'] = json_encode($data_insta);
                $response['success'] = true;
                $security_code=rand(100000,999999);
                $this->security_purchase_code=md5("$security_code");
                //TODO: enviar para el navegador los datos del usuario logueado en las cookies para chequearlas en los PASSOS 2 y 3
            } else {
                if ($real_status ==1) {
                    $this->user_model->update_user($client[$i]['id'], array(
                        'name' => $data_insta->full_name,
                        'email' => $datas['client_email'],
                        'login' => $datas['client_login'],
                        'pass' => $datas['client_pass'],
                        'language' =>  $GLOBALS['language'],
                        'init_date' => time()));
                    $this->client_model->update_client($client[$i]['id'], array(
                        'insta_followers_ini' => $data_insta->follower_count,
                        'insta_following' => $data_insta->following,
                        'utm_source'=>$datas['utm_source'],
                        'HTTP_SERVER_VARS' => json_encode($_SERVER)));
                    
                    $this->client_model->insert_initial_instagram_datas($client[$i]['id'], array(
                        'followers' => $data_insta->follower_count,
                        'followings' => $data_insta->following,
                        'date' => time()));
                    $response['datas'] = json_encode($data_insta);
                    if ($early_client_canceled)
                        $response['early_client_canceled'] = true;
                    else
                        $response['early_client_canceled'] = false;
                    $response['pk'] = $client[$index]['user_id'];
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['message'] = $this->T('O usuario informado jÃ¡ tem cadastro no sistema.', array(), $GLOBALS['language']);
                }
            }
            if ($response['success'] == true) {
                $response['need_delete'] = ($GLOBALS['sistem_config']->INSTA_MAX_FOLLOWING - $data_insta->following);
                //TODO: guardar esta cantidad en las cookies para trabajar con lo que este en la cookie
                $response['MIN_MARGIN_TO_INIT'] = $GLOBALS['sistem_config']->MIN_MARGIN_TO_INIT;
            }
        } else {
            $response['success'] = false;
            $response['cause'] = 'missing_user';
            $response['message'] = $this->T('O nome de usuario informado nÃ£o Ã© um perfil do Instagram.', array(), $GLOBALS['language']);
        }
        if(!$origin_datas)
            echo json_encode($response);
        else
            return $response;
    }
    
    //Passo 2. CChequeando datos bancarios y guardando datos y estado del cliente pagamento 
    public function check_client_data_bank($datas=NULL) {  
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $origin_datas=$datas;
        if($datas==NULL)
            $datas = $this->input->post();
        $this->load->model('class/client_model');
        $query='SELECT status_id FROM users WHERE id='.$datas['pk'];
        $aaa=$this->client_model->execute_sql_query($query);   
        $aaa=$aaa[0]['status_id'];
        if($aaa==='8' || $aaa==='4'){
            $query='SELECT purchase_counter FROM clients WHERE user_id='.$datas['pk'];
            $purchase_counter = ($this->client_model->execute_sql_query($query));
            $purchase_counter=(int)$purchase_counter[0]['purchase_counter'];
            if($purchase_counter>0){
                $this->load->model('class/user_model');
                $this->load->model('class/user_status');
                $this->load->model('class/credit_card_status');
                if($this->validate_post_credit_card_datas($datas)){
                    //0. salvar datos del carton de credito
                    try {
                        $this->client_model->update_client($datas['pk'], array(
                            'credit_card_number' => $datas['credit_card_number'],
                            'credit_card_cvc' => $datas['credit_card_cvc'],
                            'credit_card_name' => $datas['credit_card_name'],
                            'credit_card_exp_month' => $datas['credit_card_exp_month'],
                            'credit_card_exp_month' => $datas['credit_card_exp_month'],
                            'credit_card_exp_year' => $datas['credit_card_exp_year']//,
                            //'card_type' => $card_type
                        ));

                        $this->client_model->update_client($datas['pk'], array(
                            'plane_id' => $datas['plane_type']));

                        if(isset($datas['ticket_peixe_urbano'])){
                                $ticket=trim($datas['ticket_peixe_urbano']);                        
                                $this->client_model->update_client($datas['pk'], array(
                                    'ticket_peixe_urbano' => $ticket
                                ));
                            }
                                                
                    } catch (Exception $exc) {
                        $result['success'] = false;
                        $result['exception'] = $exc->getTraceAsString();
                        $result['message'] = $this->T('Error actualizando en base de datos', array(), $GLOBALS['language'], $GLOBALS['language']);
                        //2. hacel el pagamento segun el plano
                    } finally {
                        // TODO: Hacer clase Plane
                        if ($datas['plane_type'] === '2' || $datas['plane_type'] === '3' || $datas['plane_type'] === '4' || $datas['plane_type'] === '5' || $datas['plane_type'] === '1') {
                            $sql = 'SELECT * FROM plane WHERE id=' . $datas['plane_type'];
                            $plane_datas = $this->user_model->execute_sql_query($sql)[0];
                            if($card_type==0)
                                $response = $this->do_payment_by_plane($datas, $plane_datas['initial_val'], $plane_datas['normal_val']);                            
                        } else
                            $response['flag_initial_payment'] = false;
                    }
                    //3. si pagamento correcto: logar cliente, establecer sesion, actualizar status, emails, initdate

                    if($response['flag_initial_payment']) {
                        $this->load->model('class/user_model');
                        $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass']);
                        //$this->user_model->insert_washdog($datas['pk'],'SUCCESSFUL PURCHASE');
                        if ($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                            /*if ($datas['need_delete'] < $GLOBALS['sistem_config']->MIN_MARGIN_TO_INIT)
                                $datas['status_id'] = user_status::UNFOLLOW;
                            else*/
                                $datas['status_id'] = user_status::ACTIVE;
                            $this->user_model->update_user($datas['pk'], array(
                                'init_date' => time(),
                                'status_id' => $datas['status_id']));
                            if($data_insta['insta_login_response']) {
                                $this->client_model->update_client($datas['pk'], array(
                                    'cookies' => json_encode($data_insta['insta_login_response'])));
                            }
                            $this->user_model->set_sesion($datas['pk'], $this->session, $data_insta['insta_login_response']);
                        
                        } else
                        if ($data_insta['status'] === 'ok' && !$data_insta['authenticated']) {
                            $this->user_model->update_user($datas['pk'], array(
                                'init_date' => time(),
                                'status_id' => user_status::BLOCKED_BY_INSTA));
                            $this->user_model->set_sesion($datas['pk'], $this->session);
                        } else
                        if ($data_insta['status'] === 'fail' && $data_insta['message'] == 'checkpoint_required') {
                            $this->user_model->update_user($datas['pk'], array(
                                'init_date' => time(),
                                'status_id' => user_status::VERIFY_ACCOUNT));
                            $result['resource'] = 'client';
                            $result['verify_link'] = $data_insta['verify_account_url'];
                            $result['return_link'] = 'client';
                            $result['message'] = 'Sua conta precisa ser verificada no Instagram';
                            $result['cause'] = 'checkpoint_required';
                            $this->user_model->set_sesion($datas['pk'], $this->session);
                        } else
                        if ($data_insta['status'] === 'fail' && $data_insta['message'] == '') {
                            $this->user_model->update_user($datas['pk'], array(
                                'init_date' => time(),
                                'status_id' => user_status::VERIFY_ACCOUNT));
                            $result['resource'] = 'client';
                            $result['verify_link'] = '';
                            $result['return_link'] = 'client';
                            $this->user_model->set_sesion($datas['pk'], $this->session);
                        } else {
                            $this->user_model->update_user($datas['pk'], array(
                                'init_date' => time(),
                                'status_id' => user_status::BLOCKED_BY_INSTA));
                            $this->user_model->set_sesion($datas['pk'], $this->session);
                        }
                        //Email com compra satisfactoria a atendimento y al cliente
                        //$this->email_success_buy_to_atendiment($datas['user_login'], $datas['user_email']);
                        if ($data_insta['status'] === 'ok' && $data_insta['authenticated'])
                            $this->email_success_buy_to_client($datas['user_email'], $data_insta['insta_name'], $datas['user_login'], $datas['user_pass']);
                        else
                            $this->email_success_buy_to_client($datas['user_email'], $datas['user_login'], $datas['user_login'], $datas['user_pass']);
                        $result['success'] = true;
                        $result['flag_initial_payment'] = $response['flag_initial_payment'];
                        $result['flag_recurrency_payment'] = $response['flag_recurrency_payment'];
                        $result['message'] = $this->T('UsuÃ¡rio cadastrado com sucesso', array(), $GLOBALS['language']);
                    } else {
                        $value['purchase_counter']=$purchase_counter-1;
                        $this->client_model->decrement_purchase_retry($datas['pk'],$value);
                        $result['success'] = false;
                        $result['message'] = $response['message'];
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Acesso nÃ£o permitido', array(), $GLOBALS['language']);
                } 
            }else{
                $result['success'] = false;
                $result['message'] = $this->T('AlcanÃ§Ãµu a quantidade mÃ¡xima de retentativa de compra, por favor, entre en contato con o atendimento', array(), $GLOBALS['language']);
            }
        }else{
            $result['success'] = false;
            $result['message'] = $this->T('Acesso nÃ£o permitido', array(), $GLOBALS['language']);
        }
        
        if(!$origin_datas)
            echo json_encode($result);
        else
            return $result;
    }

    public function do_payment_by_plane($datas, $initial_value, $recurrency_value) {
        $this->load->model('class/client_model');
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        //Amigos de Pedro
        if(isset($datas['ticket_peixe_urbano']) && strtoupper($datas['ticket_peixe_urbano'])==='AMIGOSDOPEDRO'){
                //1. recurrencia para un mes mas alante
                $datas['amount_in_cents'] = $recurrency_value;
                if ($datas['early_client_canceled'] === 'true'){
                    $resp = $this->check_mundipagg_credit_card($datas);
                    if(!(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $response['flag_recurrency_payment'] = false;
                        $response['flag_initial_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        return $response;
                    }
                }
                $datas['pay_day'] = strtotime("+1 month", time());
                $resp = $this->check_recurrency_mundipagg_credit_card($datas, 0);
                if (is_object($resp) && $resp->isSuccess()) {
                    $this->client_model->update_client($datas['pk'], array(
                        'order_key' => $resp->getData()->OrderResult->OrderKey,
                        'pay_day' => $datas['pay_day']));
                    $response['flag_initial_payment'] = true;
                    $response['flag_recurrency_payment'] = true;
                } else {
                    $response['flag_recurrency_payment'] = false;
                    $response['flag_initial_payment'] = false;
                    $response['message'] = $this->T('Compra nÃ£o sucedida. Problemas com o pagamento', array(), $GLOBALS['language']);
                } 
        } else 
        if(isset($datas['ticket_peixe_urbano']) && ($datas['ticket_peixe_urbano']==='OLX' || $datas['ticket_peixe_urbano']==='INSTA50P')){
                $resp=1;
                if ($datas['early_client_canceled'] === 'true'){
                    $datas['amount_in_cents'] = $recurrency_value/2;
                    $datas['pay_day']=time();
                    $resp = $this->check_mundipagg_credit_card($datas);                    
                    if(!(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $response['flag_recurrency_payment'] = false;
                        $response['flag_initial_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        return $response;
                    }
                } else{
                    $kk=$GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS;
                    $t=time();
                    $datas['pay_day'] = strtotime("+" . $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS . " days", $t);
                    $t2=$datas['pay_day'];
                    $datas['amount_in_cents'] = $recurrency_value/2;
                    $resp = $this->check_recurrency_mundipagg_credit_card($datas,1);  
                }
            
                //guardo el initial order key
                if(is_object($resp) && $resp->isSuccess()){
                    $this->client_model->update_client($datas['pk'], array('initial_order_key' => $resp->getData()->OrderResult->OrderKey));                    
                    $response['flag_initial_payment'] = true;

                    //genero una recurrencia un mes mas alante
                    $datas['amount_in_cents'] = $recurrency_value;
                    $datas['pay_day'] = strtotime("+1 month", $datas['pay_day']);
                    $resp = $this->check_recurrency_mundipagg_credit_card($datas, 0);
                    if (is_object($resp) && $resp->isSuccess()) {
                        $this->client_model->update_client($datas['pk'], array(
                            'order_key' => $resp->getData()->OrderResult->OrderKey,
                            'pay_day' => $datas['pay_day']));
                        $response['flag_recurrency_payment'] = true;
                    } else {
                        $response['flag_recurrency_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {                        
                            $this->client_model->update_client($datas['pk'], array('order_key' => $resp->getData()->OrderResult->OrderKey));
                        }
                    }
                } else{
                    $response['flag_recurrency_payment'] = false;
                    $response['flag_initial_payment'] = false;
                    if(is_array($resp))
                        $response['message'] = 'Error: '.$resp["message"]; 
                    else
                        $response['message'] = 'Incorrect credit card datas!!';
                    if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {                        
                        $this->client_model->update_client($datas['pk'], array('initial_order_key' => $resp->getData()->OrderResult->OrderKey));
                }
            }
        }else
        if(isset($datas['ticket_peixe_urbano']) &&  $datas['ticket_peixe_urbano']==='DUMBUDF20'){
                $datas['amount_in_cents'] = round(($recurrency_value*8)/10);
                if ($datas['early_client_canceled'] === 'true'){
                    $resp = $this->check_mundipagg_credit_card($datas);
                    if(!(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $response['flag_recurrency_payment'] = false;
                        $response['flag_initial_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        return $response;
                    } else{
                        $datas['pay_day'] = strtotime("+1 month", time());                
                        $resp = $this->check_recurrency_mundipagg_credit_card($datas,0);
                    }
                } else{
                    $datas['pay_day'] = strtotime("+" . $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS . " days", time());                
                    $resp = $this->check_recurrency_mundipagg_credit_card($datas,0);
                }
                if (is_object($resp) && $resp->isSuccess()) {
                    $this->client_model->update_client($datas['pk'], array(
                        'order_key' => $resp->getData()->OrderResult->OrderKey,
                        'pay_day' => $datas['pay_day']));
                    $this->client_model->update_client($datas['pk'], array(
                        'actual_payment_value' => $datas['amount_in_cents']));
                    $response['flag_recurrency_payment'] = true;
                    $response['flag_initial_payment'] = true;
                } else {
                    $response['flag_recurrency_payment'] = false;
                    $response['flag_initial_payment'] = false;
                    if(is_array($resp))
                        $response['message'] = 'Error: '.$resp["message"]; 
                    else
                        $response['message'] = 'Incorrect credit card datas!!';
                    if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {                        
                        $this->client_model->update_client($datas['pk'], array('order_key' => $resp->getData()->OrderResult->OrderKey));
                    }
                }
            } else
        if(isset($datas['ticket_peixe_urbano']) && ($datas['ticket_peixe_urbano']==='INSTA-DIRECT' || $datas['ticket_peixe_urbano']==='MALADIRETA')){
                $datas['amount_in_cents'] = $recurrency_value;
                if ($datas['early_client_canceled'] === 'true'){
                    $resp = $this->check_mundipagg_credit_card($datas);
                    if(!(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $response['flag_recurrency_payment'] = false;
                        $response['flag_initial_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        return $response;
                    } else{
                        $datas['pay_day'] = strtotime("+1 month", time());
                    }
                } else{
                    $datas['pay_day'] = strtotime("+" .'7'. " days", time());
                }                          
                $resp = $this->check_recurrency_mundipagg_credit_card($datas,0);
                if (is_object($resp) && $resp->isSuccess()) {
                    $this->client_model->update_client($datas['pk'], array(
                        'order_key' => $resp->getData()->OrderResult->OrderKey,
                        'pay_day' => $datas['pay_day']));
                    $response['flag_recurrency_payment'] = true;
                    $response['flag_initial_payment'] = true;
                } else {
                    $response['flag_recurrency_payment'] = false;
                    $response['flag_initial_payment'] = false;
                    if(is_array($resp))
                        $response['message'] = 'Error: '.$resp["message"]; 
                    else
                        $response['message'] = 'Incorrect credit card datas!!';
                    if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {                        
                        $this->client_model->update_client($datas['pk'], array('order_key' => $resp->getData()->OrderResult->OrderKey));
                    }
                }
            }else  
        if(isset($datas['ticket_peixe_urbano']) && $datas['ticket_peixe_urbano']==='INSTA15D'){
                $datas['amount_in_cents'] = $recurrency_value;
                if ($datas['early_client_canceled'] === 'true'){
                    $resp = $this->check_mundipagg_credit_card($datas);
                    if(!(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $response['flag_recurrency_payment'] = false;
                        $response['flag_initial_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        return $response;
                    } else{
                        $datas['pay_day'] = strtotime("+1 month", time());
                    }
                } else{
                    $datas['pay_day'] = strtotime("+" .'15'. " days", time());
                }
                $resp = $this->check_recurrency_mundipagg_credit_card($datas,0);
                if (is_object($resp) && $resp->isSuccess()) {
                    $this->client_model->update_client($datas['pk'], array(
                        'order_key' => $resp->getData()->OrderResult->OrderKey,
                        'pay_day' => $datas['pay_day']));
                    $response['flag_recurrency_payment'] = true;
                    $response['flag_initial_payment'] = true;
                } else {
                    $response['flag_recurrency_payment'] = false;
                    $response['flag_initial_payment'] = false;
                    if(is_array($resp))
                        $response['message'] = 'Error: '.$resp["message"]; 
                    else
                        $response['message'] = 'Incorrect credit card datas!!';
                    if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {                        
                        $this->client_model->update_client($datas['pk'], array('order_key' => $resp->getData()->OrderResult->OrderKey));
                    }
                }
            } else
        if(isset($datas['ticket_peixe_urbano']) && $datas['ticket_peixe_urbano']==='SIBITE30D'){ //30 dias de graça
                $datas['amount_in_cents'] = $recurrency_value;
                if ($datas['early_client_canceled'] === 'true'){
                    $resp = $this->check_mundipagg_credit_card($datas);
                    if(!(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $response['flag_recurrency_payment'] = false;
                        $response['flag_initial_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        return $response;
                    } else{
                        $datas['pay_day'] = strtotime("+1 month", time());
                    }
                } else{
                    $datas['pay_day'] = strtotime("+" .'30'. " days", time());
                }
                $resp = $this->check_recurrency_mundipagg_credit_card($datas,0);
                if (is_object($resp) && $resp->isSuccess()) {
                    $this->client_model->update_client($datas['pk'], array(
                        'order_key' => $resp->getData()->OrderResult->OrderKey,
                        'pay_day' => $datas['pay_day']));
                    $response['flag_recurrency_payment'] = true;
                    $response['flag_initial_payment'] = true;
                } else {
                    $response['flag_recurrency_payment'] = false;
                    $response['flag_initial_payment'] = false;
                    if(is_array($resp))
                        $response['message'] = 'Error: '.$resp["message"]; 
                    else
                        $response['message'] = 'Incorrect credit card datas!!';
                    if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {
                        $this->client_model->update_client($datas['pk'], array('order_key' => $resp->getData()->OrderResult->OrderKey));
                    }
                }
            }else
        if(isset($datas['ticket_peixe_urbano']) && (strtoupper($datas['ticket_peixe_urbano'])==='BACKTODUMBU' || strtoupper($datas['ticket_peixe_urbano'])==='BACKTODUMBU-DNLO' ||strtoupper($datas['ticket_peixe_urbano'])==='BACKTODUMBU-EGBTO') && ($datas['early_client_canceled'] === 'true' || $datas['early_client_canceled'] === true) ){
                //cobro la mitad en la hora
                $datas['pay_day'] = time();
                $datas['amount_in_cents'] = $recurrency_value/2;
                $resp = $this->check_mundipagg_credit_card($datas);
                if(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0){
                    $this->client_model->update_client(
                            $datas['pk'], 
                            array('initial_order_key' => $resp->getData()->OrderResult->OrderKey));                    
                    $response['flag_initial_payment'] = true;
                    //genero una recurrencia un mes mas alante
                    $datas['amount_in_cents'] = $recurrency_value;
                    $datas['pay_day'] = strtotime("+1 month", $datas['pay_day']);
                    $resp = $this->check_recurrency_mundipagg_credit_card($datas, 0);
                    if (is_object($resp) && $resp->isSuccess()) {
                        $this->client_model->update_client($datas['pk'], array(
                            'order_key' => $resp->getData()->OrderResult->OrderKey,
                            'pay_day' => $datas['pay_day']));
                        $response['flag_recurrency_payment'] = true;
                    } else {
                        $response['flag_recurrency_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"]; 
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {                        
                            $this->client_model->update_client($datas['pk'], array('order_key' => $resp->getData()->OrderResult->OrderKey));
                        }
                    }
                } else{
                    $response['flag_recurrency_payment'] = false;
                    $response['flag_initial_payment'] = false;
                    if(is_array($resp))
                        $response['message'] = 'Error: '.$resp["message"]; 
                    else
                        $response['message'] = 'Incorrect credit card datas!!';
                    if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {                        
                        $this->client_model->update_client($datas['pk'], array('initial_order_key' => $resp->getData()->OrderResult->OrderKey));
                    }
                }
            } else { //si es un cliente sin codigo promocional
                    $datas['amount_in_cents'] = $recurrency_value;
                    if ($datas['early_client_canceled'] === 'true'){
                        $resp = $this->check_mundipagg_credit_card($datas);
                        if(!(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                            $response['flag_recurrency_payment'] = false;
                            $response['flag_initial_payment'] = false;
                            if(is_array($resp))
                                $response['message'] = 'Error: '.$resp["message"]; 
                            else
                                $response['message'] = 'Incorrect credit card datas!!';
                            return $response;
                        } else{
                            $datas['pay_day'] = strtotime("+1 month", time());
                        }
                    } else{
                        $datas['pay_day'] = strtotime("+" . $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS . " days", time());
                    }       

                    $resp = $this->check_recurrency_mundipagg_credit_card($datas, 0);
                    if (is_object($resp) && $resp->isSuccess()) {
                        $this->client_model->update_client($datas['pk'], array(
                            'order_key' => $resp->getData()->OrderResult->OrderKey,
                            'pay_day' => $datas['pay_day']));
                        $response['flag_recurrency_payment'] = true;
                        $response['flag_initial_payment'] = true;
                    } else {
                        $response['flag_recurrency_payment'] = false;
                        $response['flag_initial_payment'] = false;
                        if(is_array($resp))
                            $response['message'] = 'Error: '.$resp["message"];
                        else
                            $response['message'] = 'Incorrect credit card datas!!';
                        if(is_object($resp) && isset($resp->getData()->OrderResult->OrderKey)) {
                            $this->client_model->update_client($datas['pk'], array('order_key' => $resp->getData()->OrderResult->OrderKey));
                        }
                    }
            }
         return $response;
    }
    
    public function check_mundipagg_credit_card($datas) {
        $payment_data['credit_card_number'] = $datas['credit_card_number'];
        $payment_data['credit_card_name'] = $datas['credit_card_name'];
        $payment_data['credit_card_exp_month'] = $datas['credit_card_exp_month'];
        $payment_data['credit_card_exp_year'] = $datas['credit_card_exp_year'];
        $payment_data['credit_card_cvc'] = $datas['credit_card_cvc'];
        $payment_data['amount_in_cents'] = $datas['amount_in_cents'];
        $payment_data['pay_day'] = time();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        $Payment = new \dumbu\cls\Payment();
        $response = $Payment->create_payment($payment_data);
        return $response;
    }

    public function check_recurrency_mundipagg_credit_card($datas, $cnt) {
        $payment_data['credit_card_number'] = $datas['credit_card_number'];
        $payment_data['credit_card_name'] = $datas['credit_card_name'];
        $payment_data['credit_card_exp_month'] = $datas['credit_card_exp_month'];
        $payment_data['credit_card_exp_year'] = $datas['credit_card_exp_year'];
        $payment_data['credit_card_cvc'] = $datas['credit_card_cvc'];
        $payment_data['amount_in_cents'] = $datas['amount_in_cents'];
        $payment_data['pay_day'] = $datas['pay_day'];
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        $Payment = new \dumbu\cls\Payment();
        
        //5 Cielo -> 1.5 | 32 -> eRede | 20 -> Stone | 42 -> Cielo 3.0 | 0 -> Auto;        
        $response = $Payment->create_recurrency_payment($payment_data, $cnt, 20);
        if (is_object($response) && $response->isSuccess()){
            return $response;
        } else{
            $response = $Payment->create_recurrency_payment($payment_data, $cnt, 5);
            if (is_object($response) && $response->isSuccess()){
                return $response;
            } else{
                $response = $Payment->create_recurrency_payment($payment_data, $cnt, 42);
                return $response;
                /*if (is_object($response) && $response->isSuccess()){
                    return $response;
                } else{
                    $response = $Payment->create_recurrency_payment($payment_data, $cnt, 32);*/
                //}
            }
        }
        
    }

    public function delete_recurrency_payment($order_key) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        $Payment = new \dumbu\cls\Payment();
        $response = $Payment->delete_payment($order_key);
        return $response;
    }

    public function unfollow_total() {
        $this->load->model('class/user_role');
        $this->load->model('class/client_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $datas = $this->input->post();
            $datas['unfollow_total'] = (int) $datas['unfollow_total'];
            //if($this->session->userdata('unfollow_total')==!$datas['unfollow_total']){
            if ($datas['unfollow_total'] == 1) {
                
            } elseif ($datas['unfollow_total'] == 0) {
                
            }
            
            ($datas['unfollow_total']==0)?$ut='DISABLED':$ut='ACTIVATED';
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'),'TOTAL UNFOLLOW '.$ut);
            
            $this->client_model->update_client($this->session->userdata('id'), array(
                'unfollow_total' => $datas['unfollow_total']
            ));
            $response['success'] = true;
            $response['unfollow_total'] = $datas['unfollow_total'];
            
        }
        echo json_encode($response);
    }
    
    public function autolike() {
        $this->load->model('class/user_role');
        $this->load->model('class/client_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $datas = $this->input->post();
            $al=(int) $datas['autolike'];
            $this->client_model->update_client($this->session->userdata('id'), array(
                'like_first' => $al
            ));
            
            ($al==0)?$ut='DISABLED':$ut='ACTIVATED';
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'),'AUTOLIKE '.$ut);
            
            $response['success'] = true;
            $response['autolike'] = $datas['AUTOLIKE'];
        }
        echo json_encode($response);
    }
    
    public function play_pause() {
        $this->load->model('class/user_role');
        $this->load->model('class/client_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $datas = $this->input->post();
            $pp = (int) $datas['play_pause'];
            $this->client_model->update_client($this->session->userdata('id'), array(
                'paused' => $pp
            ));
            
            $ut = 'PAUSED';
            
            if ($pp == 1) {
                $ut = 'PAUSED';
                $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
                $N = count($active_profiles);
                //quitar trabajo si el cliente pauso la herramienta
                for ($i = 0; $i < $N; $i++) {
                    $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                }
            }
            else {
                $ut = 'REACTIVATED';
                //no hacer nada, el robot le pone trabajo al cliente al siguiente dia
            }
            
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'),'TOOL '.$ut);

            
            $response['success'] = true;
            $response['play_pause'] = $datas['play_pause'];
        }
        echo json_encode($response);
    }
    
    public function update_client_datas() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $language=$this->input->get();
        if(isset($language['language']))
            $param['language']=$language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
        $GLOBALS['language']=$param['language'];
        
        if ($this->session->userdata('id')) {
            $this->load->model('class/client_model');
            $this->load->model('class/user_model');
            $this->load->model('class/user_status');
            $this->load->model('class/credit_card_status');
            $datas = $this->input->post();
            $now = time();
            if($this->validate_post_credit_card_datas($datas)) {
                $client_data = $this->client_model->get_client_by_id($this->session->userdata('id'))[0];                               
                if($now<$client_data['pay_day'] && 
                        (  $client_data['ticket_peixe_urbano']==='AGENCIALUUK'
                        || $client_data['ticket_peixe_urbano']==='DUMBUDF20'
                        || $client_data['ticket_peixe_urbano']==='AMIGOSDOPEDRO'
                        || $client_data['ticket_peixe_urbano']==='BACKTODUMBU' 
                        )){                    
                    $result['success'] = false;
                    $result['message'] = 'VocÃª nÃ£o pode atualizar no primeiro mÃªs, entre em contato com nosso atendimento';
                } else {
                    if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT) {
                        if ($now < $client_data['pay_day']) {
                            $payments_days['pay_day'] = strtotime("+30 days", $now);
                            $payments_days['pay_now'] = true;
                            $datas['pay_day'] = $payments_days['pay_day'];
                        } else {
                            $payments_days['pay_day'] = time();
                            $payments_days['pay_now'] = false;
                            $datas['pay_day'] = $payments_days['pay_day'];
                        }
                    } else {
                        $payments_days = $this->get_pay_day($client_data['pay_day']);
                        $datas['pay_day'] = $payments_days['pay_day'];
                    }
                    if ($payments_days['pay_day'] != null) { //dia de actualizacion diferente de dia de pagamento                    
                        try {
                            $this->user_model->update_user($this->session->userdata('id'), array(
                                'email' => $datas['client_email']));
                            $this->client_model->update_client($this->session->userdata('id'), array(
                                'credit_card_number' => $datas['credit_card_number'],
                                'credit_card_cvc' => $datas['credit_card_cvc'],
                                'credit_card_name' => $datas['credit_card_name'],
                                'credit_card_exp_month' => $datas['credit_card_exp_month'],
                                'credit_card_exp_year' => $datas['credit_card_exp_year'],
                                'pay_day' => $datas['pay_day']
                            ));
                        } catch (Exception $exc) {
                            $result['success'] = false;
                            $result['exception'] = $exc->getTraceAsString();
                            $result['message'] = $this->T('Erro actualizando em banco de dados', array(), $GLOBALS['language']);
                        } finally {
                            $flag_pay_now = false;
                            $flag_pay_day = false;

                            //Determinar valor inicial del pagamento
                            if ($datas['client_update_plane'] == 1)
                                $datas['client_update_plane'] = 4;
                            if ($now < $client_data['pay_day'] && ($datas['client_update_plane'] <= $this->session->userdata('plane_id'))) {
                                $pay_values['initial_value'] = $this->client_model->get_promotional_pay_value($datas['client_update_plane']);
                                $pay_values['normal_value'] = $this->client_model->get_normal_pay_value($datas['client_update_plane']);
                            } else
                            if ($now < $client_data['pay_day'] && ($datas['client_update_plane'] > $this->session->userdata('plane_id'))) {
                                $pay_values['initial_value'] = $this->client_model->get_promotional_pay_value($datas['client_update_plane']) - $this->client_model->get_promotional_pay_value($this->session->userdata('plane_id'));
                                $pay_values['normal_value'] = $this->client_model->get_normal_pay_value($datas['client_update_plane']);
                            } else
                            if ($datas['client_update_plane'] > $this->session->userdata('plane_id')) {
                                $promotional_time_range = $this->user_model->get_signin_date($this->session->userdata('id'));
                                $promotional_time_range = strtotime("+" . $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS . " days", $promotional_time_range);
                                $promotional_time_range = strtotime("+1 month", $promotional_time_range);
                                if (time() < $promotional_time_range) {//mes promocional
                                    $pay_values['initial_value'] = $this->client_model->get_promotional_pay_value($datas['client_update_plane']) - $this->client_model->get_promotional_pay_value($this->session->userdata('plane_id'));
                                } else {
                                    $pay_values['initial_value'] = $this->client_model->get_normal_pay_value($datas['client_update_plane']) - $this->client_model->get_normal_pay_value($this->session->userdata('plane_id'));
                                }
                                $pay_values['normal_value'] = $this->client_model->get_normal_pay_value($datas['client_update_plane']);
                                $payments_days['pay_now'] = true;
                            } else
                            if ($datas['client_update_plane'] < $this->session->userdata('plane_id')) {
                                $pay_values['initial_value'] = $this->client_model->get_normal_pay_value($datas['client_update_plane']);
                                $pay_values['normal_value'] = $this->client_model->get_normal_pay_value($datas['client_update_plane']);
                            } else {
                                $pay_values['initial_value'] = $this->client_model->get_normal_pay_value($this->session->userdata('plane_id'));
                                
                                if($client_data['actual_payment_value']!=null)
                                    $pay_values['normal_value'] = $client_data['actual_payment_value'];
                                else
                                    $pay_values['normal_value'] = $this->client_model->get_normal_pay_value($this->session->userdata('plane_id'));
                            }
                            
                            //si necesitara hacer un pagamento ahora
                            if ($payments_days['pay_now']) {                                                    
                                $datas['pay_day'] = time();
                                if($client_data['ticket_peixe_urbano']==='AGENCIALUUK' || $client_data['ticket_peixe_urbano']==='DUMBUDF20') 
                                    $datas['amount_in_cents'] = round(($pay_values['initial_value']*8)/10);
                                else
                                if($client_data['ticket_peixe_urbano']==='OLX')
                                    //$datas['amount_in_cents'] = round(($pay_values['initial_value']*5)/10);
                                    if($now < $client_data['pay_day'])
                                        $datas['amount_in_cents'] = $pay_values['normal_value']/2;
                                    else
                                        $datas['amount_in_cents'] = $pay_values['normal_value'];
                                else
                                    $datas['amount_in_cents'] = $pay_values['initial_value'];
                                $resp_pay_now = $this->check_mundipagg_credit_card($datas);
                                if (is_object($resp_pay_now) && $resp_pay_now->isSuccess() && $resp_pay_now->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0) {
                                    $this->client_model->update_client($this->session->userdata('id'), array(
                                        'pending_order_key' => $resp_pay_now->getData()->OrderResult->OrderKey));
                                    $flag_pay_now = true;
                                }
                            }

                            if (($payments_days['pay_now'] && $flag_pay_now) || !$payments_days['pay_now']) {
                                $response_delete_early_payment = '';
                                $datas['pay_day'] = $payments_days['pay_day'];
                                if($client_data['ticket_peixe_urbano']==='AGENCIALUUK' || $client_data['ticket_peixe_urbano']==='DUMBUDF20')
                                    $datas['amount_in_cents'] = round(($pay_values['normal_value']*8)/10);
                                else
                                    $datas['amount_in_cents'] = $pay_values['normal_value'];

                                $resp_pay_day = $this->check_recurrency_mundipagg_credit_card($datas, 0);
                                if (is_object($resp_pay_day) && $resp_pay_day->isSuccess()) {
                                    $flag_pay_day = true;
                                    try {
                                        $this->client_model->update_client($this->session->userdata('id'), array(
                                            'plane_id' => $datas['client_update_plane'],
                                            'pay_day' => $datas['pay_day'],
                                            'order_key' => $resp_pay_day->getData()->OrderResult->OrderKey));
                                        if ($client_data['order_key'])
                                            $response_delete_early_payment = $this->delete_recurrency_payment($client_data['order_key']);
                                        if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT || $this->session->userdata('status_id') == user_status::PENDING) {
                                            $datas['status_id'] = user_status::ACTIVE; //para que Payment intente hacer el pagamento y si ok entonces lo active y le ponga trabajo
                                        } else
                                            $datas['status_id'] = $this->session->userdata('status_id');
                                        $this->user_model->update_user($this->session->userdata('id'), array(
                                            'status_id' => $datas['status_id']));
                                        if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT) {
                                            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
                                            $N = count($active_profiles);
                                            for ($i = 0; $i < $N; $i++) {
                                                if($active_profiles[$i]['end_date']!=='NULL')
                                                $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $this->session->userdata('insta_datas'), $i, $active_profiles, $this->session->userdata('to_follow'));
                                            }
                                        }
                                        $this->session->set_userdata('plane_id', $datas['client_update_plane']);
                                        $this->session->set_userdata('status_id', $datas['status_id']);
                                    } catch (Exception $exc) {
                                        $this->user_model->update_user($datas['pk'], array(
                                            'status_id' => $this->session->userdata('status_id'))); //the previous
                                        $this->client_model->update_client($datas['pk'], array(
                                            'pay_day' => $client_data['pay_day'], //the previous
                                            'order_key' => $client_data['order_key'])); //the previous
                                        $result['success'] = false;
                                        $result['exception'] = $exc->getTraceAsString();
                                        $result['message'] = $this->T('Erro actualizando em banco de dados', array(), $GLOBALS['language']);
                                    } finally {
                                        $result['success'] = true;
                                        $result['resource'] = 'client';
                                        $result['message'] = $this->T('Dados bancÃ¡rios atualizados corretamente', array(), $GLOBALS['language']);
                                        $result['response_delete_early_payment'] = $response_delete_early_payment;
                                    }
                                }
                            }

                            if (($payments_days['pay_now'] && !$flag_pay_now) || (!$payments_days['pay_now'] && !$flag_pay_day)) {
                                //restablecer en la base de datos los datos anteriores
                                $this->client_model->update_client($this->session->userdata('id'), array(
                                    'credit_card_number' => $client_data['credit_card_number'],
                                    'credit_card_cvc' => $client_data['credit_card_cvc'],
                                    'credit_card_name' => $client_data['credit_card_name'],
                                    'credit_card_exp_month' => $client_data['credit_card_exp_month'],
                                    'credit_card_exp_year' => $client_data['credit_card_exp_year'],
                                    'pay_day' => $client_data['pay_day'],
                                    'order_key' => $client_data['order_key']
                                ));
                                $result['success'] = false;
                                $result['resource'] = 'client';
                                if ($payments_days['pay_now'] && !$flag_pay_now)
                                    $result['message'] = is_array($resp_pay_now) ? $resp_pay_now["message"] : $this->T("Erro inesperado! ProvÃ¡velmente CartÃ£o invÃ¡lido, entre em contato com o atendimento.", array(), $GLOBALS['language']);
                                else
                                    $result['message'] = is_array($resp_pay_day) ? $resp_pay_day["message"] : $this->T("Erro inesperado! ProvÃ¡velmente CartÃ£o invÃ¡lido, entre em contato com o atendimento.", array(), $GLOBALS['language']);
                            } else
                            if (($payments_days['pay_now'] && $flag_pay_now && !$flag_pay_day)) {
                                //se hiso el primer pagamento bien, pero la recurrencia mal
                                $result['success'] = true;
                                $result['resource'] = 'client';
                                $result['message'] = $this->T('ActualizaÃ§Ã£o bem sucedida, mas deve atualizar novamente atÃ© a data de pagamento ( @1 )', array(0 => $payments_days['pay_now']));
                            }
                        }
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('VocÃª nÃ£o pode atualizar seu cartÃ£o no dia do pagamento', array(), $GLOBALS['language']);
                    }
                }
                
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Acesso nÃ£o permitido', array(), $GLOBALS['language']);
            }
            
            if($this->session->userdata('id') && $result['success'] == true){
                $this->load->model('class/user_model');
                 $this->user_model->insert_washdog($this->session->userdata('id'),'CORRECT CARD UPDATE');
            } else{
                if($this->session->userdata('id')){
                    $this->load->model('class/user_model');
                    $this->user_model->insert_washdog($this->session->userdata('id'),'INCORRECT CARD UPDATE');
                }
            }
            
            echo json_encode($result);
        }
    }

    public function get_pay_day($pay_day) {
        $this->load->model('class/user_status');
        $now = time();
        $datas['pay_now'] = false;

        $d_today = date("j", $now);
        $m_today = date("n", $now);
        $y_today = date("Y", $now);
        $d_pay_day = date("j", $pay_day);
        $m_pay_day = date("n", $pay_day);
        $y_pay_day = date("Y", $pay_day);

        if ($now < $pay_day) {
            $datas['pay_day'] = $pay_day;
        } else
        if ($d_today < $d_pay_day) {
            if ($this->session->userdata('status_id') == (string) user_status::PENDING)
                $datas['pay_now'] = true;
            //1. mes anterior respecto a hoy
            $previous_month = strtotime("-30 days", $now);
            //var_dump(date('d-m-Y',$previous_month));
            //2. dia de pagamento en el mes anterior al actual
            $previous_payment_date = strtotime($d_pay_day . '-' . date("n", $previous_month) . '-' . date("Y", $previous_month));
            //var_dump(date('d-m-Y',$previous_payment_date));
            //3. nuevo dia de pagamento para el mes actual
            $datas['pay_day'] = strtotime("+30 days", $previous_payment_date);
            //var_dump(date('d-m-Y',$datas['pay_day']));
        } else
        if ($d_today > $d_pay_day) {
            //0. si pendiente por pagamento, inidcar que se debe hacer pagamento
            //if($this->session->userdata('status_id') == user_status::PENDING)                
            if ($this->session->userdata('status_id') == (string) user_status::PENDING)
                $datas['pay_now'] = true;
            $recorrency_date = strtotime($d_pay_day . '-' . $m_today . '-' . $y_today); //mes actual com el dia de pagamento
            //var_dump(date('d-m-Y',$recorrency_date));
            $datas['pay_day'] = strtotime("+30 days", $recorrency_date); //proximo mes
            //var_dump(date('d-m-Y',$datas['pay_day']));
        } else
            $datas['pay_day'] = false;
        return $datas;
    }

    
    //functions for geolocalizations
    public function client_insert_geolocalization() {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();      
            $language=$this->input->get();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;    
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;  
            $GLOBALS['language']=$param['language'];            
            $this->load->model('class/client_model');
            $this->load->model('class/user_status');
            $profile = $this->input->post();
            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($active_profiles);
            $N_geolocalization=0;
            $is_active_profile = false;
            $is_active_geolocalization = false;
            for ($i = 0; $i < $N; $i++) {
                if($active_profiles[$i]['type']==='1' && $active_profiles[$i]['deleted']==='0')
                    $N_geolocalization=$N_geolocalization+1;
                if ($active_profiles[$i]['insta_name'] == $profile['geolocalization']) {
                    if($active_profiles[$i]['deleted'] == false)
                        if($active_profiles[$i]['type'] ==='0')
                            $is_active_profile=true;
                        elseif($active_profiles[$i]['type'] ==='1')
                            $is_active_geolocalization=true;
                    break;
                }
            }
            if (/*!$is_active_profile &&*/ !$is_active_geolocalization) {
                if ($N_geolocalization < $GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT) {
                    //$profile_datas = $this->check_insta_profile($profile['geolocalization']);
                    $profile_datas = $this->check_insta_geolocalization($profile['geolocalization']);                    
                    
                    if($profile_datas) {                                                
                        //if(!$profile_datas->is_private) {
                            $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile_datas->slug, $profile_datas->location->pk, '1');
                            if ($p) {
                                if ($this->session->userdata('status_id') == user_status::ACTIVE && $this->session->userdata('insta_datas'))
                                    $q = $this->client_model->insert_profile_in_daily_work($p, $this->session->userdata('insta_datas'), $N, $active_profiles, $this->session->userdata('to_follow'));
                                else
                                    $q = true;
                                //$profile_datas = $this->check_insta_profile($profile['geolocalization'], $p);
                                $result['success'] = true;
                                $result['img_url'] = base_url().'assets/images/avatar_geolocalization_present.jpg';
                                $result['profile'] = $profile['geolocalization'];
                                $result['geolocalization_pk'] = $profile_datas->location->pk;
                                $result['follows_from_profile'] = 0;
                                if ($q) {
                                    $result['message'] = $this->T('GeolocalizaÃ§Ã£o adicionada corretamente', array(), $GLOBALS['language']);
                                } else {
                                    $result['message'] = $this->T('O trabalho com a geolocalizaÃ§Ã£o comeÃ§ara depois', array(), $GLOBALS['language']);
                                }
                            } else {
                                $result['success'] = false;
                                $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
                            }
                        /*} else {
                            $result['success'] = false;
                            $result['message'] = $this->T('A geolocalizaÃ§Ã£o @1 Ã© um perfil privado', array(0 => $profile['geolocalization']));
                        }*/
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('@1 nÃ£o Ã© uma geolocalizaÃ§Ã£o do Instagram', array(0 => $profile['geolocalization']));
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('VocÃª alcanÃ§ou a quantidade mÃ¡xima de geolocalizaÃ§Ãµes ativas', array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;                    
                if($is_active_profile)
                    $result['message'] = $this->T('A geolocalizaÃ§Ã£o informada Ã© um perfil ativo', array(), $GLOBALS['language']);
                else
                    $result['message']=$this->T('A geolocalizaÃ§ao informada ja estÃ¡ ativa', array(), $GLOBALS['language']);                
            }
            
            if( $result['success'] == true){
                $this->load->model('class/user_model');
                // $this->user_model->insert_washdog($this->session->userdata('id'),'GEOCALIZATION INSERTED '.$profile['geolocalization']);
                $this->user_model->insert_washdog($this->session->userdata('id'),'GEOCALIZATION INSERTED');
            }
            echo json_encode($result);
        }
    }
        
    public function client_desactive_geolocalization() {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config(); 
            $language=$this->input->get();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;    
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language']=$param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['geolocalization'])) {
                $result['success'] = true;
                $result['message'] = $this->T('GeolocalizaÃ§Ã£o eliminada', array(), $GLOBALS['language']);
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
            }
            
            if( $result['success'] == true){
                $this->load->model('class/user_model');
                //$this->user_model->insert_washdog($this->session->userdata('id'),'GEOCALIZATION ELIMINATED '.$profile['geolocalization']);
                $this->user_model->insert_washdog($this->session->userdata('id'),'GEOCALIZATION ELIMINATED');
            }
            echo json_encode($result);
        }
    }
    
    public function check_insta_geolocalization($profile) {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $datas_of_profile = $this->Robot->get_insta_geolocalization_data_from_client(json_decode($this->session->userdata('cookies')),$profile);
            if (is_object($datas_of_profile)) {
                return $datas_of_profile;
            } else {
                return NULL;
            }
        }
    }

    
    //functions for reference profiles
    public function client_insert_profile() {
        $id = $this->session->userdata('id');
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $language=$this->input->get();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;    
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;  
            $GLOBALS['language']=$param['language'];
            $this->load->model('class/client_model');
            $this->load->model('class/user_status');
            $profile = $this->input->post();
            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($active_profiles);
            $N_profiles=0;
            $is_active_profile = false;
            $is_active_geolocalization = false;
            for ($i = 0; $i < $N; $i++) {
                if($active_profiles[$i]['type']==='0' && $active_profiles[$i]['deleted']==='0')
                    $N_profiles=$N_profiles+1;
                if ($active_profiles[$i]['insta_name'] == $profile['profile']) {
                    if($active_profiles[$i]['deleted'] == false)
                        if($active_profiles[$i]['type'] ==='0')
                            $is_active_profile=true;
                        elseif($active_profiles[$i]['type'] ==='1')
                            $is_active_geolocalization=true;
                    break;
                }
            }
            if (!$is_active_profile/*&& !$is_active_geolocalization*/) {
                if ($N_profiles<$GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT) {
                    $profile_datas=$this->check_insta_profile_from_client($profile['profile']);
                    if($profile_datas) {
                        if(!$profile_datas->is_private) {
                            $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['profile'], $profile_datas->pk, '0');
                            if ($p) {
                                if ($this->session->userdata('status_id') == user_status::ACTIVE && $this->session->userdata('insta_datas'))
                                    $q = $this->client_model->insert_profile_in_daily_work($p, $this->session->userdata('insta_datas'), $N, $active_profiles, $this->session->userdata('to_follow'));
                                else
                                    $q = true;
                                $result['success'] = true;
                                $result['img_url'] = $profile_datas->profile_pic_url;
                                $result['profile'] = $profile['profile'];
                                $result['follows_from_profile'] = $profile_datas->follows;
                                if ($q) {
                                    $result['message'] = $this->T('Perfil adicionado corretamente', array(), $GLOBALS['language']);
                                } else {
                                    $result['message'] = $this->T('O trabalho com o perfil comeÃ§ara depois', array(), $GLOBALS['language']);
                                }
                            } else {
                                $result['success'] = false;
                                $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
                            }
                        } else {
                            $result['success'] = false;
                            $result['message'] = $this->T('O perfil @1 Ã© um perfil privado', array(0 => $profile['profile']),$GLOBALS['language']);
                        }                    
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('Confira que o perfil @1 existe no Instagram e nÃ£o tem bloqueado vocÃª', array(0 => $profile['profile']),$GLOBALS['language']);
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('VocÃª alcanÃ§ou a quantidade mÃ¡xima de perfis ativos', array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;                    
                if($is_active_profile)
                    $result['message']=$this->T('O perfil informado ja estÃ¡ ativo', array(), $GLOBALS['language']);    
                else
                    $result['message'] = $this->T('O perfil informado Ã© uma geolocalizaÃ§Ã£o ativa', array(), $GLOBALS['language']);                
            }
            
            if( $result['success'] == true){
                $this->load->model('class/user_model');
                //$this->user_model->insert_washdog($this->session->userdata('id'),'REFERENCE PROFILE INSERTED '.$profile['profile']);
                $this->user_model->insert_washdog($this->session->userdata('id'),'REFERENCE PROFILE INSERTED');
            }
            
            echo json_encode($result);
        }
    }

    public function client_desactive_profiles() {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $language=$this->input->get();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;    
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;  
            $GLOBALS['language']=$param['language'];
            
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['profile'])) {
                $result['success'] = true;
                $result['message'] = $this->T('Perfil eliminado', array(), $GLOBALS['language']);
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
            }
            
            if( $result['success'] == true){
                $this->load->model('class/user_model');
                //$this->user_model->insert_washdog($this->session->userdata('id'),'REFERENCE PROFILE ELIMINATED '.$profile['profile']);
                $this->user_model->insert_washdog($this->session->userdata('id'),'REFERENCE PROFILE ELIMINATED');
            }
            
            echo json_encode($result);
        }
    }

    public function check_insta_profile($profile) {
        //if ($this->session->userdata('id')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
        $this->Robot = new \dumbu\cls\Robot();       
        $data = $this->Robot->get_insta_ref_prof_data($profile);
        if (is_object($data)) {
            return $data;
        } else {
            return NULL;
        }
        //}
    }    
    
    public function check_insta_profile_from_client($profile) {        
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
        $this->Robot = new \dumbu\cls\Robot();       
        $data = $this->Robot->get_insta_ref_prof_data_from_client(json_decode($this->session->userdata('cookies')),$profile);
        if(is_object($data)){
            return $data;
        }
        else 
            if(is_string($data)){
                return json_decode($data);
            } else {
                return NULL;
            }
    }
    
    public function message() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->Gmail = new \dumbu\cls\Gmail();
        $language=$this->input->get();
        if(isset($language['language']))
            $param['language']=$language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;    
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;  
        $GLOBALS['language']=$param['language'];
        $datas = $this->input->post();
        $result = $this->Gmail->send_client_contact_form($datas['name'], $datas['email'], $datas['message'], $datas['company'], $datas['telf']);
        if ($result['success']) {
            $result['message'] = $this->T('Mensagem enviada, agradecemos seu contato', array(), $GLOBALS['language']);
        }
        echo json_encode($result);
    }

    public function email_success_buy_to_atendiment($username, $useremail) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \dumbu\cls\system_config();
        $this->Gmail = new \dumbu\cls\Gmail();
        $result = $this->Gmail->send_new_client_payment_done($username, $useremail);
        if ($result['success'])
            return TRUE;
        return false;
    }

    public function email_success_buy_to_client($useremail, $username, $userlogin, $userpass) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \dumbu\cls\system_config();
        $this->Gmail2 = new \dumbu\cls\Gmail();
        $result = $this->Gmail2->send_client_payment_success($useremail, $username, $userlogin, $userpass);
    }

    //auxiliar function
    public function validate_post_credit_card_datas($datas) {
        //TODO: validate emial and datas of credit card using regular expresions
        /* if (preg_match('^[0-9]{16,16}$',$datas['credit_card_number']) &&
          preg_match('^[0-9 ]{3,3}$',$datas['credit_card_cvc']) &&
          preg_match('^[A-Z ]{4,50}$',$datas['credit_card_name']) &&
          preg_match('^[0-10-9]{2,2}$',$datas['credit_card_exp_month']) &&
          preg_match('^[2-20-01-20-9]{4,4}$',$datas['credit_card_exp_year']) &&
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
            if($login_data->json_response->authenticated) {
                $data_insta['authenticated'] = true;
                $data_insta['insta_id'] = $login_data->ds_user_id;
                
                //$user_data = $this->Robot->get_insta_ref_prof_data($client_login);
                
                $user_data = $this->Robot->get_insta_ref_prof_data_from_client($login_data,$client_login);
                
                if($data_insta && isset($user_data->follower_count))
                    $data_insta['insta_followers_ini'] = $user_data->follower_count;
                else
                    $data_insta['insta_followers_ini'] = 'Access denied';
                
                if($data_insta && isset($user_data->following))
                    $data_insta['insta_following'] = $user_data->following;
                else
                    $data_insta['insta_following'] = 'Access denied';
                
                if($data_insta && isset($user_data->full_name))
                    $data_insta['insta_name']=$user_data->full_name;
                else
                    $data_insta['insta_name']='Access denied';
                
                if(is_object($login_data))
                    $data_insta['insta_login_response'] = $login_data;
                else
                    $data_insta['insta_login_response'] = NULL;
            } else {
                $data_insta['authenticated'] = false;
            }
        } else {
            if (isset($login_data->json_response->status) && $login_data->json_response->status === "fail") {
                $data_insta['status'] = $login_data->json_response->status;
                if ($login_data->json_response->message === "checkpoint_required") {
                    $data_insta['message'] = $login_data->json_response->message;
                    if(strpos($login_data->json_response->checkpoint_url,'challenge'))
                        $data_insta['verify_account_url'] = 'https://www.instagram.com'.$login_data->json_response->checkpoint_url;
                    else
                    if(strpos($login_data->json_response->checkpoint_url,'integrity'))
                        $data_insta['verify_account_url'] =$login_data->json_response->checkpoint_url;
                    else
                        $data_insta['verify_account_url'] = $login_data->json_response->checkpoint_url;
                    
                } else
                if ($login_data->json_response->message === "") {
                    if (isset($login_data->json_response->phone_verification_settings) && is_object($login_data->json_response->phone_verification_settings)) {
                        $data_insta['message'] = 'phone_verification_settings';
                        $data_insta['obfuscated_phone_number'] = $login_data->json_response->two_factor_info->obfuscated_phone_number;
                    } else {
                        $data_insta['message'] = 'empty_message';
                        $data_insta['cause'] = 'empty_message';
                    }
                } else {
                    $data_insta['message'] = 'unknow_message';
                    $data_insta['unknow_message'] = $login_data->json_response->message;
                }
            } else
            if (isset($login_data->json_response->status) && $login_data->json_response->status === "") {
                
            }
        }
        return $data_insta;
    }

    //functions for load ad dispay the diferent funtionalities views 
    public function sign_client_update() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
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
        } else {
            $this->display_access_error();
        }
    }

    public function log_out() {
        $data['user_active'] = false;
        $this->load->model('class/user_model');
        $this->user_model->insert_washdog($this->session->userdata('id'),'CLOSING SESSION');
        $this->session->sess_destroy();
        header('Location: ' . base_url() . 'index.php');
    }

    public function create_profiles_datas_to_display() {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $this->load->model('class/client_model');
            $array_profiles=array();
            $array_geolocalization=array();
            $client_active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($client_active_profiles);
            $cnt_ref_prof=0;
            $cnt_geolocalization=0;            
            if ($N > 0) {
//                $array_profiles = array(0);   
                for ($i = 0; $i < $N; $i++) {
                    $name_profile = $client_active_profiles[$i]['insta_name'];
                    $id_profile = $client_active_profiles[$i]['id'];
                    if($client_active_profiles[$i]['type']==='0'){ //es un perfil de referencia
                    $datas_of_profile = $this->Robot->get_insta_ref_prof_data_from_client(json_decode($this->session->userdata('cookies')),$name_profile, $id_profile);
                    if($datas_of_profile!=NULL){
                            $array_profiles[$cnt_ref_prof]['login_profile'] = $name_profile;
                            $array_profiles[$cnt_ref_prof]['follows_from_profile'] = $datas_of_profile->follows;
                            if (!$datas_of_profile) { //perfil existia pero fue eliminado de IG
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'deleted';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = base_url().'assets/images/profile_deleted.jpg';
                            } else
                            if ($client_active_profiles[$cnt_ref_prof]['end_date']) { //perfil
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'ended';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = $datas_of_profile->profile_pic_url;
                            } else
                            if ($datas_of_profile->is_private) { //perfil paso a ser privado
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'privated';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = base_url().'assets/images/profile_privated.jpg';
                            } else{
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'active';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = $datas_of_profile->profile_pic_url;
                            }
                            $cnt_ref_prof=$cnt_ref_prof+1;
                        } else{
                            $array_profiles[$cnt_ref_prof]['status_profile'] = 'blocked';
                            $array_profiles[$cnt_ref_prof]['img_profile'] = base_url().'assets/images/profile_privated.jpg';
                            $array_profiles[$cnt_ref_prof]['login_profile'] = $name_profile;
                            $array_profiles[$cnt_ref_prof]['follows_from_profile'] = '-+-';
                            $cnt_ref_prof=$cnt_ref_prof+1;
                        }
                    } else{ //es una geolocalizacion      
                        $datas_of_profile = $this->Robot->get_insta_geolocalization_data_from_client(json_decode($this->session->userdata('cookies')),$name_profile, $id_profile);
                        $array_geolocalization[$cnt_geolocalization]['login_geolocalization'] = $name_profile;
                        $array_geolocalization[$cnt_geolocalization]['geolocalization_pk'] = $client_active_profiles[$i]['insta_id'];
                        if($datas_of_profile)
                            $array_geolocalization[$cnt_geolocalization]['follows_from_geolocalization'] = $datas_of_profile->follows;                        
                        $array_geolocalization[$cnt_geolocalization]['img_geolocalization'] = base_url().'assets/images/avatar_geolocalization_present.jpg';
                        if(!$datas_of_profile){
                            $array_geolocalization[$cnt_geolocalization]['img_geolocalization'] = base_url().'assets/images/avatar_geolocalization_deleted.jpg';
                            $array_geolocalization[$cnt_geolocalization]['status_geolocalization'] = 'deleted';
                        } else
                        if ($client_active_profiles[$cnt_geolocalization]['end_date']) { //perfil
                            $array_geolocalization[$cnt_geolocalization]['status_geolocalization'] = 'ended';
                        } else{
                            $array_geolocalization[$cnt_geolocalization]['status_geolocalization'] = 'active';
                        }
                        $cnt_geolocalization=$cnt_geolocalization+1;                        
                    }
                }
                
                if($cnt_ref_prof)
                    $response['array_profiles'] = $array_profiles;
                else
                    $response['array_profiles']=array();
                $response['N'] = $cnt_ref_prof;  
                if($cnt_geolocalization)
                    $response['array_geolocalization'] = $array_geolocalization;
                else
                    $response['array_geolocalization'] = array();                
                $response['N_geolocalization'] = $cnt_geolocalization;
                $response['message'] = 'Profiles loaded';
                
            } else {
                $response['N'] =0;
                $response['N_geolocalization'] =0;
                $response['array_profiles'] = NULL;
                $response['array_geolocalization'] =NULL;
                $response['message'] = 'Profiles unloaded';
            }            
            return json_encode($response);
        } else {
            $this->display_access_error();
        }
    }

    public function dicas_geoloc() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;        
        $this->load->model('class/user_model');
        $this->user_model->insert_washdog($this->session->userdata('id'),'LOOKING AT GEOCALIZATION TIPS');
        $this->load->view('dicas_geoloc', $param);
    }
    
    public function help() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $language=$this->input->get();
        if(isset($language['language']))
            $param['language']=$language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;        
       $this->load->view('Dicas', $param);
    }

    public function create_profiles_datas_to_display_as_json() {
        echo($this->create_profiles_datas_to_display());
    }

    public function display_access_error() {
        $this->session->sess_destroy();
        header('Location: ' . base_url().'index.php/welcome/');
    }
    
    public function client_acept_discont(){
        $this->load->model('class/client_model');       
        $this->load->model('class/user_model');       
        $values = $this->client_model->get_plane($this->session->userdata('plane_id'))[0];
        $value=$values['normal_val'];
        $sql = "SELECT * FROM clients WHERE clients.user_id='" . $this->session->userdata('id') . "'";
        $client = $this->user_model->execute_sql_query($sql);
        
        $recurrency_order_key=$client[0]['order_key'];
        
        
        $result['success'] = true;
        echo json_encode($result);
    }
    
    public function get_names_by_chars() {
        if($this->session->userdata('id')){
            $cookies=json_decode($this->session->userdata('cookies'));
            //$datas = $this->input->post();
            $datas = $this->input->get();
            $str=$datas['str'];
            $profile_type=$datas['profile_type'];            
            $mid=$cookies->mid;
            $csrftoken=$cookies->csrftoken;
            $ds_user_id=$cookies->ds_user_id;
            $sessionid=$cookies->sessionid;            
            $headers = array();
            $headers[] = 'Host: www.instagram.com';
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:52.0) Gecko/20100101 Firefox/52.0';
            $headers[] = 'Accept: */*';
            $headers[] = 'Accept-Language: es-ES,es;q=0.8,en-US;q=0.5,en;q=0.3'; //--compressed 
            $headers[] = 'Referer: https://www.instagram.com/'; 
            $headers[] = 'X-Requested-With: XMLHttpRequest'; 
            $headers[] = 'Cookie: mid='.$mid.'; csrftoken='.$csrftoken.'; ds_user_id='.$ds_user_id.'; sessionid='.$sessionid.';';
            $headers[] = "Connection: keep-alive";            
            $url = 'https://www.instagram.com/web/search/topsearch/?context=blended&query='.$str.'/';
            $ch = curl_init("https://www.instagram.com/");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $output = curl_exec($ch);
            $info = curl_error($ch);
            $output=json_decode($output);
            if($profile_type==='places')
                $output=$output->places;
            else
            if($profile_type==='users')
                $output=$output->users;
            
            $result=array();
            $N=count($output);                    
            for($i=0;$i<$N;$i++){
                if($profile_type==='places'){
                    $result[$i]=$output[$i]->place->slug;
                }else 
                if($profile_type==='users'){
                    $result[$i]=$output[$i]->user->username;
                }
            }
            echo json_encode($result);
        }
    }
    
    public function admin_making_client_login(){
        $datas = $this->input->get();
        $datas['user_pass']=urldecode($datas['user_pass']);
        $result=$this->user_do_login($datas);
        if($result['authenticated']===true){
            $this->client();
        }
        else
            echo 'Esse cliente deve ter senha errada ou mudou suas credenciais no IG';
    }

    public function T($token, $array_params=NULL, $lang=NULL) {
        if(!$lang){
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
            $GLOBALS['language']=$param['language'];
            $lang=$param['language'];
        }
        $this->load->model('class/translation_model');
        $text = $this->translation_model->get_text_by_token($token,$lang);
        $N = count($array_params);
        for ($i = 0; $i < $N; $i++) {
            $text = str_replace('@' . ($i + 1), $array_params[$i], $text);
        }
        return $text;
    }

    public function scielo_view() {
        $this->load->view('scielo');
    }

    public function scielo() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $datas = $this->input->post();
        $datas['amount_in_cents'] = 100;
        $resp = $this->check_mundipagg_credit_card($datas);
        if (is_object($resp) && $resp->isSuccess()) {
            $order_key = $resp->getData()->OrderResult->OrderKey;
            $response['success'] = true;
            $response['message'] = "Compra relizada com sucesso! Chave da compra na mundipagg: $order_key";
        } else if (is_object($resp)) {
            $order_key = $resp->getData()->OrderResult->OrderKey;
            $response['success'] = false;
            $response['message'] = "Compra recusada! Chave da compra na mundipagg: $order_key";
        } else {
            $response['success'] = false;
            $response['message'] = "Compra recusada!";
        }
        echo json_encode($response);
    }
    
    public function get_daily_report($id) {
        if ($this->session->userdata('id')) {
            $this->load->model('class/user_model');
            $sql = "SELECT * FROM daily_report WHERE followings != '0' AND followers != '0' AND client_id=" . $id . " ORDER BY date ASC;" ;  // LIMIT 30
            $result = $this->user_model->execute_sql_query($sql);
            $followings = array();
            $followers = array();
            $N = count($result);
            for ($i = 0; $i < $N; $i++) {
                if(isset($result[$i]['date'])){
                $dd = date("j", $result[$i]['date']);
                $mm = date("n", $result[$i]['date']);
                $yy = date("Y", $result[$i]['date']);
                $followings[$i] = (object) array('x' => ($i+1), 'y' => intval($result[$i]['followings']), "yy" => $yy, "mm" => $mm, "dd" => $dd);
                $followers[$i] = (object) array('x' => ($i + 1), 'y' => intval($result[$i]['followers']), "yy" => $yy, "mm" => $mm, "dd" => $dd);
                }
            }
            $response= array(
                'followings' => json_encode($followings),
                'followers' => json_encode($followers)
            );
            return $response;
        }
    }
    
    public function get_img_profile($profile){
        $this->load->model('class/client_model');
        $datas= $this->check_insta_profile($profile);
        if($datas)
            return $datas->profile_pic_url;
        else
            return 'missing_profile';
    }
        
    public function client_black_list(){
        if($this->session->userdata('id')){
            $this->load->model('class/client_model');
            try {
                $bl=$this->client_model->get_client_black_or_white_list_by_id($this->session->userdata('id'),0);                
                $dados=array();
                $N=count($bl);
                for($i=0;$i<$N;$i++){
                    $dados[$i]=(object)array('profile'=>$bl[$i]['profile'],'url_foto'=> $this->get_img_profile($bl[$i]['profile']));
                }
                $response['client_black_list'] = $dados;
                $response['success'] = true;
                $response['cnt'] = $N;
            } catch (Exception $ex) {
                $response['success'] = false;
            }
            echo json_encode($response);
        }
    }
        
    public function insert_profile_in_black_list(){
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();            
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
            $GLOBALS['language']=$param['language'];
        
            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];   
            $datas=$this->check_insta_profile($profile);
            if($datas){
                $resp=$this->client_model->insert_in_black_or_white_list_model($this->session->userdata('id'),$datas->pk,$profile,0);
                if($resp['success']){
                    $result['success'] = true;
                    $result['url_foto'] = $datas->profile_pic_url;    
                    $this->load->model('class/user_model');
                    //$this->user_model->insert_washdog($this->session->userdata('id'),'INSERTING PROFILE '.$profile.'IN BLACK LIST');
                    $this->user_model->insert_washdog($this->session->userdata('id'),'INSERTING PROFILE IN BLACK LIST');
                } else{
                    $result['success'] = false;
                    $result['message'] = $this->T('O perfil '.$resp['message'], array(), $GLOBALS['language']);
                }
            } else{
                $result['success'] = false;
                $result['message'] = $this->T('O perfil nÃ£o existe no Instagram', array(), $GLOBALS['language']);
            }            
            echo json_encode($result);
        }
    }
    
    public function delete_client_from_black_list(){
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
            $GLOBALS['language']=$param['language'];

            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];
            if($this->client_model->delete_in_black_or_white_list_model($this->session->userdata('id'),$profile,0)){
                $result['success'] = true;
                $this->load->model('class/user_model');
                //$this->user_model->insert_washdog($this->session->userdata('id'),'DELETING PROFILE '.$profile.' IN BLACK LIST');
                $this->user_model->insert_washdog($this->session->userdata('id'),'DELETING PROFILE IN BLACK LIST');
            } else{
                $result['success'] = false;
                $result['message'] = $this->T('Erro eliminando da lista negra', array(), $GLOBALS['language']);
            }
            echo json_encode($result);
        }
    }
    
    public function client_white_list(){
        if($this->session->userdata('id')){
            $this->load->model('class/client_model');
            try {
                $bl=$this->client_model->get_client_black_or_white_list_by_id($this->session->userdata('id'),1);                
                $dados=array();
                $N=count($bl);
                for($i=0;$i<$N;$i++){
                    $dados[$i]=(object)array('profile'=>$bl[$i]['profile'],'url_foto'=> $this->get_img_profile($bl[$i]['profile']));
                }
                $response['client_white_list'] = $dados;
                $response['success'] = true;
                $response['cnt'] = $N;   
            } catch (Exception $ex) {
                $response['success'] = false;
            }
            echo json_encode($response);
        }
    }
    
    public function insert_profile_in_white_list(){
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
            $GLOBALS['language']=$param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];   
            $datas=$this->check_insta_profile($profile);
            if($datas){
                $resp=$this->client_model->insert_in_black_or_white_list_model($this->session->userdata('id'),$datas->pk,$profile,1);
                if($resp['success']){
                    $result['success'] = true;
                    $result['url_foto'] = $datas->profile_pic_url;    
                    $this->load->model('class/user_model');
                    //$this->user_model->insert_washdog($this->session->userdata('id'),'INSERTING PROFILE '.$profile.'IN WHITE LIST ');
                    $this->user_model->insert_washdog($this->session->userdata('id'),'INSERTING PROFILE IN WHITE LIST');
                } else{
                    $result['success'] = false;
                    $result['message'] = $this->T('O perfil '.$resp['message'], array(), $GLOBALS['language']);
                }
            } else{
                $result['success'] = false;
                $result['message'] = $this->T('O perfil nÃ£o existe no Instagram', array(), $GLOBALS['language']);
            }            
            echo json_encode($result);
        }
    }
    
    public function delete_client_from_white_list(){
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            if(isset($language['language']))
                $param['language']=$language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
            $GLOBALS['language']=$param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];
            if($this->client_model->delete_in_black_or_white_list_model($this->session->userdata('id'),$profile,1)){
                $result['success'] = true;
                $this->load->model('class/user_model');
                //$this->user_model->insert_washdog($this->session->userdata('id'),'DELETING PROFILE '.$profile.' IN WHITE LIST');
                $this->user_model->insert_washdog($this->session->userdata('id'),'DELETING PROFILE IN WHITE LIST');
            } else{
                $result['success'] = false;
                $result['message'] = $this->T('Erro eliminando da lista negra', array(), $GLOBALS['language']);
            }
            echo json_encode($result);
        }
    }  
    
    

    //tests optional functions
    public function t1() {
	$this->load->model('class/client_model');
	$query="SELECT * FROM clients
            INNER JOIN users ON clients.user_id = users.id
            INNER JOIN plane ON clients.plane_id = plane.id
            WHERE 
                    users.role_id = 2
            AND users.status_id <> 4
            AND users.status_id <> 8
            AND users.status_id < 11
            AND (clients.actual_payment_value = '' OR clients.actual_payment_value is null)";
	$result=$this->client_model->execute_sql_query($query);
	foreach ($result as $row ) {
		$this->client_model->update_client($row['user_id'], array(
			'actual_payment_value' => $row['normal_val']));
	}
	echo count($result);
}
    
    public function paypal() {
        $this->load->view('test_view');
    }
    
    public function update_client_after_retry_payment_success($user_id) {  
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();        
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        //1. recuperar el cliente y su plano
        $client = $this->client_model->get_all_data_of_client($user_id)[0];
        $plane = $this->client_model->get_plane($client['plane_id'])[0];
        //3. crear nueva recurrencia en la Mundipagg para el proximo mes   
        date_default_timezone_set('Etc/UTC');
        $payment_data['credit_card_number'] = $client['credit_card_number'];
        $payment_data['credit_card_name'] = $client['credit_card_name'];
        $payment_data['credit_card_exp_month'] = $client['credit_card_exp_month'];
        $payment_data['credit_card_exp_year'] = $client['credit_card_exp_year'];
        $payment_data['credit_card_cvc'] = $client['credit_card_cvc'];
        if($client['actual_payment_value']!='' && $client['actual_payment_value']!=null)
            $payment_data['amount_in_cents'] = $client['actual_payment_value'];
        else
            $payment_data['amount_in_cents'] = $plane['normal_val'];
        $payment_data['pay_day'] = strtotime("+1 month", time());
        $resp = $this->check_recurrency_mundipagg_credit_card($payment_data, 0);
        //4. salvar nuevos pay_day e order_key
        if (is_object($resp) && $resp->isSuccess()) {
            //2. eliminar recurrencia actual en la Mundipagg
            $this->delete_recurrency_payment($client['order_key']);
            $this->client_model->update_client($user_id, array(
                'initial_order_key' => '',
                'order_key' => $resp->getData()->OrderResult->OrderKey,
                'pay_day' => $payment_data['pay_day']));
            echo '<br>Client '.$user_id.' updated correctly. New order key is:  '.$resp->getData()->OrderResult->OrderKey;
            //5. actualizar status del cliente
            $data_insta = $this->is_insta_user($client['login'], $client['pass']);
            if($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::ACTIVE
                ));
                echo ' STATUS = '.user_status::ACTIVE;
            } else
            if ($data_insta['status'] === 'ok' && !$data_insta['authenticated']){
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));
                echo ' STATUS = '.user_status::BLOCKED_BY_INSTA;
            }
            else{
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));
                echo ' STATUS = '.user_status::VERIFY_ACCOUNT;
            }
        } else{
            $this->user_model->update_user($user_id, array(            
                'status_date' => time(),
                'status_id' => 1)); 
            $this->delete_recurrency_payment($client['order_key']);
            $this->client_model->update_client($user_id, array(
                'initial_order_key' => '',
                'order_key' => '',
                'observation' => 'NÃ‚O CONEGUIDO DURANTE RETENTATIVA - TENTAR CRIAR ANTES DE DATA DE PAGAMENTO',
                'order_key' => $payment_data['pay_day'],
                'observation' => 'NÂO CONEGUIDO DURANTE RETENTATIVA - TENTAR CRIAR ANTES DE DATA DE PAGAMENTO',
                'pay_day' => $payment_data['pay_day']));
            //TO-DO:Ruslan: inserta una pendencia automatica aqui
            
            if (is_object($resp))
                echo '<br>Client '.$user_id.' DONT updated. Wrong order key is:  '.$resp->getData()->OrderResult->OrderKey;
            else 
                echo '<br>Client '.$user_id.' DONT updated. Missing order key';
        }
        
        $this->client_model->update_client($user_id, array(            
            'initial_order_key' => '')); 
    }
           
    public function buy_retry_for_clients_with_puchase_counter_in_zero() {
        $this->load->model('class/client_model');
        $cl=$this->client_model->beginners_with_purchase_counter_less_value(9);
        for($i=1;$i<count($cl);$i++){            
            $clients=$cl[$i];
            $datas=array('client_login'=>$clients['login'],
                         'client_pass'=>$clients['pass'],
                         'client_email'=>$clients['email']);
            $resp=$this->check_user_for_sing_in($datas);
            
            if($resp['success']){
                $datas=array(
                    'pk'=>$clients['user_id'],
                    'credit_card_number'=>$clients['credit_card_number'],
                    'credit_card_cvc'=>$clients['credit_card_cvc'],
                    'credit_card_name'=>$clients['credit_card_name'],
                    'credit_card_exp_month'=>$clients['credit_card_exp_month'],
                    'credit_card_exp_year'=>$clients['credit_card_exp_year'],

                    'plane_type'=>$clients['plane_id'],
                    'ticket_peixe_urbano'=>$clients['ticket_peixe_urbano'],
                    'user_email'=>$clients['email'],
                    'insta_name'=>$clients['name'],
                    'user_login'=>$clients['login'],
                    'user_pass'=>$clients['pass'],
                );            
                $resp=$this->check_client_data_bank($datas);
                if($resp['success']){
                    echo 'Cliente ('.$clients['login'].')   '.$clients['login'].'comprou satisfatoriamente\n<br>';
                } else{
                    $this->client_model->update_client($clients['user_id'], array(
                        'purchase_counter' => -100 ));
                    echo 'Cliente '.$clients['login'].' ERRADO\n<br>';
                }
            } else{
                $this->client_model->update_client($clients['user_id'], array(
                        'purchase_counter' => -100 ));
                echo 'Cliente ('.$clients['login'].') '.$clients['login'].'nÃ£ passou passo 1\n<br>';
            }
        }
    }
        
    public function Pedro(){
        $this->load->model('class/user_model');
        $users= $this->user_model->get_all_users();
        $L=count($users);
        echo 'Num clientes '.$L."<br>";
        $file = fopen("media_pro.txt","w");
        for($i=0;$i<$L;$i++){
            $result=$this->user_model->get_daily_report($users[$i]['id']);
            $Ndaily_R=count($result);
            //echo $i.'----'.$users[$i]['id'].'-----'.count($users).'<br>';
            $N=0; $sum=0;
            if($Ndaily_R>5){
                for($j=1;$j<$Ndaily_R;$j++){
                    $diferencia = $result[$j]['date']-$result[$j-1]['date']; 
                    $horas = (int)($diferencia/(60*60)); 
                    if( $horas>20 && $horas <=30){
                        $N++;
                        $sum=$sum+($result[$j]['followers'] - $result[$j-1]['followers']);
                    }
                }
                //fwrite($file, ($users[$i]['id'].'---'.$users[$i]['status_id'].'---'.$users[$i]['plane_id'].'---'.((int)($sum/$N)).'<br>'));
                echo $users[$i]['id'].'---'.$users[$i]['status_id'].'---'.$users[$i]['plane_id'].'---'.((int)($sum/$N)).'<br>';
                
            }            
        }
        echo 'fin';
        fclose($file);
    }
    
    public function update_ds_user_id() {
        $this->load->model('class/client_model');
        $resul=$this->client_model->select_white_list_model();
        foreach ($resul as $key => $value) {
            $data_insta = $this->check_insta_profile($value['profile']);
            $this->client_model->update_ds_user_id_white_list_model($value['id'],$data_insta->pk);
        }
    }   
    
    public function login_all_clients(){
        $this->load->model('class/user_model');
        $a=$this->user_model->get_all_dummbu_clients();
        $N=count($a);
        for($i=0;$i<$N;$i++){
            $st=$a[$i]['status_id'];
            if($st!=='4' && $st!=='8' && $st!=='11' && $a[$i]['role_id']==='2'){
                echo $i;
                $login=$a[$i]['login'];
                $pass=$a[$i]['pass'];
                $datas['user_login']=$login;
                $datas['user_pass']=$pass;
                $result= $this->user_do_login($datas);
            }
        }
    }
    
    public function time_of_live() {
        $this->load->model('class/user_model');
        $result=$this->user_model->time_of_live_model(4);
        $response=array(
            '0-2-dias'=>array(0,0,0,0,0),
            '2-30-dias'=>array(0,0,0,0,0),
            '30-60-dias'=>array(0,0,0,0,0),
            '60-90-dias'=>array(0,0,0,0,0),
            '90-120-dias'=>array(0,0,0,0,0),
            '120-150-dias'=>array(0,0,0,0,0),
            '150-180-dias'=>array(0,0,0,0,0),
            '180-210-dias'=>array(0,0,0,0,0),
            '210-240-dias'=>array(0,0,0,0,0),
            '240-270-dias'=>array(0,0,0,0,0),
            'mais-270'=>array(0,0,0,0,0));
        
        foreach ($result as $user) {
            $difference=$user['end_date']-$user['init_date'];
            $second = 1;
            $minute = 60*$second;
            $hour   = 60*$minute;
            $day    = 24*$hour;
            
            $plane=$user['plane_id'];
            
            $num_days=floor($difference/$day);            
            if ($num_days<=2) 
                $response['0-2-dias'][$plane]=$response['0-2-dias'][$plane]+1;
            else
            if ($num_days>2 &&$num_days<=30) 
                $response['2-30-dias'][$plane]=$response['2-30-dias'][$plane]+1;
            else
            if ($num_days>30 &&$num_days<=60) 
                $response['30-60-dias'][$plane]=$response['30-60-dias'][$plane]+1;
            else
            if ($num_days>60 &&$num_days<=90) 
                $response['60-90-dias'][$plane]=$response['60-90-dias'][$plane]+1;            
            else
            if ($num_days>90 &&$num_days<=120) 
                $response['90-120-dias'][$plane]=$response['90-120-dias'][$plane]+1;
            else
            if ($num_days>120 &&$num_days<=150) 
                $response['120-150-dias'][$plane]=$response['120-150-dias'][$plane]+1;
            else
            if ($num_days>150 &&$num_days<=180) 
                $response['150-180-dias'][$plane]=$response['150-180-dias'][$plane]+1;
            else
            if ($num_days>180 &&$num_days<=210) 
                $response['180-210-dias'][$plane]=$response['180-210-dias'][$plane]+1;
            else
            if ($num_days>210 &&$num_days<=240) 
                $response['210-240-dias'][$plane]=$response['210-240-dias'][$plane]+1;
            else
            if ($num_days>240 &&$num_days<=270) 
                $response['240-270-dias'][$plane]=$response['240-270-dias'][$plane]+1;
            else 
                $response['mais-270'][$plane]=$response['mais-270'][$plane]+1;
        }        
        var_dump($response);        
    }
    
    public function users_by_month_and_plane() {
        $status = $this->input->get()['status'];
        $this->load->model('class/user_model');
        $result=$this->user_model->time_of_live_model($status);
                
        foreach ($result as $user) {
            $month=date("n", $user['init_date']);
            $year=date("Y", $user['init_date']);
            $cad=$month.'-'.$year.'<br>';
            $plane_id=$user['plane_id'];
            if(!isset($r[$cad][$plane_id] ))
                $r[$cad][$plane_id]=0;
            else
                $r[$cad][$plane_id]=$r[$cad][$plane_id]+1;
        }        
        var_dump($r);        
    }
    
    
    
    /*public function cancel_blocked_by_payment_by_max_retry_payment(){
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');        
        $result=$this->client_model->get_all_clients_by_status_id(2);        
        foreach ($result as $client) {
            if($client['retry_payment_counter']>9){
                try{
                    $this->delete_recurrency_payment($client['initial_order_key']);                
                    $this->delete_recurrency_payment($client['order_key']);                
                    $this->user_model->update_user($client['user_id'], array(  
                        'end_date' => time(),
                        'status_date' => time(),
                        'status_id' => 4));
                    $this->client_model->update_client($client['user_id'], array(
                            'observation' => 'Cancelado automaticamente por mais de 10 retentativas de pagamento sem sucessso'));
                    echo 'Client '.$client['user_id'].' cancelado por maxima de retentativas';
                } catch (Exception $e){
                    echo 'Error deleting cliente '.$client['user_id'].' in database';
                }
            }
        }
    }

    public function buy_tester(){
        
    }
    
    public function update_all_retry_clients(){
        $array_ids=array(176, 192, 419, 1290, 1921, 3046, 3179, 3218, 3590, 12707, 564, 3486, 671, 2300, 4123, 4466, 12356, 12373, 12896, 13786, 23410,25073, 15746, 23636, 24426, 15745);
        $N=count($array_ids);
        for($i=0;$i<$N;$i++){
            $this->update_client_after_retry_payment_success($array_ids[$i]);
        }
    }*/
    
    public function capturer_and_recurrency_for_blocked_by_payment(){
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $params=$this->input->get();
        $result=$this->client_model->get_all_clients_by_status_id(2); //20       
        foreach ($result as $client) {
            $aa=$client['login'];
            $status_id=$client['status_id'];
            if($client['retry_payment_counter']<13){
                if($client['credit_card_number']!=null && $client['credit_card_number']!=null && 
                        $client['credit_card_name']!=null && $client['credit_card_name']!='' && 
                        $client['credit_card_exp_month']!=null && $client['credit_card_exp_month']!='' && 
                        $client['credit_card_exp_year']!=null && $client['credit_card_exp_year']!='' && 
                        $client['credit_card_cvc']!=null && $client['credit_card_cvc']!='' ){

                    $pay_day = time();
                    $payment_data['credit_card_number'] =$client['credit_card_number'];
                    $payment_data['credit_card_name'] = $client['credit_card_name'];
                    $payment_data['credit_card_exp_month'] = $client['credit_card_exp_month'];
                    $payment_data['credit_card_exp_year'] = $client['credit_card_exp_year'];
                    $payment_data['credit_card_cvc'] = $client['credit_card_cvc'];
                    
                    $difference=$pay_day-$client['init_date'];
                    $second = 1;
                    $minute = 60*$second;
                    $hour   = 60*$minute;
                    $day    = 24*$hour;  
                    $num_days=floor($difference/$day); 

                    $payment_data['amount_in_cents'] =0;
                    if($client['ticket_peixe_urbano']==='AMIGOSDOPEDRO' || $client['ticket_peixe_urbano']==='INSTA15D'){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                    } else
                    if( ($client['ticket_peixe_urbano']==='INSTA50P' ||
                            $client['ticket_peixe_urbano']==='BACKTODUMBU' ||
                            $client['ticket_peixe_urbano']==='BACKTODUMBU-DNLO' ||
                            $client['ticket_peixe_urbano']==='BACKTODUMBU-EGBTO')){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                        if($num_days<=33)
                            $payment_data['amount_in_cents']=$payment_data['amount_in_cents']/2;
                    } else
                    if($client['ticket_peixe_urbano']==='DUMBUDF20'){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                        $payment_data['amount_in_cents']=($payment_data['amount_in_cents']*8)/10;
                    } else
                    if($client['ticket_peixe_urbano']==='INSTA-DIRECT' || $client['ticket_peixe_urbano']==='MALADIRETA'){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                    } else                
                    if($client['actual_payment_value']!=null && 
                            $client['actual_payment_value']!='null' && 
                            $client['actual_payment_value']!='' && 
                            $client['actual_payment_value']!=NULL
                            && $payment_data['amount_in_cents'] ==0
                            )
                        $payment_data['amount_in_cents'] = $client['actual_payment_value'];
                    else
                       $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);

                    $resp = $this->check_mundipagg_credit_card($payment_data);
                    if((is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $this->update_client_after_retry_payment_success($client['user_id']);
                        $this->client_model->update_client($client['user_id'], array(
                            'retry_payment_counter' => 0));
                    }else{
                        $this->client_model->update_client($client['user_id'], array(
                        'retry_payment_counter' => $client['retry_payment_counter']+1));
                    }
                }
            } else{
                try{
                    $this->delete_recurrency_payment($client['initial_order_key']);                
                    $this->delete_recurrency_payment($client['order_key']);                
                    $this->user_model->update_user($client['user_id'], array(  
                        'end_date' => time(),
                        'status_date' => time(),
                        'status_id' => 4));
                    $this->client_model->update_client($client['user_id'], array(
                            'observation' => 'Cancelado automaticamente por mais te 10 retentativas de pagamento sem sucessso'));
                    echo 'Client '.$client['user_id'].' cancelado por maxima de retentativas';
                } catch (Exception $e){
                    echo 'Error deleting cliente '.$client['user_id'].' in database';
                }
            }
        }
    }
    
      
}
