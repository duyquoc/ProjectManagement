<?php
class Profile extends CI_Model {
  private static $db;
  function __construct() {
    parent::__construct();
    self::$db = &get_instance()->db;
  }

  
  static function get_field($table, $where_criteria, $table_field) {
    return self::$db->select($table_field)->where($where_criteria)->get($table) -> row();
   }
  function comments() {
    $this->load->model('comment');
    return $this->db->where('user_id', $this->id)->get('comments')->result('Comment');
  }
}
