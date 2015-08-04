<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Set the default App Language
| 
*/


  //Loads configuration from database into global CI config
  function set_lang()
  {
   $CI =& get_instance();
   $system_lang = $CI->HookModel->get_lang();

   $CI->config->set_item('language', $system_lang);
   
   $CI->lang->load('fx', $system_lang ? $system_lang : 'english');

  }



  
?>