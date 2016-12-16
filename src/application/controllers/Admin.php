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
            
            $sql='';
            
            
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel','', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel','',true);
            $data['section3'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
            
            $this->load->view('view_admin', $data);
        } 
    }
    
}
