<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *
 * @package	Freelancer Office
 */
class StripePay extends MX_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->library('tank_auth');
		$this->load->model('mdl_pay','AppPay');
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
		$data['stripe'] = TRUE;
		
		$this->load->view('form',$data);
	}



	function authenticate(){

	// Check for a form submission:
	if ($_POST) {

	// Stores errors:
	$errors = array();
	
	// Need a payment token:
	if (isset($_POST['stripeToken'])) {
		
		$token = $_POST['stripeToken'];
		
		// Check for a duplicate submission, just in case:
		// Uses sessions, you could use a cookie instead.
		if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
			$errors['token'] = 'You have apparently resubmitted the form. Please do not do that.';
		} else { // New submission.
			$_SESSION['token'] = $token;
		}		
		
	} else {
		$errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
	}
	

	// If no errors, process the order:
	if (empty($errors)) {
		
		// create the charge on Stripe's servers - this will charge the user's card
		try {
			
			// Include the Stripe library:
			require_once APPPATH.'/libraries/stripe/lib/Stripe.php';

			// set your secret key: remember to change this to your live secret key in production
			// see your keys here https://manage.stripe.com/account
			Stripe::setApiKey(config_item('stripe_private_key'));

			$invoice_info = $this -> AppPay -> invoice_info($_POST['invoice_id']);

			$invoice = $invoice_info['inv_id'];
			$invoice_ref = $invoice_info['reference_no'];
			$currency = $invoice_info['currency'];
			$paid_by = $invoice_info['client'];
			$amount = $_POST['amount']*100;
			$payer_email = $this->applib->company_details($paid_by,'company_email');

			$metadata = array(
			                     'invoice_id' => $invoice,
			                     'payer' => $this->tank_auth->get_username(),
			                     'payer_email' => $payer_email,
			                     'invoice_ref' => $invoice_ref
			                     );

			// Charge the order:
			$charge = Stripe_Charge::create(array(
				"amount" => $amount, // amount in cents
				"currency" => $currency,
				"card" => $token,
				"metadata" => $metadata,
				"description" => "Payment for Invoice ".$invoice_ref
				)
			);

			// Check that it was paid:
			if ($charge->paid == true) {
				$metadata = $charge->metadata;
				$transaction = array(
				                     'invoice' => $metadata->invoice_id,
				                     'paid_by' => $paid_by,
				                     'payer_email' => $metadata->payer_email,
				                     'payment_method' => '1',
				                     'notes' => $charge->description,
				                     'amount' => $charge->amount/100,
				                     'trans_id' => $charge->balance_transaction,
				                     'month_paid' => date('m'),
									 'year_paid' => date('Y'),
									 'payment_date' => date('d-m-Y')
				                     );	
				// Store the order in the database.
				if ($this->db->insert('payments', $transaction)) {
                                $cur_i = $this->applib->currencies(strtoupper($charge->currency));
            	$this->_log_activity($invoice,'activity_payment_of',$icon = 'fa-usd',$paid_by, $cur_i->symbol.' '.$amount/100, $invoice_ref); //log activity

            	$this-> _send_payment_email($invoice,$charge->amount / 100); // Send email to client

            	$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Payment received and applied to Invoice '.$invoice_ref);
				redirect('invoices/view/'.$invoice);

				}else{
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Payment not recorded in the database. Please contact the system Admin.');
				redirect('invoices/view/'.$invoice);
				}
				
				
			} else { // Charge was not paid!	
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', 'Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.');
				redirect('invoices/view/'.$invoice);
			}
			
		} catch (Stripe_CardError $e) {
		    // Card was declined.
			$e_json = $e->getJsonBody();
			$err = $e_json['error'];
			$errors['stripe'] = $err['message'];
		} catch (Stripe_ApiConnectionError $e) {
		    // Network problem, perhaps try again.
		} catch (Stripe_InvalidRequestError $e) {
		    // You screwed up in your programming. Shouldn't happen!
		} catch (Stripe_ApiError $e) {
		    // Stripe's servers are down!
		} catch (Stripe_CardError $e) {
		    // Something else that's not the customer's fault.
		}

		} // A user form submission error occurred, handled below.

	
	} // Form submission.

	}

	function _send_payment_email($invoice_id,$paid_amount){
			$message = Applib::get_table_field('email_templates',array('email_group' => 'payment_email'
									), 'template_body');
			$currency = Applib::get_table_field($this->invoice_table,array('inv_id' => $invoice_id), 'currency');
                        $cur = $this->applib->client_currency($currency);

			$invoice_currency = str_replace("{INVOICE_CURRENCY}",$currency,$message);
			$amount = str_replace("{PAID_AMOUNT}",$paid_amount,$invoice_currency);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$amount);

			$client = Applib::get_table_field($this->invoice_table,array('inv_id' => $invoice_id), 'client');

			$address = Applib::get_table_field($this->clients_table,array('co_id' => $client), 'company_email');
			$data['paid_amount'] = $cur->symbol." ".$paid_amount;

			$params['recipient'] = $address;

			$params['subject'] = '[ '.config_item('company_name').' ]'.' Payment Received';
			$params['message'] = $message;
			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}


	function _notify_client($client,$invoice_ref)
    {
   			$this->load->library('email');
            $data['client_username'] = $this->applib->company_details($client,'company_name');
            $client_email = $this->applib->company_details($client,'company_email');
            $data['invoice_ref'] = $invoice_ref;

            $email_msg = $this->load->view('InvoicePaid',$data,TRUE);
            $email_subject = '['.$this->config->item('company_name').' ] Purchase Confirmation';
            $this->email->from($this->config->item('company_email'), $this->config->item('company_name').' Payments');
            $this->email->to($client_email);
            $this->email->reply_to($this->config->item('company_email'), $this->config->item('company_name'));
            $this->email->subject($email_subject);

            $this->email->message($email_msg);

            $this->email->send();
   
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