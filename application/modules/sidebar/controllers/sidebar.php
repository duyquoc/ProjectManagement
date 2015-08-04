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

class Sidebar extends MX_Controller {
function __construct()
	{
		parent::__construct();
	}
	public function admin_menu()
	{
		$data['languages'] = $this -> applib -> languages();
                $this->load->view('admin_menu',isset($data) ? $data : NULL);
	}
	public function collaborator_menu()
	{
		$this->load->view('collaborator_menu',isset($data) ? $data : NULL);
	}
	public function client_menu()
	{
		$data['languages'] = $this -> applib -> languages();
                $this->load->view('user_menu',isset($data) ? $data : NULL);
	}
	public function top_header()
	{
                $this->db->select("project_id as id, project_title as title, users.id as user_id, username, use_gravatar, email, timer as status,timer_start as start, avatar, 'project' as type",FALSE);
                        $this->db->join('users','users.id = projects.timer_started_by');
                        $this->db->join('account_details','account_details.user_id = projects.timer_started_by');
                        $this->db->where(array('timer'=>'On'));
                        $project_timers = $this->db->get(Applib::$projects_table)->result_array();

                $this->db->select("project as id, task_name as title, users.id as user_id, username, use_gravatar, email, timer_status as status,start_time as start, avatar, 'task' as type",FALSE);
                        $this->db->join('projects','projects.project_id = tasks.project');
                        $this->db->join('users','users.id = tasks.timer_started_by');
                        $this->db->join('account_details','account_details.user_id = tasks.timer_started_by');
                        $this->db->where(array('timer_status'=>'On'));
                        $task_timers = $this->db->get('tasks')->result_array();

                $data['timers'] = array_merge($project_timers,$task_timers);
                $data['updates'] = $this->applib->get_updates();
                $data['activities'] = $this->applib->get_activities();

                $this->load->view('top_header',isset($data) ? $data : NULL);
	}
	
	public function scripts()
	{
		$this->load->view('scripts/uni_scripts',isset($data) ? $data : NULL);
	}
	public function flash_msg()
	{
		$this->load->view('flash_msg',isset($data) ? $data : NULL);
	}
}
/* End of file sidebar.php */