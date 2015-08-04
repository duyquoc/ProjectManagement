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
class Item_model extends CI_Model
{
	
	function list_items()
	{
		return $this->db->where('deleted','No')->order_by('item_id','desc')->get('items_saved')->result();
	}
	function list_tasks()
	{
		return $this->db->order_by('added','desc')->get('saved_tasks')->result();
	}
	
	function saved_item_details($item)
	{
		return $this->db->where('item_id',$item)->get('items_saved')->result();
	}
	function search_item($keyword)
	{
		return $this->db->where('deleted', 'No')->like('item_desc', $keyword)->order_by("item_id","desc")->get('items_saved')->result();
	}
	function search_task($keyword)
	{
		return $this->db->like('task_desc', $keyword)->order_by("added","desc")->get('saved_tasks')->result();
	}
	function task_details($task)
	{
		return $this->db->where('template_id',$task)->get('saved_tasks')->result();
	}
	
}

/* End of file model.php */