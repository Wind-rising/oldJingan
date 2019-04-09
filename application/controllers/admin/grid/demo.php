<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends BaseController{

	protected $_export_fileds = array(
        'user_id' => '用户id',
        'contact' => '用户名称',
        'mobile' => '手机',
        'point' => '分贝',
        'user_type' => '用户类型'
    );
	
	function __construct(){
		parent::__construct();
		
	}
	
	public function index(){
		$this->load->model("Point_model");
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'point'));
		$result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '用户积分列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
		
	}
}
