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


class Inv_manage extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'client') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('invoices/invoice_model','invoice');
	}
	function index()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('invoices').' - '.$this->config->item('company_name'));
	$data['page'] = lang('invoices');
	$data['invoices'] = $this->invoice->get_all_records($table = 'invoices',
		$array = array(
			'client' => $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'),
			'inv_deleted' => 'No'
			),
		$join_table = 'companies',$join_criteria = 'companies.co_id = invoices.client','date_saved');
	$this->template
	->set_layout('users')
	->build('invoices/welcome',isset($data) ? $data : NULL);
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
				->build('invoices/welcome',isset($data) ? $data : NULL);
			
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('enter_search_keyword'));
			redirect('clients/inv_manage');
		}
	
	}
	
	function details()
	{		
		if($this->_invoice_access($this->uri->segment(4))){
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('invoices').' - '.$this->config->item('company_name'));
		$data['page'] = lang('invoices');
		$data['invoice_details'] = $this->invoice->invoice_details($this->uri->segment(4));
		$data['invoice_items'] = $this->invoice->invoice_items($this->uri->segment(4));
		$data['invoices'] = $this->invoice->get_all_records($table = 'invoices',$array = array(
			'client' => $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'),
			'inv_deleted' => 'No',
			),$join_table = 'companies',$join_criteria = 'companies.co_id = invoices.client','date_saved');
		$data['payment_status'] = $this->invoice->payment_status($this->uri->segment(4));
		$this->template
		->set_layout('users')
		->build('invoices/invoice_details',isset($data) ? $data : NULL);
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('clients/inv_manage');
		}
	}
	
	function _invoice_access($invoice){
		$client = $this->user_profile->get_invoice_details($invoice,'client');
		$user = $this->tank_auth->get_user_id();
		$user_company = $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company');
		if ($client == $user_company) {
			return TRUE;
		}else{
			return FALSE;
		}
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

/* End of file inv_manage.php */