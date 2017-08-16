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
            $this->db->join('plane', 'clients.plane_id = plane.id');
            
            if($form_filter['cod_promocional']!='--SELECT--'){
                if($form_filter['cod_promocional']==='PEIXE URBANO'){
                    $this->db->where('ticket_peixe_urbano !=', 'BACKTODUMBU');
                    $this->db->where('ticket_peixe_urbano !=', 'AMIGOSDOPEDRO');
                    $this->db->where('ticket_peixe_urbano !=', 'FITNESS');
                    $this->db->where('ticket_peixe_urbano !=', 'SHENIA');
                    $this->db->where('ticket_peixe_urbano !=', 'VANESSA');
                    $this->db->where('ticket_peixe_urbano !=', 'NINA');
                    $this->db->where('ticket_peixe_urbano !=', 'CAROL');
                    $this->db->where('ticket_peixe_urbano !=', 'NICOLE');
                    $this->db->where('ticket_peixe_urbano IS NOT NULL');
                } else{
                    $this->db->where('ticket_peixe_urbano', $form_filter['cod_promocional']);
                }
            }        
            //else
            if($form_filter['profile_client']!='')
                $this->db->where('login', $form_filter['profile_client']);
            //else
            if($form_filter['signin_initial_date']!=='')
                $this->db->where('init_date >=',strtotime($form_filter['signin_initial_date'].' 00:00:01'));
            //else
            if($form_filter['observations']!=='NAO')
                $this->db->where('observation is NOT NULL', NULL, FALSE);
            //else
            if($form_filter['email_client']!='')
                $this->db->where('email', $form_filter['email_client']);
            //else
            if($form_filter['order_key_client']!='')
                $this->db->where('order_key', $form_filter['order_key_client']);
            //else
            if($form_filter['client_id']!='')
                $this->db->where('user_id', $form_filter['client_id']);
            //else
            if($form_filter['ds_user_id']!='')
                $this->db->where('insta_id', $form_filter['ds_user_id']);
            //else
            if($form_filter['credit_card_name']!='')
                $this->db->where('credit_card_name', $form_filter['credit_card_name']);
            //else
            if($form_filter['client_status']>-1)
                $this->db->where('status_id', $form_filter['client_status']);            
            return $this->db->get()->result_array();
        }
        
        public function view_pendences_by_filter($form_filter) {
            $this->db->select('*');
            $this->db->from('pendences');
            
/*
$mañana        = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
$mes_anterior  = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
$año_siguiente = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1);
$this->db->where('init_date >=',strtotime($form_filter['signin_initial_date'].' 00:00:01'));
 */
           
            if ($form_filter['pendences_date'] == -50) { // all pendences before today
                $this->db->where('event_date <= ', time());
            }
            else if ($form_filter['pendences_date'] == 0) { // today
                $this->db->where('event_date >= ', mktime(0, 0, 0, date("m"), date("d"),   date("Y")));
                $this->db->where('event_date <= ', mktime(23, 59, 59, date("m"), date("d"),   date("Y")));
            } 
            else if ($form_filter['pendences_date'] == 50) { // all pendences after today
                $this->db->where('event_date >= ', time());
            }
            else {
                if ($form_filter['pendences_date'] < 0) { // -30, -7 o -1 days
                    $this->db->where('event_date >= ', mktime(0, 0, 0, date("m"), date("d") + $form_filter['pendences_date'],   date("Y")));
                    $this->db->where('event_date <= ', time());
                }
                else { // 1, 7 o 30 days
                    $this->db->where('event_date <= ', mktime(0, 0, 0, date("m"), date("d") + $form_filter['pendences_date'],   date("Y")));
                    $this->db->where('event_date >= ', time());
                }
            }
                       
            return $this->db->get()->result_array();
        }
    }
?>
