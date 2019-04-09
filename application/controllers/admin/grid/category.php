<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends BaseController{

	protected $_export_fileds = array(
        'category_name' => '群组名称',
    );
	
	function __construct(){
		parent::__construct();
		$this->load->model("Category_model");
	}
	
	public function index(){
        $this->db = $this->Category_model->db;
        $this->db->where('parent_id', '0');
        $this->db->where('is_delete','0');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'categories'));
		$result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '资讯分类列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
		
	}

    public function childindex(){
        $this->db = $this->Category_model->db;
        $this->db->where('parent_id', $_GET["categoryid"]);
        $this->db->where('is_delete','0');
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'categories'));
        $result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '资讯分类列表' . date('Y-m-d H:i:s');
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
