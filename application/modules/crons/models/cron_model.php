<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

class Cron_model extends CI_Model
{
	
	function overdue_projects()
	{
		$this->db->join('companies','companies.co_id = projects.client');
		$query = $this->db->select('company_email')->where(array('proj_deleted'=> 'No','due_date'=>date("d-m-Y")))->get(Applib::$projects_table);
		if ($query->num_rows() > 0){
			return $query->result();
		} else{
			return FALSE;
		}
	}

	function overdue_invoices()
	{
		$this->db->join('companies','companies.co_id = invoices.client');
		$query = $this->db->select('company_email')->where(array('inv_deleted'=> 'No','emailed'=>'Yes','due_date'=>date("d-m-Y")))->get(Applib::$invoices_table);
		if ($query->num_rows() > 0){
			return $query->result();
		}else{
			return FALSE;
		}
	}
	
	
}

/* End of file cron_model.php */