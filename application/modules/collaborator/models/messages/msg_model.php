<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package	Freelancer Office
 * @author	William M
 */
class Msg_model extends CI_Model
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
	function group_messages_by_users($user)
	{
		$this->db->join('users','users.id = messages.user_from');
		return $this->db->where(array('deleted'=>'No','user_to'=>$user))->group_by("user_from")->order_by("date_received","desc")->get('messages')->result();
	}
	function get_conversations($recipient)
	{
		$this->db->join('users','users.id = messages.user_from');
		$this->db->where('user_to', $recipient);
		$this->db->where('user_from', $this->tank_auth->get_user_id());
		$this->db->or_where('user_from', $recipient);
		$this->db->where('user_to', $this->tank_auth->get_user_id());
		return $this->db->where('deleted','No')->order_by("date_received","desc")->get('messages')->result();
	}

	function search_messsages($keyword)
	{
		$this->db->join('users','users.id = messages.user_from');
		$this->db->where('user_to', $this->tank_auth->get_user_id());
		return $this->db->like('message', $keyword)->order_by("date_received","desc")->get('messages')->result();
	}
	
	public function get_msg_text($msg_id)
   	 {
		$query = $this->db->select('message')->where('msg_id',$msg_id)->get('messages');
		if ($query->num_rows() > 0)
		{
  		 $row = $query->row();
  		 return $row->message;
  		}
	}
	public function get_user_id($username)
   	 {
	$query = $this->db->select('id')->where('username',$username)->get(Applib::$user_table);
		if ($query->num_rows() > 0)
		{
  		 $row = $query->row();
  		 return $row->id;
  		}
	}
	public function check_contact_exist($user_id,$contact)
   	 {
	$this->db->where('user_id',$user_id);
	$this->db->where('contact',$contact);
	$this->db->select('contact_id');
	$query = $this->db->get('contacts');
		if ($query->num_rows() > 0)
		{
  		 $row = $query->row();
  		 return $row->contact_id;
  		}
	}
	function mark_msg($msg_id,$status){
		$data = array(
               		'status' => $status
           		 );
		$this->db->where('msg_id', $msg_id);
		$this->db->update('messages', $data); 
	}
	
}

/* End of file model.php */