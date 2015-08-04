<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package	Freelancer
 * @author	William Mandai
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
		$query = $this->db->where('assigned_to',$this->tank_auth->get_user_id())->order_by('reported_on','desc')->get('bugs');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function bugs_by_status($status,$limit,$offset)
	{
		$this->db->join('projects','projects.project_id = bugs.project');
		$this->db->where('assigned_to',$this->tank_auth->get_user_id());
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
		$this->db->where('assign_to',$this->tank_auth->get_user_id());
		return $this->db->like('issue_ref',$keyword)
						->or_like('bug_description',$keyword)
						->order_by('reported_on','desc')
						->get('bugs',$limit)->result();
	}
	function projects()
	{
		$user = $this->tank_auth->get_user_id();
		$this->db->join('assign_projects','assign_projects.project_assigned = projects.project_id');
		$this->db->where('assigned_user', $user);
		return $this->db->order_by('date_created','desc')->get(Applib::$projects_table)->result();
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

/* End of file model.php */