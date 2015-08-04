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


class Bug_tabs extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'client') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('bugs/bugs_model','bug');
	}
	function activities()
	{		
		$data['activities'] = $this->bug->bug_activities($this->uri->segment(4));
		$this->load->view('bugs/activities',isset($data) ? $data : NULL);
	}
	function details()
	{		
		$data['bug_details'] = $this->bug->bug_details($this->uri->segment(4));
		$this->load->view('bugs/details',isset($data) ? $data : NULL);
	}
	function attachments()
	{		
		$data['bug_files'] = $this->bug->bug_files($this->uri->segment(4));
		$this->load->view('bugs/files',isset($data) ? $data : NULL);
	}
}

/* End of file bug_tabs.php */