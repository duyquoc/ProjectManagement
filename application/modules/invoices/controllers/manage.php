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

// Includes all users operations
include APPPATH.'/libraries/Requests.php';

class Manage extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		Requests::register_autoloader();
		$this -> auth_key = $this->config->item('api_key');
		$this->load->model('invoice_model','invoice');
	}

	function search()
	{
		if ($this->input->post()) {
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('invoices').' - '.$this->config->item('company_name'));
	$data['page'] = lang('invoices');
	$keyword = $this->input->post('keyword', TRUE);	
	$data['invoices'] = $this->invoice->search_invoice($keyword);
	$this->template
	->set_layout('users')
	->build('invoices',isset($data) ? $data : NULL);
		}else{
			redirect('invoices/manage/view/all');
		}
	}

	

	function edit()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('reference_no', 'Reference No', 'required');
		$this->form_validation->set_rules('client', 'Client', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('invoices/manage/by_status/all');
		}else{	
		$invoice_id = $this->input->post('invoice', TRUE);	

			$form_data = array(
			                'reference_no' => $this->input->post('reference_no'),
			                'client' => $this->input->post('client'),
			                'due_date' => $this->input->post('due_date'),
			                'notes' => $this->input->post('notes'),
			                'tax' => $this->input->post('tax'),	
			                'currency' => $this->input->post('currency'),	
			            );
			$this->db->where('inv_id',$invoice_id)->update('invoices', $form_data);

			$this->_log_activity($invoice_id,'activity_invoice_created',$icon = 'fa-pencil',$this->input->post('reference_no')); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('invoice_edited_successfully'));
			redirect('invoices/view/'.$invoice_id);
		}

		}else{


	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('invoices').' - '.$this->config->item('company_name'));
	$data['page'] = lang('add_invoice');
	$data['form'] = TRUE;
	$data['clients'] = $this->AppModel->get_all_records($table = 'companies',
		$array = array(
			'co_id >' => '0'),$join_table = '',$join_criteria = '','date_added');
	$data['invoices'] = $this->AppModel->get_all_records($table = 'invoices',
		$array = array(
			'inv_deleted' => 'No',
			),
		$join_table = 'companies',$join_criteria = 'companies.co_id = invoices.client','date_saved');
	$data['invoice_details'] =  $this->invoice->invoice_details($this->uri->segment(4));
	$this->template
	->set_layout('users')
	->build('edit_invoice',isset($data) ? $data : NULL);

		}
	}

	function item()
	{
		if ($this->input->post()) {
		$invoice_id = $this->input->post('invoice_id');
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');
		$this->form_validation->set_rules('item_desc', 'Item Description', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('invoices/view/'.$invoice_id);
		}else{			
			$form_data = array(
			                'invoice_id' => $this->input->post('invoice_id'),
			                'item_desc' => $this->input->post('item_desc'),
			                'unit_cost' => $this->input->post('unit_cost'),
			                'quantity' => $this->input->post('quantity'),
			                'total_cost' => $this->input->post('unit_cost') * $this->input->post('quantity')
			            );
			$this->db->insert('items', $form_data); 
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('item_added_successfully'));
			redirect('invoices/view/'.$invoice_id);
		}

		}else{

	redirect('invoices/manage/view/all');

		}
	}
	function details()
	{		
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('invoices').' - '.$this->config->item('company_name'));
		$data['page'] = lang('invoices');
		$data['invoice_details'] = $this->invoice->invoice_details($this->uri->segment(4));
		$data['invoice_items'] = $this->invoice->invoice_items($this->uri->segment(4));
		$data['invoices'] = $this->AppModel->get_all_records($table = 'invoices',
		$array = array(
			'inv_deleted' => 'No',
			),
		$join_table = 'companies',$join_criteria = 'companies.co_id = invoices.client','date_saved');
		$data['payment_status'] = $this->invoice->payment_status($this->uri->segment(4));
		$this->template
		->set_layout('users')
		->build('invoice_details',isset($data) ? $data : NULL);
	}
	function timeline()
	{		
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('invoices').' - '.$this->config->item('company_name'));
		$data['page'] = lang('invoices');
		$data['invoice_details'] = $this->invoice->invoice_details($this->uri->segment(4));
		$data['activities'] = $this->invoice->invoice_activities($this->uri->segment(4));
		$data['invoices'] = $this->AppModel->get_all_records($table = 'invoices',
		$array = array(
			'inv_deleted' => 'No',
			),
		$join_table = 'companies',$join_criteria = 'companies.co_id = invoices.client','date_saved');
		$this->template
		->set_layout('users')
		->build('timeline',isset($data) ? $data : NULL);
	}
	function pay()
	{
		if ($this->input->post()) {
			$invoice_id = $this->input->post('invoice_id');
			$paid_amount = $this->input->post('amount');

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
		if ($this->form_validation->run($this) == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('payment_failed'));
				redirect('invoices/view/'.$invoice_id);
		}else{	

			$invoice_payable = $this->user_profile->invoice_payable($invoice_id);
			$invoice_paid = $this->user_profile->invoice_payment($invoice_id);
			$inv_tax = $this->user_profile->get_invoice_details($invoice_id,'tax');

			$tax = ($inv_tax/100) * $invoice_payable;
			$due = ($invoice_payable + $tax) - $invoice_paid;

			if ($paid_amount > $due) {
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('overpaid_amount'));
				redirect('invoices/view/'.$invoice_id);
			}
			$form_data = array(
			                'invoice' => $this->input->post('invoice_id'),
			                'paid_by' => $this->user_profile->get_invoice_details($invoice_id,'client'),
			                'payment_method' => $this->input->post('payment_method'),
			                'amount' => $this->input->post('amount'),
			                'trans_id' => $this->input->post('trans_id'),
			                'notes' => $this->input->post('notes'),
			                'month_paid' => date('m'),
			                'year_paid' => date('Y'),
			            );
			$this->db->insert('payments', $form_data); 
                        $cur_i = $this->applib->currencies($this->user_profile->get_invoice_details($invoice_id,'currency'));
			$this->_log_activity($invoice_id,'activity_payment_of',$icon = 'fa-usd',$cur_i->symbol.' '.$this->input->post('amount'),$this->input->post('invoice_ref')); //log activity

			$this->_send_payment_email($invoice_id,$paid_amount); //send thank you email

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('payment_added_successfully'));
			redirect('invoices/view/'.$invoice_id);
			}
		}else{
			$data['invoice_id'] = $this->uri->segment(4);
			$data['reference'] = $this->uri->segment(5);
			$data['payment_methods'] = $this->invoice->payment_methods();
			$this->load->view('modal/pay_invoice',$data);
		}
	}
	function quickadd()
	{
		if ($this->input->post()) {
			$invoice = $this->input->post('invoice');
			$item = $this->input->post('item');

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('item', 'Item', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('invoices/view/'.$invoice);
		}else{			
			
			$saved_item = $this->invoice->saved_item_details($item);
			$quantity = $this->input->post('quantity');

			foreach ($saved_item as $key => $i) {
				$item_desc = $i->item_desc;
				$unit_cost = $i->unit_cost;
				$total_cost = $quantity * $i->unit_cost;
			}

			$form_data = array(
			                'invoice_id' => $invoice,
			                'item_desc' => $item_desc,
			                'unit_cost' => $unit_cost,
			                'quantity' => $quantity,
			                'total_cost' => $total_cost
			            );
			$this->db->insert('items', $form_data); 

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('item_added_successfully'));
			redirect('invoices/view/'.$invoice);
			}
		}else{
			$data['invoice'] = $this->uri->segment(4);
			$data['items'] = $this->invoice->saved_items();
			$this->load->view('modal/quickadd',$data);
		}
	}
	function delete_item(){
		if ($this->input->post() ){
					$item_id = $this->input->post('item', TRUE);
					$invoice = $this->input->post('invoice', TRUE);
					$this->db->where('item_id',$item_id)->delete('items');

					$this->session->set_flashdata('response_status', 'success');
					$this->session->set_flashdata('message', lang('item_deleted_successfully'));
					redirect('invoices/view/'.$invoice);
		}else{
			$data['item_id'] = $this->uri->segment(4);
			$data['invoice'] = $this->uri->segment(5);
			$this->load->view('modal/delete_item',$data);
		}
		
	}
	function delete()
	{
		if ($this->input->post()) {

			$invoice = $this->input->post('invoice', TRUE);

			$this->db->where('invoice_id',$invoice)->delete('items'); //delete invoice items

			$this->db->where('invoice',$invoice)->delete('payments'); //delete invoice payments

			$this->db->where(array('module'=>'invoices', 'module_field_id' => $invoice))->delete('activities'); //clear invoice activities
			$this->db->where('inv_id',$invoice)->delete('invoices'); //delete invoice



			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('invoice_deleted_successfully'));
			redirect('invoices/manage/view/all');
		}else{
			$data['invoice'] = $this->uri->segment(4);
			$this->load->view('modal/delete_invoice',$data);

		}
	}

	function _send_payment_email($invoice_id,$paid_amount){
			$client = $this->invoice->get_client($invoice_id);

			$client_address = $this->applib->company_details($client,'company_email');
			$data['paid_amount'] = $paid_amount;

			$params['recipient'] = $client_address;

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' Payment Received';
			$params['message'] = $this->load->view('email/payment_thank_you',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}
	
	function emailinvoice(){
		if ($this->input->post()) {			
			$invoice_id = $this->input->post('invoice_id');
			$ref = $this->input->post('ref');
			$subject = $this->input->post('subject');
			$clientname = str_replace("{CLIENT}",$this->input->post('client_name'),$this->input->post('message'));
			$amount = str_replace("{AMOUNT}",$this->input->post('amount'),$clientname);
			$currency = str_replace("{CURRENCY}",$this->config->item('default_currency'),$amount);
			$link = str_replace("{LINK}",base_url().'clients/inv_manage/details/'.$ref,$currency);
			$message = str_replace("{COMPANY}",$this->config->item('company_name'),$link);
			$this->_email_invoice($invoice_id,$message,$subject);

			$this->db->set('emailed', 'Yes');
			$this->db->set('date_sent', date ("Y-m-d H:i:s", time()));
			$this->db->where('inv_id',$invoice_id)->update('invoices'); 

			$this->_log_activity($invoice_id,activity_invoice_sent,$icon = 'fa-envelope',$ref); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('invoice_sent_successfully'));
			redirect('invoices/view/'.$invoice_id);
		}else{
			$data['invoice_details'] = $this->invoice->invoice_details($this->uri->segment(4));
			$this->load->view('modal/email_invoice',$data);
		}
	}
	function reminder(){
		if ($this->input->post()) {			
			$invoice_id = $this->input->post('invoice_id');
			$ref = $this->input->post('ref');
			$subject = $this->input->post('subject');
			$clientname = str_replace("{CLIENT}",$this->input->post('client_name'),$this->input->post('message'));
			$amount = str_replace("{AMOUNT}",$this->input->post('amount'),$clientname);
			$currency = str_replace("{CURRENCY}",$this->config->item('default_currency'),$amount);
			$link = str_replace("{LINK}",base_url().'clients/inv_manage/details/'.$ref,$currency);
			$message = str_replace("{COMPANY}",$this->config->item('company_name'),$link);
			$this->_email_invoice($invoice_id,$message,$subject);

			$this->db->set('module_field_id', $invoice_id);
			$this->db->set('module', 'invoices');
			$this->db->set('icon', 'fa-shopping-cart');
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', lang('activity_invoice_reminder_sent'));
			$this->db->insert('activities'); 

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('reminder_sent_successfully'));
			redirect('invoices/view/'.$invoice_id);
		}else{
			$data['invoice_details'] = $this->invoice->invoice_details($this->uri->segment(4));
			$this->load->view('modal/invoice_reminder',$data);
		}
	}
	function _email_invoice($invoice_id,$message,$subject){
			$client = $this->invoice->get_client($invoice_id);

			$client_address = $this->applib->company_details($client,'company_email');

			$invoice_details = $this->invoice->invoice_details($invoice_id);
			foreach ($invoice_details as $key => $inv) {
				$data['invoice_ref'] = $inv->reference_no;
				$reference_no = $inv->reference_no;
			}
			$invoice_payable = $this->applib->invoice_payable($invoice_id);
			$invoice_paid = $this->applib->invoice_payment($invoice_id);
			$due = $invoice_payable - $invoice_paid;

			$data['invoice_details'] = $this->invoice->invoice_details($invoice_id);
			$data['payment_status'] = $this->invoice->payment_status($invoice_id);
			$data['invoice_items'] = $this->invoice->invoice_items($invoice_id);
			
			$params['recipient'] = $client_address;

			$params['subject'] = $subject;	
			$params['message'] = $message;
			$this->load->helper('file');
			$invoice['inv_id'] = $invoice_id;
			$invoice['ref'] = $reference_no;

			$invoicehtml = modules::run('fopdf/attachinvoice',$invoice);

			$params['attached_file'] = '';
			if ( ! write_file('./resource/tmp/Invoice #'.$reference_no.'.pdf',$invoicehtml)){
			    $this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('write_access_denied'));
				redirect('invoices/view/'.$invoice_id);
			 }else{
				$params['attached_file'] = './resource/tmp/Invoice #'.$reference_no.'.pdf';
			}
			modules::run('fomailer/send_email',$params);

			unlink('./resource/tmp/Invoice #'.$reference_no.'.pdf');
	}
	
	function _log_activity($invoice_id,$activity,$icon,$value1='',$value2=''){
			$this->db->set('module', 'invoices');
			$this->db->set('module_field_id', $invoice_id);
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', $activity);
			$this->db->set('icon', $icon);
                        $this->db->set('value1', $value1);
			$this->db->set('value2', $value2);
			$this->db->insert('activities'); 
	}

}

/* End of file manage.php */