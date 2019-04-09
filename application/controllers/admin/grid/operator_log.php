<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator_log extends BaseController{
    protected $_export_fileds = array(
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Operator_log_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'operator_log_new'));
        $result = $this->grid->to_array();

        $fileds = $this->_export_fileds;
        $filename = '后台日志列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }


}
