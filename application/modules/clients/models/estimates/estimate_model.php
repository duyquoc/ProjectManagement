<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package	Freelancer
 * @author	William M
 */
class Estimate_model extends CI_Model
{
	
	function get_all_records($table,$where,$join_table,$join_criteria,$order)
	{
		$this->db->where($where);
		if($join_table){
		$this->db->join($join_table,$join_criteria);
		}
		$query = $this->db->order_by($order,'desc')->get($table);
		if ($query->num_rows() > 0){
			return $query->result();
		} else{
			return NULL;
		}
	}
	
    function clients()
	{
		$query = $this->db->where('role_id',2)->get(Applib::$user_table);
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}

    function payment_methods()
	{
			return $this->db->get('payment_methods')->result();
	}
	function saved_items()
	{
			return $this->db->get('items_saved')->result();
	}
	function estimate_details($estimate)
	{
		$this->db->join('companies','companies.co_id = estimates.client');
		return $this->db->where('est_id',$estimate)->get('estimates')->result();
	}
	function search_estimate($keyword)
	{
		$this->db->join('companies','companies.co_id = estimates.client');
		$this->db->where('client', $this->tank_auth->get_user_id());
		return $this->db->like('reference_no', $keyword)->order_by("date_saved","desc")->get('estimates')->result();
	}
	function saved_item_details($item)
	{
		return $this->db->where('item_id',$item)->get('items_saved')->result();
	}
	function estimate_items($estimate)
	{
		$this->db->join('estimates','estimates.est_id = estimate_items.estimate_id');
		$query = $this->db->where('estimate_id',$estimate)->order_by('item_order','asc')->get('estimate_items');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function get_client($invoice){
	$query = $this->db->select('client')->where('inv_id',$invoice)->get(Applib::$invoices_table);
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->client;
  		}
	}
}

/* End of file model.php */