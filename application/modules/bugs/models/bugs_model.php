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

class Bugs_model extends CI_Model
{
	
	function bug_details($bug_id)
	{
		$this->db->join('projects','projects.project_id = bugs.project');
		$query = $this->db->where('bug_id',$bug_id)->get('bugs');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function bug_activities($bug_id)
	{
		$this->db->join('users','users.id = activities.user');
		return $this->db->where(array( 'module' => 'bugs', 'module_field_id' => $bug_id))
		->order_by('activity_date','desc')->get('activities')
		->result();
	}
	function bug_comments($bug_id)
	{
		$query = $this->db->where('bug_id',$bug_id)->order_by('date_commented','desc')->get('bug_comments');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function bug_files($bug_id)
	{
		return $this->db->where('bug',$bug_id)->order_by('date_posted','desc')->get('bug_files')->result();
	}
	function bugs()
	{
		$this->db->join('projects','projects.project_id = bugs.project');
		$query = $this->db->order_by('reported_on','desc')->get('bugs');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function bugs_by_status($status,$limit,$offset)
	{
		$this->db->join('projects','projects.project_id = bugs.project');
		if ($status == 'all') { 
			$query = $this->db->order_by('reported_on','desc')->get('bugs',$limit,$offset);
		}else{
			$query = $this->db->where('bug_status',$status)->order_by('reported_on','desc')->get('bugs',$limit,$offset);
		}
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function bugs_search($keyword,$limit)
	{
		$this->db->join('projects','projects.project_id = bugs.project');
		$query = $this->db->like('issue_ref',$keyword)->or_like('bug_description',$keyword)->order_by('reported_on','desc')->get('bugs',$limit);
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function users($criteria)
	{
		if ($criteria == 'all') {
			$query = $this->db->get(Applib::$user_table);
		}else{
			$query = $this->db->where('role_id !=',2)->get(Applib::$user_table);
		}		
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function projects()
	{
		$query = $this->db->get(Applib::$projects_table);
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function bug_file_name($file,$limit){
	$query = $this->db->select('file_name')->where('file_id',$file)->get('bug_files',$limit);
		if ($query->num_rows() > 0)
			{
  		 $row = $query->row();
  		 return $row->file_name;
  		}
	}
	function get_file($file_id)
		{
			return $this->db->select()
					->from('bug_files')
					->where('file_id', $file_id)
					->get()
					->row();
		}
	function insert_file($filename,$bug,$description)
	{
		$data = array(
			'bug'	=> $bug,
			'file_name'			=> $filename,
			'description'			=> $description,
			'uploaded_by'			=> $this->tank_auth->get_user_id(),
		);
		$this->db->insert('bug_files', $data);
		return $this->db->insert_id();
	}
	
	
}

/* End of file bugs_model.php */