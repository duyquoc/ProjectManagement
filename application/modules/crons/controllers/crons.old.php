<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Crons extends MX_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('cron_model', 'CronModel');
        $this->load->library('email');

        // this controller can only be called from the command line
        //if (!$this->input->is_cli_request()) show_error('Direct access is not allowed');
    }
    
    function projects_cron(){
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);

        if ($this->config->item('cron_key') == $this->uri->segment(3)) {
                $email_lists = $this->CronModel->overdue_projects();
                if ($email_lists) {
                   foreach ($email_lists as $address)
                        {
                            $this->email->clear();
                            $this->email->to($address->email);
                            $this->email->from($this->config->item('company_email'));
                            $this->email->subject('['.$this->config->item('company_name').'] Your Project is Overdue');
                            $body = "
                                    Dear ".$address->email.",<br><br>
                                    
                                    One of your Project is Overdue.<br><br>                     
                                    
                                    To view the project, click on the link below.<br><br>
                                    
                                    <a href=\" ".base_url()."\">View Project</a> <br><br>
                                    
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
    function invoices_cron(){
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);

        if ($this->config->item('cron_key') == $this->uri->segment(3)) {
                $email_lists = $this->CronModel->overdue_invoices();
                if ($email_lists) {
                   foreach ($email_lists as $address)
                        {
                            $this->email->clear();
                            $this->email->to($address->email);
                            $this->email->from($this->config->item('company_email'));
                            $this->email->subject('['.$this->config->item('company_name').'] Your Invoice is Overdue');
                            $body = "
                                    Dear ".$address->email.",<br><br>
                                    
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

    function clean_demo_db(){
        if($this->config->item('demo_mode') == 'TRUE'){
            if ($this->config->item('reset_key') == $this->uri->segment(3)) {
                $this->_resetTables();
            }else{
                $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('message', lang('reset_key_error'));
                 redirect('settings/update/general');
            }
        


        $this->session->set_flashdata('response_status', 'success');
        $this->session->set_flashdata('message', lang('clean_demo_db_success'));
        redirect('settings/update/general');
        }else{
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', lang('clean_demo_db_error'));
            redirect('settings/update/general');
        }
    }

    function _resetTables() {
        $templine = '';
        // Read in entire file
            $lines = file('./resource/database.backup/FoData.sql');
                foreach ($lines as $line)
                    {
                     if (substr($line, 0, 2) == '--' || $line == '')
                        continue;
                        $templine .= $line;
                        if (substr(trim($line), -1, 1) == ';')
                            {
                             mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                                        $templine = '';
                            }           

                    }  
    }
}

/* End of file appcrons.php */