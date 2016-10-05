<?php

namespace dumbu\cls {
    /**
     * class User
     * 
     */
    class User {
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * 
         * @access protected
         */
        protected $id;

        /**
         * 
         * @access protected
         */
        public $name;

        /**
         * 
         * @access protected
         */
        protected $login;

        /**
         * 
         * @access protected
         */
        protected $pass;

        /**
         * 
         * @access protected
         */
        protected $email;

        /**
         * 
         * @access protected
         */
        protected $telf;

        /**
         * 
         * @access protected
         */
        protected $role_id;

        /**
         * 
         * @access protected
         */
        protected $status_id;

        /**
         * 
         * @access protected
         */
        protected $languaje;

        /**
         * 
         */
        function __construct() {            
            //$this->load->model('User_model');
        }

        /**
         * 
         *
         * @return unsigned short
         * @access public
         */
        public function do_login($user_name,$user_pass) 
         { 
            echo $user_name;
            /*$data['success'] = FALSE;
            if ($this->User_model->autenticar($user, md5($contrasena)))
            {
                $datos_usuario = $this->User_model->obtener_usuario($user, TRUE);
                $this->session->set('acceso', 'OK');
                $this->session->set('id_nivel', $datos_usuario['id_nivel']);
                $this->session->set('id_usuario', $datos_usuario['id']);
                $this->session->set('usuario', $datos_usuario['usuario']);
                $this->session->set('nombres', $datos_usuario['nombres']);
                $this->session->set('apellidos', $datos_usuario['apellidos']);
                $this->session->set('foto', $datos_usuario['foto']);            
                $this->usuario_model->actualizar_activo($datos_usuario['id'],true);

                $this->actualizar_sistema();//Inserta en la base de datos los casos subido por ftp

                $datos['success'] = TRUE;
            }
            else
            {
                $datos['message'] = 'Usuario o contraseña incorrecta';
            }
            echo json_encode($datos);*/
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
        public function load_user($user_id = 0) {
            
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

// end of member function disable_account
    }

    // end of User
}
?>
