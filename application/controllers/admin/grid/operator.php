<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends BaseController{

	protected $_export_fileds = array(
        'operator_name' => '用户id',
        'email' => '用户名称',
        'login_time' => '手机',
        'login_ip' => '积分',
        'added_time' => '添加时间',
		'added_operator' => '添加人'
    );

	function __construct(){
		parent::__construct();
		$this->load->model("Operator_model");
	}

	public function index(){
		$this->db->where('status', '1');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'operators'));
		$result = $this->grid->to_array();
		foreach ($result['rows'] as $key => $item) {
			//赋值id,删除功能需要
            $result['rows'][$key]->id = $item->operator_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '用户列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));

	}

		public function userlist(){
        $this->db->where('role_id', $this->uri->segment(4));
		$this->db->where('status', '1');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'operators'));
		$result = $this->grid->to_array();
		foreach ($result['rows'] as $key => $item) {
			//赋值id,删除功能需要
            $result['rows'][$key]->id = $item->operator_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '用户列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));

	}

	 /**
     * 批量删除
     */
    public function batch_delete()
    {

        $id = $this->input->post('id');
        $ids_arr = explode(',', $id);
		$this->id_name = 'operator_id';
        foreach ($ids_arr as $value) {
            $this->Operator_model->softDeleteById($value,$arr=array("status" => 0));
		}

        $return_struct['status'] = 1;
        $return_struct['code'] = 200;
        $return_struct['msg'] = '删除成功';

        exit(json_encode($return_struct));
    }
}
