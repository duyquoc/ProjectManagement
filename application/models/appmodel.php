<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| @package Freelancer Office
|--------------------------------------------------------------------------
|
| 
*/
class AppModel extends CI_Model {

     public function __construct()
     {
      parent::__construct();
     }

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
	function users(){
		return $this -> db -> get(Applib::$user_table)->result();
	}
	function payments(){
		return $this -> db -> get('payment_methods')->result();
	}
	function user_by_id($id){
		return $this -> db -> where(array('id'=>$id)) -> get(Applib::$user_table)->result();
	}
	function insert($table,$data){
			$this -> db -> insert($table, $data);
			return $this -> db -> insert_id();
	}
	function update($table,$data,$where){
		$this -> db -> where($where)->update($table, $data);
		return TRUE;
	}
	function search_project($keyword,$where)
	{
		//$array = array('project_title' => $keyword, 'project_code' => $keyword);
		$this->db->like('project_title',$keyword); 
		return $this->db->order_by('date_created','desc')	
						->where($where)					
						->get(Applib::$projects_table)->result();
	}
	function monthly_data($month)
	{
		$this->db->select_sum('amount');
		$this->db->where('month_paid', $month); 
		$this->db->where('year_paid', date('Y')); 
		$query = $this->db->get('payments');
		foreach ($query->result() as $row)
			{
				$amount = $row->amount ? $row->amount : 0;
   				return round($amount);
			}
	}
	function monthly_user_data($month)
	{
		$this->db->select_sum('amount');
		$this->db->where('paid_by', $this->tank_auth->get_user_id()); 
		$this->db->where('month_paid', $month); 
		$this->db->where('year_paid', date('Y')); 
		$query = $this->db->get('payments');
		foreach ($query->result() as $row)
			{
				$amount = $row->amount ? $row->amount : 0;
   				return round($amount);
			}
	}
}
     
     /* End of file appmodel.php */
     /* Location: ./application/models/appmodel.php */ 