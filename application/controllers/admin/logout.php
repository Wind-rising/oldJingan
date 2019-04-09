<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends NologinController{
	function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->admin_logout();
	}
	
	public function admin_logout(){
		$this->session->unset_userdata('operator_name');
		//跳转到登录页
		header("location:".login_url());
	}
}
