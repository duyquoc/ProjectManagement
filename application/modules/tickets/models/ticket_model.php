<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
**********************************************************************************
* Copyright: gitbench 2014
* Licence: Please check CodeCanyon.net for licence details. 
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/
class Ticket_model extends CI_Model
{

	
		public function __construct()
		{
			parent::__construct();
			//Load Dependencies
	
		}
	
		// List all your items
		public function retrieve($table_name, $where, $limit = NULL, $offset = 0 , $sort = NULL)
		{
			if (isset($sort)) {
				$this -> db -> order_by($sort['order_by'],$sort['order']);
			}
			return $this -> db -> where($where) 							   
							   -> get($table_name,$limit, $offset) 
						       -> result();
		}
	
		// Add a new item
		public function add($table_name,$data = array())
		{
			$this -> db -> insert($table_name, $data);
			return $this -> db -> insert_id();
		}
	
		//Update one item
		public function update( $table_name, $where = array(), $data = array())
		{
			return $this -> db -> where($where) -> update($table_name , $data);
		}
	
		//Delete one item
		public function delete($table_name, $where)
		{
			return $this -> db -> where($where) -> delete($table_name);
		}
}
/* End of file invoice_model.php */
/* Location: ./application/modules/invoices/models/invoice_model.php */