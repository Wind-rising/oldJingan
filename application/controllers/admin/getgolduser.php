<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getgolduser extends BaseController{

	function __construct(){
		parent::__construct();
		$this->load->model("Get_gold_user_model");
	}

    /**
	 * 用户管理
	 * @return void
	 */
	public function index()
	{
		$viewData['_page_name'] = '接金币游戏用户表';
		$viewData['_page_detail'] = '';
		$this->view('getgolduser/index.php', $viewData);
	}

	public function award()
	{
		$userId = intval($_POST['userId']);
		$saveData['lottery_name'] = trim($_POST['award']);
		$saveData['is_prize'] = 1;
		$userInfo = $this->Get_gold_user_model->getByUserId($userId);
		if(!empty($userInfo)){
			if(empty($userInfo['lottery_name'] !=='')){
				$this->Get_gold_user_model->updateById($saveData, $userId);
				$this->json_return(true, array(),'请慎重填写,该项为不可修改！');
			}else{
				$this->json_return(false, array(),'奖品已存在，不可修改！');
			}
		}
	}

	public function batch_delete()
    {
        $return_struct['status'] = 1;
        $return_struct['code'] = 200;
        $return_struct['msg'] = '删除成功';

        $ids = $this->input->post('id');
        $ids_arr = explode(',', $ids);
        foreach ($ids_arr as $value) {
            $result = $this->Get_gold_user_model->deleteById($value);
            if (!$result) {
                $return_struct['status'] = 0;
                $return_struct['msg'] = '删除失败';
            }
        }

        exit(json_encode($return_struct));
    }
}
