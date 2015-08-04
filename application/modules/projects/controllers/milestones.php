<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Freelancer Office
 * 
 * Web based project and invoicing management system available on codecanyon
 *
 * @package		Freelancer Office
 * @author		William M
 * @copyright	Copyright (c) 2014 - 2015 Gitbench, LLC
 * @license		http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
 * @link		http://codecanyon.net/item/freelancer-office/8870728
 * 
 */


class Milestones extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		if (!$this -> user) {
			$this -> applib -> redirect_to('login','error',lang('access_denied'));			
		}
		$this -> user_role = Applib::login_info($this->user)->role_id;

		$this -> template -> title(lang('projects').' - '.config_item('company_name'));
		$this -> page = lang('projects');

		
	}

	function add()
	{
		if ($this->input->post()) {
		if ($this->form_validation->run('projects','add_milestone') == FALSE)
		{
			 Applib::make_flashdata(array(
                        'form_error'=> validation_errors()
                        ));
                $this->applib->redirect_to('projects/view/' . $this->input->post('project') . '?group=milestones', 'error', lang('operation_failed'));
		}else{
			if ($this -> user_role == '1') {
			$project = $_POST['project'];
                        $_POST['start_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['start_date']), 'Y-m-d');
                        $_POST['due_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['due_date']), 'Y-m-d');
            Applib::create(Applib::$milestones_table,$_POST);

			$this->_log_activity('activity_added_new_milestone',$this->user,'projects',$project,$icon = 'fa-laptop',$_POST['milestone_name']); //log activity

			$this -> applib -> redirect_to('projects/view/'.$project.'?group=milestones','success',lang('milestone_added_successfully'));
			}
		}
		}else{
		$data['project'] = $this->uri->segment(4);
		$data['datepicker'] = TRUE;
		$this->load->view('modal/add_milestone',isset($data) ? $data : NULL);
		}
	}

	function add_task()
	{
		
		$data = array(
				'project' => $this->uri->segment(5),
				'milestone' => $this->uri->segment(4),
				'datepicker' => TRUE
				);
		$this->load->view('modal/add_milestone_task',isset($data) ? $data : NULL);
	}

	function edit()
	{
		if ($this->input->post()) {
		if ($this->form_validation->run('projects','add_milestone') == FALSE)
		{
			Applib::make_flashdata(array(
                        'form_error'=> validation_errors()
                        ));
                $this->applib->redirect_to('projects/view/' . $this->input->post('project') . '?group=milestones', 'error', lang('operation_failed'));
		}else{
			if ($this -> user_role == '1') {
			$project = $_POST['project'];
			$milestone = $_POST['id'];
                        $_POST['start_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['start_date']), 'Y-m-d');
                        $_POST['due_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['due_date']), 'Y-m-d');
            Applib::update(Applib::$milestones_table,array('id'=>$milestone),$_POST);
            $this->_log_activity('activity_edited_milestone',$this->user,'projects',$project,$icon = 'fa-pencil',$_POST['milestone_name']); //log activity

			$this -> applib -> redirect_to('projects/view/'.$project.'/?group=milestones&view=milestone&id='.$milestone,'success',lang('milestone_edited_successfully'));
			}
		}
		}else{
		$milestone = $this->uri->segment(4);
                $data['datepicker'] = TRUE;
		$data['details'] = Applib::retrieve(Applib::$milestones_table,array('id'=>$milestone));
		$this->load->view('modal/edit_milestone',isset($data) ? $data : NULL);
		}
	}

	function delete()
	{
		if ($this->input->post()) {
		$this->form_validation->set_rules('project', 'Project ID', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('delete_failed'));
				redirect('projects');
		}else{	
			$project = $this->input->post('project');
			$milestone = $this->input->post('id');
			$name = $this->db->where('id',$milestone)->get(Applib::$milestones_table)->row()->milestone_name;

			Applib::delete(Applib::$milestones_table,array('id'=>$milestone));

			$this->_log_activity('activity_deleted_milestone',$this->user,'projects',$project,$icon = 'fa-trash-o',$name); //log activity

			$this -> applib -> redirect_to('projects/view/'.$project.'?group=milestones','success',lang('milestone_deleted_successfully'));
		}
		}else{
			$data['project'] = $this->uri->segment(4);
			$data['milestone'] = $this->uri->segment(5);
			$this->load->view('modal/delete_milestone',$data);
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
}

/* End of file milestones.php */