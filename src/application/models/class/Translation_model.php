<?php

class Translation_model extends CI_Model {

    public $language = NULL;

    function __construct() {
//        if (!$this->language) {
//            $this->db->select('value');
//            $this->db->from('dumbu_system_config');
//            $this->db->where('name', 'LANGUAGE');
//            $a = $this->db->get()->row_array()['value'];
//            if($a==='PT')
//                $this->language ='portugues';
//            if($a==='EN')
//                $this->language ='ingles';
//            if($a==='ES')
//                $this->language ='espanol';
//        }
    }

    public function get_text_by_token($token,$lang) {
        if($lang==='PT')
                $this->language ='portugues';
            if($lang==='EN')
                $this->language ='ingles';
            if($lang==='ES')
                $this->language ='espanol';
            
        $this->db->select($this->language);
        $this->db->from('translation');
        $this->db->where('token', $token);
        $string = $this->db->get()->row_array();

        if (!count($string)) {
            $data['token'] = $token;
            $data['portugues'] = $token;
            $data['ingles'] = 'Not traduction yet';
            $data['espanol'] = 'Not traduction yet';
            $this->db->insert('translation', $data);
        } else
            return $string[$this->language];
    }

}
