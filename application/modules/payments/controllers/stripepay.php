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
	}
	
	function pay()
	{
		$userid = $this->tank_auth->get_user_id();
		 
		$invoice = $this -> AppPay -> invoice_info($this->uri->segment(3));

		$invoice_cost = $this -> applib -> invoice_payable($invoice['inv_id']);

		$payment_made = $this -> applib -> invoice_payment($invoice['inv_id']);

		$inv_tax = $invoice['tax'];
		$tax = ($inv_tax/100) * $invoice_cost;

		$invoice_due = ($invoice_cost + $tax) - $payment_made;

		$data['invoice_info'] = array('item_name'=> $invoice['reference_no'], 
										'item_number' => $invoice['inv_id'],
										'currency' => $invoice['currency'],
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
			Stripe::setApiKey(config_item('STRIPE_PRIVATE_KEY'));

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
				                     'payment_method' => '3',
				                     'notes' => $charge->description,
				                     'amount' => $charge->amount/100,
				                     'trans_id' => $charge->balance_transaction,
				                     'month_paid' => date('m'),
									 'year_paid' => date('Y'),
				                     );	
				// Store the order in the database.
				if ($this->db->insert('payments', $transaction)) {
                $cur_i = $this->applib->currencies($this->applib->get_invoice_details($invoice,'currency'));                
            	$this->_log_activity($invoice,'activity_payment_of',$icon = 'fa-usd',$paid_by, $cur_i->symbol.' '.$amount, $invoice_ref); //log activity
                    
            	$this->_notify_client($paid_by,$invoice_ref); // Send the mail

            	$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Payment received and applied to Invoice '.$invoice_ref);
				redirect('clients/inv_manage/details/'.$invoice);

				}else{
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Payment not recorded in the database. Please contact the system Admin.');
				redirect('clients/inv_manage/details/'.$invoice);
				}
				
				
			} else { // Charge was not paid!	
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.');
				redirect('clients/inv_manage/details/'.$invoice);
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