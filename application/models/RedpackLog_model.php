<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RedpackLog_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('redpack_log');
		$this->set_id('id');
	}
	
	public function insert($info)
    {
        $this->db->insert('redpack_log', $info);

        return $this->db->insert_id();
    }

    public function getByCode($code)
    {
        return $this->db->select()
                        ->where('code', $code)
                        ->get('redpack_log')
                        ->row_array();
    }

    public function getByOpenidToday($openid)
    {
        $today = date('Y-m-d');

        return $this->db->select()
                        ->where('openid', $openid)
                        ->where("date_format(added_time, '%Y-%m-%d') =", $today)
                        ->get('redpack_log')
                        ->row_array();
    }

    public function getRedPackListToday()
    {
        $today = date('Y-m-d');

        return $this->db->select()
                        ->where("date_format(added_time, '%Y-%m-%d') =", $today)
                        ->get('redpack_log')
                        ->result_array();
    }
    
    public function updateById($data, $id)
    {
        $this->db->where('id', $id)
                 ->update('redpack_log', $data);
    }
}
