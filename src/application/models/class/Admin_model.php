<?php

    require_once 'User_model.php';
    class Admin_model extends User_model {
        
        public function add_admin() {
            
        }

        public function delete_admin() {
            
        }
        
        public function update_admin() {
            
        }
        
        public function view_clients_by_filter($form_filter) {
            $this->db->select('*');
            $this->db->from('clients');
            $this->db->join('users', 'clients.user_id = users.id');
            if($form_filter['client_status']!=0)
                $this->db->where('status_id', $form_filter['client_status']);
            return $this->db->get()->result_array();
        }

   
    }
?>
