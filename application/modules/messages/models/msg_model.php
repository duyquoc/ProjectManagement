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
		return $this->db->where('user_to',$user)->group_by("user_from")->order_by("date_received","desc")->get('messages')->result();
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
	function search_message($keyword)
	{
		$this->db->join('users','users.id = messages.user_from');
		$this->db->where(array('user_to'=>$this->tank_auth->get_user_id(),'deleted'=>'No'));
		return $this->db->like('message', $keyword)->order_by("date_received","desc")->get('messages')->result();
	}
	
}

/* End of file model.php */