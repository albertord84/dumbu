<?php


/**
 * class User
 * 
 */
class User_model extends CI_Model {
    /** Aggregations: */
    /** Compositions: */
    /*     * * Attributes: ** */

    /**
     * Variable defined as setter and getter reference example,
     * study carefully:
     * If function with same variable name is defined, the magic getter 
     * and setter will called without (resp. with) the $value param, 
     * so it function can determine if should do a get or o set..
     * 
     * @access public
     */
    public $id;

    public function id($value = NULL) {
        if (isset($value)) {
            $this->id = $value;
        } else {
            return $this->id;
        }
    }

    /**
     * 
     * @access public
     */
    public $name;

    /**
     * 
     * @access public
     */
    public $login;

    /**
     * 
     * @access public
     */
    public $pass;

    /**
     * 
     * @access public
     */
    public $email;

    /**
     * 
     * @access public
     */
    public $telf;

    /**
     * 
     * @access public
     */
    public $role_id;

    /**
     * 
     * @access public
     */
    public $status_id;

    /**
     * 
     * @access public
     */
    public $languaje;

    /**
     * 
     */
    function __construct() {
        
    }

    /**
     * 
     *
     * @return unsigned short
     * @access public
     */
    public function login($user_login, $user_pass , $session) {
        try {
            $this->db->select('id, name, login, pass, email, telf, role_id, status_id, languaje');
            $this->db->from('users');
            $this->db->where('login', $user_login);
            $this->db->where('pass', $user_pass);
            $user_data = $this->db->get()->row_array();
            if (count($user_data)) {
                /*$this->id = $user_data['id'];
                $this->name = $user_data['name'];                
                $this->login = $user_data['login'];
                $this->pass = $user_data['pass'];
                $this->email = $user_data['email'];
                $this->telf = $user_data['telf'];
                $this->role_id = $user_data['role_id'];
                $this->status_id = $user_data['status_id'];
                $this->languaje = $user_data['languaje'];*/
                
                $session->set_userdata('id',$user_data['id']);
                $session->set_userdata('name',$user_data['name']);
                $session->set_userdata('login',$user_data['login']);
                $session->set_userdata('pass',$user_data['pass']);
                $session->set_userdata('email',$user_data['email']);
                $session->set_userdata('telf',$user_data['telf']);
                $session->set_userdata('role_id',$user_data['role_id']);
                $session->set_userdata('status_id',$user_data['status_id']);
                $session->set_userdata('languaje',$user_data['languaje']);
                return $user_data['id'];
            } else {
                return false;
            }
        } catch (Exception $exception) {
            echo 'Error accediendo a la base de datos durante el login';
        }
    }
    
    public function get_user_role($user_login, $user_pass){
        $this->db->select('role_id');
        $this->db->from('users');
        $this->db->where('login', $user_login);
        $this->db->where('pass', $user_pass);
        return $this->db->get()->row_array();
    }

// end of member function do_login

    /**
     * 
     *
     * @return bool
     * @access public
     */
    public function update_user() {
        
    }
    
       
// end of member function update_user

    /**
     * 
     *
     * @param serial user_id 

     * @return User
     * @access public
     */
    public function load_user($user_login=0, $user_pass=0) {
        if($user_login!=0){
            $this->db->select('*');
            $this->db->from('users');        
            $this->db->where('login', $user_login);
            $this->db->where('pass', $user_pass);
        } else{
            $this->db->select('*');
            $this->db->from('users');
        }        
        return $this->db->get()->row_array();
    }

// end of member function load_user

    /**
     * 
     *
     * @return bool
     * @access public
     */
    public function disable_account() {
        
    }

}
?>