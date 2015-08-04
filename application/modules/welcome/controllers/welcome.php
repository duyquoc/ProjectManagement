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


class Welcome extends MX_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->tank_auth->is_logged_in()) {
			$this->session->set_flashdata('message',lang('login_required'));
			redirect('logout');
		}
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'staff') {
			redirect('collaborator');
		}
		if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'client') {
			redirect('clients');
		}

	}

	function index()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(config_item('company_name'));
	$data['page'] = lang('home');
	$data['projects'] = $this->db->order_by('date_created','desc')->get(Applib::$projects_table,5)->result();
	$data['activities'] = $this->db->order_by('activity_date','DESC')->get(Applib::$activities_table,50)->result();
        $data['sums'] = $this->_totals_per_currency();
        if(Applib::count_num_rows(Applib::$invoice_items_table,array()) == 0){
            $data['no_invoices'] = TRUE;
        }
    			

	$this->template
	->set_layout('users')
	->build('user_home',isset($data) ? $data : NULL);
	}
        
    function _totals_per_currency() {
            $invoices = $this->db->where('inv_deleted','No')->get(Applib::$invoices_table)->result();
            $paid = $due = array();
            $currency = 'USD';
            $symbol = array();
            foreach($invoices as $inv) {
                if (!isset($paid[$inv->currency])) { $paid[$inv->currency] = 0; }
                if (!isset($due[$inv->currency])) { $due[$inv->currency] = 0; }
                $paid[$inv->currency] += $this->applib->_invoice_paid_amount($inv->inv_id);
                $due[$inv->currency] += $this->applib->_invoice_due_amount($inv->inv_id);
                $currency = $this->applib->currencies($inv->currency);
                $symbol[$inv->currency] = $currency->symbol;
            }
            return array("paid"=>$paid, "due"=>$due, "symbol"=>$symbol);
        
        }
}

/* End of file welcome.php */