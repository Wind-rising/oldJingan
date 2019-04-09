<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pointlog extends BaseController{

	protected $_export_fileds = array(
		'mobile' => '账号',
		'user_type' => '用户类型(0：用户，1：销售顾问)',
		'point' => '变化积分',
		'mobile' => '兑换人手机号',
		'added_time' => '发放时间',
		'series_name' => '车系'
    );

	function __construct(){
		parent::__construct();
		$this->load->model("Point_log_model");
	}

	public function index(){
		$this->load->model("User_model");
		
		$this->db->where('1', '1');
		$this->db->where('event_type <>', '');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'point_log'));
		$result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '积分兑换日志' . date('Y-m-d H:i:s');
		foreach($result['rows'] as &$data){
			$data->mobile = '';
			$data->contact = '';
			$user = $this->User_model->getById($data->user_id);
			if(empty($user)){
				continue;
			}
			$data->mobile = $user['mobile'];
			$data->contact = $user['nick_name'];
		}
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
	}
}
