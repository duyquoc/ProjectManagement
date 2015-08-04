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


class Timesheet extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		if (!$this -> user) {
			$this -> applib -> redirect_to('auth/login','error',lang('access_denied'));			
		}
		$this -> template -> title(lang('projects').' - '.config_item('company_name'));
		$this -> page = lang('projects');

		$this -> project_list = Applib::retrieve(Applib::$projects_table,array('project_id !='=>'0'));
		
	}

	function add_time()
	{
		if ($this->input->post()) {				

		$start_time = strtotime($_POST['start_time']);
		$end_time = strtotime($_POST['end_time']);
		$time_spent = $end_time - $start_time;
			if($_POST['cat'] == 'tasks'){
				if ($this->form_validation->run('projects','add_task_time') == FALSE)
					{
						$this->session->set_flashdata('response_status', 'error');
						$this->session->set_flashdata('message', lang('error_in_form'));
						redirect($_SERVER['HTTP_REFERER']);

					}else{
				$args = array(
			                'task' => $_POST['task'],
			                'pro_id' => $_POST['project'],
			                'start_time' => $start_time,
			                'end_time' => $end_time,
			                'user' => $this -> user
			                );		
			    Applib::create(Applib::$task_timer_table,$args);

				$logged_time = Applib::get_table_field(Applib::$tasks_table,
								array('t_id'=>$_POST['task']),'logged_time');

				$this -> db 
					-> set('logged_time',$time_spent+$logged_time) 
					-> where(array('t_id'=>$_POST['task'])) 
					-> update(Applib::$tasks_table);
				} 

				}else{
					$args = array(
			                'project' => $_POST['project'],
			                'start_time' => $start_time,
			                'user' => $this -> user,
			                'end_time' => $end_time
			                );
				Applib::create(Applib::$project_timer_table,$args);

				$logged_time = Applib::get_table_field(Applib::$projects_table,array('project_id'=>$_POST['project']),'time_logged');
				$this -> db -> set('time_logged',$time_spent+$logged_time) -> where(array('project_id'=>$_POST['project'])) -> update(Applib::$projects_table);
			}

			$this -> applib -> redirect_to('projects/view/'.$_POST['project'].'/?group=timesheets&cat='.$_POST['cat'],'success',lang('time_logged_successfully'));
		 // Passed validation

		}else{
			$data['project'] = $this->uri->segment(4);
			$data['cat'] = isset($_GET['cat']) ? $_GET['cat'] : 'projects';
			$this->load->view('modal/add_time', isset($data) ? $data : NULL);
		}
	}
	function edit()
	{
		if ($this->input->post()) {	
		$start_time = strtotime($_POST['start_time']);
		$end_time = strtotime($_POST['end_time']);
		$time_spent = $end_time - $start_time;
		if($_POST['cat'] == 'tasks'){
			$args = array(
			                'task' => $_POST['task'],
			                'pro_id' => $_POST['project'],
			                'start_time' => $start_time,
			                'end_time' => $end_time,
			                'user' => $this -> user
			                );			
			$this -> db 
				  -> where('timer_id',$_POST['timer_id']) 
				  -> update(Applib::$task_timer_table,$args);	

				}else{
			$args = array(
			                'project' => $_POST['project'],
			                'start_time' => $start_time,
			                'user' => $this -> user,
			                'end_time' => $end_time
			                );
			$this -> db -> where('timer_id',$_POST['timer_id']) -> update(Applib::$project_timer_table,$args);			
			}

			$this -> applib -> redirect_to('projects/view/'.$_POST['project'].'/?group=timesheets&cat='.$_POST['cat'],'success',lang('time_logged_successfully'));
		}else{
			$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
			$timer_id = isset($_GET['id']) ? $_GET['id'] : '';
			$data['project'] = $this->uri->segment(4);
			$data['timer_id'] = $timer_id;
			$data['cat'] = $cat;
			if($cat == 'tasks'){ 
				$data['info'] = $this->db->where('timer_id',$timer_id)->get(Applib::$task_timer_table)->result(); 
			}else{ 
				$data['info'] = $this->db->where('timer_id',$timer_id)->get(Applib::$project_timer_table)->result();
			}
			$this->load->view('modal/edit_time', isset($data) ? $data : NULL);
		}
	}

	function delete()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('project', 'Project ID', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this -> applib -> redirect_to('projects/view/'.$_POST['project'].'/?group=timesheets&cat='.$_POST['cat'],'error',lang('delete_failed'));
		}else{	
			$project = $this->input->post('project');

			if ($_POST['cat'] == 'tasks') {
				$this -> db->delete(Applib::$task_timer_table, array('timer_id' => $this->input->post('timer_id'))); 
				}else{
				$this->db->delete(Applib::$project_timer_table, array('timer_id' => $this->input->post('timer_id'))); 
			}
			$this -> applib -> redirect_to('projects/view/'.$project.'/?group=timesheets&cat='.$_POST['cat'],'success',lang('time_deleted_successfully'));
			}
		}else{
			$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
			$timer_id = isset($_GET['id']) ? $_GET['id'] : '';

			$data['project'] = $this->uri->segment(4);
			$data['timer_id'] = $timer_id;
			$data['cat'] = $cat;
			$this->load->view('modal/delete_time',$data);
		}
	}

	
	function _log_activity($activity,$user,$module,$module_field_id,$icon,$value1='',$value2=''){
		
					$params = array(
					                'user'				=> $user,
					                'module' 			=> $module,
					                'module_field_id'	=> $module_field_id,
					                'activity'			=> $activity,
					                'icon'				=> $icon,
					                'value1'			=> $value1,
					                'value2'			=> $value2
					                );
					Applib::create(Applib::$activities_table,$params);
	}
	function _log_timesheet($project,$start_time,$end_time){
		$args = array(
			'project' => $project,
			'start_time' => $start_time,
			'end_time' => $end_time
			);
		Applib::create(Applib::$project_timer_table,$args);
	}
}

/* End of file projects.php */