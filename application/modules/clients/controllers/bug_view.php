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


class Bug_view extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'client') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('bugs/bugs_model');
	}
	function details()
	{		
		if($this->_bug_access($this->uri->segment(4))){
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('bug_tracking').' - '.$this->config->item('company_name'));
		$data['page'] = lang('bug_tracking');
		$data['bugs'] = $this->bugs_model->bugs();
		$data['bug_details'] = $this->bugs_model->bug_details($this->uri->segment(4));
		$data['bug_activities'] = $this->bugs_model->bug_activities($this->uri->segment(4));
		$data['bug_comments'] = $this->bugs_model->bug_comments($this->uri->segment(4));
		$this->template
		->set_layout('users')
		->build('bugs/bug_details',isset($data) ? $data : NULL);
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('clients/bugs');
		}
	}
	function add()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('issue_ref', 'Issue Ref', 'required');
		$this->form_validation->set_rules('project', 'Project', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('issue_not_submitted'));
				redirect('clients/bugs');
		}else{		
			$assigned_to = $this->user_profile->get_project_details($this->input->post('project'),'assign_to');
			$form_data = array(
			                'issue_ref' => $this->input->post('issue_ref'),
			                'project' => $this->input->post('project'),
			                'reporter' => $this->tank_auth->get_user_id(),
			                'assigned_to' => $assigned_to,
			                'bug_status' => 'Unconfirmed',
			                'priority' => $this->input->post('priority'),
			                'bug_description' => $this->input->post('description'),
			                'last_modified' => date("Y-m-d H:i:s"),
			            );
			$this->db->insert('bugs', $form_data); 
			$bug_id = $this->db->insert_id();
			$this->_log_bug_activity($bug_id,'bug_created',$icon = 'fa-plus',$this->input->post('issue_ref')); //log activity

			$this->_bug_notification($assigned_to);
			
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('issue_submitted_successfully'));
			redirect('clients/bugs');
		}
		}else{
		$data['projects'] = $this->bugs_model->projects();
		$this->load->view('bugs/add_bug',$data);
		}
	}
	function edit()
	{
		

		if ($this->input->post()) {

			if($this->_bug_access($this->input->post('bug_id'))){
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('issue_ref', 'Issue Ref', 'required');
		$this->form_validation->set_rules('project', 'Project', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('issue_not_edited'));
				redirect('clients/bugs');
		}else{	
		$assigned_to = $this->user_profile->get_project_details($this->input->post('project'),'assign_to');
		$bug_id	 =  $this->input->post('bug_id');
			$form_data = array(
			                'issue_ref' => $this->input->post('issue_ref'),
			                'project' => $this->input->post('project'),
			                'assigned_to' => $assigned_to,
			                'reporter' => $this->tank_auth->get_user_id(),
			                'priority' => $this->input->post('priority'),
			                'bug_description' => $this->input->post('description'),
			                'last_modified' => date("Y-m-d H:i:s"),
			            );
			$this->db->where('bug_id',$bug_id)->update('bugs', $form_data); 
			$this->_log_bug_activity($bug_id,'activity_issue_edited',$icon = 'fa-edit',$this->input->post('issue_ref')); //log activity

			$this->_bug_notification($assigned_to);

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('issue_edited_successfully'));
			redirect('clients/bug_view/details/'.$bug_id);
			}

			}else{
			$this->session->set_flashdata('message', lang('project_access_denied'));
			redirect('clients/bugs');
		}
		}else{
		$data['projects'] = $this->bugs_model->projects();
		$data['bug_details'] = $this->bugs_model->bug_details($this->uri->segment(4));
		$this->load->view('bugs/edit_bug',$data);
		}
	}

	function _bug_access($bug){
		$bug_details = $this->bugs_model->bug_details($bug);
		foreach ($bug_details as $key => $bug) {
			$bug_reporter = $bug->reporter;
			$project = $bug->project;
		}
		$project_client = $this->user_profile->get_project_details($project,'client');
		$user = $this->tank_auth->get_user_id();
		if ($bug_reporter == $user OR $project_client == $user) {
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function _log_bug_activity($bug_id,$activity,$icon){
			$this->db->set('module', 'bugs');
			$this->db->set('module_field_id', $bug_id);
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', $activity);
			$this->db->set('icon', $icon);
			$this->db->insert('activities'); 
	}
	function _bug_notification($assigned_to){
			
			$added_by = $this->tank_auth->get_username();
			$data['project_manager'] = $this->user_profile->get_user_details($assigned_to,'username');
			$data['added_by'] = $added_by;

			$params['recipient'] = $this->user_profile->get_user_details($assigned_to,'email');

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' New Bug Reported';
			$params['message'] = $this->load->view('emails/bug_notification',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}
}

/* End of file bug_view.php */