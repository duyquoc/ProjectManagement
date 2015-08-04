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


class Tasks extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'staff') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('projects/c_model','project');
	}
	function edit()
	{		
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('task_name', 'Task Name', 'required');
		$this->form_validation->set_rules('project', 'Project', 'required');
		$this->form_validation->set_rules('progress', 'progress', 'required');

		$project = $this->input->post('project', TRUE);
		$task_id = $this->input->post('task_id', TRUE);
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('task_update_failed'));
				redirect('collaborator/projects/details/'.$project);
		}else{
		if ($this->input->post('visible') == 'on') { $visible = 'Yes'; } else { $visible = 'No'; }	
			$form_data = array(
			                'task_name' => $this->input->post('task_name'),
			                'project' => $this->input->post('project'),
			                'visible' => $visible,
			                'task_progress' => $this->input->post('progress'),
			                'description' => $this->input->post('description'),
			                'estimated_hours' => $this->input->post('estimate')
			            );
			$this->db->where('t_id',$task_id)->update('tasks', $form_data); 

			$this->_assigned_notification($project,$this->input->post('task_name'),$this->tank_auth->get_user_id()); 
			//send notification to assigned user

			$this->_log_activity($project,'activity_edited_a_task',$icon='fa-tasks',$this->input->post('task_name')); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('task_update_success'));
			redirect('collaborator/projects/details/'.$project);
		}
	}else{
		$data['task_details'] = $this->project->task_details($this->uri->segment(4));
		$this->load->view('modal/edit_task',isset($data) ? $data : NULL);
	}
}
	function add()
	{		
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('task_name', 'Task Name', 'required');
		$this->form_validation->set_rules('project', 'Project', 'required');
		$this->form_validation->set_rules('progress', 'progress', 'required');

		$project = $this->input->post('project', TRUE);
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('task_add_failed'));
				redirect('collaborator/projects/details/'.$project);
		}else{
		if ($this->input->post('visible') == 'on') { $visible = 'Yes'; } else { $visible = 'No'; }	
			$form_data = array(
			                'task_name' => $this->input->post('task_name'),
			                'project' => $this->input->post('project'),
			                'visible' => $visible,
			                'task_progress' => $this->input->post('progress'),
			                'description' => $this->input->post('description'),
			                'estimated_hours' => $this->input->post('estimate'),
			                'added_by' => $this->tank_auth->get_user_id(),
			            );
			$this->db->insert('tasks', $form_data); 

			$this->_assigned_notification($project,$this->input->post('task_name'),$this->tank_auth->get_user_id()); 
			//send notification to assigned user

			$this->_log_activity($project,'activity_added_new_task',$icon = 'fa-tasks',$this->input->post('task_name')); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('task_add_success'));
			redirect('collaborator/projects/details/'.$project);
		}
	}else{
		$this->load->view('modal/add_task',isset($data) ? $data : NULL);
	}
}
	function tracking()
	{
		$action = ucfirst($this->uri->segment(4));
		$project = $this->uri->segment(5);
		$task = $this->uri->segment(6);
		if ($action == 'Off') {			
			$task_start =  $this->project->get_task_start($task); //task start time
			$task_logged_time =  $this->project->get_task_logged_time($task); 
			$time_logged = (time() - $task_start) + $task_logged_time; //time already logged

			$this->db->set('timer_status', $action);
			$this->db->set('logged_time', $time_logged);
			$this->db->set('start_time', '');
			$this->db->where('t_id',$task)->update('tasks');
			$this->_log_timesheet($project,$task,$task_start,time()); //log activity
			$timer_msg = 'timer_stopped_success';

		}else{
			$this->db->set('timer_status', $action);
			$this->db->set('start_time', time());
			$this->db->where('t_id',$task)->update('tasks');
			$timer_msg = 'timer_started_success';
		}
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang($timer_msg));
			redirect('collaborator/projects/details/'.$project);
	}
	function timesheet()
	{		
		$data['timesheets'] = $this->project->timesheets($this->uri->segment(4));
		$this->load->view('tabs/timesheets',isset($data) ? $data : NULL);
	}
	function tasks()
	{		
		$data['project_tasks'] = $this->project->project_tasks($this->uri->segment(4));
		$this->load->view('tabs/tasks',isset($data) ? $data : NULL);
	}
	function pilot(){
		if ($this->uri->segment(4) == 'on') {
			$status = 'TRUE';
		}else{
			$status = 'FALSE';
		}
			$task = $this->uri->segment(5);
			$project = $this->uri->segment(6)/8600;

			$this->db->set('auto_progress', $status);
			$this->db->where('t_id',$task)->update('tasks');

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('progress_auto_calculated'));
			redirect('collaborator/projects/details/'.$project);
	}

	function _assigned_notification($project,$task_name,$assigned_to){
			$project_title = $this->user_profile->get_project_details($project,'project_title');

			$data['project_title'] = $project_title;
			$data['task_name'] = $task_name;

			$params['recipient'] = $this->user_profile->get_user_details($assigned_to,'email');

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' New task assigned to you';
			$params['message'] = $this->load->view('emails/assigned_notification',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}
	function _log_timesheet($project,$task,$start_time,$end_time){
			$this->db->set('pro_id', $project);
			$this->db->set('task', $task);
			$this->db->set('start_time', $start_time);
			$this->db->set('end_time', $end_time);
			$this->db->insert('tasks_timer'); 
	}

	function _log_activity($project,$activity,$icon,$value1='',$value2=''){
			$this->db->set('module', 'projects');
			$this->db->set('module_field_id', $project);
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', $activity);
			$this->db->set('icon', $icon);
                        $this->db->set('value1', $value1);
			$this->db->set('value2', $value2);
			$this->db->insert('activities'); 
	}
}

/* End of file tasks.php */