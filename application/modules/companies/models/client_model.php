<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package	Freelancer Office
 */
class Client_model extends CI_Model
{
	function client_details($company)
	{
		$query = $this->db->where('co_id',$company)->get('companies');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function client_invoices($company)
	{
		$query = $this->db->where('client',$company)->get(Applib::$invoices_table);
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function client_projects($company)
	{
		$query = $this->db->where('client',$company)->get(Applib::$projects_table);
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function client_links($company)
	{
		$query = $this->db->where('client',$company)->get('links');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function client_contacts($company)
	{
		$this->db->join('companies','companies.co_id = account_details.company');
		$this->db->join('users','users.id = account_details.user_id');
		$query = $this->db->where('company',$company)->get(Applib::$profile_table);
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function user_activities($user_id,$limit)
	{
		$this->db->join('users','users.id = activities.user');
		return $this->db->where('user',$user_id)
							->order_by('activity_date','DESC')
							->get('activities',$limit,$this->uri->segment(5))->result();
	}
}

/* End of file model.php */