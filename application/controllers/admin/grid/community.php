<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community extends BaseController{
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
        $this->load->model("Post_model");
        $this->load->model("User_model");
        $this->load->model("Community_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        // $this->db->join('user','user.user_id = post.user_id','left');
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'post'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $key => &$val) {
            $postId = $val->post_id;
            $userId = $val->user_id;
            $userInfo = $this->Post_model->getUseInfoById($userId);
            $val->name = empty($userInfo[0]['full_name'])? '暂未添加姓名':$userInfo[0]['full_name'];
            $val->mobile = $userInfo[0]['mobile'];
            $replyList = $this->Community_model->getDetailsList($postId);
            $count = count($replyList);
            $val->count = $count;
        }
        $fileds = $this->_export_fileds;
        $filename = '论坛管理表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }

    public function details($postId){
        $this->db->where('post_id',$postId);
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'community'));
        $result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '回复管理表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}