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


class Notebook extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		if (!$this -> user) {
			$this -> applib -> redirect_to('auth/login','error',lang('access_denied'));			
		}
	}

	
	function savenote()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('project', 'Project', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this -> applib -> redirect_to('projects/view/'.$_POST['project'].'/?group=notes','error',lang('error_in_form'));
		}else{		
			$project = $this->input->post('project', TRUE);	
			$form_data = array(
			                'notes' => $this->input->post('notes')						
			            );
			$this->db->where('project_id',$project)->update('projects', $form_data);
			$this -> applib -> redirect_to('projects/view/'.$project.'/?group=notes','success',lang('note_saved_successfully')); 
			}
		}else{
			redirect('projects');
		}
	}
}

/* End of file project_home.php */