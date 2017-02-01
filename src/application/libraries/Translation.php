<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Translation{
    
    function __construct(){
      $this->ci = & get_instance();
    }
   
    function t($token,$array_params){ 
        $this->ci->load->model('Translation_model');
        $this->ci->Translation_model->get_text_by_token($token);
        //COMO FUNCIONA
    }
}

?> 