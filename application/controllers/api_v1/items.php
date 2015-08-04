<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * Invoices API Methods
 *
 * @package     CodeIgniter
 * @subpackage  Invoice Rest Server
 * @category    Controller
 * @author      William Mandai
 * @link        http://codecanyon.net/item/freelancer-office/8870728
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Items extends REST_Controller
{
    private $table_name         = 'items';  

    function __construct()
    {
        // Construct our parent class
        parent::__construct();
        $this->load->model('invoices/mdl_invoice','invoice');
        
        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; //50 requests per hour per user/key
    }


    function index_post()
    {
        $username = $this -> input -> get_post('username');
        $api_key = $this -> input -> get_post(config_item('rest_key_name'));
        $valid = $this -> _validate_key($username,$api_key); 
        $role_id = $this -> _check_api_username($username);
        $allowed = $this -> applib -> allowed_module('add_invoices',$username);
        $role = Applib::get_table_field('roles',array('r_id' => $role_id),'role'); // Get user role using username

        if (!$role_id) {
            $this->response(array('error' => 'Wrong API username submitted!'), 401); 
            // Username not found in DB
        } 
        $invoice = $_POST['invoice_id'];
        if ($role == 'admin' OR $allowed == true) {
            $data = $_POST;
            $this -> db -> insert($this -> table_name,$data);
            $insert_id = $this -> db -> insert_id();
        }        

        $message = array('invoice_id' => $invoice, 'message' => lang('item_added_successfully'));
        
        $this->response($message,200); // 200 being the HTTP response code
    }


    function index_delete()
    {
        $this->invoice->DeleteItem( $this->input->get_post('item') );

        $message = array('invoice_id' => $this->input->get_post('invoice'), 'message' => lang('item_deleted_successfully'));
        
        $this->response($message, 200); // 200 being the HTTP response code
    }

    function _check_api_username($username){
        if(!$username)
        {
            return FALSE;
        }else{
            return Applib::get_table_field('users',array('username' => $username),'role_id');
        }
    }

    function _validate_key($username,$api_key){
        $valid_api_key = $this -> applib -> validate_api_key($username,$api_key);
        if (!$valid_api_key) {
            $this->response(array('error' => 'API Key does not match your username.'), 401); // username does not match API Key
        } 
    }
    function _check_invoice_owner($invoice,$username){
        $user_id = Applib::get_table_field('users',array('username'=>$username),'id');
        $user_company = Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user_id),'company');
        $invoice_client = Applib::get_table_field('invoices',array('inv_id'=>$invoice),'client');

        if ($invoice_client == $user_company) {
           return TRUE;
        }else{
            $this->response(array('error' => 'Not allowed to view Invoice!'), 404);
        }
    }

}