<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
**********************************************************************************
* Copyright: gitbench 2014
* Licence: Please check CodeCanyon.net for licence details. 
* More licence clarification available here: htttp://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
* CodeCanyon User: http://codecanyon.net/user/gitbench
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/


class Payments extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation'));

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		$this -> user_role = Applib::login_info($this->user)->role_id;

		if ($this -> user_role != '1') {
			$this -> applib -> redirect_to('auth/login','error',lang('access_denied'));			
		}
		$this -> template -> title(lang('payments').' - '.config_item('company_name'));
		$this -> page = lang('payments');

		$this->payments_list = Applib::retrieve(Applib::$payments_table,array('invoice !='=>'0'));
		
	}

	function index()
	{
	$data['page'] = $this->page;
	$data['datatables'] = TRUE;
	$data['payments'] = $this->payments_list;
	$this->template
	->set_layout('users')
	->build('payments',isset($data) ? $data : NULL);
	}

	function edit($transaction = NULL)
	{
		if ($this->input->post()) {
			$id = $this->input->post('p_id',TRUE);

		if ($this->form_validation->run('invoices','edit_payment') == FALSE)
		{
				$_POST = '';
				$this->applib->redirect_to('invoices/payments/edit/'.$id,'error',lang('error_in_form'));
		}else{	

            $_POST['payment_date'] = date_format(date_create_from_format(config_item('date_php_format'),
            							$_POST['payment_date']), 'Y-m-d');

            $_POST['month_paid'] = date("m",strtotime($_POST['payment_date']));
            $_POST['year_paid'] = date("Y",strtotime($_POST['payment_date']));

			Applib::update(Applib::$payments_table,array('p_id'=>$id),$_POST);

			$payment = $this->db->where(array('p_id' => $id))->get(Applib::$payments_table)->row();

			$p_ref = $payment->trans_id;
			$p_amount = $payment->amount;
			$p_invoice = $payment->invoice;

			$this->_log_activity($p_invoice,'activity_edited_payment','fa-pencil',$p_ref,$p_amount);

			$this->applib->redirect_to('invoices/payments/edit/'.$id,'success',lang('payment_edited_successfully'));
			
			}
		}else{

			$data['page'] = $this -> page;
			$data['datepicker'] = TRUE;
			$data['payments'] = $this->payments_list;
			$data['payment_methods'] = $this -> db 
											 -> where(array('method_id >' => 0)) 
											 -> get(Applib::$payment_methods_table) 
											 -> result();

			$data['payment_details'] = $this -> db 
											 -> where('p_id',$this->uri->segment(4)) 
											 -> get(Applib::$payments_table)
											 ->result();

			$this->template
			->set_layout('users')
			->build('edit_payment',isset($data) ? $data : NULL);

		}
	}

	function details()
	{		
		$data['page'] = $this->page;
		$data['payment_details'] = $this -> db 
										 -> where('p_id',$this->uri->segment(4)) 
										 -> get(Applib::$payments_table)
										 ->result();
		$data['payments'] = $this->payments_list;
		$this->template
		->set_layout('users')
		->build('payment_details',isset($data) ? $data : NULL);
	}	

	

	function delete($id = NULL)
	{
		if ($this->input->post()) {

			$transaction = $this->input->post('transaction', TRUE);
			$payment = $this->db
							->where(array('p_id' => $transaction))
							->get(Applib::$payments_table)
							->row();

			$p_ref = $payment->trans_id;
			$p_amount = $payment->amount;
			$p_invoice = $payment->invoice;

			Applib::delete(Applib::$payments_table,array('p_id'=>$transaction)); //delete transaction

			$this->_log_activity($p_invoice,'activity_delete_payment','fa-times',$p_ref,$p_amount);

			$this -> applib -> redirect_to('invoices/payments','success',lang('payment_deleted_successfully'));
		}else{
			$data['transaction'] = $id;
			$this->load->view('modal/delete_payment',$data);

		}
	}

	function _log_activity($invoice_id,$activity,$icon,$value1='',$value2=''){
		$args = array(
			'module' => 'invoices',
			'module_field_id' => $invoice_id,
			'user' => $this->tank_auth->get_user_id(),
			'activity' => $activity,
			'icon' => $icon,
			'value1' => $value1,
			'value2' => $value2
			);
			Applib::create(Applib::$activities_table,$args);
	}

}

/* End of file payments.php */