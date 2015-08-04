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


class Calendar extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library(array('tank_auth'));
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
                
	}

	function index()
	{
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('calendar'));
                $data['fullcalendar'] = TRUE;
                $data['page'] = lang('calendar');
                $data['role'] = $this->tank_auth->get_role_id();
                $this->template
                ->set_layout('users')
                ->build('calendar',isset($data) ? $data : NULL);

	}
	function settings()
	{
                if ($_POST) {
                    $this->db->where('config_key','gcal_api_key')->update('config',array('value' => $_POST['gcal_api_key']));
                    $this->db->where('config_key','gcal_id')->update('config',array('value' => $_POST['gcal_id']));
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->load->view('modal/calendar-settings',isset($data) ? $data : NULL);
                }
	}
	function update()
	{
		
	}
}

/* End of file contacts.php */