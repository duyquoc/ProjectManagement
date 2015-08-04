<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package	Freelancer Office
 * @author	William M
 */
class Welcome extends CI_Model
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

	function recent_activities($user,$limit)
	{
		
		$this->db->join('users','users.id = activities.user');
		return $this->db->where('user',$user)->order_by('activity_date','DESC')->get('activities',$limit)->result();
	}
	function recent_projects($user,$limit)
	{
		$user_company = $this->user_profile->get_profile_details($user,'company');
		return $this->db->where(array('client'=>$user_company,'proj_deleted'=>'No'))->order_by('date_created','DESC')->get('projects',$limit)->result();
	}
	
}

/* End of file model.php */