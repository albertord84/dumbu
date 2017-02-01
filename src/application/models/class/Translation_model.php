<?php

class Translation_model extends CI_Model {

    function __construct() {
        
    }
    
    public function get_text_by_token($token) {
        //TODO: aqui es donde debo cargar el idioma activo en la base de dados, segun la configuracion del sistema
        $language='portugues';
        $this->db->select($language);
        $this->db->from('translation');
        $this->db->where('token',$token);
        $string=$this->db->get()->result();
        
        if(!$string){
            $data['token']=$token;
            $data['portugues']='Not traduction yet';
            $data['ingles']='Not traduction yet';
            $data['espanol']='Not traduction yet';
            $this->db->insert('translation',$data);
        }else
            return $string;
    }
    
   
}
