<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency extends BaseController{
    protected $_export_fileds = array(
        'agency_name' => '经销商名称',
        'pname' => '所在省份',
        'cname' => '所在城市',
        'mobile' => '销售电话',
        'mobile2' => '售后电话',
        'address' => '经销商地址',
        'status' => '是否展示',
        'edit_time' => '编辑时间'
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Agency_model");
        // $this->db = $this->load->database('cma',true);
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'agent'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $rv->id = $rv->agent_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '经销商列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}
