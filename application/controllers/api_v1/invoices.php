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

class Invoices extends REST_Controller
{
    private $table_name         = 'invoices';  

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
    function index_get()
    {
        $username = $this -> get('username');
        $api_key = $this -> get(config_item('rest_key_name'));
        $valid = $this -> _validate_key($username,$api_key); 
        $role_id = $this -> _check_api_username($username);
        $allowed = $this -> applib -> allowed_module('view_all_invoices',$username);
        $role = Applib::get_table_field('roles',array('r_id' => $role_id),'role'); // Get user role using username

        $order_by = $this->get('order_by')?$this->get('order_by'):'date_saved';
        $order = $this->get('order')?$this->get('order'):'desc'; 

        if (!$role_id) {
            $this->response(array('error' => 'Wrong API username submitted!'), 401); 
            // Username not found in DB
        }    

        if ($role == 'admin' OR $allowed == true) {
            $invoices = $this -> invoice -> AdminInvoices($this -> table_name, $order_by, $order);
        }else{
            $invoices = $this -> invoice -> ClientInvoices($this -> table_name, $username, $order_by, $order);
        } 
        
            $this->response($invoices, 200); // 200 being the HTTP response code
    }
    
    function invoice_get()
    {
        $username = $this -> get('username');
        $api_key = $this -> get(config_item('rest_key_name'));
        $valid = $this -> _validate_key($username,$api_key); 
        $role_id = $this -> _check_api_username($username);
        $allowed = $this -> applib -> allowed_module('view_all_invoices',$username);
        $role = Applib::get_table_field('roles',array('r_id' => $role_id),'role'); // Get user role using username

        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        if ($role == 'admin' OR $allowed == true) {
            $invoice_info = $this -> invoice -> InvoiceById($this -> table_name, $this->get('id'), $username);
        }else{
            $this -> _check_invoice_owner($this->get('id'),$username);
            $invoice_info = $this -> invoice -> InvoiceById($this -> table_name, $this->get('id'), $username);
        }         
                     
        if($invoice_info)
        {
            $this->response($invoice_info, 200); // 200 being the HTTP response code
        }else
        {
            $this->response(array('error' => 'Invoice not found!'), 404);
        }
    }

    function items_get()
    {
        $username = $this -> get('username');
        $api_key = $this -> get(config_item('rest_key_name'));
        $valid = $this -> _validate_key($username,$api_key); 
        $role_id = $this -> _check_api_username($username);
        $allowed = $this -> applib -> allowed_module('view_all_invoices',$username);
        $role = Applib::get_table_field('roles',array('r_id' => $role_id),'role'); // Get user role using username

        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        if ($role == 'admin' OR $allowed == true) {
            $invoice_items = $this -> invoice -> InvoiceItems($this -> table_name, $this->get('id'));
        }else{
            $this -> _check_invoice_owner($this->get('id'),$username);
            $invoice_items = $this -> invoice -> InvoiceItems($this -> table_name, $this->get('id'));
        }         
                     
        
            $this->response($invoice_items, 200); // 200 being the HTTP response code
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
        $username = $this -> get('username');
        $api_key = $this -> get(config_item('rest_key_name'));
        $valid = $this -> _validate_key($username,$api_key); 
        $role_id = $this -> _check_api_username($username);

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
        $insert_id = '';
        if ($role == 'admin' OR $allowed == true) {
            $data = $_POST;
            $this -> db -> insert($this -> table_name,$data);
            $insert_id = $this -> db -> insert_id();
        }        

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