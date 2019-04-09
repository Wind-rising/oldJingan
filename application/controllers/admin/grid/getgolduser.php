<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getgolduser extends BaseController{
    protected $_export_fileds = array(
        'user_id' => 'ID',
        'activity_id' => '游戏活动ID',
        'is_prize' => '是否中奖',
        'user_name' => '游戏昵称',
        'gold' => '金币数',
        'address_name' => '联系人',
        'lottery_name' => '奖品名称',
        'mobile' => '联系方式',
        'province' => '省份',
        'city' => '城市',
        'address' => '详细地址',
        'start_time' => '活动开始时间',
        'end_time' => '活动结束时间',
        'create_time' => '参加时间',
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Get_gold_user_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'get_gold_user'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $rv->id = $rv->user_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '接金币游戏用户表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}