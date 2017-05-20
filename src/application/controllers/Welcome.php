<?php

class Welcome extends CI_Controller {
    
    
    public function i() {
        echo date("Y-m-d",time());
    }
    public function index() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $param['languaje'] = $GLOBALS['sistem_config']->LANGUAGE;
        $this->load->view('user_view', $param);
    }
    
    public function admin_making_client_login(){
        $datas = $this->input->get();
        $result=$this->user_do_login($datas);
        if($result['authenticated']===true){
            $this->client();
        }
    }

    public function T($token, $array_params) {
        $this->load->model('class/translation_model');
        $text = $this->translation_model->get_text_by_token($token);
        $N = count($array_params);
        for ($i = 0; $i < $N; $i++) {
            $text = str_replace('@' . ($i + 1), $array_params[$i], $text);
        }
        return $text;
    }

    public function purchase() {
        if ($this->session->userdata('id')) {
            $this->load->model('class/user_model');
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $datas['user_id'] = $this->session->userdata('id');
            $datas['profiles'] = $this->create_profiles_datas_to_display();
            $datas['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            
            $datas['Afilio_UNIQUE_ID'] = $this->session->userdata('id');
            $query='SELECT * FROM plane WHERE id='.$this->session->userdata('plane_id');
            $result = $this->user_model->execute_sql_query($query);
            $datas['Afilio_order_price']=$result[0]['initial_val'];
            $datas['Afilio_total_value']=$result[0]['normal_val'];
            $datas['Afilio_product_id']= $this->session->userdata('plane_id');
            $this->load->view('purchase_view', $datas);
        }else
            echo 'Access error';
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
            $sql = "SELECT * FROM daily_report WHERE client_id=" . $id . " ORDER BY date ASC;";  // LIMIT 30
            $result = $this->user_model->execute_sql_query($sql);
            $followings = array();
            $followers = array();
            $N = count($result);
            for ($i = 0; $i < $N; $i++) {
                $dd = date("j", $result[$i]['date']);
                $mm = date("n", $result[$i]['date']);
                $yy = date("Y", $result[$i]['date']);
                $followings[$i] = (object) array('x' => ($i+1), 'y' => intval($result[$i]['followings']), "yy" => $yy, "mm" => $mm, "dd" => $dd);
                $followers[$i] = (object) array('x' => ($i + 1), 'y' => intval($result[$i]['followers']), "yy" => $yy, "mm" => $mm, "dd" => $dd);
            }
            $response= array(
                'followings' => json_encode($followings),
                'followers' => json_encode($followers)
            );
            return $response;
        }
    }

    public function client() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/user_role');
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_status');
        $status_description = array(1 => 'ATIVO', 2 => 'DESABILITADO', 3 => 'INATIVO', 4 => '', 5 => '', 6 => 'ATIVO'/* 'PENDENTE' */, 7 => 'NÂO INICIADO', 8 => '', 9 => 'INATIVO', 10 => 'LIMITADO');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
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
            $datas1['plane_id'] = $this->session->userdata('plane_id');
            $datas1['all_planes'] = $this->client_model->get_all_planes();
            $datas1['currency'] = $GLOBALS['sistem_config']->CURRENCY;
            $datas1['language'] = $GLOBALS['sistem_config']->LANGUAGE;

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
            $login_by_client=true;
        }
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
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
                    $result['message'] = $this->T('Não foi possível conferir suas credencias com o Instagram', array());
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
                            if ($data_insta['insta_login_response']) {
                                $N = count($active_profiles);
                                for ($i = 0; $i < $N; $i++) {
                                    $sql = 'SELECT * FROM daily_work WHERE reference_id=' . $active_profiles[$i]['id'];
                                    $response = count($this->user_model->execute_sql_query($sql));
                                    if (!$response && $active_profiles[$i]['end_date']!=='NULL')
                                        $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                                }
                            }
                            $result['resource'] = 'client';
                            $result['message'] = $this->T('Usuário @1 logueado', array(0 => $datas['user_login']));
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

                            if ($data_insta['insta_login_response']) {
                                $this->client_model->update_client($user[$index]['id'], array(
                                    'cookies' => json_encode($data_insta['insta_login_response'])));
                            }
                            $this->user_model->set_sesion($user[$index]['id'], $this->session, $data_insta['insta_login_response']);
                            $result['resource'] = 'client';
                            $result['message'] = $this->T('Usuário @1 logueado', array(0 => $datas['user_login']));                            
                            $result['role'] = 'CLIENT';
                            $result['authenticated'] = true;
                        } else
                        if ($st == user_status::BEGINNER) {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = $this->T('Falha no login! Seu cadastro esta incompleto. Por favor, termine sua assinatura.', array());
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        } else
                        if ($st == user_status::DELETED || $st == user_status::INACTIVE) {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = $this->T('Falha no login! Você deve assinar novamente para receber o serviço', array());
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        }
                    } else {
                        $result['resource'] = 'index#lnk_sign_in_now';
                        $result['message'] = $this->T('Falha no login! Você deve assinar para receber o serviço', array());
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
                            $result['message'] = $this->T('Falha no login! Entre com suas credenciais do Instagram.', array());
                            $result['cause'] = 'credentials_update_required';
                            $result['authenticated'] = false;
                        } else {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = $this->T('Você deve assinar novamente para receber o serviço.', array());
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
                                $result['message'] = $this->T('Senha incorreta!. Entre com sua senha de Instagram.', array());
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;
                            } else {
                                //el perfil existe en instagram pero no en la base de datos
                                $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array());
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;
                            }
                        } else {
                            //nombre de usuario informado no existe en instagram
                            $result['message'] = $this->T('Falha no login! O nome de usuário fornecido não existe no Instagram.', array());
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
                        }
                        $this->user_model->update_user($user[$index]['id'], array(
                            'login' => $datas['user_login'],
                            'pass' => $datas['user_pass'],
                            'status_id' => $status_id
                        ));
                        $this->user_model->set_sesion($user[$index]['id'], $this->session);
                        $result['resource'] = 'client';
                        $result['verify_link'] = $data_insta['verify_account_url'];
                        $result['return_link'] = 'client';
                        $result['message'] = $this->T('Sua conta precisa ser verificada no Instagram', array());
                        $result['cause'] = 'checkpoint_required';
                        $result['authenticated'] = true;
                    } else {
                        //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                        $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array());
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
                            }
                            $this->user_model->update_user($user[$index]['id'], array(
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => $status_id
                            ));
                            $this->user_model->set_sesion($user[$index]['id'], $this->session);
                            $result['return_link'] = 'index';
                            $result['message'] = $this->T('Sua conta precisa ser verificada no Instagram com código enviado ao numero de telefone que comtênm os digitos ', array(0 => $data_insta['obfuscated_phone_number']));
                            $result['cause'] = 'phone_verification_settings';
                            $result['verify_link'] = '';
                            $result['obfuscated_phone_number'] = $data_insta['obfuscated_phone_number'];
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array());
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
                            }
                            $this->user_model->update_user($user[$index]['id'], array(
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => $status_id
                            ));
                            $this->user_model->set_sesion($user[$index]['id'], $this->session);
                            $result['resource'] = 'client';
                            $result['return_link'] = 'index';
                            $result['verify_link'] = '';
                            $result['message'] = $this->T('Sua conta esta presentando problemas temporalmente no Instagram. Entre em contato conosco para resolver o problema', array());
                            $result['cause'] = 'empty_message';
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array());
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
                            }
                            $this->user_model->update_user($user[$index]['id'], array(
                                'login' => $datas['user_login'],
                                'pass' => $datas['user_pass'],
                                'status_id' => $status_id
                            ));
                            $result['resource'] = 'client';
                            $result['return_link'] = 'index';
                            $result['verify_link'] = '';
                            $result['message'] = $data_insta['unknow_message'];
                            $result['cause'] = 'unknow_message';
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array());
                            $result['cause'] = 'error_login';
                            $result['authenticated'] = false;
                        }
                    }
                } else {
                    $result['message'] = $this->T('Se o problema no login continua, por favor entre em contato com o Atendimento', array());
                    $result['cause'] = 'error_login';
                    $result['authenticated'] = false;
                }
            }
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
    
    public function check_user_for_sing_in() { //sign in with passive instagram profile verification
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');
        $datas = $this->input->post();
        //$datas['utm_source'] = isset($datas_get['utm_source']) ? urldecode($datas_get['utm_source']) : "NULL";
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
                //TODO: enviar para el navegador los datos del usuario logueado en las cookies para chequearlas en los PASSOS 2 y 3
            } else {
                if ($real_status ==1) {
                    $this->user_model->update_user($client[$i]['id'], array(
                        'name' => $data_insta->full_name,
                        'email' => $datas['client_email'],
                        'login' => $datas['client_login'],
                        'pass' => $datas['client_pass'],
                        'init_date' => time()));
                    $this->client_model->update_client($client[$i]['id'], array(
                        'insta_followers_ini' => $data_insta->follower_count,
                        'insta_following' => $data_insta->following,
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
                    $response['message'] = $this->T('O usuario informado já tem cadastro no sistema.', array());
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
            $response['message'] = $this->T('O nome de usuario informado não é um perfil do Instagram.', array());
        }
        echo json_encode($response);
    }

    public function check_client_data_bank() {  //new_check_client_data_bank       
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $datas = $this->input->post(); 
        $this->load->model('class/client_model');
        $query='SELECT purchase_counter FROM clients WHERE user_id='.$datas['pk'];
        $purchase_counter = ($this->client_model->execute_sql_query($query));
        $purchase_counter=(int)$purchase_counter[0]['purchase_counter'];

        if($purchase_counter>0){
           
            $this->load->model('class/user_model');
            $this->load->model('class/user_status');
            $this->load->model('class/credit_card_status');
            
            if ($this->validate_post_credit_card_datas($datas)) {
                //0. salvar datos del carton de credito
                try {
                    $this->client_model->update_client($datas['pk'], array(
                        'credit_card_number' => $datas['credit_card_number'],
                        'credit_card_cvc' => $datas['credit_card_cvc'],
                        'credit_card_name' => $datas['credit_card_name'],
                        'credit_card_exp_month' => $datas['credit_card_exp_month'],
                        'credit_card_exp_year' => $datas['credit_card_exp_year']
                    ));
                    if(isset($datas['ticket_peixe_urbano'])){
                        $this->client_model->update_client($datas['pk'], array(
                            'ticket_peixe_urbano' => $datas['ticket_peixe_urbano']                    
                        ));
                    }
                } catch (Exception $exc) {
                    $result['success'] = false;
                    $result['exception'] = $exc->getTraceAsString();
                    $result['message'] = $this->T('Error actualizando en base de datos', array());
                    //2. hacel el pagamento segun el plano
                } finally {
                    // TODO: Hacer clase Plane
                    if ($datas['plane_type'] === '2' || $datas['plane_type'] === '3' || $datas['plane_type'] === '4' || $datas['plane_type'] === '5') {
                        $sql = 'SELECT * FROM plane WHERE id=' . $datas['plane_type'];
                        $plane_datas = $this->user_model->execute_sql_query($sql)[0];
                        $response = $this->do_payment_by_plane($datas, $plane_datas['initial_val'], $plane_datas['normal_val']);
                    } else
                        $response['flag_initial_payment'] = false;
                }
                //3. si pagamento correcto: logar cliente, establecer sesion, actualizar status, emails, initdate
                if ($response['flag_initial_payment']) {
                    $this->client_model->update_client($datas['pk'], array(
                        'plane_id' => $datas['plane_type']));
                    $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass']);
                    if ($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                        /*if ($datas['need_delete'] < $GLOBALS['sistem_config']->MIN_MARGIN_TO_INIT)
                            $datas['status_id'] = user_status::UNFOLLOW;
                        else*/
                            $datas['status_id'] = user_status::ACTIVE;
                        $this->user_model->update_user($datas['pk'], array(
                            'init_date' => time(),
                            'status_id' => $datas['status_id']));
                        if ($data_insta['insta_login_response']) {
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
                    $result['message'] = $this->T('Usuário cadastrado com sucesso', array());
                } else {
                    $value['purchase_counter']=$purchase_counter-1;
                    $this->client_model->decrement_purchase_retry($datas['pk'],$value);
                    $result['success'] = false;
                    $result['message'] = 'Incorrect credit card datas!!';
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Acesso não permitido', array());
            } 
        }else{
            $result['success'] = false;
            $result['message'] = $this->T('Alcançõu a quantidade máxima de retentativa de compra, por favor, entre en contato con o atendimento', array());
        }
        
        echo json_encode($result);
    }

    public function do_payment_by_plane($datas, $initial_value, $recurrency_value) {
        $this->load->model('class/client_model');
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        //Amigos de Pedro
        if(isset($datas['ticket_peixe_urbano']) && ($datas['ticket_peixe_urbano']==='AMIGOSDOPEDRO'|| ($datas['ticket_peixe_urbano']==='FITNESS'))){
            //1. recurrencia para un mes mas alante
            $datas['amount_in_cents'] = $recurrency_value;
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
                $response['message'] = $this->T('Compra não sucedida. Problemas com o pagamento', array());
            }            
        } else{
            //1. hacer un pagamento inicial con el valor inicial del plano
            $response = array();
            if ($datas['early_client_canceled'] === 'false' || $datas['early_client_canceled'] === false)
                $datas['amount_in_cents'] = $initial_value;
            else
                $datas['amount_in_cents'] = $recurrency_value;

            //1.1 + dos dias gratis
            $flag=false;
            $datas['pay_day'] = time();
            if ($datas['early_client_canceled'] !== 'false'){
                $resp = $this->check_mundipagg_credit_card($datas);
                if(is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)
                    $flag=true;
            } else {
                $datas['pay_day'] = strtotime("+" . $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS . " days", $datas['pay_day']);
                $resp = $this->check_recurrency_mundipagg_credit_card($datas, 1);
                if(is_object($resp) && $resp->isSuccess())
                    $flag=true;
            }
            if (is_object($resp)) {
                if ($flag) {
                    $this->client_model->update_client($datas['pk'], array(
                        'initial_order_key' => $resp->getData()->OrderResult->OrderKey));
                    $response['flag_initial_payment'] = true;
                    //2. recurrencia para un mes mas alante
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
                    }
                } else {
                    $response['flag_initial_payment'] = false;
                    if (isset($resp->getData()->OrderResult->OrderKey)) {
                        $this->client_model->update_client($datas['pk'], array(
                            'initial_order_key' => $resp->getData()->OrderResult->OrderKey));
                    }
                    $response['message'] = $this->T('Compra não sucedida. Problemas com o pagamento', array());
                }
            } else {
                $response['flag_initial_payment'] = false;
                if (is_array($resp))
                    $response['message'] = $resp["message"];
                else
                    $response['message'] = $this->T('Compra não sucedida. Problemas com o pagamento', array());
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
        $response = $Payment->create_recurrency_payment($payment_data, $cnt);
        //}
        return $response;
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
            $this->client_model->update_client($this->session->userdata('id'), array(
                'unfollow_total' => $datas['unfollow_total']
            ));
            $response['success'] = true;
            $response['unfollow_total'] = $datas['unfollow_total'];
            //}
        }
        echo json_encode($response);
    }
    
    public function autolike() {
        $this->load->model('class/user_role');
        $this->load->model('class/client_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $datas = $this->input->post();
            $this->client_model->update_client($this->session->userdata('id'), array(
                'like_first' => (int) $datas['autolike']
            ));
            $response['success'] = true;
            $response['autolike'] = $datas['autolike'];
        }
        echo json_encode($response);
    }

    public function update_client_datas() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        if ($this->session->userdata('id')) {
            //1.TODO: recibir los datos que vienen en las cookies desde el navegador y verificar que sea el mismo usuario que se logueo en PASSO 1
            //---despues de verificar datos bancarios correctos, pasar as user_status::UNFOLLOW o a ACTIVE
            $this->load->model('class/client_model');
            $this->load->model('class/user_model');
            $this->load->model('class/user_status');
            $this->load->model('class/credit_card_status');
            $datas = $this->input->post();
            $now = time();
            if ($this->validate_post_credit_card_datas($datas)) {
                $client_data = $this->client_model->get_client_by_id($this->session->userdata('id'))[0];
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
                        $result['message'] = $this->T('Erro actualizando em banco de dados', array());
                    } finally {
                        $flag_pay_now = false;
                        $flag_pay_day = false;
                        //Determinar valor inicial del pagamento
                        if ($datas['client_update_plane'] == 1)
                            $datas['client_update_plane'] = 4;
                        if ($now < $client_data['pay_day'] && !($datas['client_update_plane'] > $this->session->userdata('plane_id'))) {
                            $pay_values['initial_value'] = $this->client_model->get_promotional_pay_value($datas['client_update_plane']);
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
                            $pay_values['normal_value'] = $this->client_model->get_normal_pay_value($this->session->userdata('plane_id'));
                        }

                        if ($payments_days['pay_now']) { //si necesitara hacer un pagamento ahora
                            $datas['pay_day'] = time();
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
                                    $result['message'] = $this->T('Erro actualizando em banco de dados', array());
                                } finally {
                                    $result['success'] = true;
                                    $result['resource'] = 'client';
                                    $result['message'] = $this->T('Dados bancários atualizados corretamente', array());
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
                                $result['message'] = is_array($resp_pay_now) ? $resp_pay_now["message"] : $this->T("Erro inesperado! Provávelmente Cartão inválido, entre em contato com o atendimento.", array());
                            else
                                $result['message'] = is_array($resp_pay_day) ? $resp_pay_day["message"] : $this->T("Erro inesperado! Provávelmente Cartão inválido, entre em contato com o atendimento.", array());
                        } else
                        if (($payments_days['pay_now'] && $flag_pay_now && !$flag_pay_day)) {
                            //se hiso el primer pagamento bien, pero la recurrencia mal
                            $result['success'] = true;
                            $result['resource'] = 'client';
                            $result['message'] = $this->T('Actualização bem sucedida, mas deve atualizar novamente até a data de pagamento ( @1 )', array(0 => $payments_days['pay_now']));
                        }
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Você não pode atualizar seu cartão no dia do pagamento', array());
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Acesso não permitido', array());
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
        $id = $this->session->userdata('id');
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
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
                                    $result['message'] = $this->T('Geolocalização adicionada corretamente', array());
                                } else {
                                    $result['message'] = $this->T('O trabalho com a geolocalização começara depois', array());
                                }
                            } else {
                                $result['success'] = false;
                                $result['message'] = $this->T('Erro no sistema, tente novamente', array());
                            }
                        /*} else {
                            $result['success'] = false;
                            $result['message'] = $this->T('A geolocalização @1 é um perfil privado', array(0 => $profile['geolocalization']));
                        }*/
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('@1 não é uma geolocalização do Instagram', array(0 => $profile['geolocalization']));
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Você alcançou a quantidade máxima de geolocalizações ativas', array());
                }
            } else {
                $result['success'] = false;                    
                if($is_active_profile)
                    $result['message'] = $this->T('A geolocalização informada é um perfil ativo', array());
                else
                    $result['message']=$this->T('A geolocalizaçao informada ja está ativa', array());                
            }
            echo json_encode($result);
        }
    }
        
    public function client_desactive_geolocalization() {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['geolocalization'])) {
                $result['success'] = true;
                $result['message'] = $this->T('Geolocalização eliminada', array());
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro no sistema, tente novamente', array());
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
            if (!$is_active_profile /*&& !$is_active_geolocalization*/) {
                if ($N_profiles < $GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT) {
                    $profile_datas = $this->check_insta_profile($profile['profile']);
                    if($profile_datas) {                                                
                        if(!$profile_datas->is_private) {
                            $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['profile'], $profile_datas->pk, '0');
                            if ($p) {
                                if ($this->session->userdata('status_id') == user_status::ACTIVE && $this->session->userdata('insta_datas'))
                                    $q = $this->client_model->insert_profile_in_daily_work($p, $this->session->userdata('insta_datas'), $N, $active_profiles, $this->session->userdata('to_follow'));
                                else
                                    $q = true;
                                //$profile_datas = $this->check_insta_profile($profile['profile'], $p);
                                $result['success'] = true;
                                $result['img_url'] = $profile_datas->profile_pic_url;
                                $result['profile'] = $profile['profile'];
                                $result['follows_from_profile'] = $profile_datas->follows;
                                if ($q) {
                                    $result['message'] = $this->T('Perfil adicionado corretamente', array());
                                } else {
                                    $result['message'] = $this->T('O trabalho com o perfil começara depois', array());
                                }
                            } else {
                                $result['success'] = false;
                                $result['message'] = $this->T('Erro no sistema, tente novamente', array());
                            }
                        } else {
                            $result['success'] = false;
                            $result['message'] = $this->T('O perfil @1 é um perfil privado', array(0 => $profile['profile']));
                        }                        
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('@1 não é um perfil do Instagram', array(0 => $profile['profile']));
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Você alcançou a quantidade máxima de perfis ativos', array());
                }
            } else {
                $result['success'] = false;                    
                if($is_active_profile)
                    $result['message']=$this->T('O perfil informado ja está ativo', array());    
                else
                    $result['message'] = $this->T('O perfil informado é uma geolocalização ativa', array());                
            }
            echo json_encode($result);
        }
    }

    public function client_desactive_profiles() {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['profile'])) {
                $result['success'] = true;
                $result['message'] = $this->T('Perfil eliminado', array());
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro no sistema, tente novamente', array());
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
    
    
    public function message() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \dumbu\cls\system_config();
        $this->Gmail = new \dumbu\cls\Gmail();
        $datas = $this->input->post();
        $result = $this->Gmail->send_client_contact_form($datas['name'], $datas['email'], $datas['message'], $datas['company'], $datas['telf']);
        if ($result['success']) {
            $result['message'] = $this->T('Mensagem enviada, agradecemos seu contato', array());
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
                $data_insta['insta_followers_ini'] = $user_data->follower_count;
                $data_insta['insta_following'] = $user_data->following;
                $data_insta['insta_name']=$user_data->full_name;
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
                            $response['array_profiles'] = NULL;
                            $response['message'] = 'Profiles unloaded by instagram failed connection';
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

    public function help() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $param['languaje'] = $GLOBALS['sistem_config']->LANGUAGE;
        $this->load->view('ajuda', $param);
    }

    public function create_profiles_datas_to_display_as_json() {
        echo($this->create_profiles_datas_to_display());
    }

    public function display_access_error() {
        $this->session->sess_destroy();
        header('Location: ' . base_url().'index.php/welcome/');
    }
    
    public function update_all_retry_clients(){
        //$array_ids=array(715,1176,1735,2821,2193,245,2942,3423,4629,5187,5885,6211,6351,6512,6544,7724,7952,7953,8239,8326,8450,11428,10981,11323,11527,11461,11271,11431,11522);
        //$array_ids=array(2942,3423,5187,5885,6211,7952,7953,8239,11428,10981,11527,11461,11431,11522);
        $array_ids=array(1296,1825,3178,7147,9397,7935,10074,10377,10881,10984,11344,11363,11382,11440,11340,11313,11330,11369,11451,11395,11607,11610,11522);
        $N=count($array_ids);
        for($i=0;$i<$N;$i++){
            $this->update_client_after_retry_payment_success($array_ids[$i]);
        }
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
        $payment_data['amount_in_cents'] = $plane['normal_val'];
        $payment_data['pay_day'] = strtotime("+1 month", time());
        $resp = $this->check_recurrency_mundipagg_credit_card($payment_data, 0);
        //4. salvar nuevos pay_day e order_key
        if (is_object($resp) && $resp->isSuccess()) {
            //2. eliminar recurrencia actual en la Mundipagg
            $this->delete_recurrency_payment($client['order_key']);
            $this->client_model->update_client($user_id, array(
                'order_key' => $resp->getData()->OrderResult->OrderKey,
                'pay_day' => $payment_data['pay_day'])); 
            echo 'Client '.$user_id.' updated correctly. New order key is:  '.$resp->getData()->OrderResult->OrderKey.'<br>';
            //5. actualizar status del cliente
            $data_insta = $this->is_insta_user($client['login'], $client['pass']);
            if($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                $this->user_model->update_user($user_id, array(
                    'status_id' => user_status::ACTIVE
                ));
            } else
            if ($data_insta['status'] === 'ok' && !$data_insta['authenticated'])
                $this->user_model->update_user($user_id, array(
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));


            else
                $this->user_model->update_user($user_id, array(
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));
        }        
    }
    
    public function prevalence(){
        $this->load->model('class/user_model');
        $result=$this->user_model->client_prevalence();
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
    
}
