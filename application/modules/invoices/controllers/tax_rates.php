<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
**********************************************************************************
* Copyright: gitbench 2014
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/

// Includes all users operations
class Tax_rates extends MX_Controller {

	function __construct()
	{
		
		parent::__construct();		
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> user_role = Applib::login_info($this->user)->role_id;

		if ($this -> user_role != '1') {
			$this -> applib -> redirect_to('auth/login','error',lang('access_denied'));			
		}
		$this->page = lang('tax_rates');

		$this -> rates = Applib::retrieve(Applib::$tax_rates_table,array('tax_rate_id !='=>'0'));

		
		
	}

	function index()
	{		

	$data['page'] = $this -> page;	
	$data['datatables'] = TRUE;
	$data['role'] = $this -> user_role;
	
	$data['rates'] = $this -> rates;

	$this->template
	->set_layout('users')
	->build('rates',isset($data) ? $data : NULL);
	}

	function add(){
		if ($this->input->post()) {

		if ($this -> form_validation -> run('invoices','add_tax') == FALSE)
		{	
				$_POST = '';
				$this -> applib -> redirect_to('invoices/tax_rates/','error',lang('error_in_form'));	
		}else{	
				if(Applib::create(Applib::$tax_rates_table, $_POST)){
					$this -> applib -> redirect_to('invoices/tax_rates/','success',lang('tax_added_successfully'));
				}
			}
		}else{
			$this->load->view('modal/add_rate');
		}
	}

	function edit(){
		if ($this->input->post()) {
		
		if ($this -> form_validation -> run('invoices','edit_tax') == FALSE)
		{	
				$_POST = '';
				$this -> applib -> redirect_to('invoices/tax_rates','error',lang('error_in_form'));	
		}else{	
				if(Applib::update(Applib::$tax_rates_table, array('tax_rate_id' => $this->input->post('tax_rate_id')),$this->input->post())){
					$this -> applib -> redirect_to('invoices/tax_rates','success',lang('tax_updated_successfully'));
				}
			}
		}else{
			$rate_id = $this->uri->segment(4);
			$data['rate_info'] = Applib::retrieve(Applib::$tax_rates_table,array('tax_rate_id' => $rate_id));
			$this->load->view('modal/edit_rate',$data);
		}
	}

	function delete(){
		if ($this->input->post() ){
					$tax_rate_id = $this->input->post('tax_rate_id', TRUE);

					if(Applib::delete(Applib::$tax_rates_table,array('tax_rate_id' => $tax_rate_id))){
						$this -> applib -> redirect_to('invoices/tax_rates','success',lang('tax_deleted_successfully'));
					}
		}else{
			$data['tax_rate_id'] = $this -> uri -> segment(4);
			$this->load->view('modal/delete_tax',$data);
		}
	}


}

/* End of file invoices.php */