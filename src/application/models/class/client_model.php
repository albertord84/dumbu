<?php

//require_once 'Reference_profile[].php';

//namespace dumbu\cls {
    require_once 'user_model.php';

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
        public function sign_in($data_user,$data_client) {
            try {
                $this->db->insert('users',$data_user);
                $id=$this->db->insert_id();
                $data_client['user_id']=$id;
                $this->db->insert('clients',$data_client);
                $id=$this->db->insert_id();
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
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
        
        public function sign_update($data_user,$data_client) {
            try {
                $this->db->update('users',$data_user);
                $this->db->update('clients',$data_client);
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }        

        
        
        public function check_insta_user($client_login,$client_pass) {
            require_once('Robot.php');
            //require_once '../../../worker/class/Robot.php';
            //1.loguear el usuario en Instagram 
            
            //2.actualizar la variable $data_insta como descrita abajo
            
                       
            $is_insta_user=true; //usada para pode ejecutar mis funciones, puedes eliminarla
            
            if($is_insta_user){                
                $data_insta['success']=true;                
                $data_insta['insta_id']='3858629065';
                $data_insta['insta_followers_ini'] =40;
                $data_insta['insta_following'] = 143;                
            } else{
                $data_insta['success']=false;
            }   
            return $data_insta;
        }
        
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
        
        public function check_insta_profile($master_login,$master_pass,$profile) {
            //loguear el usuario del sistema, verificar si esxiste el perfile, y devolver el ID de instagram
            $perfil_is_correct=true;
            
            $insta_id='1112223334';
            
            if($perfil_is_correct)
                return $insta_id;
            else
                return 0;
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
        
        public function desactive_profiles($clien_id, $profiles,$N){
            try {
                for($i=0;$i<$N;$i++){
                    $this->db->select('*');
                    $this->db->from('reference_profile');
                    $this->db->where('id',$profiles[$i]);
                    $profile_data = $this->db->get()->row_array();            
                    $profile_data['deleted']='1';
                    
                    $this->db->where("id",$profiles[$i]);
                    $this->db->update('reference_profile',$profile_data);
                }
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        
        public function credit_card($client_credit_card_number, $client_credit_card_cvc, $client_credit_card_name){
        
            return true;
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
        
       
        
    }

    // end of Client
//}
?>
