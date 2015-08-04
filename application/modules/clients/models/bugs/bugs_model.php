<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package	Freelancer
 * @author	William M
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
		$user_company = $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company');
		$this->db->join('projects','projects.project_id = bugs.project');
		$query = $this->db->where('client',$user_company)->order_by('reported_on','desc')->get('bugs');
		if ($query->num_rows() > 0){
			return $query->result();
		} 
	}
	function bugs_by_status($status,$limit,$offset)
	{
		$this->db->join('projects','projects.project_id = bugs.project');
		$this->db->where('client',$this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'));
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
		$this->db->where('client',$this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'));
		return $this->db->like('issue_ref',$keyword)
						->or_like('bug_description',$keyword)
						->order_by('reported_on','desc')
						->get('bugs',$limit)->result();
	}
	function projects()
	{
		$query = $this->db->where('client',$this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'company'))->get(Applib::$projects_table);
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

/* End of file model.php */