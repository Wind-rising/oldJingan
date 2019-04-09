<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends BaseController{
	function __construct(){
		parent::__construct();
	}

	public function index(){
	    $viewData['_left_parent_page_name'] = '主面板';
		$viewData['_page_name'] = '主面板';
		$viewData['_page_detail'] = '';
		$this->view('demo.php', $viewData);
	}

	public function testindex(){
	    $viewData['_left_parent_page_name'] = '主面板';
		$viewData['_page_name'] = '主面板';
		$viewData['_page_detail'] = '';
		$this->view('demo.php', $viewData);
	}
}
