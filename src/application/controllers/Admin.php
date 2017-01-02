<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function index() {
        $datas1=$this->input->get();
        $datas['login']=urldecode($datas1['login']);
        $datas['pass']=urldecode($datas1['pass']);
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');
        $query='SELECT * FROM users'.
                ' WHERE login="'.$datas['login'].'" AND pass="'.$datas['pass'].
                '" AND role_id='.user_role::ADMIN.' AND status_id='.user_status::ACTIVE;
        $user= $this->user_model->execute_sql_query($query);
        
        if($this->user_model->set_sesion($user[0]['id'], $this->session, '')){            
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel','', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel','',true);
            $data['section3'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
            
            $this->load->view('view_admin', $data);
        }
    }
    
    public function list_filter_view() {
        if ($this->session->userdata('id')) {
            $this->load->model('class/admin_model');
            $form_filter=$this->input->get();
            $result=$this->admin_model->view_clients_by_filter($form_filter);
            $datas['result']=$result;
            $datas['form_filter']=$form_filter;
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel','', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel',$datas,true);
            $data['section3'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }
    }
    
    public function desactive_client() {
        if ($this->session->userdata('id')) {
            $this->load->model('class/user_model');
            $this->load->model('class/user_status');
            $id=$this->input->post()['id'];
            try {
                $this->user_model->update_user($id, array(
                    'status_id' => user_status::DELETED,
                    'end_date' => time()));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $result['success']=false;
                $result['message']="Erro no banco de dados. Contate o grupo de desenvolvimento!";
            } finally {
                $result['success']=true;
                $result['message']="Cliente desativado com sucesso!";
            }
        }
        echo json_encode($result);
    }
    
    public function recorrency_cancel(){
        if ($this->session->userdata('id')) {
            $this->load->model('class/client_model');
            $id=$this->input->post()['id'];
            $client=$this->client_model->get_client_by_id($id)[0];  
            require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
            $Payment = new \dumbu\cls\Payment();
            $response=json_decode($Payment->delete_payment($client['order_key']));
            if($response->success){
                $result['success']=true;
                $result['message']='Recorrência cancelada corretamente';
            } else{
                $result['success']=false;
                $result['message']='A recorrência já tinha sido cancelada ou não existe. Verifique na Mundipagg';
            }            
            echo json_encode($result);
        }
    }
    
    public function reference_profile_view(){
        if ($this->session->userdata('id')){
            $this->load->model('class/client_model');
            $id=$this->input->get()['id'];
            $active_profiles=$this->client_model->get_client_active_profiles($id);
            $canceled_profiles=$this->client_model->get_client_canceled_profiles($id);            
            $datas['active_profiles']=$active_profiles;
            $datas['canceled_profiles']=$canceled_profiles;
            $datas['my_daily_work']=$this->get_daily_work($active_profiles);
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel','', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_reference_profile',$datas,true);
            $data['section3'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        }
    }
    
    public function get_daily_work($active_profiles){        
        $this->load->model('class/client_model');
        $n=count($active_profiles);
        $my_daily_work=array();
        for($i=0;$i<$n;$i++){
            $work=$this->client_model->get_daily_work_to_profile($active_profiles[$i]['id']);            
            if(count($work)){
                $work=$work[0];
            }
            if(count($work)){
                $to_follow=$work['to_follow'];
                $to_unfollow=$work['to_unfollow'];
            } else{
                $to_follow='----';
                $to_unfollow='----';
            }            
            $tmp=array('profile'=>$active_profiles[$i]['insta_name'],
                       'id'=>$active_profiles[$i]['id'],
                       'to_follow'=>$to_follow,
                       'to_unfollow'=>$to_unfollow,
                       'end_date'=>$active_profiles[$i]['end_date']
                    );
            $my_daily_work[$i]=$tmp;
        }
        return $my_daily_work;
    }
}
