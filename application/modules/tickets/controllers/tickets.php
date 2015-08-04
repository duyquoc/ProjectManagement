<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
**********************************************************************************
* Copyright: gitbench 2014
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/

class Tickets extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation','encrypt'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		if (!$this -> user) {
			$this -> applib -> redirect_to('login','error',lang('access_denied'));			
		}
		$this -> user_role = Applib::login_info($this->user)->role_id;
		$this->user_company = Applib::profile_info($this->user)->company;

		$this -> template -> title(lang('tickets').' - '.config_item('company_name'));
		$this -> page = lang('tickets');

        // $this->load->model('ticket_model', 'mdlticket');

        $archive = FALSE;
        if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }

        if ($this->user_role == '1') {
        	$this -> tickets_list = $this -> _admin_tickets($archive);
        }elseif ($this->user_role == '3') {
        	$this -> tickets_list = $this->_staff_tickets($archive);
        }else{        	
        	$this -> tickets_list = Applib::retrieve(Applib::$tickets_table,array('reporter'=>$this->user));
        }
	}

	function index()
	{		
        $archive = FALSE;
        if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }
        $data = array(
        	'page' => $this -> page,
        	'datatables' => TRUE,
        	'archive' => $archive,
        	'role' => $this -> user_role,
        	'tickets' => $this -> tickets_list
        	);
		$this->template
		->set_layout('users')
		->build('tickets',isset($data) ? $data : NULL);
	}

	function view($id = NULL)
	{	

		$this->_has_access($this -> user_role,$this->user_company,$id);

		$data['page'] = $this -> page;
		$data['role'] = $this -> user_role;

		$data['ticket_details'] = Applib::retrieve(Applib::$tickets_table,array('id'=>$id));
		$data['ticket_replies'] = Applib::retrieve(Applib::$ticket_replies_table,array('ticketid'=>$id));
		$data['tickets'] = $this -> tickets_list; // GET a list of the Tickets

		$this->template
		->set_layout('users')
		->build('ticket_details',isset($data) ? $data : NULL);
	}

	

	function add()
	{
		if ($this->input->post()) {
			if (isset($_POST['dept'])) {
				$this -> applib -> redirect_to('tickets/add/?dept='.$_POST['dept'],'success','Department selected');
			}

		if ($this -> form_validation -> run('tickets','add_ticket') == FALSE)
		{
				Applib::make_flashdata(array(
				 	'response_status' => 'error',
				 	'message' => lang('operation_failed'),
                    'form_error'=> validation_errors()
                     ));

				redirect($_SERVER['HTTP_REFERER']);
		}else{	

			if(file_exists($_FILES['userfile']['tmp_name']) || is_uploaded_file($_FILES['userfile']['tmp_name']))
			                	$attachment = $this->_upload_attachment($_POST);

			            	// check additional fields
		$additional_fields = array();		
		$additional_data = $this -> db -> where(array('deptid'=>$_POST['department'])) 
									   ->get(Applib::$custom_fields_table) 
									   ->result_array();
		if (is_array($additional_data))
			foreach ($additional_data as $additional)
			{
				// We create these vales as an array
				$name = $additional['uniqid'];
				$additional_fields[$name] = $this -> encrypt -> encode($this -> input -> post($name));
			}
			$_POST['subject'] = '['.$_POST['ticket_code'].'] : '.$_POST['subject'];
			$insert = array(
				'subject' => $_POST['subject'],
				'ticket_code' => $_POST['ticket_code'],
				'department' => $_POST['department'],
				'priority' => $_POST['priority'],
				'body' => $_POST['body'],
				'status' => 'open'
				);

			if (is_array($additional_fields)){
				$insert['additional'] = json_encode($additional_fields);
			}

			if (isset($attachment)){
				$insert['attachment'] = $attachment;
			}
			if ($this -> user_role != '1') {
				$insert['reporter'] = $this->user;
				$_POST['reporter'] = $this->user;
			}else{
				$insert['reporter'] = $_POST['reporter'];
			}
			


			if($ticket_id = Applib::create(Applib::$tickets_table,$insert)){

				// Send email to Staff

				$this -> _send_email_to_staff($_POST);

				// Send email to Client 

				$this -> _send_email_to_client($_POST);

				// Log Activity
				$this -> _log_activity('activity_ticket_created',$this->user,'tickets',$ticket_id,'fa-ticket',$_POST['ticket_code']);

	$this -> applib -> redirect_to('tickets/view/'.$ticket_id,'success',lang('ticket_created_successfully'));
				}			
			
			
		}
	}else{

		$data = array(
			'page' 		 => $this -> page,
			'role'		 => $this -> user_role,
			'datepicker' => TRUE,
			'form'		 => TRUE,
			'clients'	 => $this -> _get_clients(),
			'tickets'	 => $this -> tickets_list
			);

			$this->template
			->set_layout('users')
			->build('create_ticket',isset($data) ? $data : NULL);

		}
	}


	function edit($id = NULL)
	{
		$this->_has_access($this -> user_role,$this->user_company,$id);
		
		if ($this->input->post()) {
			$ticket_id = $this -> input -> post('id', TRUE);
		if ($this -> form_validation -> run('tickets','edit_ticket') == FALSE)
		{
			Applib::make_flashdata(array(
				 	'response_status' => 'error',
				 	'message' => lang('error_in_form'),
                    'form_error'=> validation_errors()
                     ));

				redirect($_SERVER['HTTP_REFERER']);
		}else{	
			
			if(file_exists($_FILES['userfile']['tmp_name']) || is_uploaded_file($_FILES['userfile']['tmp_name'])) 
			                	$attachment = $this->_upload_attachment($_POST);

			if (isset($attachment)){
				$_POST['attachment'] = $attachment;
			}

			Applib::update(Applib::$tickets_table,array('id'=>$ticket_id),$_POST);
				
				// Log Activity
				$this -> _log_activity('activity_ticket_edited',$this->user,'tickets',$ticket_id,'fa-pencil',$_POST['ticket_code']);

				$this -> applib -> redirect_to('tickets/view/'.$ticket_id,'success',lang('ticket_edited_successfully'));
			
			}
		}else{

			$data = array(
				'page'		 	 => $this -> page,
				'datepicker' 	 => TRUE,
				'form'		 	 => TRUE,
				'clients'	 	 => $this -> _get_clients(),
				'tickets'	 	 => $this -> tickets_list,
				'ticket_details' => Applib::retrieve(Applib::$tickets_table,array('id'=>$id))
				);

			$this->template
			->set_layout('users')
			->build('edit_ticket',isset($data) ? $data : NULL);

		}
	}

	function reply()
	{
		if ($this->input->post()) {			
			$ticket_id = $this -> input -> post('ticketid');

		if ($this -> form_validation -> run('tickets','ticket_reply') == FALSE)
		{
				$_POST = '';
				$this -> applib -> redirect_to('tickets/view/'.$ticket_id,'error',lang('error_in_form'));
		}else{	
			$insert = array(
				'ticketid' => $_POST['ticketid'],
				'body' => $_POST['reply'],
				'replierid' => $this->user,
				);		

			if($reply_id = Applib::create(Applib::$ticket_replies_table,$insert)){
				if($this->user_role != '2'){

				$this -> db -> set('status','answered') 
				-> where(array('id'=>$_POST['ticketid'])) 
				-> update(Applib::$tickets_table);
			}

				$user_role = Applib::login_info($_POST['replierid'])->role_id;

				if ($user_role == '2') {
					$this -> _notify_ticket_reply('admin',$_POST); // Send email to admins
				}else{
					$this -> _notify_ticket_reply('client',$_POST); // Send email to client
				}
				// Log Activity
			$this -> _log_activity('activity_ticket_replied',$this->user,'tickets',$ticket_id,'fa-ticket',$_POST['ticket_code']);
			$this -> applib -> redirect_to('tickets/view/'.$ticket_id,'success',lang('ticket_replied_successfully'));
				}			
			
			
		}
	}else{
			$this -> index();

		}
	}

	
	function delete($id = NULL)
	{
		if ($this->input->post()) {

			$ticket = $this->input->post('ticket', TRUE);

			Applib::delete(Applib::$ticket_replies_table,array('ticketid'=>$ticket)); //delete ticket replies

			Applib::delete(Applib::$activities_table,array('module'=>'tickets', 'module_field_id' => $ticket));  //clear ticket activities
			Applib::delete(Applib::$tickets_table,array('id'=>$ticket)); //delete ticket

			$this -> applib -> redirect_to('tickets','success',lang('ticket_deleted_successfully'));
		}else{
			$data['ticket'] = $id;
			$this->load->view('modal/delete_ticket',$data);

		}
	}
        
	function archive()
	{
		$id = $this->uri->segment(3);
        $ticket = $this->db->where('id',$id)->get(Applib::$tickets_table)->row();
		$archived = $this->uri->segment(4);
        $data = array("archived_t" => $archived);
        $this->db->where('id',$id)->update(Applib::$tickets_table, $data);
		$this->_log_activity('activity_ticket_edited',$this->user,'tickets',$id,$icon = 'fa-pencil',$ticket->ticket_code); //log activity
        $this -> applib -> redirect_to('tickets','success',lang('ticket_edited_successfully'));
	}

	function download_file($ticket = NULL)
	{
	$this->load->helper('download');
	$file_name = Applib::get_table_field(Applib::$tickets_table,array('id'=>$ticket),'attachment');
	if(file_exists('./resource/attachments/'.$file_name)){
			$data = file_get_contents('./resource/attachments/'.$file_name); // Read the file's contents
			force_download($file_name, $data);
		}else{
			$this -> applib -> redirect_to('tickets/view/'.$ticket,'error',lang('operation_failed'));
			}
	}


	function status($ticket = NULL){
		if (isset($_GET['status'])) {
			$status = $_GET['status'];	
			$this -> db -> set('status',$status) -> where(array('id'=>$ticket)) -> update(Applib::$tickets_table);

			if ($status == 'closed') {
				// Send email to ticket reporter
				$this -> _ticket_closed($ticket);
			}

			$this->_log_activity('activity_ticket_status_changed',$this->user,'tickets',$ticket,$icon = 'fa-ticket'); //log activity

			$this -> applib -> redirect_to('tickets/view/'.$ticket,'success',lang('ticket_status_changed'));
		}else{
			$this->index();
		}
	}


	

	function _ticket_closed($ticket){
				$message = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_closed_email'
							), 'template_body');
				$subject = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_closed_email'
							), 'subject');

				$no_of_replies = Applib::count_num_rows(Applib::$ticket_replies_table,array('ticketid' => $ticket));

				$reporter = Applib::get_table_field(Applib::$tickets_table,array('id' => $ticket), 'reporter');

				$reporter_email = Applib::login_info($reporter)->email;

				$ticket_code = Applib::get_table_field(Applib::$tickets_table,array('id' => $ticket), 'ticket_code');				

				$TicketCode = str_replace("{TICKET_CODE}",$ticket_code,$message);
				$ReporterEmail = str_replace("{REPORTER_EMAIL}",$reporter_email,$TicketCode);
				$StaffUsername = str_replace("{STAFF_USERNAME}",ucfirst($this->username),$ReporterEmail);
				$TicketStatus = str_replace("{TICKET_STATUS}",'Closed',$StaffUsername);
				$TicketReplies = str_replace("{NO_OF_REPLIES}",$no_of_replies,$TicketStatus);
				$TicketLink = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket,$TicketReplies);
				$message = str_replace("{SITE_NAME}",config_item('company_name'),$TicketLink);

				$subject = str_replace("[TICKET_CODE]",'['.$ticket_code.']',$subject);

				$data['message'] = $message;
				$message = $this->load->view('email_template', $data, TRUE);

				$params['subject'] = $subject;
				$params['message'] = $message;
				$params['attached_file'] = '';

				$params['recipient'] = $reporter_email;
				
				modules::run('fomailer/send_email',$params);
				
	}

	function _notify_ticket_reply($group,$data){

				$message = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_reply_email'
									), 'template_body');

				$subject = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_reply_email'
									), 'subject');

				$status = Applib::get_table_field(Applib::$tickets_table,
							array('id' => $data['ticketid']), 'status');

				$TicketCode = str_replace("{TICKET_CODE}",$data['ticket_code'],$message);
				$TicketStatus = str_replace("{TICKET_STATUS}",ucfirst($status),$TicketCode);
				$TicketLink = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$data['ticketid'],$TicketStatus);
				$message = str_replace("{SITE_NAME}",config_item('company_name'),$TicketLink);

				$subject = str_replace("[TICKET_CODE]",'['.$data['ticket_code'].']',$subject);

				$data['message'] = $message;
				$message = $this->load->view('email_template', $data, TRUE);

				$params['subject'] = $subject;
				$params['message'] = $message;
				$params['attached_file'] = '';
			
			switch ($group) {
				case 'admin':
				$dept = Applib::get_table_field(Applib::$tickets_table,
							array('id' => $_POST['ticketid']), 'department');

				$staffs = Applib::retrieve(Applib::$profile_table,array('department'=> $dept));

				foreach ($staffs as $staff)
				{
					$email = Applib::login_info($staff->user_id)->email;
					$params['recipient'] = $email;
					modules::run('fomailer/send_email',$params);
				}
				return TRUE;
				break;
				
				default:
				$reporter_id = Applib::get_table_field(Applib::$tickets_table,
								array('id' =>$data['ticketid']), 'reporter');
				$reporter_email = Applib::login_info($reporter_id)->email;

				$params['recipient'] = $reporter_email;
				
				modules::run('fomailer/send_email',$params);
				
				return TRUE;
				break;
			}
	}

	function _send_email_to_staff($postdata)
	{
		if (config_item('email_staff_tickets') == 'TRUE') {

			$staffs = Applib::retrieve(Applib::$profile_table,array('department'=> $postdata['department']));

				$reporter_email = Applib::login_info($postdata['reporter'])->email;

				$ticket_id = Applib::get_table_field(Applib::$tickets_table,
							array('ticket_code'=>$postdata['ticket_code']),'id');

				$message = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_staff_email'
							), 'template_body');

				$subject = Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_staff_email'
							), 'subject');

				$TicketCode = str_replace("{TICKET_CODE}",$postdata['ticket_code'],$message);
				$ReporterEmail = str_replace("{REPORTER_EMAIL}",$reporter_email,$TicketCode);
				$TicketLink = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket_id,$ReporterEmail);
				$message = str_replace("{SITE_NAME}",config_item('company_name'),$TicketLink);

				$data['message'] = $message;
				$message = $this->load->view('email_template', $data, TRUE);

				$subject = str_replace("[TICKET_CODE]",'['.$postdata['ticket_code'].']',$subject);
				
				$params['subject'] = $subject;
				$params['message'] = $message;
				$params['attached_file'] = '';

				foreach ($staffs as $staff)
				{
					$params['recipient'] = $staff->email;
					modules::run('fomailer/send_email',$params);
				}

				return TRUE;
		}else{
				return TRUE;
			}

	}

	function _send_email_to_client($postdata)
	{
		
				$email = 	Applib::login_info($postdata['reporter'])->email;

				$message =  Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_client_email'
									), 'template_body');

				$subject =  Applib::get_table_field(Applib::$email_templates_table,
							array('email_group' => 'ticket_client_email'
									), 'subject');

				$ticket_id = Applib::get_table_field(Applib::$tickets_table,
							array('ticket_code'=>$postdata['ticket_code']),'id');


				$client_email = str_replace("{CLIENT_EMAIL}",$email,$message);
				$ticket_code = str_replace("{TICKET_CODE}",$postdata['ticket_code'],$client_email);
				$ticket_link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket_id,$ticket_code);
				$message = str_replace("{SITE_NAME}",config_item('company_name'),$ticket_link);
				$data['message'] = $message;

				$message = $this->load->view('email_template', $data, TRUE);

				$subject = str_replace("[TICKET_CODE]",'['.$postdata['ticket_code'].']',$subject);

				$params['recipient'] = $email;
				$params['subject'] = $subject;
				$params['message'] = $message;
				$params['attached_file'] = '';
				
				modules::run('fomailer/send_email',$params);
				return TRUE;

	}

	function _upload_attachment($postfiles){
		// Upload the file.
					$config['upload_path'] = './resource/attachments/';
					$config['allowed_types'] = config_item('allowed_files');
					$config['max_size'] = config_item('file_max_size');
					$config['overwrite'] = FALSE;
					$this -> load -> library('upload', $config);


					if (!$this -> upload -> do_upload())
					{
						$error = $this -> upload -> display_errors('<span style="color:red">', '</span><br>');
						Applib::make_flashdata(array(
						 	'response_status' => 'error',
						 	'message' => lang('operation_failed'),
		                    'form_error'=> $error
                     ));
						redirect($_SERVER['HTTP_REFERER']);
					}

					$data = $this -> upload -> data();

					if (is_array($data))

						return $data['file_name'];

	}

	function _has_access($role,$company,$ticket){
		$ticket_dept = Applib::get_table_field(Applib::$tickets_table,array('id'=>$ticket),'department');
		$user_dept = Applib::profile_info($this->user)->department;
		$ticket_reporter = Applib::get_table_field(Applib::$tickets_table,array('id'=>$ticket),'reporter');

		if ($this -> user_role == '1' OR $user_dept == $ticket_dept OR $ticket_reporter == $this->user) {
			return TRUE;
		}else{
			$this -> applib -> redirect_to('tickets','error',lang('access_denied'));	
		}
	}
        
	function _admin_tickets($archive = FALSE){
            
                if ($archive) {
                    return $this->db->where('archived_t','1')->get(Applib::$tickets_table)->result();
                }
		 return $this->db->where('archived_t !=','1')->get(Applib::$tickets_table)->result();
	}


	function _staff_tickets($archive = FALSE){
            $staff_department = Applib::profile_info($this->user)->department;
                if ($archive) {
                    return $this->db->
                            where('archived_t','1')->
                            where("(department = '".$staff_department."' OR reporter = '".$this->user."')",NULL, FALSE)->
                            get(Applib::$tickets_table)->result();
                }
		return $this->db->
                            where('archived_t !=','1')->
                            where("(department = '".$staff_department."' OR reporter = '".$this->user."')",NULL, FALSE)->
                            get(Applib::$tickets_table)->result();
	}


	function _get_clients(){		
			return Applib::retrieve(Applib::$user_table,array('role_id'=>'2')); 

	}


	function _log_activity($activity,$user,$module,$module_field_id,$icon,$value1='',$value2=''){
		
					$params = array(
					                'user'				=> $user,
					                'module' 			=> $module,
					                'module_field_id'	=> $module_field_id,
					                'activity'			=> $activity,
					                'icon'				=> $icon,
					                'value1'			=> $value1,
					                'value2'			=> $value2
					                );
					Applib::create(Applib::$activities_table,$params);
	}
}

/* End of file invoices.php */