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

class Settings extends REST_Controller
{
    private $table_name         = 'config';  

    function __construct()
    {
        // Construct our parent class
        parent::__construct();
        $this->load->model('settings/mdl_settings','settings');
        
        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; //50 requests per hour per user/key
    }
    function index_get()
    {
        $username = $this -> get('username');
        $role_id = $this -> _check_api_username($username);

        if (!$role_id) {
            $this->response(array('error' => 'Wrong API username submitted!'), 401); // Username not found in DB
        }
        $role = Applib::get_table_field('roles',array('r_id' => $role_id),'role'); // Get user role using username

        $allowed_permissions = Applib::get_table_field('roles',array('r_id' => $role_id),'permissions');
        $allow_view_settings = $this ->_check_permission($allowed_permissions,$role_id,'view_settings'); //Check if allowed to view settings      
        

        if ($role == 'admin' OR $allow_view_settings == TRUE) {
            $setting = $this -> settings -> GetSettings($this -> table_name);
        }else{
            $setting = FALSE;
        }
        
        if($setting)
        {
            $this->response($setting, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Couldn\'t find setting!'), 404);
        }
    }
    
    function config_get()
    {
        $username = $this -> get('username');
        $role_id = $this -> _check_api_username($username);
        $config_key = $this->get('key');

        $allowed_permissions = Applib::get_table_field('roles',array('r_id' => $role_id),'permissions');
        $allowed_permissions = array('view_setting','view_all_settings');
        $allow_view_settings = $this -> _check_permission($allowed_permissions,$role_id,'view_settings'); //Check if allowed to view settings         
        if(!$config_key)
        {
            $this->response(NULL, 400);
        }
        if($allow_view_settings == TRUE){
            $config_value = config_item($config_key);
        }else{ $config_value = NULL; }
                     
        if($config_value)
        {
            $this->response($config_value, 200); // 200 being the HTTP response code
        }else
        {
            $this->response(array('error' => 'Setting not found or not allowed to view setting!'), 404);
        }
    }

    function paid_get()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        $amount = $this->invoice->InvoicePaidTotal($this->get('id'));
        
                if($amount)
                {
                    $this->response($amount, 200); // 200 being the HTTP response code
                }else{
                    $this->response("0", 404);
                }
    }

    function status_get()
    {
        $role_id = $this -> _check_api_username($this -> get('username'));

        if (!$role_id) {
            $this->response(array('error' => 'Wrong API username submitted!'), 401);
        }
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        $status = $this->invoice->InvoiceStatus($this->get('id'));
        
                if($status)
                {
                    $this->response($status, 200); // 200 being the HTTP response code
                }else{
                    $this->response(NULL, 404);
                }
    }

    function index_post()
    {
        $data = $_POST;
        $this -> db -> insert($this -> table_name,$data);
        $insert_id = $this -> db -> insert_id();

        $message = array('invoice_id' => $insert_id, 'message' => lang('invoice_created_successfully'));
        
        $this->response($message,200); // 200 being the HTTP response code
    }

    function index_put()
    {
        if(!$this->get('id'))
         {
             $this->response(NULL, 400);
         }
        $invoice = $this->get('id');
        $data = $_POST;        
        $this -> db -> where('inv_id',$invoice) -> update($this -> table_name, $data);
        $message = array('invoice_id' => $invoice, 'message' => lang('invoice_edited_successfully'));
        
        $this->response($message, 200); // 200 being the HTTP response code
    }

    function invoice_delete()
    {
        $this->invoice->Delete( $this->get('id') );

        $message = array('inv_id' => $this->get('id'), 'message' => 'Invoice Deleted successfully!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }

    function _check_api_username($username){
        if(!$username)
        {
            return FALSE;
        }else{
            return $this -> user_profile -> get_any_field('users',array('username' => $username),'role_id');
        }
    }
    function _check_permission($allowed_permissions,$role,$module){
        if (in_array($module, $allowed_permissions)) {
            return  TRUE;
        }else{
            return FALSE;
        }
    }


    public function send_post()
    {
        var_dump($this->request->body);
    }


    public function send_put()
    {
        var_dump($this->put('foo'));
    }
}