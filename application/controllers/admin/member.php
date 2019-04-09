<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends BaseController{

	function __construct(){
		parent::__construct();
		$this->load->model("Member_model");
	}

    /**
	 * 会员管理
	 * @return void
	 */
	public function index()
	{
		$viewData['_page_name'] = '会员管理';
		$viewData['_page_detail'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;前台链接 <a href="http://www1.yuedazb.net:800/ydzb/index.php/user/index" target="_blank;">http://www1.yuedazb.net:800/ydzb/index.php/user/index</a>';

		$this->view('member/index.php', $viewData);
	}

	public function batch_delete()
    {
        $return_struct['status'] = 1;
        $return_struct['code'] = 200;
        $return_struct['msg'] = '删除成功';

        $ids = $this->input->post('id');
        $ids_arr = explode(',', $ids);
        foreach ($ids_arr as $value) {
            $result = $this->Member_model->deleteById($value);
            if (!$result) {
                $return_struct['status'] = 0;
                $return_struct['msg'] = '删除失败';
            }
        }

        exit(json_encode($return_struct));
    }
}
