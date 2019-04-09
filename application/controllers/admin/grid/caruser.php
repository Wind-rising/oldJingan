<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caruser extends BaseController{
    protected $_export_fileds = array(
        'user_id' => 'ID',
        'data' => '活动日期',
        'user_name' => '用户名',
        'mobile' => '联系方式',
        'lottery_name' => '奖品名称',
        'address' => '详细地址',
        'create_time' => '参加时间',
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Car_game_user_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'car_game_user'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $rv->id = $rv->user_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '赛车游戏用户表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}