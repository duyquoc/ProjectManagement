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


class Account extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('user_model');
	}
	function index(){
		$this->active();
	}

	function active()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('users').' - '.$this->config->item('company_name'));
	$data['page'] = lang('users');
	$data['datatables'] = TRUE;
	$data['form'] = TRUE;
	$data['users'] = $this->user_model->users();
	$data['roles'] = $this->user_model->roles();
	$data['companies'] = $this->AppModel->get_all_records($table = 'companies',
		$array = array(
			'co_id >' => '0'),$join_table = '',$join_criteria = '','date_added');
	$this->template
	->set_layout('users')
	->build('users',isset($data) ? $data : NULL);
	}

	function auth()
	{
		if ($this->input->post()) {
			if ($this->config->item('demo_mode') == 'TRUE') {
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('demo_warning'));
			redirect('users/account');
		}
		$user_password = $this->input->post('password');
		$username = $this->input->post('username');

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('username', 'User Name', 'required|trim|xss_clean');

		if(!empty($user_password)) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
        }
		
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('users/account');
		}else{				

			$user_id =  $this->input->post('user_id');
			$args = array(
			                'email' 	=> $this->input->post('email'),
			                'role_id' 	=> $this->input->post('role_id'),
			                'modified' 	=> date("Y-m-d H:i:s")             
			            );

			$db_debug = $this->db->db_debug; //save setting
			$this->db->db_debug = FALSE; //disable debugging for queries
			$result = $this->db->set('username',$username)
							   ->where('id',$user_id)
							   ->update(Applib::$user_table); //run query
			$this->db->db_debug = $db_debug; //restore setting

			if(!$result){
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('username_not_available'));
				redirect('users/account');	
			}

			Applib::update(Applib::$user_table,array('id' => $user_id), $args);

			if(!empty($user_password)) {
                $this->tank_auth->set_new_password($user_id,$user_password);
            }
            $name = Applib::profile_info($user_id)->fullname 
            		? Applib::profile_info($user_id)->fullname 
            		: Applib::login_info($user_id)->username;

            $args = array(
            	'user' => $this->tank_auth->get_user_id(),
            	'module' => 'Users',
            	'module_field_id' => $user_id,
            	'activity' => 'activity_updated_system_user',
            	'icon' => 'fa-edit',
            	'value1' => $name
            	);
            Applib::create(Applib::$activities_table,$args);

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('user_edited_successfully'));
			redirect('users/account');
		}
		}else{
		$data['user_details'] = $this-> user_model ->user_details($this->uri->segment(4));
		$data['roles'] = $this-> user_model -> roles();
		$data['companies'] = Applib::retrieve(Applib::$companies_table,array('co_id >' => '0'));
		$this->load->view('modal/edit_login',$data);
		}
	}


	

	function delete()
	{
		if ($this->input->post()) {

			if (config_item('demo_mode') == 'TRUE') {
				Applib::make_flashdata(array(
					'response_status' => 'error',
					'message' => lang('demo_warning')
					));
			redirect($this->input->post('r_url'));
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_id', 'User ID', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('delete_failed'));
				$this->input->post('r_url');
		}else{	
			$user = $this->input->post('user_id');

			if (Applib::profile_info($user)->avatar != 'default_avatar.jpg') {
				unlink('./resource/avatar/'.Applib::profile_info($user)->avatar);
			}			


			Applib::delete(Applib::$comments_table, array('posted_by' => $user)); 
			Applib::delete(Applib::$messages_table, array('user_to' => $user)); 
			Applib::delete(Applib::$assign_tasks_table, array('assigned_user' => $user)); 
			Applib::delete(Applib::$assigned_projects_table, array('assigned_user' => $user)); 
			Applib::delete(Applib::$activities_table, array('user' => $user));  

			Applib::delete(Applib::$profile_table, array('user_id' => $user)); 
			Applib::delete(Applib::$user_table, array('id' => $user)); 
			// Log Activity
			$args = array(
				'user'	=> $this->tank_auth->get_user_id(),
				'module' => 'users',
				'module_field_id' => $user,
				'activity' => 'activity_deleted_system_user',
				'icon'		=> 'fa-trash-o'
				);
			Applib::create(Applib::$activities_table,$args);
			
			Applib::make_flashdata(array(
					'response_status' => 'success',
					'message' => lang('user_deleted_successfully')
					));
			redirect($this->input->post('r_url'));
		}
		}else{
			$data['user_id'] = $this->uri->segment(4);
			$this->load->view('modal/delete_user',$data);
		}
	}
}

/* End of file account.php */