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


class Messages extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->module('layouts');
		$this->load->library(array('template','tank_auth'));
		$this->template->title(lang('messages').' - '.config_item('company_name'));
		$this->page = lang('messages');

		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->group = isset($_GET['group']) ? $_GET['group'] : 'inbox';
	}

	function index()
	{

	$data['page'] = $this->page;
	$data['group'] = $this->group;
	switch ($data['group']) {
		case 'inbox':
			$data['messages'] = $this->db->where(
								array(
									'user_to' => $this->tank_auth->get_user_id(),
									'deleted' => 'No'
								))->group_by('user_from')->get(Applib::$messages_table)->result();
			break;
		case 'sent':
				$data['messages'] = $this->db->where(
								array(
									'user_from' => $this->tank_auth->get_user_id(),
									'deleted' => 'No'
								))->group_by('user_to')->get(Applib::$messages_table)->result();
				break;
		case 'favourites':
				$data['messages'] = $this->db->where(
								array(
									'user_to' => $this->tank_auth->get_user_id(),
									'favourite' => '1',
									'deleted' => 'No'
								))->group_by('user_from')->get(Applib::$messages_table)->result();
				break;
		
		default:
			$data['messages'] = $this->db->where(
								array(
									'user_to' => $this->tank_auth->get_user_id(),
									'deleted' => 'Yes'
								))->group_by('user_from')->get(Applib::$messages_table)->result();
			break;
	}
	

	$this->template
	->set_layout('users')
	->build('messages',isset($data) ? $data : NULL);
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
			Applib::make_flashdata(array(
				'response_status' => 'error',
				'message' => lang('message_not_sent'),
				'form_error' => validation_errors()
				));
				redirect($_SERVER['HTTP_REFERER']);
		}else{	
			$message = $this->input->post('message', TRUE);
			$user_to = $this->input->post('user_to', TRUE);

			foreach ($user_to as $key => $user) {
					$form_data = array(
			                'user_to' => $user,
			                'user_from' => $this->tank_auth->get_user_id(),
			                'message' => $this->input->post('message'),
			            );
				Applib::create(Applib::$messages_table, $form_data);
				
				if (config_item('notify_message_received') == 'TRUE') {
					$this->_message_notification($user,$message);
				}
				
			}

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message',lang('message_sent'));

			redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
				
				$data['page'] = $this->page;
				$data['form'] = TRUE;
				$data['group'] = $this->group;
				$data['clients'] = $this->db->where(array(
											'role_id' 	=> 2,
											'activated' => 1
												))->get(Applib::$user_table)->result();

				$data['admins'] = $this->db->where(array(
											'role_id !=' 	=> 2,
											'activated' 	=> 1
												))->get(Applib::$user_table)->result();

				$this->template
				->set_layout('users')
				->build('send_message',isset($data) ? $data : NULL);
		}
	}

	function view($user_from = NULL)
	{
	
	$data['page'] = $this->page;
	$data['user_from'] = $user_from;
	$data['group'] = $this->group;

	$this->_set_read($user_from);

	$this->template
	->set_layout('users')
	->build('conversations',isset($data) ? $data : NULL);
	}

	function favourite($msg = NULL){
		$status = Applib::get_table_field(Applib::$messages_table,array('msg_id' => $msg),'favourite');
		($status == '1') ? $status = 0 : $status = 1 ;
		($status == '1') ? $response = 'Message favourited' : $response = 'Removed from favourites';
		$data = array('favourite' => $status);

		Applib::update(Applib::$messages_table,array('msg_id' => $msg),$data);

		$this->session->set_flashdata('response_status', 'success');
		$this->session->set_flashdata('message', $response);
		redirect($_SERVER['HTTP_REFERER']);
	}

	function restore($msg = NULL){

		$data = array('deleted' => 'No');
		Applib::update(Applib::$messages_table,array('msg_id' => $msg),$data);
		
		$this->session->set_flashdata('response_status', 'success');
		$this->session->set_flashdata('message', 'Message restored');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function remove($msg = NULL){

		Applib::delete(Applib::$messages_table,array('msg_id' => $msg));
		
		$this->session->set_flashdata('response_status', 'success');
		$this->session->set_flashdata('message', 'Message deleted');
		redirect($_SERVER['HTTP_REFERER']);
	}


	function _set_read($user_from){
			$this->db->set('status', 'Read');
			$this->db->where('user_to',$this->tank_auth->get_user_id());
			$this->db->where('user_from',$user_from)->update('messages'); 
	}


	function _message_notification($user_to,$message){

		$email_message = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'message_received'), 'template_body');

        $subject = Applib::get_table_field(Applib::$email_templates_table,
        					array('email_group' => 'message_received'),'subject');

		$recipient =Applib::login_info($user_to)->email;

		$email_recipient = str_replace("{RECIPIENT}",$recipient,$email_message);
		$sender = str_replace("{SENDER}",$this -> tank_auth -> get_username(),$email_recipient);
		$site_url = str_replace("{SITE_URL}",base_url(),$sender);
		$msg = str_replace("{MESSAGE}",$message,$site_url);
		$message = str_replace("{SITE_NAME}",config_item('company_name'),$msg);

		$data['message'] = $message;
		$message = $this->load->view('email_template', $data, TRUE);
			

		$params['recipient'] = $recipient;

		$params['subject'] = $subject;

		$params['message'] = $message;

		$params['attached_file'] = '';

		modules::run('fomailer/send_email',$params);
	}



	

	function delete($msg_id = NULL)
	{
		if ($this->input->post()) {
				$this->load->library('form_validation');
				$this->form_validation->set_rules('msg_id', 'Msg ID', 'required');

				$msg_id = $this->input->post('msg_id', TRUE);

				if ($this->form_validation->run() == FALSE)
				{
						$this->session->set_flashdata('response_status', 'error');
						$this->session->set_flashdata('message', lang('delete_failed'));
						redirect($_SERVER['HTTP_REFERER']);
				}else{	
					$this->db->set('deleted', 'Yes');
					$this->db->where('msg_id',$msg_id)->update(Applib::$messages_table);

					$this->session->set_flashdata('response_status', 'success');
					$this->session->set_flashdata('message', lang('message_deleted_successfully'));
					redirect($_SERVER['HTTP_REFERER']);
					}
		}else{
			$data['msg_id'] = $msg_id;
			$this->load->view('modal/delete_message',$data);
		}
	}

	function search()
	{
		$this->load->model('msg_model');
		if ($this->input->post()) {
				$data['page'] = $this->page;
				$data['group'] = 'inbox';
				$keyword = $this->input->post('keyword', TRUE);
				$data['messages'] = $this->msg_model->search_message($keyword);
				$data['users'] = $this->msg_model->group_messages_by_users($this->tank_auth->get_user_id());
				$this->template
				->set_layout('users')
				->build('messages',isset($data) ? $data : NULL);
			
		}else{
			redirect('messages');
		}
	
	}
}

/* End of file messages.php */