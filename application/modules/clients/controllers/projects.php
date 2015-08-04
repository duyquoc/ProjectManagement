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


class Projects extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		$this->user_company = Applib::profile_info($this->user)->company;

		if (!$this -> user) {
			$this -> applib -> redirect_to('auth/login','error',lang('access_denied'));			
		}
		$this -> template -> title(lang('projects').' - '.config_item('company_name'));
		$this -> page = lang('projects');

        $this->load->model('projects/c_model', 'mdl_project');

        $this->projects_table = 'projects';
        $this->clients_table = 'companies';
        $this->activities_table = 'activities';
        $this->comments_table = 'comments';
        $this->users_table = 'users';

		$this -> project_list = $this -> db -> where('client',$this->user_company) -> get($this->projects_table) -> result();
	}

	function index()
	{
	$data['page'] = $this->page;
	$data['datatables'] = TRUE;
	$data['projects'] = $this->project_list;
	$this->template
	->set_layout('users')
	->build('projects/projects',isset($data) ? $data : NULL);
	}

	function search()
	{
		if ($this->input->post()) {
				$this->load->module('layouts');
				$this->load->library('template');
				$this->template->title(lang('projects').' - '.$this->config->item('company_name'));
				$data['page'] = lang('projects');
				$keyword = $this->input->post('keyword', TRUE);
				$data['projects'] = $this->AppModel->search_project(
					$keyword,
					$where = array('client' => $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'),
						));
				$this->template
				->set_layout('users')
				->build('projects/projects',isset($data) ? $data : NULL);
			
		}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('enter_search_keyword'));
			redirect('clients/projects');
		}
	
	}
	function details()
	{		
		if($this->_project_access($this->uri->segment(4))){
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('projects').' - '.$this->config->item('company_name'));
		$data['page'] = lang('projects');
		$project = $this->uri->segment(4);
		$data['project_details'] = $this->AppModel->get_all_records('projects',
			array('project_id' => $project),'','','date_created');
		$data['project_activities'] = $this->AppModel->get_all_records('activities',
			array(
				'module'=> 'projects',
				'module_field_id' => $project)
			,'users','users.id = activities.user','activity_date');
		$data['project_comments'] = $this->AppModel->get_all_records('comments',
			array(
			'project'=> $project, 
			'deleted' => 'No')
			,'','','date_posted');
		$data['project_tasks'] = $this->AppModel->get_all_records('tasks',
			array('visible' => 'Yes', 'project'=>$project),'','','date_added');
		$data['project_files'] = $this->AppModel->get_all_records('files',
			array('project'=>$project),'users','users.id = files.uploaded_by','date_posted');
		$this->template
		->set_layout('users')
		->build('projects/project_details',isset($data) ? $data : NULL);
		}else{
			$this->session->set_flashdata('message', lang('project_access_denied'));
			redirect('clients/projects');
		}
	}
	function comment()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('comment', 'Comment', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('comment_failed'));
				redirect('clients/projects/details/'.$this->input->get('project',TRUE));
		}else{		
		$project_id = $this->input->post('project_id');	
			$form_data = array(
			                'project' => $project_id,
			                'posted_by' => $this->tank_auth->get_user_id(),
			                'message' => $this->input->post('comment')
			            );
			$this->db->insert('comments', $form_data); 
			$this->_log_activity($project_id,'activity_project_comment_added',$icon='fa-comment',$this->input->post('project_code')); //log activity

			$this->_comment_notification($project_id); //send notification to the administrator

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('comment_successful'));
			redirect('clients/projects/details/'.$this->input->get('project',TRUE));
			}
		}else{
		redirect('clients/projects');
		}
	}
	function replies()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('message', 'Message', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('comment_failed'));
				redirect('clients/projects/details/'.$this->input->post('project',TRUE));
		}else{		
		$project_id = $this->input->post('project');	
			$form_data = array(
			                'parent_comment' => $this->input->post('comment', TRUE),
			                'reply_msg' => $this->input->post('message'),
			                'replied_by' => $this->tank_auth->get_user_id()
			            );
			$this->db->insert('comment_replies', $form_data); 

			$this->_comment_notification($project_id); //send notification to the administrator

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('comment_replied_successful'));
			redirect('clients/projects/details/'.$this->input->post('project',TRUE));
			}
		}else{
		$data['comment'] = $this->input->get('c', TRUE);
		$data['project'] = $this->input->get('p', TRUE);
		$this->load->view('modal/comment_reply',isset($data) ? $data : NULL);
		}
	}
	function delcomment()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('comment', 'Comment ID', 'required');
		$this->form_validation->set_rules('project', 'Project ID', 'required');
		$project_id = $this->input->post('project', TRUE);
		$comment_id = $this->input->post('comment', TRUE);

		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('comment_delete_failed'));
				redirect('clients/projects/details/'.$project_id);
		}else{			
			$this->db->set('deleted', 'Yes');
			$this->db->where('comment_id',$comment_id)->update('comments'); 
	
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('comment_deleted'));
			redirect('clients/projects/details/'.$project_id);
			}
		}else{
			$data['comment_id'] = $this->input->get('c', TRUE);
			$data['project_id'] = $this->input->get('p', TRUE);
			$this->load->view('modal/delete_comment',$data);
		}
	}
	

	function _comment_notification($project){
			$project_title = $this->applib->get_project_details($project,'project_title');
			$client = $this->applib->get_project_details($project,'client');
			$client_email = $this->applib->company_details($client,'company_email');

			$posted_by = $this->user_profile->get_user_details($this->tank_auth->get_user_id(),'username');
			$data['project_title'] = $project_title;
			$data['posted_by'] = $posted_by;

			$params['recipient'] = $this->user_profile->get_user_details($client_email,'email');

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' New comment received from '.$posted_by;
			$params['message'] = $this->load->view('emails/project_comment',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}

	function _project_access($project){
		$client = $this->user_profile->get_project_details($project,'client');
		$user = $this->tank_auth->get_user_id();
		$user_company = $this->user_profile->get_profile_details($user,'company');
		if ($client == $user_company) {
			return TRUE;
		}else{
			return FALSE;
		}
	}
			

	function _log_activity($project_id,$activity,$icon,$value1='',$value2=''){
			$this->db->set('module', 'projects');
			$this->db->set('module_field_id', $project_id);
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', $activity);
			$this->db->set('icon', $icon);
                        $this->db->set('value1', $value1);
			$this->db->set('value2', $value2);
			$this->db->insert('activities'); 
	}
}

/* End of file projects.php */