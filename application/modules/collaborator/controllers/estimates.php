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


class Estimates extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'staff') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('estimates/estimate_model','estimate');
	}
	function index()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('estimates').' - '.$this->config->item('company_name'));
	$data['page'] = lang('estimates');
	$data['estimates'] = $this->estimate->get_all_records($table = 'estimates',
		$array = array(
			'client' => $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'),
			'est_deleted' => 'No'
			),
		$join_table = 'companies',$join_criteria = 'companies.co_id = estimates.client','date_saved');
	$this->template
	->set_layout('users')
	->build('estimates/welcome',isset($data) ? $data : NULL);
	}

	function search()
	{
		if ($this->input->post()) {
				$this->load->module('layouts');
				$this->load->library('template');
				$this->template->title(lang('estimates').' - '.$this->config->item('company_name'));
				$data['page'] = lang('estimates');
				$keyword = $this->input->post('keyword', TRUE);
				$data['estimates'] = $this->estimate->search_estimate($keyword);
				$this->template
				->set_layout('users')
				->build('estimates/welcome',isset($data) ? $data : NULL);
			
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('enter_search_keyword'));
			redirect('collaborator/estimates');
		}
	
	}
	
	function details()
	{		
		if($this->_estimate_access($this->uri->segment(4))){
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('estimates').' - '.$this->config->item('company_name'));
		$data['page'] = lang('estimates');
		$data['estimate_details'] = $this->estimate->estimate_details($this->uri->segment(4));
		$data['estimate_items'] = $this->estimate->estimate_items($this->uri->segment(4));
		$data['estimates'] = $this->estimate->get_all_records($table = 'estimates',
		$array = array(
			'client' => $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'),
			'est_deleted' => 'No'
			),
		$join_table = 'companies',$join_criteria = 'companies.co_id = estimates.client','date_saved');
		$this->template
		->set_layout('users')
		->build('estimates/estimate_details',isset($data) ? $data : NULL);
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('collaborator/estimates');
		}
	}

	function status(){
		$estimate = $this->uri->segment(5);
		$ref_no = $this->uri->segment(6);
			if ($this->uri->segment(4) == 'accepted') {
				$status = 'Accepted';
			}else{
				$status = 'Declined';
			}
			$this->db->set('status', $status);
			$this->db->where('est_id',$estimate)->update('estimates'); 

			$this->_log_activity($estimate,'activity_estimate_marked',$icon = 'fa-paperclip',$this->uri->segment(6),$this->uri->segment(4)); //log activity	 
			$this->_estimate_changed($ref_no,$status); //send email notification	 

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('estimate_'.$this->uri->segment(4).'_successfully'));
			redirect('collaborator/estimates/details/'.$estimate);

	}

	function _estimate_changed($ref_no,$status){

			$company_address = $this->config->item('company_email');
			$data['ref_no'] = $ref_no;
			$data['status'] = $status;

			$params['recipient'] = $company_address;

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' Estimate '.$ref_no.' '.$status;
			$params['message'] = $this->load->view('emails/estimate_status',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}

	function _estimate_access($estimate){
		$client = $this->user_profile->estimate_details($estimate,'client');
		$user = $this->tank_auth->get_user_id();
		$user_company = $this->user_profile->get_profile_details($user,'company');
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

/* End of file estimates.php */