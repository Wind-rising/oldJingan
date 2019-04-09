<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PointGoodsType extends BaseController{
	function __construct(){
		parent::__construct();
	}
	
	// 获取所有积分商品类型
	public function index(){
	    $viewData['_left_parent_page_name'] = 'goods';
		$viewData['_page_name'] = '商品类型';
		$viewData['_page_detail'] = '';
		$this->load->model("Point_goods_type_model");
		$allPointGoodsType = $this->Point_goods_type_model->getAllPointGoodsType();
		$viewData['point_goods_type'] = $allPointGoodsType;
		$this->view('point_goods_type/index.php', $viewData);
	}
	
	//保存积分商品页
	public function savePointGoodsType($id = 0){
		$id = intval($id);
		$pointGoodsType = $_POST;
		//数据验证
		$verifyMessage = $this->verifyPointGoodsType($pointGoodsType);
		if($verifyMessage !== true){
			$this->json_return(false, "" , $verifyMessage);
			return false;
		}
		//数据处理
		$saveData = $this->formPointGoodsType($pointGoodsType);
		$this->load->model("Point_goods_type_model");
		if($id == 0){//新增
			$result = $this->Point_goods_type_model->addPointGoodsType($saveData);
		}else{//编辑
			$result = $this->Point_goods_type_model->getPointGoodsTypeById($id);
			if(empty($result)){
				$this->json_return(false, "" , "无此商品类型");
				return false;
			}
			$result = $this->Point_goods_type_model->savePointGoodsTypeById($id, $saveData);
		}
		if($result == false){
			$this->json_return(false, "" , "保存失败");
		}else{
			$this->json_return(true);
		}
	}
	
	//删除积分商品
	public function deletePointGoodsType($id = 0){
		$this->load->model("Point_goods_model");
		$thisTypeGoodsCount = $this->Point_goods_model->getGoodsCountByTypeId($id);
		if($thisTypeGoodsCount > 0){
			$this->json_return(false, "", "请删除该类型下所有商品后再删除该类型");
		}
		
		$this->load->model("Point_goods_type_model");
		$result = $this->Point_goods_type_model->deletePointGoodsTypeById($id);
		if($result == 1){
			$this->json_return(true);
		}else{
			$this->json_return(false, '', '删除失败');
		}
	}
	
	//私有函数
	//验证商品参数有效性
	protected function verifyPointGoodsType($pointGoodsType){
		$returnMessage = true;
		if(!isset($pointGoodsType['goods_type_name']) || trim($pointGoodsType['goods_type_name']) == ""){
			$returnMessage = "商品类型不可为空";
		}else{
			$this->load->model("Point_goods_type_model");
			$existPointGoodsType = $this->Point_goods_type_model->getByTypeName(trim($pointGoodsType['goods_type_name']));
			if(!empty($existPointGoodsType)){
				$returnMessage = "类型名称已经存在";
			}
		}
		return $returnMessage;
	}
	
	//商品参数处理
	protected function formPointGoodsType($pointGoodsType){
		$pointGoodsType['goods_type_name'] = trim($pointGoodsType['goods_type_name']);
		$saveData = array(
			'type_name' => $pointGoodsType ['goods_type_name'],
			'is_delete' => 0,
			'operator' => $this->session->operator_name,
		);
		return $saveData;
	}
}
