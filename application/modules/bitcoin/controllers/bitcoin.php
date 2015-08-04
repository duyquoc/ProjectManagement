<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *
 * @package	Freelancer Office
 */
class bitcoin extends MX_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->library('tank_auth');
		$this->load->model('bitcoinpay', 'AppPay');
		$this->invoice_table = 'invoices';
		$this->clients_table = 'companies';
	}
	
	function pay($invoice = NULL)
	{
		$userid = $this->tank_auth->get_user_id();
		$reference_no = $this -> applib->get_any_field('invoices',array('inv_id'=>$invoice),'reference_no');
		$currency = $this -> applib->get_any_field('invoices',array('inv_id'=>$invoice),'currency');

		$invoice_due = $this -> applib -> calculate('invoice_due',$invoice);
		if ($invoice_due <= 0) {  $invoice_due = 0.00;	}

		$data['invoice_info'] = array('item_name'=> $reference_no, 
										'item_number' => $invoice,
										'currency' => $currency,
										'amount' => $invoice_due) ;
		$data['bitcoin'] = TRUE;
		
		$this->load->view('form',$data);
	}
	function cancel()
	{
		$this->session->set_flashdata('response_status', 'error');
		$this->session->set_flashdata('message', 'Bitcoin payment canceled.');
		redirect('clients');
	}
	
	function success(){
		function round_up ( $value, $precision ) { 
			$pow = pow ( 10, $precision ); 
			return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
		}
		$transactionid = $_GET['transaction_hash'];
		$invoiceid = $_GET['invoice'];
		$invoicename = $_GET['invoicename'];
		$usdamount = $_GET['usdamount'];
		$btcamount = $_GET['btcamount'];
		$client = $_GET['client'];
		$amountsentsatoshi = $_GET['value'];
		$amountsent = $amountsentsatoshi / 100000000;
		$client_username = $this->user_profile->get_user_details($client,'username'); //get client username
		$client_email = $this->user_profile->get_user_details($client,'email'); //get client email
		$ratio = $amountsent / $btcamount;
		$paid = $usdamount * $ratio;
		$paid = round_up($paid, 2);
		
		$p_info = array(
			'invoice' => $invoiceid,
			'paid_by' => $client,
			'payment_method' => '1',
			'amount' => $paid,
			'trans_id' => $transactionid,
			'notes' => 'Amount in BTC: '.$amountsent,
			'month_paid' => date('m'),
			'year_paid' => date('Y'),
		);
		$this->db->insert('payments',$p_info); // insert to payments
                $cur_i = $this->applib->currencies($this->user_profile->get_invoice_details($invoiceid,'currency'));
		$this->_log_activity($invoiceid,'activity_payment_of',$icon = 'fa-btc',$client,$cur_i->symbol.' '.$paid,$invoicename); //log activity
				echo "*ok*";
		$this->_notifyme($client_email,$client_username,$invoicename);
		
	}
	function _notifyme($client_email,$client_username,$invoice_ref)
    {
   
            $data['client_username'] = $client_username;
            $data['invoice_ref'] = $invoice_ref;

            $email_msg = $this->load->view('InvoicePaid',$data,TRUE);
            $email_subject = '['.$this->config->item('company_name').' ] Purchase Confirmation';
            $this->email->from("billing@vincenttaglia.com", 'Vincent Taglia - Web Design Payments');
            $this->email->to($client_email);
            $this->email->reply_to($this->config->item('company_email'), $this->config->item('company_name'));
            $this->email->subject($email_subject);

            $this->email->message_plain($email_msg);
            $this->email->message_html($email_msg);

            $this->email->send();
   
    }
	function send()
	{
		if ($_POST) {
			$mail_to = 'vman678@gmail.com';
			$subject = 'Bitcoin payment completed';

			$body_message = '$'.$_POST['amount'].' in the amount of '.$_POST['btc_amount'].' BTC'."\n";
			$body_message .= 'Invoice #'.$_POST['invoice_id']."\n";
			$body_message .= 'Transaction ID: '.$_POST['txnum']."\n";

			$headers = 'From: billing@vincenttaglia.com\r\n';

			$mail_status = mail($mail_to, $subject, $body_message, $headers);
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', 'Your invoice will be updated as "Paid" within the next 24 hours.');
			redirect('clients');
		}else{
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', 'Something went wrong please contact us if your Payment doesn\'t appear shortly');
        redirect('clients');
        }
		
		
	}
	
	
       function _log_activity($invoice_id,$activity,$icon,$user,$value1='',$value2=''){
            $this->db->set('module', 'invoices');
            $this->db->set('module_field_id', $invoice_id);
            $this->db->set('user', $user);
            $this->db->set('activity', $activity);
            $this->db->set('icon', $icon);
            $this->db->set('value1', $value1);
            $this->db->set('value2', $value2);
            $this->db->insert('activities'); 
    }
}


////end 
