<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends NologinController{
	function __construct(){
		parent::__construct();
		if(true == $this->session->has_userdata('operator_name')){
			if(($this->session->operator_name) && $this->session->operator_name == 'cowinauto'){//凯翼组
				header("location:".kaiyi_url());
				return false;
			}
			header("location:".default_url());
		}
	}

	public function index(){
		//如果验证成功
		$this->load->view("login/login");
	}

	public function admin_login(){
		//如果验证成功
		if(($this->session->operator_name) && $this->session->operator_name == 'cowinauto'){//凯翼组
			header("location:".kaiyi_url());
			return false;
		}
		header("location:".default_url());
	}

	public function ajax_login(){
		$admin_name = isset($_POST['admin_name']) ? $_POST['admin_name'] : "";
		$password = isset($_POST['password']) ? $_POST['password'] : "";
		$this->load->model("Operator_model");
        
		$operator = $this->Operator_model->getUserByName($admin_name);
		if(empty($operator) || $operator['password'] != md5($password)){
			$this->json_return(false);
		}else{
            $arrlog = array();
			$this->session->operator_name = $operator['operator_name'];
			$this->session->password = md5($password);
			$this->session->operator_id = $operator['operator_id'];
			$this->session->role_id = $operator['role_id'];
			$role = $this->Operator_model->get_data(array('form_name' => 'roles','where'=> array('id'=>$operator['role_id'])));
			$this->session->role_name = $role[0]['name'];
			$this->session->privilege_list = $operator['privilege_list'];
            $arrlog['login_ip'] = getIP();
            $arrlog['login_time'] = date("Y-m-d H:i:s");
            $this->Operator_model->set_table('operators');
            $flag = $this->Operator_model->save(array('operator_id'=>$operator['operator_id']), $arrlog);
			$this->json_return(true);
		}
	}
}
