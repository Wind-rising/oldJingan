<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultation extends BaseController{
    protected $_export_fileds = array(
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Consultation_model");
        $this->load->model("User_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->db->where('type', 4);
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'consultation'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as &$r) {
            $r->id = $r->consultation_id;
        }

        $fileds = $this->_export_fileds;
        $filename = '系统消息管理' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }

    public function details($id){
        $this->db->where('consultation_id',$id);
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'community'));
        $result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '回复管理表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }

    public function batch_delete()
    {
        $return_struct['status'] = 1;
        $return_struct['code'] = 200;
        $return_struct['msg'] = '删除成功';

        $ids = $this->input->post('id');
        $ids_arr = explode(',', $ids);
        foreach ($ids_arr as $value) {
            $result = $this->Consultation_model->deleteById($value);
            if (!$result) {
                $return_struct['status'] = 0;
                $return_struct['msg'] = '删除失败';
            }
        }

        exit(json_encode($return_struct));
    }
}