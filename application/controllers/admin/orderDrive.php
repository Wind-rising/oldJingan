<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderDrive extends BaseController{

	function __construct(){
		parent::__construct();
		$this->load->model("Orderdrive_model");
		$this->load->library('session');
	}

    /**
	 * 用户管理
	 * @return void
	 */
	public function index()
	{
		$session = $this->session;
		$viewData['_page_name'] = '预约试驾用户管理';
		$viewData['_page_detail'] = '';
		$this->view('orderDrive/index.php', $viewData);
	}

	public function award()
	{
		$upkeepId = intval($_POST['upkeepId']);
		$saveData['status'] = 1;
		$upkeepInfo = $this->Upkeep_model->getById($upkeepId);
		if(!empty($upkeepInfo)){
			if($upkeepInfo['status'] == 0){
				$res = $this->Upkeep_model->updateStatus($upkeepId,$saveData);
				$this->json_return(true, array(),'请慎重填写,该项为不可修改！');
			}
		}
	}
}
