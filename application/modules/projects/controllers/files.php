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


class Files extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		if (!$this -> user) {
			$this -> applib -> redirect_to('login','error',lang('access_denied'));			
		}
		$this -> template -> title(lang('projects').' - '.config_item('company_name'). ' '.config_item('version'));
		$this -> page = lang('projects');

		$this -> project_list = Applib::retrieve(Applib::$projects_table,array('project_id !='=>'0'));
	}
	function add()
	{		
		if ($this->input->post()) {
			$project = $this->input->post('project', TRUE);
			$description = $this->input->post('description', TRUE);


			Applib::is_demo();

					$p = $this->db->where('project_id',$project)->get(Applib::$projects_table)->result();
                    $p = $p[0];
                    $path = date("Y-m-d",  strtotime($p->date_created))."_".$project."_".$p->project_code.'/';
					$fullpath = './resource/project-files/'.$path;
					Applib::create_dir($fullpath);  
					$config['upload_path'] = $fullpath;
                    $config['allowed_types'] = config_item('allowed_files');
                    $config['max_size']	= config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if(!$this->upload->do_multi_upload("projectfiles")) {
                    Applib::make_flashdata(array(
                    	'response_status' => 'error',
                    	'message' => lang('operation_failed'),
                        'form_error'=> $this->upload->display_errors('<span style="color:red">', '</span><br>')
                        ));
                    	redirect('projects/view/'.$project.'?group=files');
                    } else {

                        $fileinfs = $this->upload->get_multi_upload_data();
                        foreach($fileinfs as $findex=>$fileinf) {
                            $data = array(
                                'project'       => $project,
                                'path'          => $path,
                                'file_name'	=> $fileinf['file_name'],
                                'title'         => $_POST['title'],
                                'ext'           => $fileinf['file_ext'],
                                'size'  	=> $fileinf['file_size'],
                                'is_image'      => $fileinf['is_image'],
                                'image_width'   => $fileinf['image_width'],
                                'image_height'  => $fileinf['image_height'],
                                'description'	=> $description,
                                'uploaded_by'	=> $this->user,
                            );
                            $file_id = Applib::create(Applib::$files_table,$data);
                        }

                if (config_item('notify_project_files') == 'TRUE') {
                	$this->_upload_notification($project);
            	}
            	// log activity
            	$this->_log_activity($project,'activity_uploaded_file', 'fa-file', $this->input->post('title')); 
                $this -> applib -> redirect_to('projects/view/'.$project.'/?group=files','success',lang('file_uploaded_successfully'));
                    }

		}else{
		$data['project'] = $this->uri->segment(4);
		$this->load->view('modal/add_file',isset($data) ? $data : NULL);
	}
}
	function edit()
	{		
		if ($this->input->post()) {
			Applib::is_demo();
                            
			$project = $this->input->post('project', TRUE);
			$title = $this->input->post('title', TRUE);
			$file_id = $this->input->post('file_id', TRUE);
			Applib::update(Applib::$files_table,array('file_id' => $file_id),$this->input->post());
			// log activity
            $this->_log_activity($project,'activity_edited_file', 'fa-file', $title); 

			$this -> applib -> redirect_to('projects/view/'.$project.'/?group=files','success',lang('file_edited_successfully'));

		}else{
			if(isset($_GET['id'])){
				$data['file_id'] = $_GET['id'];
				$file = Applib::retrieve(Applib::$files_table,array('file_id' => $data['file_id']));
			
                $path = $file[0]->path;
                $fullpath = './resource/project-files/'.$path.$file[0]->file_name;
                    if($path == NULL)
                        $fullpath = './resource/project-files/'.$file[0]->file_name;

                $data['file_path'] = $fullpath;
                $data['file_details'] = $file;
				$data['project_id'] = $this->uri->segment(4);
				$this->load->view('modal/edit_file',$data);
			}			
	}
}
	function download()
	{
	$this->load->helper('download');
	$file_id = $this->uri->segment(4);
	$file = Applib::retrieve(Applib::$files_table,array('file_id'=>$file_id));
	$project = $file[0]->project;
	$file_name = $file[0]->file_name;

	$path = $file[0]->path;
            $fullpath = './resource/project-files/'.$path.$file_name;
    if($path == NULL)
            $fullpath = './resource/project-files/'.$file_name;

	if($fullpath){
			$data = file_get_contents($fullpath); // Read the file's contents
			force_download($file_name, $data);
		}else{
			$this -> applib -> redirect_to('projects/view/'.$project,'error',lang('operation_failed'));
			}
	}

	function preview(){
        if (!$this->input->post()) {
            $file_id = $this->uri->segment(4);
            $project_id = $this->uri->segment(5);
            $file =  $this->db->select()
                ->from(Applib::$files_table)
                ->where('file_id', $file_id)
                ->get()
                ->row();

            $path = $file->path;
            $fullpath = 'resource/project-files/'.$path.$file->file_name;
            if($path == NULL)
                $fullpath = 'resource/project-files/'.$file->file_name;

            if ($file)
            {
                if(file_exists($fullpath)){
                    $data['file'] = $file;
                    $data['file_path'] = $fullpath;
                    $data['project_id'] = $project_id;
                    $this->load->view('modal/preview_file', $data);
                }else{
                	$this->session->set_flashdata('response_status','error');
                    $this->session->set_flashdata('message',lang('operation_failed'));
                    redirect('projects/view/'.$project_id);
                }
            }
            else
            {
            	$this->session->set_flashdata('response_status','error');
                $this->session->set_flashdata('message',lang('operation_failed'));
                redirect('projects/view/'.$project_id);
            }
        }
    }

	function delete()
	{
		if ($this->input->post()) {

		$project_id = $this->input->post('project', TRUE);
		$file_id = $this->input->post('file', TRUE);

		$file = Applib::retrieve(Applib::$files_table,array('file_id'=>$file_id));
		$file_name = $file[0]->file_name;

		$path = $file[0]->path;
            $fullpath = './resource/project-files/'.$path.$file_name;
    	if($path == NULL)
            $fullpath = './resource/project-files/'.$file_name;
        
			unlink($fullpath);
			Applib::delete(Applib::$files_table,array('file_id' => $file_id));
			// log activity
            $this->_log_activity($project_id,'activity_deleted_a_file', 'fa-times', $file_name); 

			$this -> applib -> redirect_to('projects/view/'.$project_id.'/?group=files','success',lang('file_deleted'));

		}else{
			if(isset($_GET['id'])){
				$data['file_id'] = $_GET['id'];
				$data['project_id'] = $this->uri->segment(4);
				$this->load->view('modal/delete_file',$data);
			}			
			
		}
	}

	 

	function _upload_notification($project){
			$project_title = Applib::get_table_field(Applib::$projects_table,
						array('project_id'=>$project),'project_title');


			$message = Applib::get_table_field(Applib::$email_templates_table,
						array('email_group' => 'project_file'), 'template_body');

            $subject = Applib::get_table_field(Applib::$email_templates_table,
            			array('email_group' => 'project_file'), 'subject');

			$uploaded_by = str_replace("{UPLOADED_BY}",$this->username,$message);
			$title = str_replace("{PROJECT_TITLE}",$project_title,$uploaded_by);
			$project_url = str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project.'/?group=files',$title);			
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$project_url);		

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);	

			$assigned_to = Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project),'assign_to');			

			if (!empty($assigned_to)) {
				 foreach (unserialize($assigned_to) as $value) { 
					$params['recipient'] = Applib::login_info($value)->email;

					$params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
					$params['message'] = $message;

					$params['attached_file'] = '';

					modules::run('fomailer/send_email',$params);
			}
		}
		// Send email to client
					$project_client = Applib::get_table_field(Applib::$projects_table,
										array('project_id'=>$project),'client');

					$params['recipient'] = Applib::get_table_field(Applib::$companies_table,
										array('co_id'=>$project_client),'company_email');

					$params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
					$params['message'] = $message;

					$params['attached_file'] = '';

					modules::run('fomailer/send_email',$params);

	}

	function _log_activity($project, $activity, $icon, $value1 = '', $value2 = '') {
        $data = array(
            'module' => 'projects',
            'module_field_id' => $project,
            'user' => $this->user,
            'activity' => $activity,
            'icon' => $icon,
            'value1' => $value1,
            'value2' => $value2
            );
        Applib::create(Applib::$activities_table,$data);
    }
}

/* End of file files.php */