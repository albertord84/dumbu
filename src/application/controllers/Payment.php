<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function check_payment() {
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        // Get all users
        $this->db->select('*');
        $this->db->from('clients');
        $this->db->join('users', 'clients.user_id = users.id');
        $this->db->where('status !=', user_status::DELETED);
        $clients = $this->db->get()->result_array();
        // Check payment for each user
        foreach ($clients as $cient) {
            //$checked = $this->check_client_payment($client);
            var_dump($cient);
        }
    }
    
    
    public function check_client_payment($client) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        $Payment = new \dumbu\cls\Payment();
        
        $Payment->check_payment("e0c0954a-dbd5-4e79-b513-0769d89bb490");
    }

}
