<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Fetch the config variables from DB
| 
*/
class HookModel extends CI_Model {

     public function __construct()
     {
      parent::__construct();
     }

     public function get_config()
     {
      return $this->db->get('config');
     }

     public function get_lang()
     {
      if ($this->session->userdata('lang')) {
        return $this->session->userdata('lang');
      }else{
            $query = $this->db->select('language')->where('user_id',$this->session->userdata('user_id'))->get(Applib::$profile_table);
              if ($query->num_rows() > 0)
                {
                 $row = $query->row();
                 return $row->language;
                }
           }
   }
}