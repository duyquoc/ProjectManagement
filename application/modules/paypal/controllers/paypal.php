<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *
 * @package	Freelancer Office
 */
class Paypal extends MX_Controller {

	function Paypal()
	{
		parent::__construct();		
		$this->load->library('tank_auth');
		$this->load->library('Paypal_Lib');
		$this->load->model('mdl_pay','AppPay');
	}
	
	function index()
	{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('paypal_canceled'));
				redirect('clients');
	}
	
	function pay($invoice = NULL)
	{
		$userid = $this->tank_auth->get_user_id();
		$reference_no = Applib::get_table_field(Applib::$invoices_table,array('inv_id'=>$invoice),'reference_no');
		$currency = Applib::get_table_field(Applib::$invoices_table,array('inv_id'=>$invoice),'currency');

		$invoice_due = $this -> applib -> calculate('invoice_due',$invoice);
		if ($invoice_due <= 0) {  $invoice_due = 0.00;	}

		$data['invoice_info'] = array(
		    'item_name'=> $reference_no, 
			'item_number' => $invoice,
			'currency' => $currency,
			'amount' => $invoice_due) ;

		if (config_item('paypal_live') == 'FALSE') {
			$paypalurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}else{
			$paypalurl = 'https://www.paypal.com/cgi-bin/webscr';
		}
		$data['paypal_url'] = $paypalurl;
		
		$this->load->view('form',$data);
	}
	function cancel()
	{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('paypal_canceled'));
				redirect('clients');
	}
	
	function success()
	{
        if($_POST){
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', lang('payment_added_successfully'));
				redirect('clients');
        }else{
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', 'Something went wrong please contact us if your Payment doesn\'t appear shortly');
        redirect('clients');
        }
	}
}


////end 