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
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
        $data['role'] = Applib::login_info($this->session->userdata('user_id'))->role_id;
        $data['currencies'] = $this -> applib -> currencies();
        $data['languages'] = $this -> applib -> languages();
		$data['client_details'] = $this->user->client_details($this->uri->segment(4));
		$data['client_invoices'] = $this->user->client_invoices($this->uri->segment(4));
		$data['client_projects'] = $this->user->client_projects($this->uri->segment(4));
		$data['client_links'] = $this->user->client_links($this->uri->segment(4));
		$data['client_contacts'] = $this->user->client_contacts($this->uri->segment(4));
		$data['countries'] = Applib::retrieve('countries',array('id >' => '0'));
		$this->template
		->set_layout('users')
		->build('client_details',isset($data) ? $data : NULL);
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
	function delete()
	{
		if ($this->input->post()) {

			$company = $this->input->post('company', TRUE);
			$company_invoices = $this->AppModel->get_all_records($table = 'invoices',
								$array = array(
								'client' => $company),$join_table = '',$join_criteria = '','date_saved');
			if (!empty($company_invoices)) {
				foreach ($company_invoices as $invoice) {
					$this->db->where('invoice_id',$invoice->inv_id)->delete('items'); //delete invoice items
				}
			}

			$this->db->where('client',$company)->delete('invoices'); //delete invoices

			$this->db->where('paid_by',$company)->delete('payments'); //delete invoice payments

			$this->db->where(array('module'=>'Clients', 'module_field_id' => $company))->delete('activities'); //clear invoice activities
			$this->db->where('co_id',$company)->delete('companies'); //delete invoice items
			$company_contacts = $this->AppModel->get_all_records($table = 'account_details',
								$array = array(
								'company' => $company),$join_table = '',$join_criteria = '','id');
			if (!empty($company_contacts)) {
				foreach ($company_contacts as $contact) {
					$this->db->set('company','-');
					$this->db->where('company',$company)->update('account_details'); //set contacts to blank
				}
			}

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('company_deleted_successfully'));
			redirect('companies');
		}else{
			$data['company_id'] = $this->uri->segment(4);
			$this->load->view('modal/delete',$data);

		}
	}
}

/* End of file view.php */