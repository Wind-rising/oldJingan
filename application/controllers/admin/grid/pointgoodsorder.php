<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pointgoodsorder extends BaseController{

	protected $_export_fileds = array(
       'order_code' => '订单号',
       'goods_name' => '商品名称',
       'contact' => '联系人',
		'shopname' => '所在公司',
		'brand_name' => '品牌',
       'phone' => '电话',
       'area' => '城市',
       'address' => '地址',
       'point' => '消耗积分',
       'carriage' => '运费',
       'status_str' => '订单状态'
    );

	function __construct(){
		parent::__construct();
		$this->load->model("Point_goods_order_model");
	}

	public function index(){
		//$this->Point_goods_order_model->dbselect('chelaba_slave');
        //$this->db = $this->Point_goods_order_model->db;
		
		$this->db->where('1', '1');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'point_goods_order'));
		$result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '积分订单列表' . date('Y-m-d H:i:s');
		$POINT_ORDER = POINT_ORDER();
		
		$config['POINT_ORDER']['POINT_ORDER_APPLY'] = 0;//订单未支付
		$config['POINT_ORDER']['POINT_ORDER_PAYED'] = 1;//订单已经支付
		$config['POINT_ORDER']['POINT_ORDER_SENDED'] = 2;//订单已经发货
		$config['POINT_ORDER']['POINT_ORDER_FAILED'] = 9;//订单作废
		
		foreach($result['rows'] as &$data){
			$data->address = htmlspecialchars($data->address);
			$data->contact = htmlspecialchars($data->contact);
			$data->status_str = '';
			switch($data->status){
				case $POINT_ORDER['POINT_ORDER_APPLY']:
					$data->status_str = '暂未支付';
					break;
				case $POINT_ORDER['POINT_ORDER_PAYED']:
					$data->status_str = '已付运费(未发货)';
					break;
				case $POINT_ORDER['POINT_ORDER_SENDED']:
					$data->status_str = '兑换成功(已发货)';
					break;
				case $POINT_ORDER['POINT_ORDER_FAILED']:
					$data->status_str = '订单已取消';
					break;
				default:
					break;
			}
		}
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
	}
}
