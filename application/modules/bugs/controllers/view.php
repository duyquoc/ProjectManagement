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


class View extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('bugs_model');
	}
	function details()
	{		
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
		->build('bug_details',isset($data) ? $data : NULL);
	}
	function add()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('issue_ref', 'Issue Ref', 'required');
		$this->form_validation->set_rules('project', 'Project', 'required');
		$this->form_validation->set_rules('reporter', 'Reporter', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('issue_not_submitted'));
				redirect('bugs');
		}else{			
			$form_data = array(
			                'issue_ref' => $this->input->post('issue_ref'),
			                'project' => $this->input->post('project'),
			                'reporter' => $this->input->post('reporter'),
			                'assigned_to' => $this->input->post('assigned_to'),
			                'bug_status' => 'Unconfirmed',
			                'priority' => $this->input->post('priority'),
			                'bug_description' => $this->input->post('description'),
			                'last_modified' => date("Y-m-d H:i:s"),
			            );
			$this->db->insert('bugs', $form_data); 
			$bug_id = $this->db->insert_id();
			$this->_log_bug_activity($bug_id,'bug_created',$icon = 'fa-plus',$this->input->post('issue_ref')); //log activity

			$this->_assigned_notification($bug_id, $this->input->post('assigned_to'));
			
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('issue_submitted_successfully'));
			redirect('bugs/view_by_status/all');
		}
		}else{
			$data['admins'] = $this->bugs_model->users('');
			$data['users'] = $this->bugs_model->users('all');
			$data['projects'] = $this->bugs_model->projects();
		$this->load->view('modal/add_bug',$data);
		}
	}
	function edit()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('issue_ref', 'Issue Ref', 'required');
		$this->form_validation->set_rules('project', 'Project', 'required');
		$this->form_validation->set_rules('reporter', 'Reporter', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('issue_not_edited'));
				redirect('bugs');
		}else{	
		$bug_id	 =  $this->input->post('bug_id');
			$form_data = array(
			                'issue_ref' => $this->input->post('issue_ref'),
			                'project' => $this->input->post('project'),
			                'assigned_to' => $this->input->post('assigned_to'),
			                'reporter' => $this->input->post('reporter'),
			                'priority' => $this->input->post('priority'),
			                'bug_description' => $this->input->post('description'),
			                'last_modified' => date("Y-m-d H:i:s"),
			            );
			$this->db->where('bug_id',$bug_id)->update('bugs', $form_data); 
			$this->_log_bug_activity($bug_id,'activity_issue_edited',$icon = 'fa-pencil',$this->input->post('issue_ref')); //log activity

			$this->_assigned_notification($bug_id, $this->input->post('assigned_to'));

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('issue_edited_successfully'));
			redirect('bugs/view/details/'.$bug_id);
		}
		}else{
		$data['admins'] = $this->bugs_model->users('');
		$data['users'] = $this->bugs_model->users('all');
		$data['projects'] = $this->bugs_model->projects();
		$data['bug_details'] = $this->bugs_model->bug_details($this->uri->segment(4));
		$this->load->view('modal/edit_bug',$data);
		}
	}

	function _assigned_notification($bug,$assigned_to){
			$bug_details = $this->bugs_model->bug_details($bug);
			foreach ($bug_details as $key => $b) {
				$issue_ref = $b->issue_ref;
				$project = $b->project;
			}

			$project_title = $this->user_profile->get_project_details($project,'project_title');

			$assigned_by = $this->user_profile->get_user_details($this->tank_auth->get_user_id(),'username');
			$data['project_title'] = $project_title;
			$data['assigned_by'] = $assigned_by;
			$data['issue_ref'] = $issue_ref;

			$params['recipient'] = $this->user_profile->get_user_details($assigned_to,'email');

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' New bug assigned by '.$assigned_by;
			$params['message'] = $this->load->view('emails/assigned_notification',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}

	function _log_bug_activity($bug_id,$activity,$icon){
			$this->db->set('module', 'bugs');
			$this->db->set('module_field_id', $bug_id);
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('icon', $icon);
			$this->db->set('activity', $activity);
			$this->db->insert('activities'); 
	}
}

/* End of file view.php */