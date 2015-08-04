<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
**********************************************************************************
* Copyright: gitbench 2014
* Licence: Please check CodeCanyon.net for licence details. 
* More licence clarification available here: htttp://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
* CodeCanyon User: http://codecanyon.net/user/gitbench
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/

class Fomailer extends MX_Controller {

		function __construct()
	{
		parent::__construct();
	}

	function send_email($params)
	{

		// If postmark API is being used
		if(config_item('use_postmark') == 'TRUE'){
			$config = array('api_key' => config_item('postmark_api_key'));
        	$this->load->library('postmark',$config);
        	
        	$this->postmark->from(config_item('postmark_from_address'), config_item('company_name'));
			$this->postmark->to($params['recipient']);
			$this->postmark->subject($params['subject']);
			$this->postmark->message_plain($params['message']);
			$this->postmark->message_html($params['message']);

			// Check attached file
			if(isset($params['attachment_url'])){ 
					$this->postmark->attach($params['attached_file']);
				}
        	$this->postmark->send();

    	}else{
    		// If using SMTP
					if (config_item('protocol') == 'smtp') {
						$this->load->library('encrypt');
						$raw_smtp_pass =  $this->encrypt->decode(config_item('smtp_pass'));
						$config = array(
    							'smtp_host' => config_item('smtp_host'),
    							'smtp_port' => config_item('smtp_port'),
    							'smtp_user' => config_item('smtp_user'),
    							'smtp_pass' => $raw_smtp_pass,
    							'crlf' 		=> "\r\n",    							
    							'protocol'	=> config_item('protocol'),
						);						
					}	
				// Send email 
				$config['useragent'] = 'Freelance Office';
				$config['mailtype'] = "html";
				$config['newline'] = "\r\n";
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				
    			$this->load->library('email',$config);

				$this->email->from(config_item('company_email'), config_item('company_name'));
				$this->email->to($params['recipient']);

				$this->email->subject($params['subject']);
				$this->email->message($params['message']);
				    if($params['attached_file'] != ''){ 
				    	$this->email->attach($params['attached_file']);
				    }
				$this->email->send();

    	}
	
	}
}

/* End of file fomailer.php */