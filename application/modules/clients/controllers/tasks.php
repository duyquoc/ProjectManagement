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
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'client') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('projects/c_model','project');
	}
	function add()
	{		
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('task_name', 'Task Name', 'required');
		$this->form_validation->set_rules('project', 'Project', 'required');

		$project = $this->input->post('project', TRUE);
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('task_add_failed'));
				redirect('clients/projects/details/'.$project);
		}else{	
			$assigned_to = $this->user_profile->get_project_details($project,'assign_to');

			$form_data = array(
			                'task_name' => $this->input->post('task_name'),
			                'project' => $this->input->post('project'),
			                'assigned_to' => $assigned_to,
			                'visible' => 'Yes',
			                'task_progress' => '0',
			                'description' => $this->input->post('description'),
			                'estimated_hours' => $this->input->post('estimate'),
			                'added_by' => $this->tank_auth->get_user_id(),
			            );
			$this->db->insert('tasks', $form_data); 

			$this->_assigned_notification($project,$this->input->post('task_name'),$assigned_to); 
			//send notification to assigned user

			$this->_log_activity($project,'activity_added_new_task',$icon = 'fa-tasks',$this->input->post('task_name')); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('task_add_success'));
			redirect('clients/projects/details/'.$project);
		}
	}else{
		$this->load->view('modal/add_task',isset($data) ? $data : NULL);
	}
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

	function _assigned_notification($project,$task_name,$assigned_to){
			$project_title = $this->user_profile->get_project_details($project,'project_title');

			$added_by = $this->user_profile->get_user_details($this->tank_auth->get_user_id(),'username');
			$data['project_title'] = $project_title;
			$data['added_by'] = $added_by;
			$data['task_name'] = $task_name;

			$params['recipient'] = $this->user_profile->get_user_details($assigned_to,'email');

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' New task requested by '.$added_by;
			$params['message'] = $this->load->view('emails/assigned_notification',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
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