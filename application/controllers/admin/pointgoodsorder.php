<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PointGoodsOrder extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->model("Point_goods_order_model");
	}

	public function index(){
		$viewData['_left_parent_page_name'] = 'PointGoodsOrder';
		$viewData['_page_name'] = '积分商品订单';
		$viewData['_page_detail'] = '';
		$this->view('point_goods_order/index.php', $viewData);
	}
	
	//获取所有兑换订单
	public function index_1(){
        $viewData['_left_parent_page_name'] = 'point';
		$viewData['_page_name'] = '兑换订单';
		$viewData['_page_detail'] = '';
		$this->load->model("Point_goods_order_model");
		$orders = $this->Point_goods_order_model->getPointGoodsOrder();
		$viewData['orders'] = $orders;
		$viewData['POINT_ORDER'] = config_item("POINT_ORDER");
		$this->view('point_goods_order/index_1.php', $viewData);
	}
	
	//获取兑换订单详情
	public function detail($orderId = 0){
		$viewData['_left_parent_page_name'] = 'point';
		$viewData['_page_name'] = '兑换订单详情';
		$viewData['_page_detail'] = '';
		$this->load->model("Point_goods_order_model");
		$order = $this->Point_goods_order_model->getById($orderId);
		if(empty($order)){
			$this->error_page("该订单不存在!");
			return false;
		}
		$userId = $order['user_id'];
		$userType = $order['user_type'];
		$user = array();
		$this->load->model("User_model");
		$user = $this->User_model->getById($userId);
		
		$this->load->model("Point_goods_order_detail_model");
		$orderDetail =$this->Point_goods_order_detail_model->getPointGoodsOrderDetail($order['order_id']);
		$viewData['order'] = $order;
		$viewData['user'] = $user;
		$viewData['orderDetail'] = $orderDetail;
		$viewData['POINT_ORDER'] = config_item("POINT_ORDER");
		$this->view('point_goods_order/detail.php', $viewData);
	}
	
	public function sendGoods($orderId = 0){
		$this->load->model("Point_goods_order_model");
		$order = $this->Point_goods_order_model->getById($orderId);
		if(empty($order)){
			$this->json_return(false, "" , "无此订单");
			return false;
		}
		$POINT_ORDER = config_item("POINT_ORDER");
		if($order['status'] != $POINT_ORDER['POINT_ORDER_PAYED']){
			$this->json_return(false, "" , "该订单不可发货");
			return false;
		}
		$updateData['status'] = $POINT_ORDER['POINT_ORDER_SENDED'];
		$order = $this->Point_goods_order_model->updateById($updateData, $orderId);
		$this->json_return(true);
	}
}
