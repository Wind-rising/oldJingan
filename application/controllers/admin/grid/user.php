<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends BaseController{

	protected $_export_fileds = array(
        'user_id' => '用户id',
        'nick_name' => '用户名称',
        'mobile' => '手机',
        'point' => '积分',
        'create_time' => '添加时间'
    );
    
    protected $_export_fileds_saler = array(
        'user_id' => '用户id',
        'full_name' => '名称',
        'mobile' => '手机',
        'amount' => '佣金',
        'create_time' => '添加时间'
    );

	function __construct(){
		parent::__construct();
		$this->load->model("User_model");
	}

	public function index(){
		$this->db->where('status', '1');
        $this->db->where('type', '1');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'user'));
		$result = $this->grid->to_array();
		foreach ($result['rows'] as $key => $item) {
			//赋值id,删除功能需要
            $result['rows'][$key]->id = $item->user_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '用户列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));

	}
    
    public function salerapply(){

        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'apply'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $key => $item) {
            //赋值id,删除功能需要
            $result['rows'][$key]->id = $item->user_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '经纪人列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
        
    }
    
    public function saler(){
        $this->db->where('user.status', '1');
        $this->db->where('user.type', '2');
        $this->db->join('shop', 'user.shop_id = shop.shop_id', 'left');
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'user'));
        $result = $this->grid->to_array();

        $orderList = $this->db->select('saler_id')->get('order')->result_array();
        foreach ($result['rows'] as $key => &$item) {
            //赋值id,删除功能需要
            $result['rows'][$key]->id = $item->user_id;
            $item->order_count = 0;
            foreach ($orderList as $ov) {
                if ($item->user_id == $ov['saler_id']) {
                    $item->order_count = $item->order_count + 1; 
                }
            }
        }
        $fileds = $this->_export_fileds_saler;
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
		$this->id_name = 'user_id';
        foreach ($ids_arr as $value) {
            $return = $this->User_model->softDeleteById($value,$arr=array("status" => 0),'user_id');
		}
        $return_struct['status'] = 1;
        $return_struct['code'] = 200;
        $return_struct['msg'] = '删除成功';

        exit(json_encode($return_struct));
    }
}
