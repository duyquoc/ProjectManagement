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


class Tabs extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('project_model','project');
	}
	function timeline()
	{		
		$data['activities'] = $this->project->project_activities($this->uri->segment(4));
		$this->load->view('tabs/timeline',isset($data) ? $data : NULL);
	}
	function bugs()
	{	
		$data['bugs'] = $this->project->project_bugs($this->uri->segment(4));
		$this->load->view('tabs/bugs',isset($data) ? $data : NULL);
	}
	function timesheet()
	{		
		$data['timesheets'] = $this->project->timesheets($this->uri->segment(4));
		$data['tasks_log'] = $this->project->task_timer($this->uri->segment(4));
		$this->load->view('tabs/timesheets',isset($data) ? $data : NULL);
	}
	function tasks()
	{		
		$data['project_tasks'] = $this->project->project_tasks($this->uri->segment(4));
		$this->load->view('tabs/tasks',isset($data) ? $data : NULL);
	}
	function files()
	{		
		$data['project_files'] = $this->project->project_files($this->uri->segment(4));
		$this->load->view('tabs/files',isset($data) ? $data : NULL);
	}
}

/* End of file tabs.php */