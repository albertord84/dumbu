<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index1() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
        $this->Robot = new \dumbu\cls\Robot();
        $login_data = $this->Robot->bot_login('ffonsecassa', 'cff100303');
        var_dump($login_data);
//     
        
    }

    public function index() {
        $data['section1'] = $this->load->view('responsive_views/user/users_ initial_painel', '', true);
        $data['section2'] = $this->load->view('responsive_views/user/users_howfunction_painel', '', true);
        $data['section3'] = $this->load->view('responsive_views/user/users_singin_painel', '', true);
        $data['section4'] = $this->load->view('responsive_views/user/users_talkme_painel', '', true);
        $data['section5'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
        $this->load->view('view', $data);
    }

    public function purchase() {
        $data['section1'] = $this->load->view('responsive_views/purchase/purchase_ initial_painel', '', true);
        $data['section2'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
        $this->load->view('view_purchase', $data);
    }

    public function client() {
        $this->load->model('class/user_role');
        $this->load->model('class/dumbu_system_config');
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_status');
        $status_description = array(1 => 'ATIVO', 2 => 'DESABILITADO', 3 => 'INATIVO', 4 => '', 5 => '', 6 => 'ATIVO'/* 'PENDENTE' */, 7 => 'NÂO INICIADO', 8 => '', 9 => 'INATIVO', 10 => 'LIMITADO');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $datas1['MAX_NUM_PROFILES'] = dumbu_system_config::REFERENCE_PROFILE_AMOUNT;
            $my_profile_datas = $this->Robot->get_insta_ref_prof_data($this->session->userdata('login'));
            $datas1['my_img_profile'] = $my_profile_datas->profile_pic_url;

            $sql = "SELECT * FROM clients WHERE clients.user_id='" . $this->session->userdata('id') . "'";
            $init_client_datas = $this->user_model->execute_sql_query($sql);

            $sql = "SELECT * FROM reference_profile WHERE client_id='" . $this->session->userdata('id') . "'";
            $total_amount_reference_profile_today = $this->user_model->execute_sql_query($sql);

//            $sql = "SELECT count(*) as followeds FROM followed INNER JOIN reference_profile ON reference_profile.id = followed.reference_id INNER JOIN clients ON clients.user_id = reference_profile.client_id WHERE (clients.user_id = " . $this->session->userdata('id') . ")";
            $sql = "SELECT SUM(follows) as followeds FROM reference_profile WHERE client_id = " . $this->session->userdata('id');
            //$sql="SELECT * FROM reference_profile WHERE client_id='".$this->session->userdata('id')."'";
            $total_amount_followers_today = $this->user_model->execute_sql_query($sql);
            $followeds = (string) $total_amount_followers_today[0]["followeds"];

            $datas1['my_actual_followers'] = $my_profile_datas->follower_count;
            $datas1['my_actual_followings'] = $my_profile_datas->following;
            $datas1['my_sigin_date'] = date('d-m-Y', $this->session->userdata('init_date'));
            date_default_timezone_set('Etc/UTC');
            $datas1['today'] = date('d-m-Y', time());
            $datas1['my_initial_followers'] = $init_client_datas[0]['insta_followers_ini'];
            $datas1['my_initial_followings'] = $init_client_datas[0]['insta_following'];
            $datas1['total_amount_reference_profile_today'] = count($total_amount_reference_profile_today);
            $datas1['total_amount_followers_today'] = $followeds;
            $datas1['my_login_profile'] = $this->session->userdata('login');
            $datas1['plane_id'] = $this->session->userdata('plane_id');
            $datas1['all_planes'] = $this->client_model->get_all_planes();
            $datas1['currency'] = dumbu_system_config::CURRENCY;

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
                                if (!$response)
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
            $this->load->view('view_client', $data);
        } else {
            $this->display_access_error();
        }
    }

    public function user_do_login() {
        $datas = $this->input->post();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        $this->load->model('class/dumbu_system_config');
        //Is an active Administrator?
        $query = 'SELECT * FROM users' .
                ' WHERE login="' . $datas['user_login'] . '" AND pass="' . $datas['user_pass'] .
                '" AND role_id=' . user_role::ADMIN . ' AND status_id=' . user_status::ACTIVE;
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
                                    if (!$response)
                                        $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                                    //$this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, dumbu_system_config::DIALY_REQUESTS_BY_CLIENT);
                                }
                            }
                            $result['resource'] = 'client';
                            $result['message'] = 'Usuário ' . $datas['user_login'] . ' logueado';
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
                                        $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                                        //$this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, dumbu_system_config::DIALY_REQUESTS_BY_CLIENT);
                                    }
                                }
                            }

                            if ($st == user_status::UNFOLLOW && $data_insta['insta_following'] < Dumbu_system_config::INSTA_MAX_FOLLOWING - Dumbu_system_config::MIN_MARGIN_TO_INIT) {
                                $st = user_status::ACTIVE;
                                $active_profiles = $this->client_model->get_client_active_profiles($user[$index]['id']);
                                $N = count($active_profiles);
                                //crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                                for ($i = 0; $i < $N; $i++) {
                                    $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                                    //$this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, dumbu_system_config::DIALY_REQUESTS_BY_CLIENT);
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
                            $result['message'] = 'Usuário ' . $datas['user_login'] . ' logueado';
                            $result['role'] = 'CLIENT';
                            $result['authenticated'] = true;
                        } else
                        if ($st == user_status::BEGINNER) {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = 'Falha no login! Seu cadastro esta incompleto. Por favor, termine sua assinatura.';
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        } else
                        if ($st == user_status::DELETED || $st == user_status::INACTIVE) {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = 'Falha no login! Você deve assinar novamente para receber o serviço';
                            $result['cause'] = 'signin_required';
                            $result['authenticated'] = false;
                        }
                    } else {
                        $result['resource'] = 'index#lnk_sign_in_now';
                        $result['message'] = 'Falha no login! Você deve assinar para receber o serviço';
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
                            $result['message'] = 'Falha no login! Entre com suas credenciais do Instagram.';
                            $result['cause'] = 'credentials_update_required';
                            $result['authenticated'] = false;
                        } else {
                            $result['resource'] = 'index#lnk_sign_in_now';
                            $result['message'] = 'Você deve assinar novamente para receber o serviço.';
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
                                $result['message'] = 'Senha incorreta!. Entre com sua senha de Instagram.';
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;
                            } else {
                                //el perfil existe en instagram pero no en la base de datos
                                $result['message'] = 'Falha no login! Certifique-se de que possui uma assinatura antes de entrar.';
                                $result['cause'] = 'error_login';
                                $result['authenticated'] = false;
                            }
                        } else {
                            //nombre de usuario informado no existe en instagram
                            $result['message'] = 'Falha no login! O nome de usuário fornecido não existe no Instagram.';
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
                        $result['message'] = 'Sua conta precisa ser verificada no Instagram';
                        $result['cause'] = 'checkpoint_required';
                        $result['authenticated'] = true;
                    } else {
                        //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                        $result['message'] = 'Falha no login! Certifique-se de que possui uma assinatura antes de entrar.';
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
                            $result['message'] = 'Sua conta precisa ser verificada no Instagram com código enviado ao numero de telefone que comtênm os digitos ' . $data_insta['obfuscated_phone_number'];
                            $result['cause'] = 'phone_verification_settings';
                            $result['verify_link'] = '';
                            $result['obfuscated_phone_number'] = $data_insta['obfuscated_phone_number'];
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = 'Falha no login! Certifique-se de que possui uma assinatura antes de entrar.';
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
                            $result['message'] = 'Sua conta esta presentando problemas temporalmente no Instagram. Entre em contato conosco para resolver o problema';
                            $result['cause'] = 'empty_message';
                            $result['authenticated'] = false;
                        } else {
                            //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                            $result['message'] = 'Falha no login! Certifique-se de que possui uma assinatura antes de entrar.';
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
                            $result['message'] = 'Falha no login! Certifique-se de que possui uma assinatura antes de entrar.';
                            $result['cause'] = 'error_login';
                            $result['authenticated'] = false;
                        }
                    }
                } else {
                    $result['message'] = 'Se o problema no login continua, por favor entre em contato com o Atendimento';
                    $result['cause'] = 'error_login';
                    $result['authenticated'] = false;
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
        //$datas['utm_source'] = isset($datas_get['utm_source']) ? urldecode($datas_get['utm_source']) : "NULL";
        $data_insta = $this->check_insta_profile($datas['client_login']);
        if ($data_insta) {
            if (!$data_insta->following)
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
                if ($real_status == 1) {
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
                    if ($early_client_canceled)
                        $response['early_client_canceled'] = true;
                    else
                        $response['early_client_canceled'] = false;
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
        } else {
            $response['success'] = false;
            $response['cause'] = 'missing_user';
            $response['message'] = 'O nome de usuario informado não é um perfil do Instagram.';
        }
        echo json_encode($response);
    }

    public function check_client_data_bank() {  //new_check_client_data_bank       
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/dumbu_system_config');
        $this->load->model('class/credit_card_status');
        $datas = $this->input->post();
        if ($this->validate_post_credit_card_datas($datas)) {
            //0. salvar datos del carton de credito
            try {
                $this->client_model->update_client($datas['pk'], array(
                    'credit_card_number' => $datas['client_credit_card_number'],
                    'credit_card_cvc' => $datas['client_credit_card_cvv'],
                    'credit_card_name' => $datas['client_credit_card_name'],
                    'credit_card_exp_month' => $datas['client_credit_card_validate_month'],
                    'credit_card_exp_year' => $datas['client_credit_card_validate_year']
                ));
            } catch (Exception $exc) {
                $result['success'] = false;
                $result['exception'] = $exc->getTraceAsString();
                $result['message'] = 'Error actualizando en base de datos';
                //2. hacel el pagsmento segun el plano    
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
                    if ($datas['need_delete'] < dumbu_system_config::MIN_MARGIN_TO_INIT)
                        $datas['status_id'] = user_status::UNFOLLOW;
                    else
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
                } else{
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
                $result['message'] = 'Usuário cadastrado com sucesso';
            } else {
                $result['success'] = false;
                $result['message'] = $response['message'];
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Acesso não permitido';
        }
        echo json_encode($result);
    }

    public function do_payment_by_plane($datas, $initial_value, $recurrency_value) {
        $this->load->model('class/client_model');
        $this->load->model('class/dumbu_system_config');
        //1. hacer un pagamento inicial con el valor inicial del plano
        $response = array();
        if ($datas['early_client_canceled'] === 'false' || $datas['early_client_canceled'] === false)
            $datas['amount_in_cents'] = $initial_value;
        else
            $datas['amount_in_cents'] = $recurrency_value;

        //1.1 + dos dias gratis
        $datas['pay_day'] = time();
        $datas['pay_day'] = strtotime("+" . dumbu_system_config::PROMOTION_N_FREE_DAYS . " days", $datas['pay_day']);

        $resp = $this->check_mundipagg_credit_card($datas, 1);
        if (is_object($resp) && $resp->isSuccess()) {
            $this->client_model->update_client($datas['pk'], array(
                'initial_order_key' => $resp->getData()->OrderResult->OrderKey));
            $response['flag_initial_payment'] = true;
            //2. recurrencia para un mes mas alante
            $datas['amount_in_cents'] = $recurrency_value;
            $datas['pay_day'] = strtotime("+1 month", $datas['pay_day']);
            $resp = $this->check_mundipagg_credit_card($datas, 0);
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
            if (is_array($resp))
                $response['message'] = $resp["message"];
            else
                $response['message'] = 'Compra não sucedida. Problemas com o pagamento';
        }
        return $response;
    }

    public function check_mundipagg_credit_card($datas, $cnt) {
        $payment_data['credit_card_number'] = $datas['client_credit_card_number'];
        $payment_data['credit_card_name'] = $datas['client_credit_card_name'];
        $payment_data['credit_card_exp_month'] = $datas['client_credit_card_validate_month'];
        $payment_data['credit_card_exp_year'] = $datas['client_credit_card_validate_year'];
        $payment_data['credit_card_cvc'] = $datas['client_credit_card_cvv'];
        $payment_data['amount_in_cents'] = $datas['amount_in_cents'];
        $payment_data['pay_day'] = $datas['pay_day'];
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        $Payment = new \dumbu\cls\Payment();
        /* if ($cnt === 1) {
          $response = $Payment->create_payment($payment_data);
          } else { */
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

    public function old_update_client_datas() {
        if ($this->session->userdata('id')) {
            //1.TODO: recibir los datos que vienen en las cookies desde el navegador y verificar que sea el mismo usuario que se logueo en PASSO 1
            //---despues de verificar datos bancarios correctos, pasar as user_status::UNFOLLOW o a ACTIVE
            $this->load->model('class/client_model');
            $this->load->model('class/user_model');
            $this->load->model('class/user_status');
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/credit_card_status');
            $datas = $this->input->post();
            if ($this->validate_post_credit_card_datas($datas)) {
                $client_data = $this->client_model->get_client_by_id($this->session->userdata('id'))[0];
                if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT)
                    $datas['pay_day'] = time();
                else
                    $datas['pay_day'] = $client_data['pay_day'];
                $datas['amount_in_cents'] = dumbu_system_config::PAYMENT_VALUE;
                try {
                    $this->user_model->update_user($this->session->userdata('id'), array(
                        'email' => $datas['client_email']));
                    $this->client_model->update_client($this->session->userdata('id'), array(
                        'credit_card_number' => $datas['client_credit_card_number'],
                        'credit_card_cvc' => $datas['client_credit_card_cvv'],
                        'credit_card_name' => $datas['client_credit_card_name'],
                        'credit_card_exp_month' => $datas['client_credit_card_validate_month'],
                        'credit_card_exp_year' => $datas['client_credit_card_validate_year'],
                        'pay_day' => $datas['pay_day']
                    ));
                } catch (Exception $exc) {
                    $result['success'] = false;
                    $result['exception'] = $exc->getTraceAsString();
                    $result['message'] = 'Error actualizando en base de datos';
                } finally {
                    //if(true){                    
                    $response_delete_early_payment = '';
                    $resp = $this->check_mundipagg_credit_card($datas, 0);
                    if (is_object($resp) && $resp->isSuccess()) {
                        try {
                            $this->client_model->update_client($this->session->userdata('id'), array(
                                'order_key' => $resp->getData()->OrderResult->OrderKey));
                            if ($client_data['order_key'])
                                $response_delete_early_payment = $this->delete_recurrency_payment($client_data['order_key']);
                            if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT) {
                                $datas['status_id'] = user_status::ACTIVE; //para que Payment intente hacer el pagamento y si ok entonces lo active y le ponga trabajo
                            } else
                                $datas['status_id'] = $this->session->userdata('status_id');
                            if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT) {
                                $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
                                $N = count($active_profiles);
                                for ($i = 0; $i < $N; $i++) {
                                    $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $this->session->userdata('insta_login_response'), $i, $active_profiles, $this->session->userdata('to_follow'));
                                    //$this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $this->session->userdata('insta_login_response'), $i, $active_profiles, dumbu_system_config::DIALY_REQUESTS_BY_CLIENT);
                                }
                            }
                            $this->user_model->update_user($this->session->userdata('id'), array(
                                'status_id' => $datas['status_id']));
                            $this->session->set_userdata('status_id', $datas['status_id']);
                        } catch (Exception $exc) {
                            $this->user_model->update_user($datas['pk'], array(
                                'status_id' => $this->session->userdata('status_id'))); //the previous
                            $this->client_model->update_client($datas['pk'], array(
                                'order_key' => $client_data['order_key'])); //the previous
                            $result['success'] = false;
                            $result['exception'] = $exc->getTraceAsString();
                            $result['message'] = 'Error actualizando en base de datos';
                        } finally {
                            $result['success'] = true;
                            $result['resource'] = 'client';
                            $result['message'] = 'Dados bancários confirmados corretamente';
                            $result['response_delete_early_payment'] = $response_delete_early_payment;
                        }
                    } else {
                        //restablecer en la base de datos los datos anteriores
                        $this->client_model->update_client($this->session->userdata('id'), array(
                            'credit_card_number' => $client_data['credit_card_number'],
                            'credit_card_cvc' => $client_data['credit_card_cvc'],
                            'credit_card_name' => $client_data['credit_card_name'],
                            'credit_card_exp_month' => $client_data['credit_card_exp_month'],
                            'credit_card_exp_year' => $client_data['credit_card_exp_year'],
                            'order_key' => $client_data['order_key']
                        ));
                        $result['success'] = false;
                        $result['message'] = 'Dados bancários incorretos. Se o problema continua entre em contasto com o Atendimento';
                    }
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Acesso não permitido';
            }
            echo json_encode($result);
        }
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
            if ($this->validate_post_credit_card_datas($datas)) {
                $client_data = $this->client_model->get_client_by_id($this->session->userdata('id'))[0];
                if ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT) {
                    $payments_days['pay_day'] = time();
                    $payments_days['pay_now'] = false;
                    $datas['pay_day'] = $payments_days['pay_day'];
                } else {
                    $payments_days = $this->get_pay_day($client_data['pay_day']);
                    $datas['pay_day'] = $payments_days['pay_day'];
                }
                if ($payments_days['pay_day'] != null) { //dia de actualizacion diferente de dia de pagamento                    
                    try {
                        $this->user_model->update_user($this->session->userdata('id'), array(
                            'email' => $datas['client_email']));
                        $this->client_model->update_client($this->session->userdata('id'), array(
                            'credit_card_number' => $datas['client_credit_card_number'],
                            'credit_card_cvc' => $datas['client_credit_card_cvv'],
                            'credit_card_name' => $datas['client_credit_card_name'],
                            'credit_card_exp_month' => $datas['client_credit_card_validate_month'],
                            'credit_card_exp_year' => $datas['client_credit_card_validate_year'],
                            'pay_day' => $datas['pay_day']
                        ));
                    } catch (Exception $exc) {
                        $result['success'] = false;
                        $result['exception'] = $exc->getTraceAsString();
                        $result['message'] = 'Error actualizando em banco de dados';
                    } finally {
                        $flag_pay_now = false;
                        $flag_pay_day = false;

                        //Determinar valor inicial del pagamento
                        if ($datas['client_update_plane'] == 1)
                            $datas['client_update_plane'] = 4;
                        if ($datas['client_update_plane'] > $this->session->userdata('plane_id')) {
                            $promotional_time_range = $this->user_model->get_signin_date($this->session->userdata('id'));
                            $promotional_time_range = strtotime("+" . dumbu_system_config::PROMOTION_N_FREE_DAYS . " days", $promotional_time_range);
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
                            $resp_pay_now = $this->check_mundipagg_credit_card($datas, 1);
                            if (is_object($resp_pay_now) && $resp_pay_now->isSuccess()) {
                                $this->client_model->update_client($this->session->userdata('id'), array(
                                    'pending_order_key' => $resp_pay_now->getData()->OrderResult->OrderKey));
                                $flag_pay_now = true;
                            }
                        }

                        if (($payments_days['pay_now'] && $flag_pay_now) || !$payments_days['pay_now']) {
                            $response_delete_early_payment = '';
                            $datas['pay_day'] = $payments_days['pay_day'];
                            $datas['amount_in_cents'] = $pay_values['normal_value'];
                            $resp_pay_day = $this->check_mundipagg_credit_card($datas, 0);
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
                                    $result['message'] = 'Error actualizando en base de datos';
                                } finally {
                                    $result['success'] = true;
                                    $result['resource'] = 'client';
                                    $result['message'] = 'Dados bancários atualizados corretamente';
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
                                $result['message'] = $resp_pay_now["message"];
                            else
                                $result['message'] = $resp_pay_day["message"];
                        } else
                        if (($payments_days['pay_now'] && $flag_pay_now && !$flag_pay_day)) {
                            //se hiso el primer pagamento bien, pero la recurrencia mal
                            $result['success'] = true;
                            $result['resource'] = 'client';
                            $result['message'] = 'Actualização bem sucedida, mas deve atualizar novamente até a data de pagamento (' . $payments_days['pay_now'] . ')';
                        }
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Você não pode atualizar seu cartão no dia do pagamento';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Acesso não permitido';
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
                $result['message'] = 'Sua solicitação não pode ser processada no momento. Tente novamente.';
            }
        }
    }

    //functions for reference profiles
    public function client_insert_profile() {
        if ($this->session->userdata('id')) {
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
                    if ($profile_datas) {
                        if (!$profile_datas->is_private) {
                            $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['profile'], $profile_datas->pk);
                            if ($p) {
                                if ($this->session->userdata('status_id') == user_status::ACTIVE && $this->session->userdata('insta_datas'))
                                    $q = $this->client_model->insert_profile_in_daily_work($p, $this->session->userdata('insta_datas'), $N, $active_profiles, $this->session->userdata('to_follow'));
                                //$q = $this->client_model->insert_profile_in_daily_work($p, $this->session->userdata('insta_datas'), $N, $active_profiles, dumbu_system_config::DIALY_REQUESTS_BY_CLIENT);
                                else
                                    $q = true;
                                //$profile_datas = $this->check_insta_profile($profile['profile'], $p);
                                $result['success'] = true;
                                $result['img_url'] = $profile_datas->profile_pic_url;
                                $result['profile'] = $profile['profile'];
                                $result['follows_from_profile'] = $profile_datas->follows;
                                if ($q) {
                                    $result['message'] = 'Perfil adicionado corretamente';
                                } else {
                                    $result['message'] = 'O trabalho com o perfil começara depois';
                                }
                            } else {
                                $result['success'] = false;
                                $result['message'] = 'Erro no sistema, tente novamente';
                            }
                        } else {
                            $result['success'] = false;
                            $result['message'] = 'O perfil ' . $profile['profile'] . ' é um perfil privado';
                        }
                    } else {
                        $result['success'] = false;
                        $result['message'] = $profile['profile'] . ' não é um perfil do Instagram';
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Você alcançou a quantidade máxima de perfis ativos';
                }
            } else {
                if ($is_active_profile) {
                    $result['success'] = false;
                    $result['message'] = 'O perfil informado ja está ativo';
                } elseif ($is_deleted_profile && $N < dumbu_system_config::REFERENCE_PROFILE_AMOUNT) {
                    // $this->client_model->activate_profile($this->session->userdata('id'),$profile);
                }
            }
            echo json_encode($result);
        }
    }

    public function client_desactive_profiles() {
        if ($this->session->userdata('id')) {
            $this->load->model('class/dumbu_system_config');
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['profile'])) {
                $result['success'] = true;
                $result['message'] = 'Perfil eliminado';
            } else {
                $result['success'] = false;
                $result['message'] = 'Erro no sistema, tente novamente';
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
            $result['message'] = 'Mensagem enviada, agradecemos seu contato';
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
                if (is_object($login_data))
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
        header('Location: ' . base_url() . 'index.php/welcome/');
    }

    public function create_profiles_datas_to_display() {
        if ($this->session->userdata('id')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
            $this->Robot = new \dumbu\cls\Robot();
            $this->load->model('class/client_model');
            $client_active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($client_active_profiles);
            if ($N > 0) {
                for ($i = 0; $i < $N; $i++) {
                    $name_profile = $client_active_profiles[$i]['insta_name'];
                    $id_profile = $client_active_profiles[$i]['id'];
                    $datas_of_profile = $this->Robot->get_insta_ref_prof_data($name_profile, $id_profile);
                    $array_profiles[$i]['login_profile'] = $name_profile;
                    $array_profiles[$i]['follows_from_profile'] = $datas_of_profile->follows;
                    if (!$datas_of_profile) { //perfil existia pero fue eliminado de IG
                        $array_profiles[$i]['status_profile'] = 'deleted';
                        $array_profiles[$i]['img_profile'] = base_url() . 'assets/img/profile_deleted.jpg';
                    } else
                    if ($client_active_profiles[$i]['end_date']) { //perfil
                        $array_profiles[$i]['status_profile'] = 'ended';
                        $array_profiles[$i]['img_profile'] = $datas_of_profile->profile_pic_url;
                    } else
                    if ($datas_of_profile->is_private) { //perfil paso a ser privado
                        $array_profiles[$i]['status_profile'] = 'privated';
                        $array_profiles[$i]['img_profile'] = base_url() . 'assets/img/profile_privated.jpg';
                    } else {
                        $array_profiles[$i]['status_profile'] = 'active';
                        $array_profiles[$i]['img_profile'] = $datas_of_profile->profile_pic_url;
                    }
                }
                $response['array_profiles'] = $array_profiles;
            } else {
                $response['array_profiles'] = NULL;
            }
            $response['N'] = $N;
            return json_encode($response);
        } else {
            $this->display_access_error();
        }
    }

    public function help() {
        $this->load->view('ajuda', '');
    }

    public function create_profiles_datas_to_display_as_json() {
        echo($this->create_profiles_datas_to_display());
    }

    public function display_access_error() {
        $this->session->sess_destroy();
        header('Location: ' . base_url() . 'index.php/welcome/');
    }

}
