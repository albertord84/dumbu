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
            //TODO: INNER JOIN
            $query='SELECT users.id, users.name, users.login, users.pass, users.email, users.status_id, clients.pay_day, clients.credit_card_number, clients.credit_card_name, clients.order_key, clients.insta_followers_ini, clients.insta_following'.
                    ' FROM users,clients '.
                    'WHERE users.id=clients.user_id';
            $result['clients']= $this->user_model->execute_sql_query($query);
            
            $data['content_header'] = $this->load->view('responsive_views/admin/admin_header_painel','', true);
            $data['content'] = $this->load->view('responsive_views/admin/admin_body_painel',$result, true);
            $data['content_footer'] = $this->load->view('responsive_views/admin/admin_footer_painel', '', true);
            
            $this->load->view('layout_admin', $data);
        } else {
           // header('Location: '. base_url().'index.php/welcome/');
        }  
    }
    
    public function welcome(){
        $this->load->model('class/user_role');
        if ($this->session->userdata('role_id')==user_role::ADMIN){
            $data['content_header'] = $this->load->view('my_views/admin_header','', true);
            $data['content'] = $this->load->view('my_views/init_painel', '', true);        
            $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
            $this->load->view('layout_admin', $data);
        } else{
            $this->display_access_error();
        }
    }
    
    public function display_access_error(){
        header('Location: '. base_url().'index.php/welcome/');
    }    
}
