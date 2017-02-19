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
    public function set_sesion($id, $session, $datas = NULL) {
        try {
            $this->load->model('class/user_role');
            /* $this->db->select('*');
              $this->db->from('users');
              $this->db->where(array('id'=>$id));
              $user_data = $this->db->get()->row_array(); */

            $this->db->select('*');
            $this->db->from('users');
            $this->db->where(array('id' => "$id"));
            $user_data = $this->db->get()->row_array();

            if (count($user_data)) {
                if($user_data['role_id'] == user_role::CLIENT) {
                    $this->db->select('*');
                    $this->db->from('clients');
                    $this->db->join('plane', 'plane.id = clients.plane_id');
                    $this->db->where(array('user_id' => "$id"));
                    $client_data = $this->db->get()->row_array();
                    if($client_data['plane_id']==1)
                        $session->set_userdata('plane_id', 4);
                    else
                        $session->set_userdata('plane_id', $client_data['plane_id']);
                    $session->set_userdata('to_follow', (int) $client_data['to_follow']);
                    $session->set_userdata('normal_val', (int) $client_data['normal_val']);
                    $session->set_userdata('unfollow_total', (int) $client_data['unfollow_total']);
                }
                $session->set_userdata('id', $user_data['id']);
                $session->set_userdata('name', $user_data['name']);
                $session->set_userdata('login', $user_data['login']);
                $session->set_userdata('pass', $user_data['pass']);
                $session->set_userdata('email', $user_data['email']);
                $session->set_userdata('role_id', $user_data['role_id']);
                $session->set_userdata('status_id', $user_data['status_id']);
                $session->set_userdata('init_date', $user_data['init_date']);
                $session->set_userdata('languaje', $user_data['languaje']);
                $session->set_userdata('insta_datas', $datas);
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            echo 'Error accediendo a la base de datos durante el login';
        }
    }

    public function get_user_role($user_login, $user_pass) {
        $this->db->select('role_id');
        $this->db->from('users');
        $this->db->where('login', $user_login);
        $this->db->where('pass', $user_pass);
        $a = $this->db->get()->row_array();
        return $a;
    }
    
    public function get_signin_date($id) {
        $this->db->select('init_date');
        $this->db->from('users');
        $this->db->where('id', $id);
        return $this->db->get()->row_array()['init_date'];        
    }

    public function get_user_by_id($user_id) {
        try {
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('id', $user_id);
            return $this->db->get()->result_array();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /* public function update_data_user($datas){
      try {
      $this->db->where('id', $datas['pk']);
      $this->db->update('users',array(
      'status_id' => $datas['status_id'],
      'email' => $datas['client_email'],
      ));
      return true;
      } catch (Exception $exc) {
      echo $exc->getTraceAsString();
      return false;
      }
      } */

    /**
     * 
     *
     * @return bool
     * @access public
     */
    public function update_user($id, $datas) {
        try {
            $this->db->where('id', $id);
            $this->db->update('users', $datas);
            return true;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return false;
        }
    }

    /**
     * 
     *
     * @param serial user_id 

     * @return User
     * @access public
     */
    public function load_user($user_login, $user_pass) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where(array('login' => $user_login, 'pass' => $user_pass));
        return $this->db->get()->row_array();
    }

    /* public function load_all_user($condition) {
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where($condition);
      return $this->db->get()->result_array();
      } */

    public function execute_sql_query($query) {
        return $this->db->query($query)->result_array();
    }

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