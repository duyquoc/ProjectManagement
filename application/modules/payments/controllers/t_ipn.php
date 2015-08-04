<?php

class T_ipn extends MX_Controller {

    // To handle the IPN post made by PayPal (uses the Paypal_Lib library).
    function ipn()
    {
    
        $this->load->library('PayPal_IPN'); // Load the library
         $this->load->library('email'); // Load the library
        // Try to get the IPN data.
        if ($this->paypal_ipn->validateIPN())
        {
            // Succeeded, now let's extract the order
            $this->paypal_ipn->extractOrder();

            // And we save the order now (persist and extract are separate because you might only want to persist the order in certain circumstances).
            $this->paypal_ipn->saveOrder();

            // Now let's check what the payment status is and act accordingly
            if ($this->paypal_ipn->orderStatus == PayPal_IPN::PAID)
            {
                 $this->load->library('tank_auth');
            	// Prepare the variables to populate the email template:
                $data = $this->paypal_ipn->order;
             	$items = $this->paypal_ipn->orderItems;
             	foreach($items as $i):
             	
		          $invoice_id = $i['item_number'];
		          $invoice_ref = $i['item_name'];
                endforeach;
                $client = $data['custom'];

            
            $txn_id = $data['txn_id'];
			$client_email = $data['payer_email'];
			$receiver = $data['receiver_email'];
			$first_name = $data['first_name'];
			$last_name = $data['last_name'];
			$paid_amount = $data['mc_gross']; 
            $client_username = $this->user_profile->get_user_details($client,'username'); //get client username
        
            if ($this->_payment_is_valid($invoice_id,$paid_amount)) {
            //$this->db->trans_start();
                            $p_info = array(
                                    'invoice' => $invoice_id,
                                    'paid_by' => $client,
                                    'payer_email' => $client_email,
                                    'payment_method' => '1',
                                    'amount' => $paid_amount,
                                    'trans_id' => $txn_id,
                                    'notes' => 'Paid by '.$first_name.' '.$last_name.' to '.$receiver,
                                    'month_paid' => date('m'),
                                    'year_paid' => date('Y'),
                                    );
                    $this->db->insert('payments',$p_info); // insert to payments
            //$this->db->trans_complete();
            $cur_i = $this->applib->currencies($this->user_profile->get_invoice_details($invoice_id,'currency'));
            $this->_log_activity($invoice_id,'activity_payment_of',$icon = 'fa-usd',$client,$cur_i->symbol.' '.$paid_amount,$invoice_ref); //log activity
                    
            $this->_notifyme($client_email,$client_username,$invoice_ref);
            
        }else{
                $this->email->from($this->config->item('company_email'), $this->config->item('company_name'));
                $this->email->to($this->config->item('company_email'));

                $this->email->subject('Payment Alert Notice');
                $this->email->message('Please investigate this transaction ID '.$txn_id.' from '.$client_email.' The Amount received does not match the amount of the Invoice.');

                $this->email->send();
            }		
    }
                }
            else // Just redirect to the root URL
            {
                redirect('');
            }
    }
    function _notifyme($client_email,$client_username,$invoice_ref)
    {
   
            $data['client_username'] = $client_username;
            $data['invoice_ref'] = $invoice_ref;

            $email_msg = $this->load->view('InvoicePaid',$data,TRUE);
            $email_subject = '['.$this->config->item('company_name').' ] Purchase Confirmation';
            $this->email->from($this->config->item('company_email'), $this->config->item('company_name').' Payments');
            $this->email->to($client_email);
            $this->email->reply_to($this->config->item('company_email'), $this->config->item('company_name'));
            $this->email->subject($email_subject);

            $this->email->message_plain($email_msg);
            $this->email->message_html($email_msg);

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

    function _payment_is_valid($invoice,$amount)
    {
        $invoice_payable = $this->user_profile->invoice_payable($invoice);
         if ($amount >= $invoice_payable) {
             return TRUE;
         }else{
            return FALSE;
         }
    }
}