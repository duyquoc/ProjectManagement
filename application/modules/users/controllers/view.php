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
		$this->load->model('user_model','user');
	}
	
	function update()
	{
		if ($this->input->post()) {
			if ($this->config->item('demo_mode') == 'TRUE') {
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('demo_warning'));
			redirect('users/account');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('users/account');
		}else{	
		$user_id =  $this->input->post('user_id');
			$profile_data = array(
			                'fullname' => $this->input->post('fullname'),
                            'company' => $this->input->post('company'),
			                'phone' => $this->input->post('phone'),		
			                'language' => $this->input->post('language'),		               
			                'locale' => $this->input->post('locale'),		               
			            );
			if (isset($_POST['department'])) {
				$profile_data['department'] = $_POST['department'];
			}

			$this->db->where('user_id',$user_id)->update('account_details', $profile_data); 

					$params['user'] = $this->tank_auth->get_user_id();
					$params['module'] = 'Users';
					$params['module_field_id'] = $user_id;
					$params['activity'] = 'activity_updated_system_user';
					$params['icon'] = 'fa-edit';
					$params['value1'] = $this->input->post('fullname');
					modules::run('activity/log',$params); //log activity

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('user_edited_successfully'));
			redirect('users/account');
		}
		}else{
		$data['user_details'] = $this->user->user_details($this->uri->segment(4));
		$data['languages'] = $this->applib->languages();
		$data['locales'] = $this->applib->locales();
		$data['roles'] = $this->user->roles();
		$data['companies'] = $this->AppModel->get_all_records($table = 'companies',
		$array = array(
			'co_id >' => '0'),$join_table = '',$join_criteria = '','date_added');
		$this->load->view('modal/edit_user',$data);
		}
	}

	function _log_user_activity($activity,$icon){
			$this->db->set('module', 'users');
			$this->db->set('module_field_id', $this->tank_auth->get_user_id());
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', $activity);
			$this->db->set('icon', $icon);
			$this->db->insert('activities'); 
	}
}

/* End of file view.php */