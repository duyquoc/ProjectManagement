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
        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
    }
    function index_get()
    {
        $invoices = $this->invoice->invoices();
        if($invoices)
        {
            $this->response($invoices, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find invoices!'), 404);
        }
    }
    
    function invoice_get()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        $invoices = $this->invoice->invoice_by_id($this->get('id'));
        
        $invoice = @$invoices;
        
        if($invoice)
        {
            $this->response($invoice, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Invoice not found!'), 404);
        }
    }
    function index_post()
    {
        $data = $_POST;
        $this -> db -> insert($this->table_name,$data);
        
        $this->response(array('success' => 'Record inserted successfully'), 200); // 200 being the HTTP response code
    }
    function invoice_put()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        $data = $_POST;
        $this -> db -> where('id',$this->get('id'))->update($this->table_name,$data);
        
        $this->response(array('success' => 'Record updated successfully'), 200); // 200 being the HTTP response code
    }

    function invoice_delete()
    {
        $this->invoice->delete( $this->get('id') );

        $message = array('id' => $this->get('id'), 'message' => 'Invoice Deleted!');
        
        $this->response($message, 200); // 200 being the HTTP response code
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