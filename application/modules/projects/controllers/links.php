<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Freelancer Office
 * 
 * Web based project and invoicing management system available on codecanyon
 *
 * @package		Freelancer Office
 * @author		William M
 * @copyright	Copyright (c) 2014 - 2015 Gitbench, LLC
 * @license		http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
 * @link		http://codecanyon.net/item/freelancer-office/8870728
 * 
 */


class Links extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> module('layouts');	
		$this -> load -> helper('cookie');	
		$this->load->library(array('tank_auth','template','form_validation'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		if (!$this -> user) {
			$this -> applib -> redirect_to('login','error',lang('access_denied'));			
		}
		$this -> user_role = Applib::login_info($this->user)->role_id;

		$this -> template -> title(lang('projects').' - '.config_item('company_name'));
		$this -> page = lang('projects');
		
	}

	function add()
	{
		if ($this->input->post()) { 
		if ($this->form_validation->run('projects','add_link') == FALSE)
		{
			Applib::make_flashdata(array(
				'response_status' => 'error',
				'message' => lang('operation_failed'),
				'form_error' => validation_errors()
				));
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			if ($this -> user_role == '1') {
			$project_id = $_POST['project_id'];
			$link_url = $_POST['link_url'];
                        if (substr($link_url, 0, 4) != 'http') {
                            $link_url = "http://".$link_url;
                        }
                        
                        if (empty($_POST['link_title'])) {
                            $meta = $this->_get_url_meta($link_url);
                            if (!empty($meta['title'])) { 
                            	$_POST['link_title'] = $meta['title']; 
                            } else { 
                            	$_POST['link_title'] = lang('link_no_title'); 
                            }
                            if (!empty($meta['description'])) { 
                            	$_POST['description'] = $meta['description']; 
                            } else { 
                            	$_POST['description'] = ''; 
                            }
                        }
                        Applib::create(Applib::$links_table,$this->input->post());

			$this->_log_activity('activity_added_new_link',$this->user,'projects',$project_id,$icon = 'fa-laptop',$_POST['link_title']); //log activity
			$this -> applib -> redirect_to('projects/view/'.$project_id.'?group=links','success',lang('link_added_successfully'));
			}
		}
		}else{
		$data['project_id'] = $this->uri->segment(4);
		$this->load->view('modal/add_link',isset($data) ? $data : NULL);
		}
	}

	function edit()
	{
		if ($this->input->post()) {
		if ($this->form_validation->run('projects','add_link') == FALSE)
		{
                        $_POST = '';
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('operation_failed'));
			$this->edit();
		}else{
			if ($this -> user_role == '1') {
			$project_id = $_POST['project_id'];
			$link_id = $_POST['link_id'];
			$link_title = Applib::get_table_field(Applib::$links_table,array('link_id'=>$link_id),'link_title');
			
			$this -> db -> where('link_id',$link_id) -> update(Applib::$links_table,$_POST);

			$this->_log_activity('activity_edited_link',$this->user,'projects',$project_id,'fa-laptop',$link_title); //log activity

			$this -> applib -> redirect_to('projects/view/'.$project_id.'/?group=links&view=link&id='.$link_id,'success',lang('link_edited_successfully'));
			}
		}
		}else{
		$link_id = $this->uri->segment(4);
		$data['details'] = Applib::retrieve(Applib::$links_table,array('link_id' => $link_id));
		$this->load->view('modal/edit_link',isset($data) ? $data : NULL);
		}
	}
        
	function pin()
	{
            if ($this -> user_role == '1') {
		$project_id = $this->uri->segment(4);
                $link_id = $this->uri->segment(5);
                $client =Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'client');
                $link_client = $this -> applib->get_any_field('links',array('link_id'=>$link_id),'client');
                if ($link_client > 0) {
                    $this -> db -> where('link_id',$link_id) -> update(Applib::$links_table,array("client"=>"0"));
                    $message = 'link_unpinned_successfully';
                } else {
                    $this -> db -> where('link_id',$link_id) -> update(Applib::$links_table,array("client"=>$client));
                    $message = 'link_pinned_successfully';
                }
                
                $data['project_id'] = $project_id;
                $data['link_id'] = $link_id;
                
            }
            if(isset($_SERVER['HTTP_REFERER']))
                {
                    $redirect_to = str_replace(base_url(),'',$_SERVER['HTTP_REFERER']);
                }
                else
                {
                    $redirect_to = $this->uri->uri_string();
                }            
                $this -> applib -> redirect_to($redirect_to,'success',lang($message));
	}

	function delete()
	{
		if ($this->input->post()) {
		$this->form_validation->set_rules('project_id', 'Project ID', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('delete_failed'));
				redirect('projects');
		}else{	
			$project_id = $this->input->post('project_id');
			$link_id = $this->input->post('link_id');

			$this->db->delete(Applib::$links_table, array('link_id' => $link_id)); 
			$this->_log_activity('activity_deleted_a_link',$this->user,'projects',$project_id,$icon = 'fa-trash-o'); //log activity

			$this -> applib -> redirect_to('projects/view/'.$project_id.'?group=links','success',lang('link_deleted_successfully'));
		}
		}else{
			$data['project_id'] = $this->uri->segment(4);
			$data['link_id'] = $this->uri->segment(5);
			$this->load->view('modal/delete_link',$data);
		}
	}

	function _log_activity($activity,$user,$module,$module_field_id,$icon,$value1='',$value2=''){
		
					$params = array(
					                'user'			=> $user,
					                'module' 		=> $module,
					                'module_field_id'	=> $module_field_id,
					                'activity'		=> $activity,
					                'icon'			=> $icon,
					                'value1'		=> $value1,
					                'value2'		=> $value2
					                );
					Applib::create(Applib::$activities_table,$params);
	}
        
        function _get_url_meta($url) 
        {
            if (intval($url) > 0) { $url = Applib::get_table_field(Applib::$links_table,array('link_id'=>$url),'link_url'); }
            $data = file_get_contents($url);
            $meta = get_meta_tags($url);
            $meta['title'] = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches) ? $matches[1] : null;
            return $meta;
        }
        
}

/* End of file links.php */