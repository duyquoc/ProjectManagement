<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class AppLib {

	private static $db;

	// Define system tables

	public static $user_table = 'users';
	public static $invoices_table = 'invoices';
	public static $invoice_items_table = 'items';
	public static $tax_rates_table = 'tax_rates';
	public static $payments_table = 'payments';
	public static $payment_methods_table = 'payment_methods';
	public static $estimates_table = 'estimates';
	public static $estimate_items_table = 'estimate_items';
	public static $milestones_table = 'milestones';
	public static $projects_table = 'projects';
	public static $project_timer_table = 'project_timer';
	public static $saved_tasks_table = 'saved_tasks';
	public static $task_files_table = 'task_files';
	public static $tasks_table = 'tasks';
	public static $task_timer_table = 'tasks_timer';
	public static $profile_table = 'account_details';
	public static $activities_table = 'activities';
	public static $assigned_projects_table = 'assign_projects';
	public static $assign_tasks_table = 'assign_tasks';
	public static $bug_comments_table = 'bug_comments';
	public static $bug_files_table = 'bug_files';
	public static $bugs_table = 'bugs';
	public static $comment_replies_table = 'comment_replies';
	public static $comments_table = 'comments';
	public static $companies_table = 'companies';
	public static $config_table = 'config';
	public static $departments_table = 'departments';
	public static $email_templates_table = 'email_templates';
	public static $custom_fields_table = 'fields';
	public static $files_table = 'files';
	public static $item_lookup_table = 'items_saved';
	public static $messages_table = 'messages';
	public static $ticket_replies_table = 'ticketreplies';
	public static $tickets_table = 'tickets';
	public static $links_table = 'links';
	

	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->database();

		self::$db = &get_instance()->db;

	}
	public function count_table_rows($table)
    	{
	$query = $this->ci->db->get($table);
		if ($query->num_rows() > 0)
			{
  		 return $query->num_rows();
  		}else{
  			return 0;
  		}
	}

	/**
	 * Get all records in $table.
	 *
	 * @return Table Data array
	 */

	 static function retrieve($table,$where = array(),$limit = NULL) {  

	 	return self::$db->where($where)->get($table,$limit)->result();                                                                                                            
    }   

    /**
	 * Insert records to $table.
	 *
	 * @return Inserted record ID
	 */

	 static function create($table,$data = array()) {  

	 	self::$db -> insert($table,$data);  
	 	return self::$db -> insert_id();                                                                                                          
    }   


    /**
	 * Update records in $table matching $match.
	 *
	 * @return Affected rows int
	 */

	 static function update($table,$match = array(),$data = array()) {  

	 	self::$db -> where($match) -> update($table,$data);  
	 	return self::$db->affected_rows();                                                                                                          
    }   

     /**
	 * Deletes data matching $where in $table.
	 *
	 * @return boolean
	 */

	 static function delete($table,$where = array()) {  

	 	return self::$db->delete($table,$where);                                                                                                            
    }   

    /**
	 * Get all records in $table matching $table_criteria.
	 *
	 * @return Table field value string
	 */

    static function get_table_field($table, $where_criteria = array(), $table_field) {

		return self::$db -> select($table_field) -> where($where_criteria) -> get($table)->row()->$table_field;

	}

	/**
	 * Get user login information
	 *
	 * @return User data array
	 */

	 static function login_info($id) {  
	 	return self::$db -> where('id',$id) -> get(Applib::$user_table)->row();                                                                                                           
    } 
    /**
	 * Get user profile information
	 *
	 * @return User data array
	 */

	 static function profile_info($id) {  
	 	return self::$db -> where('user_id',$id) -> get(Applib::$profile_table)->row();                                                                                                           
    }   

	static function make_flashdata($data){
		$ci =& get_instance();
		foreach ($data as $key => $value) {
			$ci->session->set_flashdata($key, $value);
		}
		
	}
	/**
	 * Test whether in demo mode
	 *
	 * @return redirect to request page
	 */


	static function is_demo(){
		if (config_item('demo_mode') == 'TRUE') {
			Applib::make_flashdata(array('response_status' => 'error','message' => lang('demo_warning')));
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	/**
	 * Counts num of records in $table.
	 *
	 * @return int
	 */

	 static function count_num_rows($table,$where = array()) {  

	 	return self::$db->where($where)->get($table)->num_rows();                                                                                                            
    }  

    /**
	 * Create a dir
	 *
	 * @return boolean
	 */

	 static function create_dir($path) {
                 if (!is_dir($path))
                    {
                            mkdir($path, 0777, true);
                     }
                     return TRUE;
         }

	public function validate_api_key($username,$api_key)
   	 {
   	 	$user_id = $this -> get_any_field('users',array(
														'username'=>$username
														),'id');
		$user_api_key = $this -> get_any_field(config_item('rest_keys_table'),array(
														'user'=>$user_id
														),'api_key');
  		if ($user_api_key == $api_key) {
  			return TRUE;
  		}else{
  			return FALSE;
  		}
	}

	static function protect_install(){
		if (is_dir('./install/')) {
			if(!rename('./install','./install.'.rand(5, 1000)))
			$ci =& get_instance();
			$ci -> session -> set_flashdata('response_status', 'error');
			$ci -> session -> set_flashdata('message', lang('manually_rename_install'));
		}
	}
	static function valid_sale(){
		$ci =& get_instance();
		if(config_item('valid_license') != 'TRUE'){
			$ci -> session -> set_flashdata('response_status', 'error');
			$ci -> session -> set_flashdata('message', lang('purchase_not_validated'));
			redirect('settings?settings=system');
		}	
	}

	static function pData(){
		$ci =& get_instance();
		$ci->load->helper('curl');
		$pc === config_item('purchase_code');
        $envato_username === config_item('envato_username');

        $purchase = remote_get_contents(UPDATE_URL.'verify.php?code='.$pc);
        $purchase_data = json_decode($purchase,true);
        if(!isset($purchase_data['buyer']))
            self::_p(lang('unable_verify_purchase'));

        if($purchase_data['buyer'] != $envato_username)
           self::_p(lang('set_envato_username'));

       return TRUE;
	}

	static function _p($msg){
		Applib::switchoff();
		$ci =& get_instance();
            $ci->session->set_flashdata('response_status', 'error');
            $ci->session->set_flashdata('message', $msg);
            redirect('settings?settings=system');
	}

	function redirect_to($redirect_url,$response,$message){
			$this -> ci -> session -> set_flashdata('response_status', $response);
			$this -> ci -> session -> set_flashdata('message', $message);
			redirect($redirect_url);
	}

	function allowed_module($module,$username)
   	 {
   	 	$user_id = $this -> get_any_field('users',array('username'=>$username),'id');
   	 	$allowed_modules_json = $this -> get_any_field(Applib::$profile_table,array('user_id'=>$user_id),'allowed_modules');
   	 	if ($allowed_modules_json == NULL) {
   	 		$allowed_modules_json = '{"settings":"permissions"}';
   	 	}
   	 	$allowed_modules = json_decode($allowed_modules_json);
   	 	if ( array_key_exists($module, $allowed_modules) ) {
                     return TRUE;
        }else{
  					return FALSE;
  		}
	}

	function get_gravatar($email){
		$this -> ci-> load -> library('gravatar');
		$gravatar_url = $this -> ci-> gravatar -> get($email);
		return $gravatar_url;
	}

	function project_setting($setting,$project)
   	 {
   	 	$project_settings_json = $this -> get_any_field(self::$projects_table,array('project_id'=>$project),'settings');
   	 	if ($project_settings_json == NULL) {
   	 		$project_settings_json = '{"settings":"on"}';
   	 	}
   	 	$project_settings = json_decode($project_settings_json);
   	 	if ( array_key_exists($setting, $project_settings) ) {
                     return TRUE;
        }else{
  					return FALSE;
  		}
	}

	public function invoice_perc($invoice)
   	 {
   	 $invoice_payment = $this->invoice_payment($invoice);
   	 $invoice_payable = $this->invoice_payable($invoice);
   	 if ($invoice_payable < 1 OR $invoice_payment < 1) {
   	 	$perc_paid = 0;
   	 }else{
   	 	$perc_paid = ($invoice_payment/$invoice_payable)*100;
   	 }
		return round($perc_paid);
	}
        
	public function invoice_payment($invoice)
   	 {
	$this->ci->db->where('invoice',$invoice);
	$this->ci->db->select_sum('amount');
	$query = $this->ci->db->get('payments');
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->amount;
  		}
	}
        
        function ordered_items($id,$type='invoice') {
            $table = ($type == 'invoice' ? '' : 'estimate_').'items';
            $result = $this->ci->db->where($type.'_id',$id)->order_by('item_order','asc')->get($table)->result();
            return $result;
        }

	function calculate($invoice_value,$invoice){
		switch ($invoice_value)
			        {
			            case 'invoice_cost':	
			            	return $this->_invoice_cost($invoice);
			                break;
			            case 'tax':
			                return $this->_invoice_tax_amount($invoice);
			                break;
			            case 'discount':
			                return $this->_invoice_discount($invoice);
			                break;
			            case 'paid_amount':
			                return $this->_invoice_paid_amount($invoice);
			                break;
			            case 'invoice_due':
			                return $this->_invoice_due_amount($invoice);
			                break;
			        }
	}

	function _invoice_cost($invoice){
		return self::$db -> select_sum('total_cost') 
						 -> where('invoice_id',$invoice) 
						 -> get(Applib::$invoice_items_table) 
						 -> row()->total_cost;
	}

	static function _invoice_tax_amount($invoice){
		$invoice_cost = self:: _invoice_cost($invoice);
		$tax = self::get_table_field(Applib::$invoices_table, array('inv_id'=>$invoice), 'tax');
		return ($tax/100) * $invoice_cost;
	}
	static function _invoice_discount($invoice){
		$invoice_cost = self::_invoice_cost($invoice);
		$discount = AppLib::get_table_field(Applib::$invoices_table, array('inv_id'=>$invoice), 'discount');
		return ($discount/100) * $invoice_cost;
	}
	static function _invoice_paid_amount($invoice){
		return self::$db -> select_sum('amount') 
						 -> where('invoice',$invoice) 
						 -> get(Applib::$payments_table) 
						 -> row()
						 ->amount;	
	}
	function _invoice_due_amount($invoice){

		$tax =self:: _invoice_tax_amount($invoice);
		$discount = self::_invoice_discount($invoice);
		$invoice_cost =self:: _invoice_cost($invoice);
		$payment_made = self::_invoice_paid_amount($invoice);
		$due_amount =  (($invoice_cost - $discount) + $tax) - $payment_made;
		if($due_amount <= 0){ $due_amount = 0; }
		return $due_amount;
	}

	function est_calculate($estimate_value,$estimate){
		switch ($estimate_value)
			        {
			            case 'estimate_cost':	
			            	return $this->_estimate_cost($estimate);
			                break;
			            case 'tax':
			                return $this->_estimate_tax_amount($estimate);
			                break;
			            case 'discount':
			                return $this->_estimate_discount($estimate);
			                break;
			            case 'estimate_amount':
			                return $this->_estimate_amount($estimate);
			                break;
			        }
	}

	function _estimate_cost($estimate){
		$row = $this -> ci -> db -> select_sum('total_cost') -> where('estimate_id',$estimate) -> get('estimate_items') -> row();
  		 return $row->total_cost;
	}

	function _estimate_tax_amount($estimate){
		$estimate_cost = $this -> _estimate_cost($estimate);
		$tax = $this -> get_any_field(self::$estimates_table, array('est_id'=>$estimate), 'tax');
		return ($tax/100) * $estimate_cost;
	}
	function _estimate_discount($estimate){
		$estimate_cost = $this -> _estimate_cost($estimate);
		$discount = $this -> get_any_field(self::$estimates_table, array('est_id'=>$estimate), 'discount');
		return ($discount/100) * $estimate_cost;
	}
	function _estimate_amount($estimate){

		$tax = $this -> _estimate_tax_amount($estimate);
		$discount = $this -> _estimate_discount($estimate);
		$estimate_cost = $this -> _estimate_cost($estimate);
		return (($estimate_cost - $discount) + $tax);

	}

	function pro_calculate($project_value,$project){
		switch ($project_value)
			        {
			            case 'project_cost':	
			            	return $this->_project_cost($project);
			                break;
			            case 'project_hours':	
			            	return $this->_project_hours($project);
			                break;
			        }
	}
	function _project_cost($project){
		$project_hours = $this->_project_hours($project);
		$fix_rate = $this -> get_any_field('projects', array('project_id'=>$project), 'fixed_rate' ); 
		$hourly_rate = $this -> get_any_field('projects', array('project_id'=>$project), 'hourly_rate' ); 
			if ($fix_rate == 'No') {
				return $project_hours * $hourly_rate;
			}else{
				return $this -> get_any_field('projects', array('project_id'=>$project), 'fixed_price' ); 
			}
	}
	function _project_hours($project){		
		$task_time = $this->_calculate_task_time($project);
		$project_time = $this->_calculate_project_time($project);		
		$logged_time = ($task_time + $project_time)/3600;
		return round($logged_time,2);
	}

	function _calculate_task_time($project){
		$total_time = "SELECT start_time,end_time,pro_id,
		end_time - start_time time_spent FROM fx_tasks_timer WHERE pro_id = '$project'";
		$res = $this -> ci -> db -> query($total_time)->result();
		$a = array();
		foreach ($res as $key => $t) {
			$a[] = $t->time_spent;
		}
		if(is_array($a)){
			return array_sum($a);
		}else{
			return 0;
		}
		
	}

	function all_outstanding(){
		$invoices = self::$db -> get(Applib::$invoices_table) -> result();
		$due[] = array();
		foreach ($invoices as $key => $invoice) {
			$due[] = self::_invoice_due_amount($invoice->inv_id);
		}
		if(is_array($due)){
			return round(array_sum($due),2);
		}else{
			return 0;
		}
		
	}

	function client_outstanding($user){
		$user_company = $this -> get_any_field(self::$profile_table,array('user_id'=>$user),'company');
		$due[] = array();

		$invoices = $this -> ci -> db -> where('client',$user_company) -> get(self::$invoices_table) -> result();
		foreach ($invoices as $key => $invoice) {
			$due[] = $this->_invoice_due_amount($invoice->inv_id);
		}
		if(is_array($due)){
			return round(array_sum($due),2);
		}else{
			return 0;
		}
		
	}

	function all_invoice_amount(){
		$invoices = $this -> ci -> db -> get(self::$invoices_table) -> result();
		$cost[] = array();
		foreach ($invoices as $key => $invoice) {
		$tax = round($this -> _invoice_tax_amount($invoice->inv_id));
		$discount = round($this -> _invoice_discount($invoice->inv_id));
		$invoice_cost = round($this -> _invoice_cost($invoice->inv_id));

			$cost[] = ($invoice_cost + $tax) - $discount;
		}
		if(is_array($cost)){
			return round(array_sum($cost),2);
		}else{
			return 0;
		}
		
	}


	function task_time_spent($task){
		$total_time = "SELECT start_time,end_time,pro_id,end_time - start_time time_spent 
						FROM fx_tasks_timer WHERE task = '$task'";
		$res = $this -> ci -> db -> query($total_time)->result();
		$a = array();
		foreach ($res as $key => $t) {
			$a[] = $t->time_spent;
		}
		if(is_array($a)){
			return array_sum($a);
		}else{
			return 0;
		}
	}
	function _calculate_project_time($project){
		$total_time = "SELECT start_time,end_time,project,
		end_time - start_time time_spent FROM fx_project_timer WHERE project = '$project'";
		$res = $this -> ci -> db -> query($total_time)->result();
		$a = array();
		foreach ($res as $key => $t) {
			$a[] = $t->time_spent;
		}
		if(is_array($a)){
			return array_sum($a);
		}else{
			return 0;
		}
		
	}

	static function cal_amount($type,$year,$month){
		switch ($type)
			        {
			            case 'received':	
			            	return self::_chart_received_amount($year,$month);
			                break;
			        }
	}

	static function _chart_received_amount($year,$month){
		$amount = self::$db->select_sum('amount')
					  ->where(array('month_paid'=>$month,'year_paid' => $year))
					  ->get(Applib::$payments_table)
					  ->row()->amount;
		return ($amount > 0) ? $amount : 0;
	}

	

	function cal_milestone_progress($milestone){
		$all_milestone_tasks = $this -> ci -> db -> where('milestone',$milestone) -> get('tasks') -> num_rows();
		$complete_milestone_tasks = $this -> ci -> db -> where(
		                                                       array('task_progress'=>'100',
		                                                             'milestone'=>$milestone
		                                                             )) -> get('tasks') -> num_rows();
		if ($all_milestone_tasks > 0) {
			return round(($complete_milestone_tasks/$all_milestone_tasks) * 100);
		}else{
			return 0;
		}
		
	}
	
	public function total_tax($client = NULL)
   	 {
   	 	$avg_tax = $this->average_tax($client);
		$invoice_amount = $this->get_sum('items','total_cost',array('total_cost >'=>0));
		$tax = ($avg_tax/100) * $invoice_amount;
		return $tax;
	}
	function client_tax($client)
   	 {
   	 	$avg_tax = $this->average_tax($client);
		$invoice_amount = $this->client_payable($client);
		$tax = ($avg_tax/100) * $invoice_amount;
		return $tax;
	}
	function average_tax($client)
   	 {
   	 	$this->ci->db->select_avg('tax');
   	 	if($client != NULL){ $this->ci->db->where('client',$client); }   	 	
		$query = $this->ci->db->get(Applib::$invoices_table)->row();
		return $query->tax;
	}
	public function client_paid($client)
   	 {
		$query = $this->ci->db->where('paid_by',$client)->select_sum('amount')->get('payments')->row();
		return $query->amount;
	}
	public function invoice_payable($invoice)
   	 {
	$this->ci->db->select_sum('total_cost');
	$query = $this->ci->db->where('invoice_id',$invoice)->get('items');
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->total_cost;
  		}
	}

	public function client_invoices($client)
   	 {
	return $this->ci->db->get_where('invoices',array('client' => $client))->result_array();
	}

	function get_any_field($table, $where_criteria, $table_field) {
	$query = $this -> ci -> db -> select($table_field) -> where($where_criteria) -> get($table);
		if ($query->num_rows() > 0)
			{
  		 		$row = $query -> row();
  		 		return $row -> $table_field;
  			}
	}

	function payment_status($invoice) {
		$tax = $this -> _invoice_tax_amount($invoice);		
		$discount = $this -> _invoice_discount($invoice);
		$invoice_cost = $this -> _invoice_cost($invoice);
		$payment_made = round($this->_invoice_paid_amount($invoice),2);
		$due = round(((($invoice_cost - $discount) + $tax) - $payment_made));

		if($payment_made < 1){
			return lang('not_paid');
		}elseif ($due <= 0) {
			return lang('fully_paid');
		}else{
			return lang('partially_paid');
		}
	}

	function get_time_spent($seconds){
		$minutes = $seconds/60;
		$hours = $minutes/60;
		if ($minutes >= 60) {
			return round($hours,2).' '.lang('hours');
		}elseif($seconds > 60){
			return round($minutes,2).' '.lang('minutes');
		}else{
			return $seconds.' '.lang('seconds');
		}
	}

	public function client_payable($client)
   	 {
   	 	$this->ci->db->join('invoices','invoices.inv_id = items.invoice_id');
		$this->ci->db->select_sum('total_cost');
		$this->ci->db->where('client', $client);
		$query = $this->ci->db->get('items');
		if ($query->num_rows() > 0)
			{
  		 	$row = $query->row();
  		 	$sum_total = $row->total_cost;
  		 	return $sum_total;
  		}else{
  			return 0;
  		}
	}

	public function estimate_payable($estimate)
   	 {
	$query = $this->ci->db->where('estimate_id',$estimate)->select_sum('total_cost')->get('estimate_items');
  	$row = $query->row();
  	return $row->total_cost;
	}
	public function average_monthly_paid($month)
   	 {
   	 $month_paid = $this->monthly_payment($month);
   	 $amount_paid = $this->total_payments();
   	 if ($amount_paid == 0 OR $month_paid == 0) {
   	 	$perc_paid = 0;	
  		 return $perc_paid;
   	 }else{
   	 $perc_paid = ($month_paid/$amount_paid)*100;	
  		 return round($perc_paid);
  		}
	}

	function project_hours($project){
		$task_time = $this->get_sum('tasks','logged_time',array('project'=>$project));
		$project_time = $this->get_sum('projects','time_logged',array('project_id'=>$project));
		$logged_time = ($task_time + $project_time)/3600;
		return $logged_time;
	}
	public function total_payments()
   	 {
	$this->ci->db->select_sum('amount');
	$query = $this->ci->db->get('payments');
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->amount;
  		}
	}
	public function generate_string()
   	 {
   	 $this->ci->load->helper('string');
   	 return random_string('nozero', 7);
	}
	function prep_response($response){
		return json_decode($response,TRUE);
	}

	function generate_invoice_number() {
	$query = $this -> ci -> db -> select_max('inv_id') -> get(Applib::$invoices_table);
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
            $next_number = ++$row->inv_id;
			$next_number = $this->_ref_no_exists($next_number);
            $next_number = sprintf('%04d', $next_number);
			return $next_number;
		}else{
			return sprintf('%04d', '1');
		} 
	}
	function _ref_no_exists($next_number){
        $next_number = sprintf('%04d', $next_number);

        $records = $this->ci->db->where('reference_no',config_item('invoice_prefix').$next_number)->get(Applib::$invoices_table)->num_rows();
		if ($records > 0) {
			return $this->_ref_no_exists($next_number + 1);
		}else{
			return $next_number;
		}
	}

	function get_project_details($project,$field) {
	$this->ci->db->where('project_id',$project);
	$this->ci->db->select($field);
	$query = $this->ci->db->get(Applib::$projects_table);
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->$field;
  		}
	}

	function estimate_details($estimate,$field) {
	$this->ci->db->where('est_id',$estimate);
	$this->ci->db->select($field);
	$query = $this->ci->db->get('estimates');
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->$field;
  		}
	}
	function payment_details($pid,$field) {
	$this->ci->db->where('p_id',$pid);
	$this->ci->db->select($field);
	$query = $this->ci->db->get('payments');
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->$field;
  		}
	}
	function company_details($company,$field) {
	$this->ci->db->where('co_id',$company);
	$this->ci->db->select($field);
	$query = $this->ci->db->get('companies');
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->$field;
  		}
	}
	function count_rows($table,$where)
	{
		$this->ci->db->where($where);
		$query = $this->ci->db->get($table);
		if ($query->num_rows() > 0){
			return $query->num_rows();
		} else{
			return 0;
		}
	}
	function get_sum($table,$field,$where)
	{
		$this->ci->db->where($where);
		$this->ci->db->select_sum($field);
		$query = $this->ci->db->get($table);
		if ($query->num_rows() > 0){
		$row = $query->row();
  		 return $row->$field;
		} else{
			return 0;
		}
	}

	function get_time_diff($from , $to){
	$diff = abs ( $from - $to );
	$years = $diff/31557600;
	$months = $diff/2635200;
	$weeks = $diff/604800;
	$days = $diff/86400;
	$hours = $diff/3600;
	$minutes = $diff/60;
	if ($years > 1) {
		$duration = round($years) .lang('years');
	}elseif ($months > 1) {
		$duration = round($months) .lang('months');
	}elseif ($weeks > 1) {
		$duration = round($weeks) .lang('weeks');
	}elseif ($days > 1) {
		$duration = round($days).lang('days');
	}elseif ($hours > 1) {
		$duration = round($hours) .lang('hours');
	} else {
		$duration = round($minutes).lang('minutes');
	}
	
	return $duration;
	}

	

	static function remote_get_contents($url)
{
        if (function_exists('curl_get_contents') AND function_exists('curl_init'))
        {
                return $this->ci->curl_get_contents($url);
        }
        else
        {
                return file_get_contents($url);
        }
}

function curl_get_contents($url)
{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
}

	

  static function switchoff(){
            Applib::update(Applib::$config_table,array('config_key'=>'valid_license'),array('value'=>'FALSE'));
        }
  static function switchon(){
            Applib::update(Applib::$config_table,array('config_key'=>'valid_license'),array('value'=>'TRUE'));
        }

  static function pc(){
  	$purchase = self::$db->where('config_key','valid_license')->get(self::$config_table)->row();
  	return ($purchase->value == 'FALSE') ? 'Not verified' : 'Verified';
  	}
  
    function currencies($code = FALSE)
    {
            if (!$code) {
                    return $this->ci->db->order_by('name','ASC')->get('currencies')->result();
            }
            $c = $this->ci->db->where('code',$code)->get('currencies')->result();
            return $c[0];
    }
  
    function languages($lang = FALSE)
    {
            if (!$lang) {
                    return $this->ci->db->order_by('name','ASC')->get('languages')->result();
            }
            $c = $this->ci->db->where('name',$lang)->get('languages')->result();
            return $c[0];
    }
    
    function locales()
    {
            return $this->ci->db->order_by('name')->get('locales')->result();
    }
    
    function translations()
    {
    	$tran = array();
            $companies = $this->ci->db->select('language')->group_by('language')->order_by('language','ASC')->get('companies')->result();
            $users = $this->ci->db->select('language')->group_by('language')->order_by('language','ASC')->get(Applib::$profile_table)->result();
            foreach ($companies as $lang) { $tran[$lang->language] = $lang->language; }
            foreach ($users as $lan) { $tran[$lan->language] = $lan->language; }
            unset($tran['english']);
            return $tran;
    }
    
    function file_size($url)
    {
            $this->ci->load->helper('file');
            $info = get_file_info($url);
            return $info['size'];
    }
    
    function client_language($co_id = FALSE)
    {
            if (!$co_id) { return FALSE; }
            
            $client = $this->ci->db->where('co_id', $co_id)->get('companies')->result();
            $language = $this->ci->db->where('name',$client[0]->language)->get('languages')->result();
            return $language[0];
    }
    
    function file_icon($ext = FALSE) 
    {
            $icon = "fa-file-o";
            if (!$ext) { return $icon; }
            
            if (in_array($ext, array('.pdf'))) { $icon = 'fa-file-pdf-o'; }
            if (in_array($ext, array('.doc', '.docx', '.odt'))) { $icon = 'fa-file-word-o'; }
            if (in_array($ext, array('.xls', '.xlsx', '.ods'))) { $icon = 'fa-file-excel-o'; }
            if (in_array($ext, array('.mp3', '.wav'))) { $icon = 'fa-file-sound-o'; }
            if (in_array($ext, array('.zip', '.rar', '.gzip', '.7z'))) { $icon = 'fa-file-archive-o'; }
            if (in_array($ext, array('.txt'))) { $icon = 'fa-file-text-o'; }
            if (in_array($ext, array('.ppt', 'pptx'))) { $icon = 'fa-file-powerpoint-o '; }
            if (in_array($ext, array('.mp4', 'avi', 'wmv', 'qt', 'mpg', 'mkv'))) { $icon = 'fa-file-video-o'; }
            if (in_array($ext, array('.php', '.html', '.sql', '.xml', '.js', 'css'))) { $icon = 'fa-file-code-o'; }
            if (in_array($ext, array('.psd'))) { $icon = 'fa-camera-retro'; }
            if (in_array($ext, array('.ai', '.cdr', 'eps', 'svg'))) { $icon = 'fa-paint-brush'; }
            
            return $icon;
        
    }
                                    

    function client_currency($co_id = FALSE)
    {
            if (!$co_id) { return FALSE; }
            
            $client = $this->ci->db->where('co_id', $co_id)->get('companies')->result();
            $currency = $this->ci->db->where('code',$client[0]->currency)->get('currencies')->result();
            return $currency[0];
    }
    
    function short_string($string = FALSE, $from_start = 30, $from_end = 10, $limit = FALSE)
    {
            if (!$string) { return FALSE; }
            if ($limit) { if (mb_strlen($string) < $limit) { return $string; } }
            return mb_substr($string, 0, $from_start - 1)."...".($from_end > 0 ? mb_substr($string, - $from_end) : '' );
    }
    
    function set_locale($user = FALSE)
    {
        if (!$user) {
            $locale_config = $this->ci->db->where('config_key','locale')->get('config')->result();
            $locale = $this->ci->db->where('locale',$locale_config[0]->value)->get('locales')->result();
        } else {
            $locale_user = $this->ci->db->where('user_id',$user)->get(Applib::$profile_table)->result();
            if (empty($locale_user[0]->locale)) { $loc = 'en-US'; } else { $loc = $locale_user[0]->locale; }
            $locale = $this->ci->db->where('locale',$loc)->get('locales')->result();
        }
            $loc = $locale[0];
            $loc_unix = $loc->locale.".UTF-8";
            $loc_win = str_replace("_", "-", $loc->locale);
            setlocale(LC_ALL, $loc_unix, $loc_win, $loc->code);
            return $loc;
    }
    
}

/* End of file User_prof.php */