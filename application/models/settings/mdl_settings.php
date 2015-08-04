<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| @package Freelancer Office
|--------------------------------------------------------------------------
|
| 
*/
class Mdl_settings extends CI_Model {

     public function __construct()
     {
      parent::__construct();
     }

     function GetSettings($table){

		return $this -> db -> get($table)->result();
	}
	function ClientInvoices($table, $username, $order_by, $order){
		$UserId = Applib::get_table_field('users',array('username' => $username),'id'); 
		$UserCompany = Applib::get_table_field(Applib::$profile_table,array('user_id' => $UserId),'company'); 

		return $this -> db -> where(array('client' => $UserCompany)) -> order_by($order_by,$order) -> get($table)->result();		
	}    
	function InvoiceById($table, $id ,$username){
		if ($this -> _allowed_to_view_invoice($username,$id)) {
            return $this -> db -> where(array('inv_id'=>$id)) -> get($table)->result(); // If allowed to view Invoice
        }  else{ return NULL; }
		
	}
	function Delete($id){
		return $this -> db -> where(array('inv_id'=>$id)) -> delete('invoices');
	}
	function InvoiceTotal($id) {
		return $this -> applib -> invoice_payable($id);
	}
	function InvoicePaidTotal($id){
			$query = $this -> db -> select_sum('amount') -> where(array('invoice' => $id)) -> get('payments');
			$row = $query->row();
		  	return $row->amount;
	}
	function InvoiceStatus($invoice){
		$invoice_payable = $this -> applib -> invoice_payable($invoice);
		$invoice_paid = $this -> applib -> invoice_payment($invoice);
		$due = $invoice_payable - $invoice_paid;
		if($invoice_paid < 1){
			return lang('not_paid');
		}elseif ($due <= 0) {
			return lang('fully_paid');
		}else{
			return lang('partially_paid');
		}
	}
	function _allowed_to_view_invoice($username,$invoice){
		$RoleId = Applib::get_table_field('users',array('username' => $username),'role_id');
		if ($RoleId == '1') {
		 	return TRUE;
		 } else{
		 	$UserId = Applib::get_table_field('users',array('username' => $username),'id');
		 	$UserCompany = Applib::get_table_field(Applib::$profile_table,array('user_id' => $UserId),'company');
		 	$InvoiceClient = Applib::get_table_field('invoices',array('inv_id' => $invoice),'client');
		 	if ($UserCompany == $InvoiceClient) {
		 		return TRUE;
		 	}else{
		 		return FALSE;
		 	}
		 }
		 
	}
}
     
     /* End of file mdl_invoice.php */
     /* Location: ./application/models/mdl_invoice.php */ 