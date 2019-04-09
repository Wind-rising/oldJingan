<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Official extends BaseController{
    protected $_export_fileds = array(
    );

	function __construct(){
		parent::__construct();
		$this->load->model("Official_model");
	}

	public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'official'));
        $result = $this->grid->to_array();

        $fileds = $this->_export_fileds;
        $filename = '问答官方人员管理' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
	}
}
