<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends BaseController{
    protected $_export_fileds = array(
        'id' => 'ID',
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Company_profile_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'company_profile'));
        $result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '公司简介管理' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}