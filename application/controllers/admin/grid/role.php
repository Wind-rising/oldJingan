<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends BaseController{

	protected $_export_fileds = array(
        'name' => '名称',
        'remark' => '描述',
        'created' => '生成时间'
    );
	
	function __construct(){
		parent::__construct();
		$this->load->model("Role_model");
	}
	
	public function index(){
		$this->db->where('active','Y');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'roles'));
		$result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '角色列表' . date('Y-m-d H:i:s');
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
            $this->Role_model->softDeleteById($value,$arr=array("active" => "N"));
		}

        $return_struct['status'] = 1;
        $return_struct['code'] = 200;
        $return_struct['msg'] = '删除成功';

        exit(json_encode($return_struct));
    }
}
