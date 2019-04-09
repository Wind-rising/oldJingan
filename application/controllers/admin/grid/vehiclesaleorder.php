<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleSaleOrder extends BaseController{
    protected $_export_fileds = array(
        'order_no' => '订单编码',
        'series_name' => '商品信息',
        'shop_name' => '经销商名称',
        'spec_name' => '车款',
        'contact' => '姓名',
        'mobile' => '手机号',
        'affiliate_contact' => '分销人',
        'affiliate_mobile' => '分销人号码',
        'saler_mobile' => '接单人号码',
        'create_time' => '生成时间',
        'paid_time' => '支付时间',
        'status' => '订单状态'
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Vehicle_sale_order_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
        public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'order'));
        $result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '汽车订单列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}
