<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Point extends BaseController{
	function __construct(){
		parent::__construct();
	}
	
	public function index($page = 1){
		$page = intval($page) <= 0 ? 1 : intval($page);
	    $viewData['_left_parent_page_name'] = 'point';
		$viewData['_page_name'] = '用户积分';
		$viewData['_page_detail'] = '';
		$pageNum = 10;
		$this->load->model("Point_model");
		$point = $this->Point_model->getPointByPage($page, $pageNum);
		
		$viewData['point_arr'] = $point;
		$viewData['page'] = $page;
		$this->view('point/index.php', $viewData);
	}
	
	public function index2($page = 1){
		$viewData['_page_name'] = '用户积分';
		$viewData['_page_detail'] = '';
		$this->view('point/index2.php', $viewData);
	}
}
