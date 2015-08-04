<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Freelancer Office
 * 
 * Web based project and invoicing management system available on codecanyon
 *
 * @package		Freelancer Office
 * @author		William M
 * @copyright	Copyright (c) 2014 - 2015 Gitbench, LLC
 * @license		http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
 * @link		http://codecanyon.net/item/freelancer-office/8870728
 * 
 */

class Crons extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('tank_auth','email'));
        $this->load->model('cron_model', 'CronModel');
        $this -> invoices_table = 'invoices';
        $this -> clients_table = 'companies';
        $this -> items_table = 'items';
    }

    function run($cron_key = NULL){
        $this -> recur($cron_key);
        $this-> projects_cron($cron_key);
        $this -> invoices_cron($cron_key);

    }

    function clean_demo_db(){
        if($this->config->item('demo_mode') == 'TRUE'){
            if ($this->config->item('reset_key') == $this->uri->segment(3)) {
                $this->_resetTables();
            }else{
                $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('message', lang('reset_key_error'));
                 redirect('settings');
            }
        


        $this->session->set_flashdata('response_status', 'success');
        $this->session->set_flashdata('message', lang('clean_demo_db_success'));
        redirect('settings/update/general');
        }else{
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', lang('clean_demo_db_error'));
            redirect('settings');
        }
    }

    function _resetTables() {
        $templine = '';
        // Read in entire file
            $lines = file('./resource/tmp/demo.sql');
                foreach ($lines as $line)
                    {
                     if (substr($line, 0, 2) == '--' || $line == '')
                        continue;
                        $templine .= $line;
                        if (substr(trim($line), -1, 1) == ';')
                            {
                            $this->db->query($templine);
                            $templine = '';
                            }           

                    }  

                 
    }

    public function recur($cron_key = NULL)
    {
        if ($cron_key == config_item('cron_key'))
        {
            $this->load->model('invoices/mdl_invoices_recurring');
            $this->load->model('invoices/mdl_invoices');
            

            // Gather a list of recurring invoices to generate
            $invoices_recurring = $this->mdl_invoices_recurring->active();



            foreach ($invoices_recurring as $invoice_recurring)
            {
                // This is the original invoice id
                $source_id = $invoice_recurring->inv_id;

                // This is the original invoice
                $invoice = $this->mdl_invoices_recurring->get_invoice($source_id,$this->invoices_table);

                // Create the new invoice
                $db_array = array(
                    'client'            => $invoice->client,
                    'due_date'          => $this->mdl_invoices->get_date_due($invoice_recurring->recur_next_date),
                    'reference_no'       => 'INV'.$this -> applib -> generate_invoice_number(),
                    'discount'      => $invoice->discount,
                    'tax'           => $invoice->tax,
                    'currency'     => $invoice->currency,
                    'notes'        => $invoice->notes
                );

                // This is the new invoice id
                $this -> db -> insert($this->invoices_table,$db_array);
                $target_id = $this -> db -> insert_id();

                // Copy the original invoice to the new invoice
                $this->mdl_invoices->copy_invoice($source_id, $target_id);

                // Update the next recur date for the recurring invoice
                $this->mdl_invoices_recurring->set_next_recur_date($invoice_recurring->inv_id);

                // Email the new invoice if applicable
                if (config_item('automatic_email_on_recur') == 'TRUE')
                {

                $new_invoice = $this -> db -> where('inv_id',$target_id) -> get($this->invoices_table) -> row();

                $client_name = $this -> applib -> get_any_field($this->clients_table,array('co_id' => $new_invoice->client), 'company_name');
                $invoice_cost = number_format($this -> applib -> calculate('invoice_due',$new_invoice->inv_id),2,config_item('decimal_separator'),config_item('thousand_separator'));

                $subject = $this -> applib -> get_any_field('email_templates',array(
                                                    'email_group' => 'invoice_message'
                                    ), 'subject') . ' ' . $new_invoice->reference_no;
                $message = $this -> applib -> get_any_field('email_templates',array(
                                                    'email_group' => 'invoice_message'
                                    ), 'template_body');

                $ClientName = str_replace("{CLIENT}",$client_name,$message);
                $Amount = str_replace("{AMOUNT}",$invoice_cost,$ClientName);
                $Currency = str_replace("{CURRENCY}",$new_invoice->currency,$Amount);
                $link = str_replace("{INVOICE_LINK}",base_url().'invoices/view/'.$new_invoice->inv_id,$Currency);
                $message = str_replace("{SITE_NAME}",config_item('company_name'),$link);

                $this->_email_invoice($new_invoice->inv_id,$message,$subject); // Email Invoice

                $data = array('emailed' => 'Yes', 'date_sent' => date ("Y-m-d H:i:s", time()));

                $this -> db -> where('inv_id',$new_invoice->inv_id) -> update($this->invoices_table,$data);

                }
            }
        }
    }


    function projects_cron($cron_key){
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);

        if ($this->config->item('cron_key') == $cron_key) {
                $email_lists = $this->CronModel->overdue_projects();
                if ($email_lists) {
                   foreach ($email_lists as $address)
                        {
                            $this->email->clear();
                            $this->email->to($address->company_email);
                            $this->email->from($this->config->item('company_email'));
                            $this->email->subject('['.$this->config->item('company_name').'] Your Project is Overdue');
                            $body = "
                                    Dear ".$address->company_email.",<br><br>
                                    
                                    One of your Project is Overdue.<br><br>                     
                                    
                                    To view the project, click on the link below.<br><br>
                                    
                                    <a href=\" ".base_url()."projects\">View Project</a> <br><br>
                                    
                                    Note: Do not reply to this email as this email is not monitored.<br><br>
                                    Regards<br>"
                                    .$this->config->item('company_name');

                            $this->email->message($body);
                            $this->email->send();
                        }
                        return TRUE;  
                    }else{
                        log_message('error', 'There are no overdue projects to send emails');
                        return TRUE;
                    }
            }else{
                log_message('error', 'Wrong CRON Key entered. Please verify your key');
                return FALSE;
            }
    }
    function invoices_cron($cron_key){
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);

        if ($this->config->item('cron_key') == $cron_key) {
                $email_lists = $this->CronModel->overdue_invoices();
                if ($email_lists) {
                   foreach ($email_lists as $address)
                        {
                            $this->email->clear();
                            $this->email->to($address->company_email);
                            $this->email->from($this->config->item('company_email'));
                            $this->email->subject('['.$this->config->item('company_name').'] Your Invoice is Overdue');
                            $body = "
                                    Dear ".$address->company_email.",<br><br>
                                    
                                    One of your Invoice is Overdue.<br><br>                     
                                    
                                    To view the invoice and Pay for it, click on the link below.<br><br>
                                    
                                    <a href=\" ".base_url()."\">Pay Invoice</a> <br><br>
                                    
                                    Note: Do not reply to this email as this email is not monitored.<br><br>
                                    Regards<br>"
                                    .$this->config->item('company_name');

                            $this->email->message($body);
                            $this->email->send();
                        }
                        return TRUE;  
                    }else{
                        log_message('error', 'There are no overdue invoices to send emails');
                        return TRUE;
                    }
            }else{
                log_message('error', 'Wrong CRON Key entered. Please verify your key');
                return FALSE;
            }
    }

  
    

    function _email_invoice($invoice_id,$message,$subject){
            $client = $this -> applib -> get_any_field($this->invoices_table,array('inv_id' => $invoice_id),'client');

            $invoice = $this -> db -> where('inv_id',$invoice_id) -> get($this->invoices_table) -> row();
            $recipient = $this -> applib -> get_any_field($this->clients_table,array('co_id'=>$client),'company_email');

            $params = array(
                            'recipient' => $recipient,
                            'subject'   => $subject,
                            'message'   => $message
                            );

            $this->load->helper('file');
            $attach['inv_id'] = $invoice_id;

            $invoicehtml = modules::run('fopdf/attach_invoice',$attach);

            $params['attached_file'] = '';
            if ( ! write_file('./resource/tmp/Invoice #'.$invoice->reference_no.'.pdf',$invoicehtml)){
                $this -> applib -> redirect_to('invoices/view/'.$invoice_id,'error',lang('write_access_denied'));
             }else{
                $params['attached_file'] = './resource/tmp/Invoice #'.$invoice->reference_no.'.pdf';
                $params['attachment_url'] = base_url().'resource/tmp/Invoice #'.$invoice->reference_no.'.pdf';
            }
            modules::run('fomailer/send_email',$params);

            unlink('./resource/tmp/Invoice #'.$invoice->reference_no.'.pdf');
    }


function _log_activity($activity,$user,$module,$module_field_id,$icon,$value1='',$value2=''){
        
                    $params = array(
                                    'user'              => $user,
                                    'module'            => $module,
                                    'module_field_id'   => $module_field_id,
                                    'activity'          => $activity,
                                    'icon'              => $icon,
                                    'value1'		=> $value1,
                                    'value2'		=> $value2
                                    );
                    modules::run('activity/log',$params); //pass to activitylog module
    }
}

/* End of file crons.php */