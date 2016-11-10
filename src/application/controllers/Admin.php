<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function index() {
        $datas=$this->input->get();
        $this->load->model('class/user_model');
        if($this->user_model->set_sesion($datas['login'], $datas['pass'], $this->session)){            
            $this->load->model('class/user_status');
            $this->load->model('class/user_role');            
            
            $query='SELECT users.id, users.name, users.login, users.pass, users.email, users.status_id, clients.pay_day '.
                    'FROM users,clients '.
                    'WHERE users.id=clients.user_id'; 
            $result['clients']= $this->user_model->get_cliets_by_query($query);
            
            
            $data['content_header'] = $this->load->view('my_views/admin_header', '', true);
            $data['content'] = $this->load->view('my_views/admin_painel',$result, true);
            $data['content_footer'] = $this->load->view('my_views/admin_footer', '', true);
            $this->load->view('layout_admin', $data);
        } else {
            header('Location: '. base_url().'index.php/welcome/');
        }
    }
    
   
    
    
    
}
