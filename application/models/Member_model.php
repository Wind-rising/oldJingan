<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('user');
		$this->set_id('user_id');
	}
	
    public function login($mobile,$name,$activityId){
        $cond = array(
                'where' => array(
                    'mobile' =>$mobile,
                    'user_name' =>$name,
                    'activity_id' =>$activityId
                )
        );
        $data = $this->get_data($cond);
        if(empty($data)){
            return array();
        }else{
            return $data[0];
        }
    }
    
	public function getByMobile($mobile,$activityId){
		$cond = array(
				'where' => array(
					'mobile' =>$mobile,
                    'activity_id' =>$activityId
				)
		);
		$data = $this->get_data($cond);
		if(empty($data)){
			return array();
		}else{
			return $data[0];
		}
	}

	public function getByOpenId($openid){
		$cond = array(
				'where' => array(
					'openid' =>$openid
				)
		);
		$data = $this->get_data($cond);
		if(empty($data)){
			return array();
		}else{
			return $data[0];
		}
	}

	public function getByUserId($userId){
		$cond = array(
				'where' => array(
					'user_id' =>$userId
				)
		);
		$data = $this->get_data($cond);
		if(empty($data)){
			return array();
		}else{
			return $data[0];
		}
	}

	public function getAllUserInfo($gold,$activityId){
		$cond = array(
            'where' => array(
                    'activity_id'  =>$activityId,
                ),
			'order_by' => $gold,
		);

		$data = $this->get_data($cond);
		if(empty($data)){
			return array();
		}else{
			return $data;
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

    public function updateById($updateData, $id,$con='ydzb'){
        $this->db->where('user_id', $id);
       return $result = $this->db->update('get_gold_user', $updateData );
    }

    public function getRank($userId,$gold,$activityId)
    {
        if (empty($userId)) {
            return 0;
        }
        //输出排行
        $list = $this->getAllUserInfo($gold,$activityId);
        $list = $this->arrayValueList($list, 'user_id');
        $ranking = array_search($userId, $list) + 1; // 排名
        return $ranking;
    }

    public function arrayValueList()
    {
        $ret = array();
        $argList = func_get_args();
        $name = end($argList);
        if (!is_string($name)) {
            return $ret;
        }
        foreach ($argList as $arg) {
            if (!empty($arg) && is_array($arg)) {
                foreach ($arg as $item) {
                    if (isset($item[$name])) {
                        $ret[] = $item[$name];
                    }
                }
            }
        }
        return $ret;
    }

    public function page($page,$gold,$activityId,$pagesize = 10)
    {
        $all_info = $this->getAllUserInfo($gold,$activityId);
        $count = count($all_info);//总页数
        $max_page = ceil($count / $pagesize);//最大页
        $page = ($page > $max_page) ? $max_page : $page;
        $start =($page - 1) * $pagesize;
        $all_list = $this->getAllInfo($pagesize,$start,$activityId);
        foreach ($all_list as $key => &$value) {
            $value['index'] = $start + $key + 1;
        }
        return array('all_list' => $all_list, 'max_page' => $max_page);
    }

    public function getAllInfo($pagesize,$start,$activityId){
		$this->db->where('activity_id', $activityId)->order_by('gold desc');
        $this->db->limit($pagesize, $start);
        $query = $this->db->get('get_gold_user');
        $data = $query->result_array();
        return $data;
	}
}
