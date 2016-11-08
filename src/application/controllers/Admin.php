<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        if ($this->session->userdata('id'))
            
            $data['user_active'] = true;
        else
            $data['user_active'] = false;
        $data['content'] = $this->load->view('my_views/init_painel', '', true);
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);        
        $this->load->view('welcome_message', $data);
    }
    
     public function panel_admin() {
        if ($this->session->userdata('name')) {
            $data['user_active'] = true;
        } else
            $data['user_active'] = false;
        $data['content'] = $this->load->view('my_views/admin_painel', '', true);
        $data['content_footer'] = $this->load->view('my_views/general_footer', '', true);
        $this->load->view('welcome_message', $data);
        
    }
    
    
    
}
