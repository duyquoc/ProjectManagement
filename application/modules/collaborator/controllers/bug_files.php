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

class Bug_files extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'collaborator') {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->load->model('bugs/bugs_model','bug');
	}
	function add()
	{		
		if ($this->input->post()) {
			$bug = $this->input->post('bug', TRUE);
			$description = $this->input->post('description', TRUE);
			$assigned_to = $this->input->post('assigned_to', TRUE);
						$this->load->library('form_validation');
						$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
						$this->form_validation->set_rules('description', 'Description', 'required');

						if ($this->form_validation->run() == FALSE)
						{
								$this->session->set_flashdata('response_status', 'error');
								$this->session->set_flashdata('message', lang('error_in_form'));
								redirect('collaborator/bug_view/details/'.$bug);
						}else{

								if ($this->config->item('demo_mode') == 'FALSE') {
								$config['upload_path'] = './resource/bug-files/';
									$config['allowed_types'] = $this->config->item('allowed_files');
									$config['max_size']	= $this->config->item('file_max_size');
									$config['file_name'] = 'PROJECT-BUG-'.$this->input->post('issue_ref', TRUE).'-0';
									$config['overwrite'] = FALSE;

									$this->load->library('upload', $config);

									if ( ! $this->upload->do_upload())
									{
										$this->session->set_flashdata('response_status', 'error');
										$this->session->set_flashdata('message',$this->lang->line('operation_failed'));
										redirect('collaborator/bug_view/details/'.$bug);
									}
									else
									{
										$data = $this->upload->data();
										$file_id = $this->bug->insert_file($data['file_name'],$bug,$description);
										$filelink = '<a href="'.base_url().'resource/bug-files/'.$data['file_name'].'" target="_blank">'.$this->applib->short_string($data['file_name'], 10, 7, 20).'</a>';
										
										$this->_log_activity($bug,'activity_uploaded_a_file',$icon='fa-file',$filelink); //log activity
			

										$this->_upload_notification($bug,$assigned_to);

										$this->session->set_flashdata('response_status', 'success');
										$this->session->set_flashdata('message',$this->lang->line('file_uploaded_successfully'));
										redirect('collaborator/bug_view/details/'.$bug);
									}
								} else {
									$this->session->set_flashdata('response_status', 'error');
									$this->session->set_flashdata('message',$this->lang->line('demo_warning'));
										redirect('collaborator/bug_view/details/'.$bug);
								}
					}
		}else{
			$bug = $this->uri->segment(4)/1200;
			$bug_details = $this->bug->bug_details($bug);
			foreach ($bug_details as $key => $p) {
				$issue_ref = $p->issue_ref;
				$assigned_to = $p->assigned_to;
			}
		$data['issue_ref'] = $issue_ref;
		$data['bug'] = $bug;
		$data['assigned_to'] = $assigned_to;
		$this->load->view('modal/bug_file',isset($data) ? $data : NULL);
	}
}
	function download()
	{
	$this->load->helper('download');
	$file_id = $this->uri->segment(4)/1800;
	$bug = $this->uri->segment(5)/1200;
		if ($this->bug->get_file($file_id))
			{
			$file = $this->bug->get_file($file_id);
			if(file_exists('./resource/bug-files/'.$file->file_name)){
			$data = file_get_contents('./resource/bug-files/'.$file->file_name); // Read the file's contents
			force_download($file->file_name, $data);
		}else{
			$this->session->set_flashdata('message',$this->lang->line('operation_failed'));
				redirect('bugs/view/details/'.$bug);
			}
		}
		else
		{
			$this->session->set_flashdata('message',$this->lang->line('operation_failed'));
				redirect('bugs/view/details/'.$bug);
		}
	}
	function delete()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('file', 'File ID', 'required');
		$this->form_validation->set_rules('bug', 'Bug ID', 'required');

		$bug = $this->input->post('bug', TRUE);
		$file_id = $this->input->post('file', TRUE);

		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('delete_failed'));
				redirect('collaborator/bug_view/details/'.$bug);
		}else{			
			$file = $this->bug->get_file($file_id);
			unlink('./resource/bug-files/'.$file->file_name);
			$this->db->delete('bug_files', array('file_id' => $file_id)); 

			$this->_log_activity($bug,'activity_deleted_a_file',$icon='fa-times',$file->file_name); //log activity
			
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('file_deleted'));
			redirect('collaborator/bug_view/details/'.$bug);
			}
		}else{
			$data['file_id'] = $this->uri->segment(4)/1800;
			$data['bug'] = $this->uri->segment(5)/1200;
			$this->load->view('bugs/delete_bug_file',$data);
		}
	}
	function _upload_notification($bug,$assigned_to){

			$bug_details = $this->bug->bug_details($bug);
			foreach ($bug_details as $key => $p) {
				$issue_ref = $p->issue_ref;
			}

			$upload_user = $this->user_profile->get_user_details($this->tank_auth->get_user_id(),'username');
			
			$data['project_title'] = $this->user_profile->get_project_details($p->project,'project_title');
			$data['upload_user'] = $upload_user;
			$data['issue_ref'] = $issue_ref;

			$params['recipient'] = $this->user_profile->get_user_details($assigned_to,'email');

			$params['subject'] = '[ '.$this->config->item('company_name').' ]'.' New File Uploaded';
			$params['message'] = $this->load->view('emails/upload_notification',$data,TRUE);

			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}
	function _log_activity($bug,$activity,$icon,$value1='',$value2=''){
			$this->db->set('module', 'bugs');
			$this->db->set('module_field_id', $bug);
			$this->db->set('user', $this->tank_auth->get_user_id());
			$this->db->set('activity', $activity);
			$this->db->set('icon', $icon);
                        $this->db->set('value1', $value1);
			$this->db->set('value2', $value2);
			$this->db->insert('activities'); 
	}
}

/* End of file bug_files.php */