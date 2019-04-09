<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends BaseController{
    protected $_export_fileds = array(
        'user_id' => 'ID',
        'activity_id' => '游戏活动ID',
        'user_name' => '会员名称',
        'mobile' => '联系方式',
        'type' => '绑定类型',
        'create_time' => '绑定时间',
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Member_model");
    }

    /**
     * 后台会员管理
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'user'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $rv->id = $rv->user_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '会员列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}