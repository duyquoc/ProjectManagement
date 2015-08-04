<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
**********************************************************************************
* Copyright: gitbench 2014
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/

class Invoices extends MX_Controller {

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
		$this -> user_role = Applib::get_table_field(Applib::$user_table,array('id'=>$this->user),'role_id');
		$this->user_company = Applib::get_table_field(Applib::$profile_table,array('user_id'=>$this->user),'company');

		$this -> template -> title(lang('invoices').' - '.config_item('company_name'). ' '.config_item('version'));
		$this -> page = lang('invoices');
		$sort = array('order_by' => isset($_GET['order_by'])?$_GET['order_by']:'date_saved',
		              'order'    => isset($_GET['order'])?$_GET['order']:'desc'
		              );
        $this->load->model('invoice_model', 'invoice');

        if ($this->user_role == '1' OR $this -> applib -> allowed_module('view_all_invoices',$this->username)) {
        	$this -> invoices_list = Applib::retrieve(Applib::$invoices_table,array('inv_id !='=>'0'));
        }else{
        	
        	$this -> invoices_list = Applib::retrieve(Applib::$invoices_table,array('client'=>$this->user_company,'show_client'=>'Yes'));
        }

		
		
	}



	function index()
	{		

	$data['page'] = $this -> page;	
	$data['datatables'] = TRUE;
	$data['role'] = $this -> user_role;
	$data['invoices'] = $this -> invoices_list;

	$this->template
	->set_layout('users')
	->build('invoices',isset($data) ? $data : NULL);
	}

	function view($invoice_id = NULL)
	{	
		if(!$this -> _can_view_invoice($invoice_id)){
			$this -> applib -> redirect_to('invoices','error',lang('access_denied'));
		}

		$data['page'] = $this -> page;
		$data['stripe'] = TRUE;
		$data['twocheckout'] = TRUE;
		$data['role'] = $this -> user_role;
                $data['sortable'] = TRUE;
                $data['typeahead'] = TRUE;
		$data['rates'] = Applib::retrieve(Applib::$tax_rates_table,array('tax_rate_id !='=>'0'));

		$data['invoice_details'] = Applib::retrieve(Applib::$invoices_table,array('inv_id'=>$invoice_id));
		$data['invoice_items'] = $this->applib->ordered_items($invoice_id);
		$data['invoices'] = $this -> invoices_list; // GET a list of the Invoices
		$data['payment_status'] = $this -> applib -> payment_status($invoice_id);

		$this->template
		->set_layout('users')
		->build('invoice_details',isset($data) ? $data : NULL);
        }
        
        function autoitems() {
                $query = 'SELECT * FROM (
                            SELECT item_name FROM fx_items 
                            UNION ALL 
                            SELECT item_name FROM fx_estimate_items
                            UNION ALL 
                            SELECT item_name FROM fx_items_saved
                            ) a 
                            GROUP BY item_name 
                            ORDER BY item_name ASC';
                $names = $this->db->query($query)->result();
                $name = array();
                foreach ($names as $n) {
                    $name[] = $n->item_name;
                }
                $data['json'] = $name;
                $this->load->view('json',isset($data) ? $data : NULL);
        }
        function autoitem() {
                $name = $_POST['name'];
                $query = "SELECT * FROM (
                            SELECT item_name, item_desc, quantity, unit_cost FROM fx_items 
                            UNION ALL 
                            SELECT item_name, item_desc, quantity, unit_cost FROM fx_estimate_items
                            UNION ALL 
                            SELECT item_name, item_desc, quantity, unit_cost FROM fx_items_saved
                            ) a 
                            WHERE a.item_name = '".$name."'";
                $names = $this->db->query($query)->result();
                //$items = $this->db->where('item_name',$name)->get(($scope == 'invoices' ? 'items':'estimate_items'))->result();
                $name = $names[0];
                $data['json'] = $name;
                $this->load->view('json',isset($data) ? $data : NULL);
        }
        
        function add()
	{
		if(!$this -> _can_add_invoice()){
			$this -> applib -> redirect_to('invoices','error',lang('access_denied'));
		}

		if ($this->input->post()) {
		if ($this -> form_validation -> run('invoices','add_invoice') == FALSE)
		{
				$_POST = '';
				$this -> add();
		}else{	
			if(config_item('increment_invoice_number') == 'TRUE'){
				$_POST['reference_no'] = config_item('invoice_prefix').$this -> applib -> generate_invoice_number();
			}

			$_POST['allow_paypal'] = ($this->input->post('allow_paypal') == 'on') ? 'Yes' : 'No';
			$_POST['allow_2checkout'] = ($this->input->post('allow_2checkout') == 'on') ? 'Yes' : 'No';
			$_POST['allow_stripe'] = ($this->input->post('allow_stripe') == 'on') ? 'Yes' : 'No';
			$_POST['allow_bitcoin'] = ($this->input->post('allow_bitcoin') == 'on') ? 'Yes' : 'No';
                        
                                // Inherit currency
                        $currency = $this->applib->client_currency($_POST['client']);
                        $_POST['currency'] = $currency->code;
                        $_POST['due_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['due_date']), 'Y-m-d');
                        unset($_POST['files']);

			if($invoice_id = Applib::create(Applib::$invoices_table,$_POST)){
                            
                            
				// Log Activity
					$activity = array(
					                'user'			=> $this->user,
					                'module' 		=> 'invoices',
					                'module_field_id'	=> $invoice_id,
					                'activity'		=> 'activity_invoice_created',
					                'icon'			=> 'fa-plus',
                                                        'value1'                => $_POST['reference_no']
					                );
					Applib::create(Applib::$activities_table,$activity); // Log activity
					$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'success',lang('invoice_created_successfully'));
				}			
			
			
		}
	}else{
			$data['page'] = $this -> page;
			$data['role'] = $this -> user_role;
			$data['editor'] = TRUE;
			$data['datepicker'] = TRUE;
			$data['form'] = TRUE;
			$data['clients'] = $this -> _get_clients(); 			
			$data['invoices'] = $this -> invoices_list; // GET a list of the Invoices
                        $data['currencies'] = $this -> applib -> currencies();
                        $data['languages'] = $this -> applib -> languages();

			$this->template
			->set_layout('users')
			->build('create_invoice',isset($data) ? $data : NULL);

		}
	}


	function edit($invoice_id = NULL)
	{
		if($this -> user_role != '1' OR $this -> applib -> allowed_module('edit_all_invoices',$this->username)){
			$this -> applib -> redirect_to('invoices','error',lang('access_denied'));
		}

		if ($this->input->post()) {
			$invoice_id = $this -> input -> post('inv_id', TRUE);
		if ($this -> form_validation -> run('invoices','edit_invoice') == FALSE)
		{
				$_POST = '';
				$this -> applib -> redirect_to('invoices/edit/'.$invoice_id,'error',lang('error_in_form'));
		}else{	
			$_POST['allow_2checkout'] = ($this->input->post('allow_2checkout') == 'on') ? 'Yes' : 'No';
			$_POST['allow_paypal'] = ($this->input->post('allow_paypal') == 'on') ? 'Yes' : 'No';
			$_POST['allow_stripe'] = ($this->input->post('allow_stripe') == 'on') ? 'Yes' : 'No';
			$_POST['allow_bitcoin'] = ($this->input->post('allow_bitcoin') == 'on') ? 'Yes' : 'No';
            $_POST['due_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['due_date']), 'Y-m-d');
            unset($_POST['files']);

			if(Applib::update(Applib::$invoices_table,array('inv_id'=>$invoice_id),$_POST)){
				if($this->input->post('r_freq') != 'none')
					$this -> _make_recurring($invoice_id,$_POST); // set recurring
				// Log Activity
					$activity = array(
					                'user'				=> $this->user,
					                'module' 			=> 'invoices',
					                'module_field_id'	=> $invoice_id,
					                'activity'			=> 'activity_invoice_edited',
					                'icon'				=> 'fa-pencil',
                                    'value1'        	=> $_POST['reference_no']
					                );
					Applib::create(Applib::$activities_table,$activity); // Log activity

				$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'success',lang('invoice_edited_successfully'));
				}
			
			}
		}else{

			$data['page'] = $this -> page;
			$data['datepicker'] = TRUE;
			$data['form'] = TRUE;
			$data['editor'] = TRUE;
			$data['role'] = $this -> user_role;

			$data['clients'] = $this -> _get_clients(); 
			$data['invoices'] = $this -> invoices_list; // GET a list of the Invoices
                        $data['currencies'] = $this -> applib -> currencies();

			$data['invoice_details'] = Applib::retrieve(Applib::$invoices_table,array('inv_id'=>$invoice_id)); 

			$this->template
			->set_layout('users')
			->build('edit_invoice',isset($data) ? $data : NULL);

		}
	}

	function pay($invoice = NULL)
	{
		if($this -> _can_pay_invoice() == FALSE){
			$this -> applib -> redirect_to('invoices','error',lang('access_denied'));
		}
		if ($this->input->post()) {
			$invoice_id = $this->input->post('invoice');
			$paid_amount = $this->input->post('amount');
		if ($this->form_validation->run('invoices','pay_invoice') == FALSE)
		{
			$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'error',lang('payment_failed'));
		}else{	
			$due = round($this -> applib -> calculate('invoice_due',$invoice_id),2);

			if ($paid_amount > $due) {
				$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'error',lang('overpaid_amount'));
			}
			
			$transaction = array(
			              'invoice' => $invoice_id,
			              'paid_by' => Applib::get_table_field(Applib::$invoices_table,array('inv_id'=>$invoice_id),'client'),
			              'payment_method' => $this->input->post('payment_method'),
			              'currency' => $this->input->post('currency'),
			              'amount' => $paid_amount,
			              'payment_date' => date_format(date_create_from_format(config_item('date_php_format'), $this->input->post('payment_date')), 'Y-m-d'),
			              'trans_id' => $this->input->post('trans_id'),
			              'notes' => $this->input->post('notes'),
			              'month_paid' => date('m'),
			              'year_paid' => date('Y'),
			            );

			if(Applib::create(Applib::$payments_table,$transaction))
				// Log Activity
                                $inv_cur = Applib::get_table_field(Applib::$invoices_table,array('inv_id'=>$invoice_id),'currency');
                                $cur_i = $this->applib->currencies($inv_cur);
                                    
					$activity = array(
					                'user'				=> $this->user,
					                'module' 			=> 'invoices',
					                'module_field_id'	=> $invoice_id,
					                'activity'			=> 'activity_payment_of',
					                'icon'				=> 'fa-usd',
                                    'value1'         	=> $cur_i->symbol.' '.$paid_amount,
                                    'value2'            => Applib::get_table_field(Applib::$invoices_table,array('inv_id' => $invoice_id),'reference_no')
					                );
					Applib::create(Applib::$activities_table,$activity); // Log activity

			if ($this->input->post('send_thank_you') == 'on') 
				$this->_send_payment_email($invoice_id,$paid_amount); //send thank you email

			$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'success',lang('payment_added_successfully'));
			}
		}else{
			$data['page'] = $this -> page;
			$data['role'] = $this -> user_role;

			$data['invoice_id'] = $invoice;
			$data['datepicker'] = TRUE;
			$data['invoices'] = $this -> invoices_list; // GET a list of the Invoices
			$data['payment_methods'] = Applib::retrieve(Applib::$payment_methods_table,array('method_id !='=>0));
			$data['invoice_details'] = Applib::retrieve(Applib::$invoices_table,array('inv_id'=>$invoice)); 

			$this->template
			->set_layout('users')
			->build('pay_invoice',isset($data) ? $data : NULL);
		}
	}


	function show($invoice_id = NULL)
	{
		$data = array('show_client'=>'Yes');
		$where = array('inv_id'=>$invoice_id);
		Applib::update(Applib::$invoices_table,$where,$data);
		$this -> applib -> redirect_to($_SERVER['HTTP_REFERER'],'success',lang('invoice_visible'));
	}
	function hide($invoice_id = NULL)
	{
		$data = array('show_client'=>'No');
		$where = array('inv_id'=>$invoice_id);
		Applib::update(Applib::$invoices_table,$where,$data);
		$this -> applib -> redirect_to($_SERVER['HTTP_REFERER'],'success',lang('invoice_not_visible'));
	}

	function stop_recur($invoice_id = NULL)
	{
		if(!$this->user_role == '1' OR $this -> applib -> allowed_module('edit_all_invoices',$this->username)){
			$this -> applib -> redirect_to('invoices','error',lang('access_denied'));
		}
		if ($this->input->post()) {

		$invoice = $_POST['invoice'];
		$this->load->model('mdl_invoices_recurring');

			if($this -> mdl_invoices_recurring -> stop($invoice)){

				// Log Activity
				$activity = array(
					                'user'			=> $this->user,
					                'module' 		=> 'invoices',
					                'module_field_id'	=> $invoice,
					                'activity'		=> 'activity_recurring_stopped',
					                'icon'			=> 'fa-plus'
					                );
					Applib::create(Applib::$activities_table,$activity); // Log activity
					$this -> applib -> redirect_to('invoices/view/'.$invoice,'success',lang('recurring_invoice_stopped'));
				}	
	}else{
			$data['invoice'] = $invoice_id;
			$this->load->view('modal/stop_recur',$data);

		}
	}


	function timeline($invoice_id = NULL)
	{		
		$data['page'] = $this->page;
		$data['role'] = $this -> user_role;
		
		$data['invoice_details'] = Applib::retrieve(Applib::$invoices_table,
									array('inv_id'=>$invoice_id)); 

		$data['activities'] = Applib::retrieve(Applib::$activities_table,
									array('module_field_id'=>$invoice_id,'module'=>'invoices')); 

		$data['invoices'] = $this->invoices_list;
		$this->template
		->set_layout('users')
		->build('timeline',isset($data) ? $data : NULL);
	}

	function delete($invoice_id = NULL)
	{
		if ($this->input->post()) {

			$invoice = $this->input->post('invoice', TRUE);

			//delete invoice items
			Applib::delete(Applib::$invoice_items_table,array('invoice_id'=>$invoice)); 
			//delete invoice payments
			Applib::delete(Applib::$payments_table,array('invoice'=>$invoice)); 
			 //clear invoice activities
			Applib::delete(Applib::$activities_table,array('module'=>'invoices', 'module_field_id' => $invoice)); 
			//delete invoice
			Applib::delete(Applib::$invoices_table,array('inv_id'=>$invoice)); 

			$this -> applib -> redirect_to('invoices','success',lang('invoice_deleted_successfully'));
		}else{
			$data['invoice'] = $invoice_id;
			$this->load->view('modal/delete_invoice',$data);

		}
	}

	function remind($invoice = NULL){

		if ($this->input->post()) {			
			$invoice = $this->input->post('invoice_id');
			$message = $this->input->post('message');

			$cur = Applib::retrieve(Applib::$invoices_table,array('inv_id' => $invoice));
                        $currency = $cur[0]->currency;
			$ref = Applib::retrieve(Applib::$invoices_table,array('inv_id' => $invoice));
                        $reference = $ref[0]->reference_no;

			$subject = $this->input->post('subject');

			$client_name = str_replace("{CLIENT}",$this->input->post('client_name'),$message);
			$amount = str_replace("{AMOUNT}",$this->input->post('amount'),$client_name);
			$currency = str_replace("{CURRENCY}",$currency,$amount);
			$link = str_replace("{INVOICE_LINK}",base_url().'invoices/view/'.$invoice,$currency);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$link);


			$this->_email_invoice($invoice,$message,$subject);

			// Log Activity
				$activity = array(
					                'user'				=> $this->user,
					                'module' 			=> 'invoices',
					                'module_field_id'	=> $invoice,
					                'activity'			=> 'activity_invoice_reminder_sent',
					                'icon'				=> 'fa-shopping-cart',
					                'value1'			=> $reference
					                );
					Applib::create(Applib::$activities_table,$activity); // Log activity

			$this -> applib -> redirect_to('invoices/view/'.$invoice,'success',lang('reminder_sent_successfully'));
		}else{
			$data['invoice_details'] = Applib::retrieve(Applib::$invoices_table,array('inv_id'=>$invoice)); 
			$this->load->view('modal/invoice_reminder',$data);
		}
	}


	function email($invoice_id = NULL){

		if ($this->input->post()) {			
			$invoice_id = $this->input->post('invoice_id');
			$ref = $this->input->post('ref');
			$subject = $this->input->post('subject');
			$message = $this->input->post('message',FALSE);

			

			$client_name = str_replace("{CLIENT}",$this->input->post('client_name'),$message);
			$Ref = str_replace("{REF}",$this->input->post('ref'),$client_name);
			$Amount = str_replace("{AMOUNT}",$this->input->post('amount'),$Ref);
			$Currency = str_replace("{CURRENCY}",$this->input->post('invoice_currency'),$Amount);
			$link = str_replace("{INVOICE_LINK}",base_url().'invoices/view/'.$invoice_id,$Currency);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$link);

			
			$this->_email_invoice($invoice_id,$message,$subject); // Email Invoice

			$data = array('emailed' => 'Yes', 'date_sent' => date ("Y-m-d H:i:s", time()));

			Applib::update(Applib::$invoices_table,array('inv_id'=>$invoice_id),$data);

			// Log Activity
				$activity = array(
					                'user'				=> $this->user,
					                'module' 			=> 'invoices',
					                'module_field_id'	=> $invoice_id,
					                'activity'			=> 'activity_invoice_sent',
					                'icon'				=> 'fa-envelope',
                                    'value1'            => $ref
					                );
			Applib::create(Applib::$activities_table,$activity); // Log activity

			$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'success',lang('invoice_sent_successfully'));
		}else{
			$data['invoice_details'] = Applib::retrieve(Applib::$invoices_table,array('inv_id'=>$invoice_id)); 
			$this->load->view('modal/email_invoice',$data);
		}
	}

	function _can_view_invoice($invoice){
		if ($this -> user_role == '1') {
			return TRUE;
		}elseif($this -> user_role == '3' AND $this -> applib -> allowed_module('view_all_invoices',$this->username)){
			return TRUE;
		}elseif($this -> user_role == '2'){
			$invoice_client =  Applib::get_table_field(Applib::$invoices_table,array('inv_id'=>$invoice),'client');
			$show_client =  Applib::get_table_field(Applib::$invoices_table,array('inv_id'=>$invoice),'show_client');
			if ($invoice_client == $this->user_company AND $show_client == 'Yes') {
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	function _can_add_invoice(){
		if ($this -> user_role == '1') {
				return TRUE;
			}elseif($this -> user_role == '3' AND $this -> applib -> allowed_module('add_invoices',$this->username)){
				return TRUE;
		}else{
				return FALSE;
		}
	}
	function _can_pay_invoice(){
		if ($this -> user_role == '1') {
				return TRUE;
			}elseif($this -> user_role == '3' AND $this -> applib -> allowed_module('pay_invoice_offline',$this->username)){
				return TRUE;
		}else{
				return FALSE;
		}
	}

	function chart()
	{
				$data['chart'] = TRUE; // Load chart JS 
				$this -> load -> view('invoices/invoice_chart',isset($data) ? $data : NULL);
	}

	function pdf()
	{
                $invoice_id = $this->uri->segment(3);
		if(!$this -> _can_view_invoice($invoice_id)){
			$this -> applib -> redirect_to('invoices','error',lang('access_denied'));
		}

		$data['page'] = $this -> page;
		$data['stripe'] = TRUE;
		$data['twocheckout'] = TRUE;
		$data['role'] = $this -> user_role;
                $data['sortable'] = TRUE;
                $data['typeahead'] = TRUE;
		$data['rates'] = Applib::retrieve(Applib::$tax_rates_table,array('tax_rate_id !='=>'0'));
                $inv = Applib::retrieve(Applib::$invoices_table,array('inv_id'=>$invoice_id));
		$data['invoice_details'] = $inv;
		$data['invoice_items'] = $this->applib->ordered_items($invoice_id);
		$data['invoices'] = $this -> invoices_list; // GET a list of the Invoices
		$data['payment_status'] = $this -> applib -> payment_status($invoice_id);

		$html = $this->load->view('invoice_pdf', $data, true);
                
                $pdf = array(
                    "html" => $html,
                    "title" => lang('invoice')." ".$inv[0]->reference_no,
                    "author" => config_item('company_name'),
                    "creator" => config_item('company_name'),
                );
                
                $this->applib->create_pdf($pdf);

	}

        
	function _send_payment_email($invoice_id,$paid_amount){
			$message = Applib::get_table_field(Applib::$email_templates_table,array('email_group' => 'payment_email'), 'template_body');
			$subject = Applib::get_table_field(Applib::$email_templates_table,array('email_group' => 'payment_email'), 'subject');
                        
			$currency = Applib::get_table_field(Applib::$invoices_table,array('inv_id' => $invoice_id), 'currency');
			$ref = Applib::get_table_field(Applib::$invoices_table,array('inv_id' => $invoice_id), 'reference_no');

			$invoice_currency = str_replace("{INVOICE_CURRENCY}",$currency,$message);
			$ref = str_replace("{INVOICE_REF}",$ref,$invoice_currency);
			$amount = str_replace("{PAID_AMOUNT}",$paid_amount,$ref);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$amount);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$client = Applib::get_table_field(Applib::$invoices_table,array('inv_id' => $invoice_id), 'client');

			$address = Applib::get_table_field(Applib::$companies_table,array('co_id' => $client), 'company_email');

			$params['recipient'] = $address;

			$params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
			$params['message'] = $message;
			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}


	 function _email_invoice($invoice_id,$message,$subject){
	 	
            $client = Applib::get_table_field(Applib::$invoices_table,array('inv_id' => $invoice_id),'client');

            $invoice = $this -> db -> where('inv_id',$invoice_id) -> get(Applib::$invoices_table) -> row();
            $recipient = Applib::get_table_field(Applib::$companies_table,array('co_id'=>$client),'company_email');

            $data['message'] = $message;

			$message = $this->load->view('email_template', $data, TRUE);


            $params = array(
                            'recipient' => $recipient,
                            'subject'   => $subject,
                            'message'   => $message
                            );

            $this->load->helper('file');
            $attach['inv_id'] = $invoice_id;

            $invoicehtml = modules::run('fopdf/attach_invoice',$attach);
            
            $params['attached_file'] = './resource/tmp/'.lang('invoice').' '.$invoice->reference_no.'.pdf';
            $params['attachment_url'] = base_url().'resource/tmp/'.lang('invoice').' '.$invoice->reference_no.'.pdf';

            modules::run('fomailer/send_email',$params);
			//Delete invoice in tmp folder
			unlink('./resource/tmp/'.lang('invoice').' '.$invoice->reference_no.'.pdf');
    }

	function _make_recurring($invoice,$data){
		$recur_days = $this -> _calculate_days($data['r_freq']);
		$due_date = Applib:: get_table_field(Applib::$invoices_table,array('inv_id'=>$invoice),'due_date');
		$next_date = date("Y-m-d",strtotime($due_date."+ ".$recur_days." days"));
		if ($data['recur_end_date'] == '') {
			$recur_end_date = '0000-00-00';
		}else{
			$recur_end_date = date_format(date_create_from_format(config_item('date_php_format'), $data['recur_end_date']), 'Y-m-d');
		}
		$update_invoice = array(
		                        'recurring' => 'Yes',
		                        'r_freq' => $recur_days,
		                        'recur_frequency' => $data['r_freq'],
		                        'recur_start_date'=>date_format(date_create_from_format(config_item('date_php_format'), $data['recur_start_date']), 'Y-m-d'),
		                        'recur_end_date'=>$recur_end_date,
		                        'recur_next_date' => $next_date
		                        );
		Applib::update( Applib::$invoices_table, array('inv_id'=>$invoice), $update_invoice);
		return TRUE; 

	}
	function _calculate_days($frequency){
		switch ($frequency)
			        {
			            case '7D':	
			            	return 7;
			                break;
			            case '1M':
			                return 31;
			                break;
			            case '3M':
			                return 90;
			                break;
			            case '6M':
			                return 182;
			                break;
			            case '1Y':
			                return 365;
			                break;
			        }
	}

	function _get_clients(){		
			$sort = array('order_by' => 'date_added','order' => 'desc');
			return Applib::retrieve(Applib::$companies_table,array('co_id !='=>'0')); 

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
					modules::run('activity/log',$params); //pass to activitylog module
	}
}

/* End of file invoices.php */