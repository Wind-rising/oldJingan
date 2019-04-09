<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apply extends BaseController{

    protected $_export_fileds = array(
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Apply_model");
    }

    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'apply'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $rv->id = $rv->apply_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '在线申请列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));

    }
}
