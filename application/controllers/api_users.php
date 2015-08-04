<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users
 *
 *
 * @package		Freelancer Office
 * @subpackage	Users Module
 * @category	Controller
 * @author		William Mandai
*/

// Includes all users operations
include APPPATH.'/libraries/Requests.php';


class Api_users extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		Requests::register_autoloader();
		$this -> auth_ = array(
    			'auth' => new Requests_Auth_Basic(array($this->config->item('api_username'), $this->config->item('api_password')))
				);
	}

	function view_all()
	{
		Requests::register_autoloader();
		$data['request'] = Requests::get(base_url().USERS_GET_URL);
		$this->load->view('users', $data);
	}
	function view_by_id($id)
	{
		Requests::register_autoloader();
		$data['request'] = Requests::get(base_url().USER_BY_ID_URL.'id/'.$id);
		$this->load->view('users', $data);
	}
	function payments()
	{		
		if ($_POST) {
		//$payments = $_POST;	
		$data['response'] = Requests::post(base_url().PAYMENTS_POST_URL,array(), $_POST,$this -> auth_);
		$data['request'] = Requests::get(base_url().PAYMENTS_GET_URL,array(),$this -> auth_);
		$this->load->view('add_method',$data);
		 	}else{
		$data['request'] = Requests::get(base_url().PAYMENTS_GET_URL,array(),$this -> auth_);
		$this->load->view('add_method',$data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */