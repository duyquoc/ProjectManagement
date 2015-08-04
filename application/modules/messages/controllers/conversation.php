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


class Conversation extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('msg_model');
	}

	function index($user_from = NULL)
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('messages').' - '.config_item('company_name'));
	$data['page'] = lang('messages');
	$data['user_from'] = $user_from;

	$this->template
	->set_layout('users')
	->build('conversations',isset($data) ? $data : NULL);
	}
	function send()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('user_to', 'User To', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('message_not_sent'));
				redirect($this->input->post('r_url'));
		}else{	
			$message = $this->input->post('message', TRUE);
			$user_to = $this->input->post('user_to', TRUE);

			foreach ($user_to as $key => $user) {
					$form_data = array(
			                'user_to' => $user,
			                'user_from' => $this->tank_auth->get_user_id(),
			                'message' => $this->input->post('message'),
			            );
					Applib::create(Applib::$messages_table,$form_data);
				
				if (config_item('notify_message_received') == 'TRUE') {
					$this->_message_notification($user,$message);
				}
				
			}

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message',lang('message_sent'));

			redirect($this->input->post('r_url'));
			}
		}else{
				$this->load->module('layouts');
				$this->load->library('template');
				$this->template->title(lang('messages').' - '.config_item('company_name'));
				$data['page'] = lang('messages');
				$data['form'] = TRUE;
				$data['clients'] = Applib::retrieve($user_table,array('role_id'=> 2,'activated' => 1));
				$data['admins'] = Applib::retrieve($user_table,array('role_id !='=> 2,'activated' => 1));
				$data['users'] = $this->msg_model->group_messages_by_users($this->tank_auth->get_user_id());
				$this->template
				->set_layout('users')
				->build('send_message',isset($data) ? $data : NULL);
		}
	}
	function delete()
	{
		if ($this->input->post()) {
				$this->load->library('form_validation');
				$this->form_validation->set_rules('msg_id', 'Msg ID', 'required');

				$r_url = $this->input->post('r_url', TRUE);
				$msg_id = $this->input->post('msg_id', TRUE);

				if ($this->form_validation->run() == FALSE)
				{
						$this->session->set_flashdata('response_status', 'error');
						$this->session->set_flashdata('message', lang('delete_failed'));
						redirect('messages/conversation/view/'.$r_url);
				}else{	
					$this->db->set('deleted', 'Yes');
					$this->db->where('msg_id',$msg_id)->update(Applib::$messages_table);

					$this->session->set_flashdata('response_status', 'success');
					$this->session->set_flashdata('message', lang('message_deleted_successfully'));
					redirect('messages/conversation/view/'.$r_url);
					}
		}else{
			$data['msg_id'] = $this->uri->segment(4)/1200;
			$data['r_url'] = $this->uri->segment(5);
			$this->load->view('modal/delete_message',$data);
		}
	}
	function _set_read($user_from){
			$this->db->set('status', 'Read');
			$this->db->where('user_to',$this->tank_auth->get_user_id());
			$this->db->where('user_from',$user_from)->update(Applib::$messages_table); 
	}



	function _message_notification($user_to,$message){
			$email_message = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'message_received'), 'template_body');

        	$subject = Applib::get_table_field(Applib::$email_templates_table,
        					array('email_group' => 'message_received'),'subject');

			$recipient = Applib::login_info($user_to)->email;

			$email_recipient = str_replace("{RECIPIENT}",$recipient,$email_message);
			$sender = str_replace("{SENDER}",$this -> tank_auth -> get_username(),$email_recipient);
			$site_url = str_replace("{SITE_URL}",base_url(),$sender);
			$msg = str_replace("{MESSAGE}",$message,$site_url);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$msg);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);
			

			$params['recipient'] = $recipient;

			$params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;

			$params['message'] = $message;

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}
}

/* End of file conversation.php */