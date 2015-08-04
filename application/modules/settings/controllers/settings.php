<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
**********************************************************************************
* Copyright: gitbench 2014
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/

// Includes all users operations
include APPPATH.'/libraries/Requests.php';
class Settings extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> library(array('tank_auth','form_validation'));

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		$this -> user_role = Applib::get_table_field(Applib::$user_table,array('id'=>$this->user),'role_id');
		if ($this -> user_role != '1') {
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('login');
		}
		
		Requests::register_autoloader();
		$this -> auth_key = config_item('api_key'); // Set our API KEY
		
		$this -> load -> module('layouts');
		$this->load->config('rest');
		$this -> load -> library('template');
		$this -> template -> title(lang('settings').' - '.config_item('company_name'). ' '.config_item('version'));
		$this -> page = lang('settings');
		$this -> load -> model('settings_model','settings');	
		$this->general_setting = '?settings=general';
		$this->invoice_setting = '?settings=invoice';
		$this->estimate_setting = '?settings=estimate';
		$this->system_setting = '?settings=system';
		$this->theme_setting = '?settings=theme';
                $this->language_files = array(
                        "fx_lang.php" => "./application/language/",
                        "tank_auth_lang.php" => "./application/language/",
                        "calendar_lang.php" => "./system/language/",
                        "date_lang.php" => "./system/language/",
                        "db_lang.php" => "./system/language/",
                        "email_lang.php" => "./system/language/",
                        "form_validation_lang.php" => "./system/language/",
                        "ftp_lang.php" => "./system/language/",
                        "imglib_lang.php" => "./system/language/",
                        "migration_lang.php" => "./system/language/",
                        "number_lang.php" => "./system/language/",
                        "profiler_lang.php" => "./system/language/",
                        "unit_test_lang.php" => "./system/language/",
                        "upload_lang.php" => "./system/language/",
                );
	}

	function index()
	{
		$settings = $this->input->get('settings', TRUE)?$this->input->get('settings', TRUE):'general';
		$data['page'] = $this -> page;	
		$data['form'] = TRUE;
		$data['editor'] = TRUE;
		$data['fuelux'] = TRUE;
		$data['datatables'] = TRUE;
		$data['postmark_config'] = TRUE;
		$data['countries'] = $this -> settings -> countries();
		$data['locales'] = $this -> applib -> locales();
                $data['timezones'] = $this -> settings -> timezones();
                $data['currencies'] = $this -> applib -> currencies();
                $data['languages'] = $this -> applib -> languages();
                $data['available'] = $this ->available_translations();
                $data['translations'] = $this -> applib -> translations();
		$data['load_setting'] = $settings;
                $data['locale_name'] = $this -> applib->get_any_field('locales',array('locale'=>  config_item('locale')),'name');
                
                if ($settings == 'theme') {
                    $data['iconpicker'] = TRUE;
                }
                if ($settings == 'translations') {
                        $action = $this->uri->segment(3);
                        $data['translation_stats'] = $this -> settings -> translation_stats($this->language_files);
                        if ($action == 'view') {
                                $data['language'] = $this->uri->segment(4);
                                $data['language_files'] = $this ->language_files;
                        }
                        if ($action == 'edit') {
                                $language = $this->uri->segment(4);
                                $file = $this->uri->segment(5);
                                $path = $this->language_files[$file.'_lang.php'];
                                $data['language'] = $language;
                                $data['english'] = $this->lang->load($file, 'english', TRUE, TRUE, $path);
                                if ($language == 'english') {
                                    $data['translation'] = $data['english'];
                                } else {
                                    $data['translation'] = $this->lang->load($file, $language, TRUE, TRUE);
                                }
                                $data['language_file'] = $file;
                        }
                    }
		$this->template
		->set_layout('users')
		->build('settings',isset($data) ? $data : NULL);
	}

	function vE(){
		Settings::_vP();
	}

	function templates(){
		if ($_POST) {
			Applib::is_demo();

			$data = array(
			              'subject' => $this -> input -> post('subject'),
			              'template_body' => $this -> input -> post('email_template'),
                            );			
			 $this -> db -> where(array('email_group' => $_POST['email_group'])) -> update('email_templates',$data);
			 $return_url = $_POST['return_url'];

			 $this->session->set_flashdata('response_status', 'success');
			 $this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect($return_url);
		}else{
			$this->index();
		}
	}

	function customize(){
		$this->load->helper('file');
		if($_POST){
		$data = $_POST['css-area'];			
		if(write_file('./resource/css/style.css', $data)){
					$this->session->set_flashdata('response_status', 'success');
			 		$this->session->set_flashdata('message', lang('settings_updated_successfully'));
 					redirect('settings/?settings=customize');
			}else{
					$this->session->set_flashdata('response_status', 'error');
			 		$this->session->set_flashdata('message', lang('operation_failed'));
			 		redirect('settings/?settings=customize');
	 			}
 		}else{
 		$this -> index();
 		}
	}

	function _vP(){
		Applib::pData();
		$data = array('value' => 'TRUE');
		Applib::update(Applib::$config_table,array('config_key' => 'valid_license'),$data);
		Applib::make_flashdata(array(
			'response_status' => 'success',
			'message' => 'Software validated successfully')
		);
		redirect($_SERVER['HTTP_REFERER']);
	}

	function departments(){
		if ($_POST) {
			$settings = $_POST['settings'];
			unset($_POST['settings']);

			 $this -> db -> insert('departments',$_POST);

			 $this->session->set_flashdata('response_status', 'success');
			 $this->session->set_flashdata('message', lang('department_added_successfully'));
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			$this->index();
		}
	}

	function add_custom_field(){
		if ($_POST) {
			if (isset($_POST['targetdept'])) {
				// select department and redirect to creating field
				$this -> applib -> redirect_to('settings/?settings=fields&dept='.$_POST['targetdept'],'success','Department selected');
			}else{
			$_POST['uniqid'] = $this -> _GenerateUniqueFieldValue();

			$this -> db -> insert('fields',$_POST);

			$this -> applib -> redirect_to('settings/?settings=fields&dept='.$_POST['deptid'],'success','Custom field added');
				// Insert to database additional fields

			}

		}else{

		}
	}

	function edit_custom_field($field = NULL){
		if ($_POST) {
			if(isset($_POST['delete_field']) AND $_POST['delete_field'] == 'on'){
				$this -> db -> where('id',$_POST['id']) -> delete('fields');
				$this -> applib -> redirect_to($_SERVER['HTTP_REFERER'],'success',lang('custom_field_deleted'));
			}else{
				$this -> db -> where('id',$_POST['id']) -> update('fields',$_POST);
				$this -> applib -> redirect_to($_SERVER['HTTP_REFERER'],'success',lang('custom_field_updated'));
			}
		}else{
		$data['field_info'] = $this -> db -> where(array('id'=>$field)) -> get('fields') -> result();
		$this->load->view('fields/modal_edit_field',isset($data) ? $data : NULL);
		}
	}

	

	function edit_dept($deptid = NULL){
		if ($_POST) {
			if(isset($_POST['delete_dept']) AND $_POST['delete_dept'] == 'on'){
				$this -> db -> where('deptid',$_POST['deptid']) -> delete('departments');
				$this -> applib -> redirect_to($_SERVER['HTTP_REFERER'],'success',lang('department_deleted'));
			}else{
				$this -> db -> where('deptid',$_POST['deptid']) -> update('departments',$_POST);
				$this -> applib -> redirect_to($_SERVER['HTTP_REFERER'],'success',lang('department_updated'));
			}
		}else{
		$data['deptid'] = $deptid;
		$data['dept_info'] = $this -> db -> where(array('deptid'=>$deptid)) -> get('departments') -> result();
		$this->load->view('modal_edit_department',isset($data) ? $data : NULL);
		}
	}

	function permissions(){
		if ($_POST) {
			 $permissions = json_encode($_POST);
			 $data = array(
			              'allowed_modules' => $permissions);			
			 $this -> db -> where(array('user_id' => $_POST['user_id'])) -> update('account_details',$data);

			 $this->session->set_flashdata('response_status', 'success');
			 $this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect(base_url().'settings/?settings=permissions&staff='.$_POST['user_id']);
		}else{
			$this->index();
		}
	}

	function translations(){
            
            $action = $this->uri->segment(3);
            
		if ($_POST) {
                    if ($action == 'save')
                        {
                            $jpost = array();
                            $jsondata = json_decode(html_entity_decode($_POST['json']));
                            foreach($jsondata as $jdata) {
                                $jpost[$jdata->name] = $jdata->value;
                            }
                            $jpost['_path'] = $this->language_files[$jpost['_file'].'_lang.php'];
                            $data['json'] = $this->settings->save_translation($jpost);
                            $this->load->view('json',isset($data) ? $data : NULL);
                            return;
                        }
                    if ($action == 'active')
                        {
                            $language = $this->uri->segment(4);
                            return $this->db->where('name',$language)->update('languages',$_POST);
                        }
		}else{
                    if ($action == 'add')
                        {
                            $language = $this->uri->segment(4);
                            $this->settings->add_translation($language, $this->language_files);
                            $this->session->set_flashdata('response_status', 'success');
                            $this->session->set_flashdata('message', lang('translation_added_successfully'));
                            redirect($_SERVER['HTTP_REFERER']);
                        }
                    if ($action == 'backup')
                        {
                            $language = $this->uri->segment(4);
                            return $this->settings->backup_translation($language, $this->language_files);
                        }
                    if ($action == 'restore')
                        {
                            $language = $this->uri->segment(4);
                            return $this->settings->restore_translation($language, $this->language_files);
                        }
                    if ($action == 'submit')
                        {
                            $language = $this->uri->segment(4);
                            $path = "./application/language/".$language."/".$language."-backup.json";
                            if (!file_exists($path)) {
                                $this->settings->backup_translation($language, $this->language_files);
                            }
                            $params['recipient'] = 'translations@gitbench.com';
                            $params['subject'] = 'User submitted translation: '.ucwords(str_replace("_"," ", $language));
                            $params['message'] = 'The .json language file is attached';
                            $params['attached_file'] = $path;
                            return modules::run('fomailer/send_email',$params);
                        }
			$this->index();
		}
	}
        
	function available_translations(){
            
                $ex = $this -> db ->get('languages')->result();
                foreach ($ex as $e) { $existing[] = $e->name; }
		$ln = $this -> db ->group_by('language')->get('locales')->result();
                foreach ($ln as $l) { if (!in_array($l->language, $existing)) { $available[] = $l; } }
                return $available;

	}

	function update(){
		if ($_POST) {
			Applib::is_demo();
			 switch ($_POST['settings'])
			        {
			            case 'general':			                
			            	$this->_update_general_settings($this->general_setting);
			                break;
			            case 'email':
			                $this->_update_email_settings();
			                break;
			            case 'payments':
			                $this->_update_payment_settings();
			                break;
			            case 'system':
			                $this->_update_system_settings('system');
			                break;
			            case 'theme':
                                        if(file_exists($_FILES['iconfile']['tmp_name']) || is_uploaded_file($_FILES['iconfile']['tmp_name'])) {
                                                    $this->upload_favicon($_POST);
                                            }
                                        if(file_exists($_FILES['appleicon']['tmp_name']) || is_uploaded_file($_FILES['appleicon']['tmp_name'])) {
                                                    $this->upload_appleicon($_POST);
                                            }
                                        if(file_exists($_FILES['logofile']['tmp_name']) || is_uploaded_file($_FILES['logofile']['tmp_name'])) {
                                                    $this->upload_logo($_POST);
                                            }
			                $this->_update_theme_settings('theme');
			                break;
			            case 'estimate':
			                $this->_update_estimate_settings('estimate');
			                break;
			            case 'invoice':
			            	if(file_exists($_FILES['userfile']['tmp_name']) || is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			                	$this->upload_invoice_logo($_POST);
			            	}
			                $this->_update_invoice_settings('invoice');
			                break;
			        }

		}else{
			$this->index();
		}
	}

	function _update_general_settings($setting){
		Applib::is_demo();

		$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		$this->form_validation->set_rules('company_address', 'Company Address', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			redirect('settings/'.$this->general_setting);
		}else{
			foreach ($_POST as $key => $value) {
				$data = array('value' => $value); 
				$this->db->where('config_key', $key)->update('config', $data);
                                $exists = $this->db->where('config_key', $key)->get('config');
                                if ($exists->num_rows() == 0) {
                                        $this->db->insert('config',array("config_key"=>$key, "value"=>$value));
                                }
			}
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect('settings/'.$this->general_setting);
		}
		
	}

	function _update_system_settings($setting){
		Applib::is_demo();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('file_max_size', 'File Max Size', 'required');		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			$this->session->set_flashdata('form_error', validation_errors());
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			foreach ($_POST as $key => $value) {
                                if(strtolower($value) == 'on') {
                                    $value = 'TRUE';
                                } elseif(strtolower($value) == 'off') {
                                    $value = 'FALSE';
                                }
				$data = array('value' => $value); 
				$this->db->where('config_key', $key)->update('config', $data); 
			}
                        
                        //Set date format for date picker
                        switch($_POST['date_format']) {
                            case "%d-%m-%Y": $picker = "dd-mm-yyyy"; $phptime = "d-m-Y"; break;
                            case "%m-%d-%Y": $picker = "mm-dd-yyyy"; $phptime = "m-d-Y"; break;
                            case "%Y-%m-%d": $picker = "yyyy-mm-dd"; $phptime = "Y-m-d"; break;
                        }
                        $this->db->where('config_key', 'date_picker_format')->update('config', array("value" => $picker));
                        $this->db->where('config_key', 'date_php_format')->update('config', array("value" => $phptime));

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('settings_updated_successfully'));
                            redirect('settings/'.$this->system_setting);
		}
		
	}

	function _update_theme_settings($setting){
		Applib::is_demo();
                foreach ($_POST as $key => $value) {
                        $this->db->where('config_key', $key)->update('config', array('value' => $value)); 
                }
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('settings_updated_successfully'));
                redirect('settings/'.$this->theme_setting);
	}

	function _update_invoice_settings($setting){
			Applib::is_demo();

		$this->form_validation->set_rules('invoice_color', 'Invoice Color', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			redirect('settings/'.$this->invoice_setting);
		}else{
			
			foreach ($_POST as $key => $value) {
                if(strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif(strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $data = array('value' => $value);
				$this->db->where('config_key', $key)->update('config', $data); 
			}
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect('settings/'.$this->invoice_setting);
		}
		
	}

	function _update_estimate_settings($setting){
			Applib::is_demo();

		$this->form_validation->set_rules('estimate_color', 'Estimate Color', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			redirect('settings/'.$this->estimate_setting);
		}else{
			
			foreach ($_POST as $key => $value) {
                if(strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif(strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $data = array('value' => $value);
				$this->db->where('config_key', $key)->update('config', $data); 
			}
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect('settings/'.$this->estimate_setting);
		}
		
	}

	 function _update_email_settings(){
		Applib::is_demo();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('settings', 'Settings', 'required');	
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('form_error', validation_errors());
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			redirect($_SERVER['HTTP_REFERER']);
		}else{
            
			foreach ($_POST as $key => $value) {
                                if(strtolower($value) == 'on') {
                                    $value = 'TRUE';
                                } elseif(strtolower($value) == 'off') {
                                    $value = 'FALSE';
                                }

                                $data = array('value' => $value);
                Applib::update(Applib::$config_table,array('config_key'=>$key),$data);
			}
                        if (isset($_POST['smtp_pass'])) 
                        {
                            $this->load->library('encrypt');
                            $raw_smtp_pass =  $this->input->post('smtp_pass');
                            $smtp_pass = $this->encrypt->encode($raw_smtp_pass);
                            $data = array('value' => $smtp_pass); 
                Applib::update(Applib::$config_table,array('config_key' => 'smtp_pass'),$data);
                        }
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect($_SERVER['HTTP_REFERER']);
		}
		
	}
	function _update_payment_settings(){
		if ($this->input->post()) {
			Applib::is_demo();
			

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');	
		$this->form_validation->set_rules('paypal_email', 'Paypal Email', 'required');		
		$this->form_validation->set_rules('paypal_cancel_url', 'Paypal Cancel', 'required');	
		$this->form_validation->set_rules('paypal_ipn_url', 'Paypal IPN', 'required');	
		$this->form_validation->set_rules('paypal_success_url', 'Paypal Success', 'required');	
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('form_error', validation_errors());
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			redirect($_SERVER['HTTP_REFERER']);
		}else{

			foreach ($_POST as $key => $value) {
                if(strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif(strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $data = array('value' => $value);
				$this->db->where('config_key', $key)->update('config', $data); 
			}


			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect('settings/?settings=payments');
		}
	}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			redirect('settings/?settings=payments');
	}
		
	}

	function update_email_templates(){
		if ($this->input->post()) {
			Applib::is_demo();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('email_estimate_message', 'Estimate Message', 'required');
		$this->form_validation->set_rules('email_invoice_message', 'Invoice Message', 'required');	
		$this->form_validation->set_rules('reminder_message', 'Reminder Message', 'required');	
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			$_POST = '';
			$this->update('email');
		}else{
			foreach ($_POST as $key => $value) {
				$data = array('value' => $value); 
				$this->db->where('config_key', $key)->update('config', $data); 
			}

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('settings_updated_successfully'));
			redirect('settings/update/email');
		}
	}else{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('settings_update_failed'));
			redirect('settings/update/email');
	}
		
	}

	function upload_favicon($files){
		Applib::is_demo();

		if ($files) {
				$config['upload_path']   = './resource/images/';
            			$config['allowed_types'] = 'jpg|jpeg|png|ico';
            			$config['max_width']  = '300';
            			$config['max_height']  = '300';
            			$config['overwrite']  = TRUE;
            			$this->load->library('upload', $config);
						if ($this->upload->do_upload('iconfile'))
						{
							$data = $this->upload->data();
							$file_name = $data['file_name'];
							$data = array('value' => $file_name);
							$this->db->where('config_key', 'site_favicon')->update('config', $data); 
							return TRUE;
						}else{
							$this->session->set_flashdata('response_status', 'error');
							$this->session->set_flashdata('message', lang('logo_upload_error'));
							redirect('settings/'.$this->theme_setting);
						}
			}else{
							return FALSE;
			}
	}

	function upload_appleicon($files){
		Applib::is_demo();

		if ($files) {
				$config['upload_path']   = './resource/images/';
            			$config['allowed_types'] = 'jpg|jpeg|png|ico';
            			$config['max_width']  = '300';
            			$config['max_height']  = '300';
            			$config['overwrite']  = TRUE;
            			$this->load->library('upload', $config);
						if ($this->upload->do_upload('appleicon'))
						{
							$data = $this->upload->data();
							$file_name = $data['file_name'];
							$data = array('value' => $file_name);
							$this->db->where('config_key', 'site_appleicon')->update('config', $data); 
							return TRUE;
						}else{
							$this->session->set_flashdata('response_status', 'error');
							$this->session->set_flashdata('message', lang('logo_upload_error'));
							redirect('settings/'.$this->theme_setting);
						}
			}else{
							return FALSE;
			}
	}

	function upload_logo($files){
		Applib::is_demo();

		if ($files) {
				$config['upload_path']   = './resource/images/';
            			$config['allowed_types'] = 'jpg|jpeg|png';
            			$config['max_width']  = '300';
            			$config['max_height']  = '300';
            			$config['remove_spaces'] = TRUE;
            			
            			$config['overwrite']  = TRUE;
            			$this->load->library('upload', $config);
						if ($this->upload->do_upload('logofile'))
						{
							$filedata = $this->upload->data();
							$file_name = $filedata['file_name'];
							$data = array('value' => $file_name);
							$this->db->where('config_key', 'company_logo')->update('config', $data);
							$this->session->set_flashdata('response_status', 'success');
							$this->session->set_flashdata('message', lang('file_uploaded_successfully'));
                                                        redirect('settings/'.$this->theme_setting);
							return TRUE;
						}else{
							$this->session->set_flashdata('response_status', 'error');
							$this->session->set_flashdata('message', lang('logo_upload_error'));
							redirect('settings/'.$this->theme_setting);
						}
			}else{
							return FALSE;
			}
	}
	function upload_invoice_logo($files){
		Applib::is_demo();

		if ($files) {
						$config['upload_path']   = './resource/images/logos/';
            			$config['allowed_types'] = 'jpg|jpeg|png';
            			$config['remove_spaces'] = TRUE;
            			$config['file_name']  = 'invoice_logo';
            			$config['overwrite']  = TRUE;
            			$this->load->library('upload', $config);
						if ($this->upload->do_upload())
						{
							$data = $this->upload->data();
							$file_name = $data['file_name'];
							$data = array(
								'value' => $file_name);
							$this->db->where('config_key', 'invoice_logo')->update('config', $data); 
							return TRUE;
						}else{
							$this->session->set_flashdata('response_status', 'error');
							$this->session->set_flashdata('message', lang('logo_upload_error'));
							redirect('settings/'.$this->invoice_setting);
						}
			}else{
							return FALSE;
			}
	}


	function _GenerateUniqueFieldValue()
	{
		$uniqid = uniqid('f');
		// Id should start with an character other than digit

		$this -> db -> where('uniqid', $uniqid) -> get('fields');

		if ($this -> db -> affected_rows() > 0)
		{
			$this -> GetUniqueFieldValue();
			// Recursion
		}
		else
		{
			return $uniqid;
		}

	}

	function database()
	{
		Applib::is_demo();
		$this->load->helper('file');
		$this->load->dbutil();
		$prefs = array(
                'format'      => 'zip',             // gzip, zip, txt
                'filename'    => 'database-full-backup_'.date('Y-m-d').'.zip',   
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );
			$backup =& $this->dbutil->backup($prefs);

        if ( ! write_file('./resource/backup/database-full-backup_'.date('Y-m-d').'.zip', $backup))
            {
                $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('message', 'The resource/backup folder is not writable.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            $this->load->helper('download');
			force_download('database-full-backup_'.date('Y-m-d').'.zip', $backup);

			
	}
	

	
}

/* End of file settings.php */