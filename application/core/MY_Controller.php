<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class MY_Controller extends MX_Controller{
 //presumes you use hmvc

     // this is the main controller, it feeds data to its child(extended) controllers
     // use the protected keyword over the private keyword for methods and vars

     protected $user, $permissions=array(), $group;

     // define some permission constants to check with MY_Controller scope including
     // children(extended)
     const PERM_READ = 'read'; 
     const PERM_EDIT = 'edit';
     const PERM_DELETE = 'delete';

     // an alternative is to use bit and bitewise operations
     // tutorial here http://codingrecipes.com/how-to-write-a-permission-system-using-bits-and-bitwise-operations-in-php


     public function __construct(){
         parent::__construct();

         //check the session data and assign a user to the user var

         $this->user = ($this->session->userdata('user_id')) 
                     ? User::find($this->session->userdata('user_id')) 
                     : NULL;

         if($this->user !== NULL)
         {
             $this->_assign_group();
             $this->_assign_permissions();
         }
     }

     public function _assign_group(){
         return $this->group = $this->user->group;
     }

     public function _assign_permissions(){
        // permissions are stored as json object in the database
        // this works fine as we dont need to do a serach on the object
        // we simply store and return
        // {["read", "update", "delete"]}

        return $this->permissions = (array)json_decode($this->user->permissions);
     }

     public function _can_read(){
         return (bool) (in_array(self::PERM_READ, $this->permissions));
     }

     public function _can_edit(){
         return (bool) (in_array(self::PERM_EDIT, $this->permissions));
     }

     public function _can_delete(){
         return (bool) (in_array(self::PERM_DELETE, $this->permissions));
     }
}