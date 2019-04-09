<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('user');
		$this->set_id('user_id');
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

	public function getAdd($data) {
        if (empty($data) || !is_array($data)) {
            $result = 0;
        } else {
            $this->db->insert($this->form_name, $data);    //插入数据
            if (($this->db->affected_rows()) >= 1) {
                $result = $this->db->insert_id();      //如果插入成功，则返回插入的id
            } else {
                $result = 0;    //如果插入失败,返回0
            }
        }
        return $result;
    }

    public function getUserInfoById($userId){
    	if(empty($userId)){
    		return array();
    	}
    	$query = $this->db->select('full_name,mobile')->where('user_id',$userId)->get('user');
    	$data = $query->result_array();
    	return $data[0];
    }

    public function getUserIdByShopId($shopId = 0)
    {
    	if (empty($shopId)) {
    		return array();
    	}

    	$res = $this->db->select()
    					->where('shop_id', $shopId)
    					->get('user')
    					->row_array();

    	return !empty($res['user_id']) ? $res['user_id'] : 0;
    }
}
