<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function index() {        
        $this->load->view('admin_login_view');
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
    
    public function admin_do_login() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $datas['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
        $datas = $this->input->post();        
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');
        $query = 'SELECT * FROM users'.
                ' WHERE login="' . $datas['user_login'] . '" AND pass="' . md5($datas['user_pass']) .
                '" AND role_id=' . user_role::ADMIN . ' AND status_id=' . user_status::ACTIVE;
        $user = $this->user_model->execute_sql_query($query);
        if(count($user)){
            $this->user_model->set_sesion($user[0]['id'], $this->session, '');
            $result['role'] = 'ADMIN';
            $result['authenticated'] = true;
            echo json_encode($result);
        } else{
            $result['resource'] = 'index#lnk_sign_in_now';
            $result['message'] = 'Credenciais incorretas';
            $result['cause'] = 'signin_required';
            $result['authenticated'] = false;
            echo json_encode($result);
        }
    }
    
    public function log_out() {
        $data['user_active'] = false;
        $this->load->model('class/user_model');
        $this->user_model->insert_washdog($this->session->userdata('id'),'CLOSING SESSION');
        $this->session->sess_destroy();
        header('Location: ' . base_url() . 'index.php/admin/');
    }    
    
    public function view_admin(){
        $this->load->model('class/user_model');
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $datas['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $query = 'SELECT DISTINCT utm_source FROM clients';
            $datas['utm_source_list'] = $this->user_model->execute_sql_query($query);
            $data['SCRIPT_VERSION'] = $GLOBALS['sistem_config']->SCRIPT_VERSION;
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }        
    }

    public function list_filter_view_or_get_emails() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/admin_model');
            $form_filter = $this->input->get();
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
            $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
            $datas['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $datas['result'] = $this->admin_model->view_clients_or_get_emails_by_filter($form_filter);
            $datas['form_filter'] = $form_filter;
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'),'GET EMAILS');
            $query = 'SELECT DISTINCT utm_source FROM clients';
            $datas['utm_source_list'] = $this->user_model->execute_sql_query($query);
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        } else{
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
    
    public function list_filter_view_pendences() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'),'VIEW PENDENCES');
            $this->load->model('class/admin_model');
            $form_filter = $this->input->get();
            $datas['result'] = $this->admin_model->view_pendences_by_filter($form_filter);
            $datas['form_filter'] = $form_filter;
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_pendences', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }
        else {
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
        
    public function create_pendence() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/admin_model');
            $form_filter = $this->input->get();
            $datas['result'] = $this->admin_model->create_pendence_by_form($form_filter);
            $datas['form_filter'] = $form_filter;
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_pendences', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }
        else {
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
    
    public function update_pendence() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/admin_model');
            $form_filter = $this->input->get();
            $datas['result'] = $this->admin_model->update_pendence($form_filter);
            $datas['form_filter'] = $form_filter;
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_pendences', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }
        else {
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
    
    public function resolve_pendence() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/admin_model');
            $form_filter = $this->input->get();
            $datas['result'] = $this->admin_model->resolve_pendence($form_filter);
            $datas['form_filter'] = $form_filter;
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_pendences', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
            
        }
        else {
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }

    public function desactive_client() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/user_model');
            $this->load->model('class/user_status');
            $id = $this->input->post()['id'];
            try {
                require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/DB.php';
                $DB = new \dumbu\cls\DB();
                $DB->delete_daily_work_client($id);
                $this->user_model->update_user($id, array(
                    'status_id' => user_status::DELETED,
                    'end_date' => time()));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $result['success'] = false;
                $result['message'] = "Erro no banco de dados. Contate o grupo de desenvolvimento!";
            } finally {
                $result['success'] = true;
                $result['message'] = "Cliente desativado com sucesso!";
            }
            echo json_encode($result);
        } else{
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }

    public function recorrency_cancel() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/client_model');
            $id = $this->input->post()['id'];
            $client = $this->client_model->get_client_by_id($id)[0];
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
            $Payment = new \dumbu\cls\Payment();
            $status_cancelamento=0;
            if(count($client['initial_order_key'])>3){
                $response = json_decode($Payment->delete_payment($client['initial_order_key']));
                if ($response->success) 
                    $status_cancelamento=1;
            }
            $response = json_decode($Payment->delete_payment($client['order_key']));
            if ($response->success) 
                $status_cancelamento=$status_cancelamento+2;
                
            if ($status_cancelamento==0){
                $result['success'] = false;
                $result['message'] = 'Não foi possivel cancelar o pagamento, faça direito na Mundipagg!!';
            } else 
            if ($status_cancelamento==1){
                $result['success'] = true;
                $result['message'] = 'ATENÇÂO: somente foi cancelado o initial_order_key. Cancele manualmente a Recurrancia!!';
            }else
            if ($status_cancelamento==2){
                $result['success'] = true;
                $result['message'] = 'ATENÇÂO: somente foi cancelada a Recurrencia. Confira se o cliente não tem Initial Order Key!!';
            }else
            if ($status_cancelamento==3){
                $result['success'] = true;
                $result['message'] = 'Initial_Order_Key e Recurrencia cancelados corretamente!!';
            }
            echo json_encode($result);
        } else{
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }

    public function reference_profile_view() {
        $this->load->model('class/user_role');
        //if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/client_model');
            $this->load->model('class/user_model');
            $id = $this->input->get()['id'];
            
            $sql = 'SELECT plane_id FROM clients WHERE user_id='.$id;
            $plane_id = $this->user_model->execute_sql_query($sql);
            
            $sql = 'SELECT * FROM plane WHERE id='.$plane_id[0]['plane_id'];
            $plane_datas = $this->user_model->execute_sql_query($sql);
            
            $active_profiles = $this->client_model->get_client_active_profiles($id);
            $canceled_profiles = $this->client_model->get_client_canceled_profiles($id);
            $datas['active_profiles'] = $active_profiles;
            $datas['canceled_profiles'] = $canceled_profiles;
            $datas['my_daily_work'] = $this->get_daily_work($active_profiles);
            $datas['plane_datas'] = $plane_datas[0]['to_follow'];
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_reference_profile', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        //} else{
            //echo "Não pode acessar a esse recurso, deve fazer login!!";
        //}
    }

    public function pendences() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            /*$this->load->model('class/client_model');
            $id = $this->input->get()['id'];
            $active_profiles = $this->client_model->get_client_active_profiles($id);
            $canceled_profiles = $this->client_model->get_client_canceled_profiles($id);
            $datas['active_profiles'] = $active_profiles;
            $datas['canceled_profiles'] = $canceled_profiles;
            $datas['my_daily_work'] = $this->get_daily_work($active_profiles);*/
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_pendences', '', true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }
        else {
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
        
    public function change_ticket_peixe_urbano_status_id() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN){
            $this->load->model('class/client_model');
            $datas=$this->input->post();
            if($this->client_model->update_cupom_peixe_urbano_status($datas)){
                $result['success'] = true;
                $result['message'] = 'Stauts de Cupom atualizado corretamente';
            } else{
                $result['success'] = false;
                $result['message'] = 'Erro actualizando status do Cupom';
            }
            echo json_encode($result);
        } else{
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
    
    public function get_daily_work($active_profiles) {
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $n = count($active_profiles);
        $my_daily_work = array();
        //if($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN){
            for ($i = 0; $i < $n; $i++){
                $work = $this->client_model->get_daily_work_to_profile($active_profiles[$i]['id']);
                if (count($work)) {
                    $work = $work[0];
                }
                if (count($work)) {
                    $to_follow = $work['to_follow'];
                    $to_unfollow = $work['to_unfollow'];
                } else {
                    $to_follow = '----';
                    $to_unfollow = '----';
                }
                $tmp = array('profile' => $active_profiles[$i]['insta_name'],
                    'id' => $active_profiles[$i]['id'],
                    'to_follow' => $to_follow,
                    'to_unfollow' => $to_unfollow,
                    'end_date' => $active_profiles[$i]['end_date']
                );
                $my_daily_work[$i] = $tmp;
            }
            return $my_daily_work;
        //} else return 0;
        
    }
    
    public function watchdog() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_watchdog', '' , true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }
        else {
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
    
    public function list_filter_view_watchdog() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $this->load->model('class/admin_model');
            $form_filter = $this->input->get();
            $datas['result'] = $this->admin_model->view_watchdog_by_filter($form_filter);
            $datas['form_filter'] = $form_filter;
            
            $daily_report = $this->get_daily_report($form_filter['user_id']);
            $datas['followings'] = $daily_report['followings'];
            $datas['followers']  = $daily_report['followers'];
            
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_watchdog', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        } else{
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
    
    public function get_daily_report($id) {
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
    
    public function send_curl() {
        $this->load->model('class/user_role');
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $client_id = $this->input->post()['client_id'];
            $curl = urldecode($this->input->post()['curl']);
            
            try {
                require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Robot.php';
                $Robot = new \dumbu\cls\Robot();
                $Robot->set_client_cookies_by_curl($client_id, $curl, NULL);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
                $result['success'] = false;
                $result['message'] = "Erro no banco de dados. Contate o grupo de desenvolvimento!";
            } finally {
                $result['success'] = true;
                $result['message'] = "cURL enviada com sucesso!";
            }
            echo json_encode($result);
        } else {
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
}
