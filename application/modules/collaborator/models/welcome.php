<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package	Freelance Office
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

	function recent_projects($user,$limit)
	{   
		$this->db->join('assign_projects','assign_projects.project_assigned = projects.project_id');
		$this->db->where('assigned_user', $user);
		return $this->db->order_by('date_created','desc')->group_by('project_assigned')->get('projects',$limit)->result();
	}
	function recent_tasks($user,$limit)
	{
		$this->db->join('assign_tasks','assign_tasks.task_assigned = tasks.t_id');
		$this->db->where('assigned_user', $user);
		return $this->db->order_by('assign_date','desc')->group_by('task_assigned')->get('tasks',$limit)->result();
	}
	function recent_activities($limit)
	{
		$query = $this->db->where('user',$this->tank_auth->get_user_id())->order_by('activity_date','DESC')->get('activities',$limit);
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function _assigned_projects($user){
		$query = $this->db->select('project_assigned')->where('assigned_user',$user)->get('assign_projects');
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
	}
	
}

/* End of file model.php */