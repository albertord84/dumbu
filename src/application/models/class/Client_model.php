<?php

//require_once 'Reference_profile[].php';

//namespace dumbu\cls {
    require_once 'User_model.php';
    //require_once '.php';

    /**
     * class Client
     * 
     */
    class Client_model extends User_model {
        /** Aggregations: */
        /** Compositions: */
        /** Attributes: */

        /**
         * 
         * @access protected
         */
        protected $credit_card_number;

        /**
         * 
         * @access protected
         */
        protected $credit_card_status_id;

        /**
         * 
         * @access protected
         */
        protected $credit_card_cvc;

        /**
         * 
         * @access protected
         */
        protected $credit_card_name;

        /**
         * 
         * @access protected
         */
        protected $pay_day;

        /**
         * 
         * @access protected
         */
        protected $insta_id;

        /**
         * 
         * @access protected
         */
        protected $insta_followers_ini;

        /**
         * 
         * @access protected
         */
        protected $insta_following;

        /**
         * 
         * @access protected
         */
        protected $reference_profiles;

        /**
         * 
         */
        function __construct() {
            parent::__construct();            
        }

        /**
         * 
         *
         * @return bool
         * @access public
         */
        
        
        public function insert_client($datas,$data_insta){
            //insert respectivity datas in the user table
            $data_user['name']=$data_insta['insta_name'];           //desde instagram
            $data_user['login']=$datas['client_login'];             //desde el formulario de logueo
            $data_user['pass']=$datas['client_pass'];               //desde el formulario de logueo
            $data_user['role_id']=$datas['role_id'];                //desde el controlador
            $data_user['status_id']=$datas['status_id'];            //desde el controlador            
            $this->db->insert('users',$data_user);
            $id_user_table=$this->db->insert_id();
            
            //insert respectivity datas in the client table
            $data_client['user_id']=$id_user_table;                                     //desde insersion anterior
            $data_client['insta_id']=$data_insta['insta_id'];                           //desde instagram
            $data_client['insta_followers_ini']=$data_insta['insta_followers_ini'];     //desde instagram
            $data_client['insta_following']=$data_insta['insta_following'];             //desde instagram
            $this->db->insert('clients',$data_client);
            
            return $id_user_table;
        }        
        
        public function check_mundipagg_credit_card($datas){
            /*$datas['client_credit_card_number']
            $datas['client_credit_card_cvv']
            $datas['client_credit_card_name']
            $datas['client_credit_card_validate_month']
            $datas['client_credit_card_validate_year']*/
            
            //TODO: usar la funcion de Alberto, pasarle devidamente los datos
            return true;
        }
        
        public function update_client($id,$datas){
            try {
                $this->db->where('user_id',$id);
                $this->db->update('clients',$datas);
                return true;
            } catch (Exception $exc) {                
                echo $exc->getTraceAsString();
                return false;
            }
        }
        
        public function sign_up($client_login,$client_pass,$status_id) {
            try {
                $this->db->select('*');
                $this->db->from('users');
                $this->db->where('login', $client_login);
                $this->db->where('pass', $user_pass);
                $user_data = $this->db->get()->row_array();
                $user_data['status_id']=$status_id;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        /*public function sign_update($data_user,$data_client) {
            try {
                $this->db->update('users',$data_user);
                $this->db->update('clients',$data_client);
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }*/
        
        public function get_client_by_ds_user_id($insta_id) {
            try {    
                $this->db->select('*');
                $this->db->from('clients');        
                $this->db->where('insta_id', $insta_id);
                return $this->db->get()->result_array();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function get_client_by_id($user_id) {
            try {    
                $this->db->select('*');
                $this->db->from('clients');        
                $this->db->where('user_id', $user_id);
                return $this->db->get()->result_array();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function insert_insta_profile($clien_id, $profile, $insta_id_profile){       
            try {
                $data['client_id']=$clien_id;        
                $data['insta_name']=$profile;
                $data['insta_id']=$insta_id_profile;
                $data['deleted']=false;
                $this->db->insert('reference_profile',$data);
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }       
        
        public function get_client_active_profiles($user_id){
            try {    
                $this->db->select('*');
                $this->db->from('reference_profile');        
                $this->db->where('client_id', $user_id);
                $this->db->where('deleted', '0');
                return $this->db->get()->result_array();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function activate_profile($client_id,$profile){
            try {        
                $this->db->select('*');
                $this->db->from('reference_profile');        
                $this->db->where('client_id', $client_id);
                $this->db->where('insta_name', $profile);
                $datas=$this->db->get()->row_array();
                $datas['deleted']=false;
                return $this->db->update('reference_profile', $datas, array('client_id' => $client_id, 'insta_name' => $profile));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function desactive_profiles($clien_id, $profile){
            try {    
                $this->db->where(array('client_id'=>$clien_id, 'insta_name'=>$profile, 'deleted'=>'0'));
                $this->db->update('reference_profile',array('deleted'=>'1'));
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function load_bank_datas_user($user_id){            
            try {
                $this->db->select('credit_card_number,credit_card_cvc,credit_card_name');
                $this->db->from('clients');        
                $this->db->where('user_id', $user_id);
                return $this->db->get()->row_array();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }   
    // end of Client
}
?>
