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


class Profile extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if (!$this->tank_auth->get_username()) {
			$this->session->set_flashdata('message', 'You are not allowed to access this page. Please contact the system admin for assistance.');
			redirect('');
		}
		$this->load->model('profile_model');
	}
	function index(){
		redirect('profile/settings');
	}

	function settings()
	{
		if($_POST){
			if ($this->config->item('demo_mode') == 'TRUE') {
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('demo_warning'));
			 redirect('profile/settings');
			}
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

		if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		                {
		                	$this->session->set_flashdata('response_status', 'error');
							$this->session->set_flashdata('message',lang('error_in_form'));
							$_POST = '';
							$this->settings();
		                    //redirect('profile/settings');
		                }else{ 
                                    if (isset($_POST['company_data'])) {
                                    $company_data = $_POST['company_data'];
                                    $this->db->where('co_id', $_POST['co_id']);
                                        $this->db->update('companies', $company_data); 
                                        unset($_POST['company_data']);
                                    }
                                        unset($_POST['co_id']);
                                    $form_data = $_POST;

                                    $this->db->where('user_id', $this->tank_auth->get_user_id());
                                        $this->db->update('account_details', $form_data); 

                                        $this->session->set_flashdata('response_status', 'success');
                                        $this->session->set_flashdata('message',lang('profile_updated_successfully'));
                                    redirect('profile/settings');
		                }


		}else{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('profile').' - '.$this->config->item('company_name'));
	$data['page'] = lang('home');
	$data['form'] = TRUE;
        $data['languages'] = $this->applib->languages();
        $data['locales'] = $this->applib->locales();
	$data['profile'] = $this->profile_model->get_all_records($table = 'account_details',
		$array = array('user_id' =>$this->tank_auth->get_user_id()),
				$join_table = '',$join_criteria = '');
	$data['countries'] = $this->profile_model->get_all($table = 'countries');
	$this->template
	->set_layout('users')
	->build('edit_profile',isset($data) ? $data : NULL);
	}
	}

	function changeavatar()
	{		


		if ($this->input->post()) {
						
						Applib::is_demo();

							if(file_exists($_FILES['userfile']['tmp_name']) || is_uploaded_file($_FILES['userfile']['tmp_name'])) {

							$config['upload_path'] = './resource/avatar/';
							$config['allowed_types'] = 'gif|jpg|png|jpeg';
							$config['file_name'] = strtoupper('USER-'.$this->tank_auth->get_username()).'-AVATAR';
							$config['overwrite'] = TRUE;

							$this->load->library('upload', $config);

							if ( ! $this->upload->do_upload())
									{
										$this->session->set_flashdata('response_status', 'error');
										$this->session->set_flashdata('message',lang('avatar_upload_error'));
										redirect($this->input->post('r_url', TRUE));
									}
									else
									{
										$data = $this->upload->data();
										$file_name = $this->profile_model->update_avatar($data['file_name']);
										
									}
								}

				if(isset($_POST['use_gravatar']) AND $_POST['use_gravatar'] == 'on'){

				$this->db->where('user_id',$this->tank_auth->get_user_id())
						 ->set('use_gravatar', 'Y')
						 ->update(Applib::$profile_table);

				}else{ 

				$this->db->where('user_id',$this->tank_auth->get_user_id())
						 ->set('use_gravatar', 'N')
						 ->update(Applib::$profile_table);
					}

										$this->session->set_flashdata('response_status', 'success');
										$this->session->set_flashdata('message',lang('avatar_uploaded_successfully'));
										redirect($this->input->post('r_url', TRUE));

					
			}else{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('no_avatar_selected'));
				redirect('profile/settings');
		}
	}

	function activities()
	{
	$this->load->model('profile_model');
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('profile').' - '.$this->config->item('company_name'));
	$data['page'] = lang('home');
	$data['datatables'] = TRUE;
	$data['activities'] = $this->profile_model->activities($this->tank_auth->get_user_id());
        $data['lastseen'] = config_item('last_seen_activities');
        $this->db->where('config_key','last_seen_activities')->update('config',array('value'=>time()));
	$this->template
	->set_layout('users')
	->build('activities',isset($data) ? $data : NULL);
	}

	function help()
	{
	$this->load->model('profile_model');
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('profile').' - '.$this->config->item('company_name'));
	$data['page'] = lang('home');
	$this->template
	->set_layout('users')
	->build('intro',isset($data) ? $data : NULL);
	}
}

/* End of file profile.php */