<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('users');
		$this->set_id('id');
	}
	
	public function getByMobile($mobile){
		$cond = array(
				'where' => array(
					'mobile' =>$mobile
				)
		);
		$data = $this->get_data($cond);
		if(empty($data)){
			return array();
		}else{
			return $data[0];
		}
	}

    public function getByOpenidToday($openid)
    {
        $today = date('Y-m-d');

        return $this->db->select()
                        ->where('openid', $openid)
                        ->where("date_format(added_time, '%Y-%m-%d') =", $today)
                        ->get('users')
                        ->row_array();
    }

    public function insert($info)
    {
        $this->db->insert('users', $info);

        return $this->db->insert_id();
    }

    public function updateById($data, $id)
    {
        $this->db->where('id', $id)
                 ->update('users', $data);
    }
}
