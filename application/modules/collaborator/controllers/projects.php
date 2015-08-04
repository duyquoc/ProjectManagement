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


class Projects extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'staff') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('projects/c_model','project_model');
	}

	function index()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('projects').' - '.$this->config->item('company_name'));
	$data['page'] = lang('projects');
	$data['projects'] = $this->project_model->assigned_projects($this->tank_auth->get_user_id());
	$this->template
	->set_layout('users')
	->build('projects/projects',isset($data) ? $data : NULL);
	}
	function details()
	{		
		if($this->_project_access($this->uri->segment(4))){
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('projects').' - '.$this->config->item('company_name'));
		$data['page'] = lang('projects');
		$data['project_details'] = $this->project_model->project_details($this->uri->segment(4));
		$data['project_activities'] = $this->project_model->project_activities($this->uri->segment(4));
		$data['project_comments'] = $this->project_model->project_comments($this->uri->segment(4));
		$data['project_tasks'] = $this->project_model->project_tasks($this->uri->segment(4));
		$data['project_files'] = $this->project_model->project_files($this->uri->segment(4));
		$this->template
		->set_layout('users')
		->build('projects/project_details',isset($data) ? $data : NULL);
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('project_access_denied'));
			redirect('collaborator/projects');
		}
	}
	function comment()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('comment', 'Comment', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('comment_failed'));
				redirect('collaborator/projects/details/'.$this->input->get('project',TRUE));
		}else{		
		$project_id = $this->input->post('project_id');	
			$form_data = array(
			                'project' => $project_id,
			                'posted_by' => $this->tank_auth->get_user_id(),
			                'message' => $this->input->post('comment')
			            );
			$this->db->insert('comments', $form_data); 
			$this->_log_activity($project_id,'activity_project_comment_added',$icon='fa-comment',$this->input->post('project_code')); //log activity

			$this->_comment_notification($project_id); //send notification to the administrator

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('comment_successful'));
			redirect('collaborator/projects/details/'.$this->input->get('project',TRUE));
			}
		}else{
		redirect('collaborator/projects');
		}
	}
	function replies()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('message', 'Message', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('comment_failed'));
				redirect('collaborator/projects/details/'.$this->input->post('project',TRUE));
		}else{		
		$project_id = $this->input->post('project');	
			$form_data = array(
			                'parent_comment' => $this->input->post('comment', TRUE),
			                'reply_msg' => $this->input->post('message'),
			                'replied_by' => $this->tank_auth->get_user_id()
			            );
			$this->db->insert('comment_replies', $form_data); 

			$this->_comment_notification($project_id); //send notification to the administrator

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('comment_replied_successful'));
			redirect('collaborator/projects/details/'.$this->input->post('project',TRUE));
			}
		}else{
		$data['comment'] = $this->input->get('c', TRUE);
		$data['project'] = $this->input->get('p', TRUE);
		$this->load->view('modal/comment_reply',isset($data) ? $data : NULL);
		}
	}

	function delcomment()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('comment', 'Comment ID', 'required');
		$this->form_validation->set_rules('project', 'Project ID', 'required');
		$project_id = $this->input->post('project', TRUE);
		$comment_id = $this->input->post('comment', TRUE);

		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('comment_delete_failed'));
				redirect('collaborator/projects/details/'.$project_id);
		}else{			
			$this->db->set('deleted', 'Yes');
			$this->db->where('comment_id',$comment_id)->update('comments'); 
	
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('comment_deleted'));
			redirect('collaborator/projects/details/'.$project_id);
			}
		}else{
			$data['comment_id'] = $this->input->get('c', TRUE);
			$data['project_id'] = $this->input->get('p', TRUE);
			$this->load->view('modal/delete_comment',$data);
		}
	}

	function tracking()
	{
		$action = ucfirst($this->uri->segment(4));
		$project = $this->uri->segment(5);
		$timer_msg = '';
		if ($action == 'Off') {			
			$project_start =  $this->project_model->get_project_start($project); //project start time
			$project_logged_time =  $this->project_model->get_project_logged_time($project); 
			$time_logged = (time() - $project_start) + $project_logged_time; //time already logged

			$this->db->set('timer', $action);
			$this->db->set('time_logged', $time_logged);
			$this->db->set('timer_start', '');
			$this->db->where('project_id',$project)->update('projects');
			$this->_log_timesheet($project,$project_start,time()); //log activity
			$timer_msg = 'timer_stopped_success';

		}else{
			$this->db->set('timer', $action);
			$this->db->set('timer_start', time());
			$this->db->where('project_id',$project)->update('projects');
			$timer_msg = 'timer_started_success';
		}
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang($timer_msg));
			redirect('collaborator/projects/details/'.$project);
	}

	function edit()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('progress', 'Progress', 'required');
		$project_id = $this->input->post('project_id');	
		
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('collaborator/projects/details/'.$project_id);
		}else{	
			
			$form_data = array(
			                'progress' => $this->input->post('progress'),
			                'estimate_hours' => $this->input->post('estimate'),
			            );
			$this->db->where('project_id',$project_id)->update('projects', $form_data);

			$this->_log_activity($project_id,'activity_edited_a_project',$icon = 'fa-pencil',$this->input->post('project_code')); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('project_edited_successfully'));
			redirect('collaborator/projects/details/'.$project_id);
		}
		}else{
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('projects').' - '.$this->config->item('company_name'));
		$data['page'] = lang('projects');
		$data['project_details'] = $this->project_model->project_details($this->uri->segment(4));
		$this->template
		->set_layout('users')
		->build('projects/edit_project',isset($data) ? $data : NULL);
		}
	}

	function search()
	{
		if ($this->input->post()) {
				$this->load->module('layouts');
				$this->load->library('template');
				$this->template->title(lang('projects').' - '.$this->config->item('company_name'));
				$data['page'] = lang('projects');
				$keyword = $this->input->post('keyword', TRUE);
				$data['projects'] = $this->project_model->search_project($keyword);
				$this->template
				->set_layout('users')
				->build('projects/projects',isset($data) ? $data : NULL);
			
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('enter_search_keyword'));
			redirect('collaborator/projects');
		}
	
	}

	function delete()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('project_id', 'Project ID', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('delete_failed'));
				redirect('projects/view_projects/all');
		}else{			
			$this->db->delete('projects', array('project_id' => $this->input->post('project_id'))); 
			$this->db->delete('comments', array('project' => $this->input->post('project_id'))); 
			$this->db->delete('activities', array('module' => 'projects','module_field_id' => $this->input->post('project_id'))); 
			$this->db->delete('project_timer', array('project' => $this->input->post('project_id'))); 
			$this->db->delete('tasks', array('project' => $this->input->post('project_id'))); 
			$this->db->delete('bugs', array('project' => $this->input->post('project_id'))); 
			$this->db->delete('assign_projects', array('project_assigned' => $this->input->post('project_id'))); 
			$this->db->delete('assign_tasks', array('project_assigned' => $this->input->post('project_id'))); 
			// Delete project files

			$this->db->delete('files', array('project' => $this->input->post('project_id'))); 
			// Log Activity
			$this->_log_activity($project_id,'activity_deleted_project',$icon = 'fa-times',$this->input->post('project_id')); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('project_deleted_successfully'));
			redirect('projects/view_projects/all');
		}
		}else{
			$data['project_id'] = $this->uri->segment(3);
			$this->load->view('modal/delete_project',$data);
		}
	}
	function timelog()
	{		
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('logged_time', 'Logged Time', 'required');

		$project = $this->input->post('project', TRUE);

		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('time_entered_failed'));
				redirect('projects/view/details/'.$project);
		}else{	
			$project_logged_time =  $this->project_model->get_project_logged_time($project); 
			$time_logged = $project_logged_time + ($this->input->post('logged_time', TRUE) *3600); //time already logged

			$this->db->set('time_logged', $time_logged);
			$this->db->where('project_id',$project)->update('projects'); 
		}

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('time_entered_success'));
			redirect('collaborator/projects/details/'.$project);
	}else{
		$data['logged_time'] =  $this->project_model->get_project_logged_time($this->uri->segment(4)/8600); 
		$data['project_details'] = $this->project_model->project_details($this->uri->segment(4)/8600);
		$this->load->view('modal/time_entry',isset($data) ? $data : NULL);
		}
	}

	function _comment_notification($project){
			$project_title = $this->user_profile->get_project_details($project,'project_title');
			$client = $this->user_profile->get_project_details($project,'client');

			$posted_by = $this->user_profile->get_user_details($this->tank_auth->get_user_id(),'username');
			$data['project_title'] = $project_title;
			$data['posted_by'] = $posted_by;

			$params['recipient'] = $this->applib->company_details($client,'company_email');

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' New project comment received from '.$posted_by;
			$params['message'] = $this->load->view('emails/comment_notification',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}

	function pilot(){
		if ($this->uri->segment(4) == 'on') {
			$status = 'TRUE';
		}else{
			$status = 'FALSE';
		}
			$project = $this->uri->segment(5)/8600;

			$this->db->set('auto_progress', $status);
			$this->db->where('project_id',$project)->update('projects');

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('progress_auto_calculated'));
			redirect('collaborator/projects/details/'.$project);
	}

	function _project_access($project){
		$user = $this->tank_auth->get_user_id();
		$client = $this->user_profile->get_project_details($project,'client');
		$assigned = $this->_assign($project,$this->tank_auth->get_user_id());
		
		if ($client == $user OR $assigned == TRUE) {
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function _assign($project,$user){
		$this->db->join('assign_projects','assign_projects.project_assigned = projects.project_id');
		$this->db->where('assigned_user', $user);
		$this->db->where('project_assigned', $project);
		$query = $this->db->get(Applib::$projects_table);
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		} 
	}

	function _log_activity($project_id,$activity,$icon,$value1='',$value2=''){
			$this->db->set('module', 'projects');
			$this->db->set('module_field_id', $project_id);
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', $activity);
			$this->db->set('icon', $icon);
			$this->db->set('value1', $value1);
			$this->db->set('value2', $value2);
			$this->db->insert('activities'); 
	}
	function _log_timesheet($project,$start_time,$end_time){
			$this->db->set('project', $project);
			$this->db->set('start_time', $start_time);
			$this->db->set('end_time', $end_time);
			$this->db->insert('project_timer'); 
	}
}

/* End of file projects.php */