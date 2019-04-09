<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleSale extends BaseController{

	protected $_export_fileds = array(
        'shop_name' => '经销商名称',
        'product_name' => '产品名称',
        'spec_name' => '车款',
        'color_name' => '颜色',
        'desc' => '优惠说明',
        'stock' => '库存',
        'price' => '定金',
        'affiliate_amount' => '分销金额',
        'create_time' => '发布时间',
    );

	function __construct(){
		parent::__construct();
		$this->load->model("Vehicle_sale_model");
	}

	public function index(){
		$this->db->where('status', 1);
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'product'));
		$result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
//        foreach ($result['rows'] as $key => $item) {
//            //读取颜色
//            $colorname = $this->Vehicle_sale_model->getByColorId($item->color_id);
//            if(!empty($shopname['0']['color_name'])) {
//                $result['rows'][$key]->colorname = $colorname['0']['color_name'];
//            }
//            //读取车款
//            $specname = $this->Vehicle_sale_model->getBySpecId($item->spec_id);
//            if(!empty($shopname['0']['spec_name'])) {
//                $result['rows'][$key]->specname = $specname['0']['spec_name'];
//            }
//            //读取经销商
//            $shopname = $this->Vehicle_sale_model->getByShopId($item->shop_id);
//            if(!empty($shopname['0']['shop_name'])) {
//                $result['rows'][$key]->shopname = $shopname['0']['shop_name'];
//            }
//        }
       
        $filename = '商品列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
	}

	public function index2()
	{
		$this->db->where('status', 0);
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'product'));
		$result = $this->grid->to_array();

        $fileds = $this->_export_fileds;
        $filename = '待审核商品列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
	}

	public function index3()
	{
		$this->db->where('status', 2);
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'product'));
		$result = $this->grid->to_array();

        $fileds = $this->_export_fileds;
        $filename = '未通过审核商品列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
	}
}
