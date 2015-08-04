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
		$this->load->model('client_model','user');
	}
	function details()
	{		
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('clients').' - '.$this->config->item('company_name'));
		$data['page'] = lang('clients');
		$data['clients'] = $this->user->get_all_records($table = 'users',
		$array = array(
			'role_id'=>'2','activated' => '1'),$join_table = 'account_details',$join_criteria = 'account_details.user_id = users.id','created');
		$data['user_details'] = $this->user->user_details($this->uri->segment(4));
		$data['user_invoices'] = $this->user->user_invoices($this->uri->segment(4));
		$data['user_projects'] = $this->user->user_projects($this->uri->segment(4));
		$this->template
		->set_layout('users')
		->build('client_details',isset($data) ? $data : NULL);
	}
	function update()
	{
		if ($this->input->post()) {
		Applib::is_demo();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('company', 'Company', 'required');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		$company = $this->input->post('company');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('companies/view/details/'.$company);
		}else{	
			$user_id =  $this->input->post('user_id');
			$args = array(
			                'fullname' => $this->input->post('fullname'),
                            'company' => $this->input->post('company'),
			                'phone' => $this->input->post('phone'),		
			                'language' => $this->input->post('language'),		               
			                'mobile' => $this->input->post('mobile'),		               
			                'skype' => $this->input->post('skype'),		               
			                'locale' => $this->input->post('locale'),	               
			            );
			Applib::update(Applib::$profile_table,array('user_id' => $user_id),$args);

			$user_data = array(
			                'email' => $this->input->post('email'),
			                'modified' => date("Y-m-d H:i:s")
			                );
			Applib::update(Applib::$user_table,array('id' => $user_id),$user_data);

			$params['user'] = $this->tank_auth->get_user_id();
			$params['module'] = 'contacts';
			$params['module_field_id'] = $user_id;
			$params['activity'] = 'activity_contact_edited';
			$params['icon'] = 'fa-edit';
            $params['value1'] = $this->input->post('fullname');
            Applib::create(Applib::$activities_table,$params);

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('user_edited_successfully'));
			redirect('companies/view/details/'.$company);
		}
		}else{
		$data['languages'] = $this->applib->languages();
		$data['locales'] = $this->applib->locales();
		$data['user_details'] = $this->user->user_details($this->uri->segment(4));
		$this->load->view('modal/edit_contact',$data);
		}
	}
	function clientinvoices()
	{		
		$data['user_invoices'] = $this->user->user_invoices($this->uri->segment(4));
		$this->load->view('client_invoices',isset($data) ? $data : NULL);
	}
	function clientprojects()
	{	
		$data['user_projects'] = $this->user->user_projects($this->uri->segment(4));
		$this->load->view('client_projects',isset($data) ? $data : NULL);
	}
	function payments()
	{		
		$data['user_payments'] = $this->user->user_payments($this->uri->segment(4));
		$this->load->view('client_payments',isset($data) ? $data : NULL);
	}
	function activities()
	{		
		$data['user_activities'] = $this->user->user_activities($this->uri->segment(4),$limit = 10);
		$this->load->view('client_activities',isset($data) ? $data : NULL);
	}
}

/* End of file view.php */