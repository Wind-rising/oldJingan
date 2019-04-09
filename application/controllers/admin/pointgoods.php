<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PointGoods extends BaseController{
	function __construct(){
		parent::__construct();
	}
	
	// 获取所有积分商品
	public function index(){
	    $viewData['_left_parent_page_name'] = 'goods';
		$viewData['_page_name'] = '积分商品';
		$viewData['_page_detail'] = '';
		$this->load->model("Point_goods_model");
		$allPointGoods = $this->Point_goods_model->getAllPointGoods();
		$viewData['point_goods'] = $allPointGoods;
		$this->view('point_goods/index.php', $viewData);
	}
	
	//删除积分商品
	public function deletePointGoods($id = 0){
		$this->load->model("Point_goods_model");
		$result = $this->Point_goods_model->deletePointGoodsById($id);
		if($result == 1){
			$this->json_return(true);
		}else{
			$this->json_return(false);
		}
	}
	
	//显示编辑积分商品页
	public function showEdit($id = 0, $href = 0){
	    $viewData['_left_parent_page_name'] = 'goods';
		$viewData['_page_name'] = '积分商品';
		$viewData['_page_detail'] = '';
		$id = intval($id);
		$this->load->model("Point_goods_model");
		$pointGoods = $this->Point_goods_model->getPointGoodsById($id);
		if(empty($pointGoods)){
			$this->error_page("该积分商品不存在!");
			return false;
		}
		$this->load->model("Point_goods_type_model");
		$allPointGoodsType = $this->Point_goods_type_model->getAllPointGoodsType();
		
		$this->load->model("Point_goods_detail_model");
		$detailCityIdArr = $this->Point_goods_detail_model->getDetailCity($id);
		$areaInfo = $this->formAreaInfo($detailCityIdArr);
		
		$viewData['area_info'] = $areaInfo;
		$viewData['point_goods_type'] = $allPointGoodsType;
		$viewData['point_goods'] = $pointGoods;
		$viewData['option_flag'] = 'edit';
		$viewData['href'] = intval($href);
		$this->view('point_goods/edit.php', $viewData);
	}
	
	public function getDetailHtml($pointGoodsId){
		$this->load->model("Point_goods_model");
		$pointGoods = $this->Point_goods_model->getPointGoodsById($pointGoodsId);
		$this->json_return(true, "", isset($pointGoods["detail"]) ? $pointGoods["detail"] : "");
	}
	
	//显示新增积分商品页
	public function showAdd(){
		$this->load->model("Point_goods_type_model");
		$allPointGoodsType = $this->Point_goods_type_model->getAllPointGoodsType();
		$viewData['point_goods_type'] = $allPointGoodsType;
		$viewData['point_goods'] = array();
		$viewData['option_flag'] = 'add';
		$viewData['_left_parent_page_name'] = 'goods';
		$viewData['_page_name'] = '积分商品';
		$viewData['_page_detail'] = '';
		$this->view('point_goods/edit.php', $viewData);
	}
	
	//保存积分商品全局
	public function savePointGoodsGlobal($id = 0){
		$id = intval($id);
		$pointGoods = $_POST;
		
		//数据验证
		$verifyMessage = $this->verifyPointGoodsGlobal($pointGoods);
		if($verifyMessage !== true){
			$this->json_return(false, "" , $verifyMessage);
			return false;
		}
		//数据处理
		$saveData = $this->formPointGoodsGlobal($pointGoods);
		$this->load->model("Point_goods_model");
		
		$exitDetailFlag = true;
		if($id == 0){//新增积分商品
			//$saveData['is_display'] = 0; //默认不显示
			$result = $this->Point_goods_model->addPointGoods($saveData);
			$this->json_return(true, $result, "保存成功。");
			return true;
		}else{//编辑积分商品
			$result = $this->Point_goods_model->getPointGoodsById($id);
			if(empty($result)){
				$this->json_return(false, "" , "无此积分商品");
				return false;
			}
			$result = $this->Point_goods_model->savePointGoodsById($id, $saveData);
			
			//保存成功，判断是否有细节属性，如果没有就设置为不展示
			$detailResult = true;//$this->checkHasDetail($id);
			if($detailResult == false){
				$this->json_return(true, $result, "保存成功。");
			}else{
				$this->json_return(true, $result, "保存成功");
			}
		}
	}
	
	//保存积分商品细节
	public function savePointGoodsDetail($id = 0){
		$id = intval($id);
		$this->load->model("Point_goods_model");
		$result = $this->Point_goods_model->getPointGoodsById($id);
		if(empty($result)){
			$this->json_return(false, "" , "无此积分商品");
			return false;
		}
			
		$pointGoodsDetail = $_POST;
		//数据验证
		$verifyMessage = $this->verifyPointGoodsDetail($pointGoodsDetail);
		if($verifyMessage !== true){
			$this->json_return(false, "" , $verifyMessage);
			return false;
		}
		//数据处理
		$saveData = $this->formPointGoodsDetail($pointGoodsDetail, $id);
		$this->load->model("Point_goods_detail_model");
		//删除这些城市的详细信息
		$this->Point_goods_detail_model->deleteByGoodsIdAndCityId($id, $pointGoodsDetail['district']);
		//再添加这些城市新的详细信息
		$this->Point_goods_detail_model->batchInsert($saveData);
		$this->json_return(true, "" , "保存细节属性成功");
	}
	
	//获取积分商品细节属性
	public function getPointGoodsDetail($id, $districtId){
		$id = intval($id);
		$districtId = intval($districtId);
		$this->load->model("Point_goods_detail_model");
		$pointGoodsDetail = $this->Point_goods_detail_model->getPointGoodsDetailByDistrictId($id, $districtId);
		if(empty($pointGoodsDetail)){
			$this->json_return(false, "" , "无此城市的细节属性");
		}else{
			$this->json_return(true, $pointGoodsDetail, "");
		}
		
	}
	
	//删除所有积分商品细节属性
	public function deleteAllDetail($pointGoodsId = 0){
		$this->load->model("Point_goods_detail_model");
		$pointGoodsDetail = $this->Point_goods_detail_model->deleteByPointGoodsId($pointGoodsId);
		$detailResult = $this->checkHasDetail($pointGoodsId);
		if($detailResult == false){
			$this->json_return(true, 1);
		}else{
			$this->json_return(true, 0);
		}
	}
	
	
	//按照省份删除
	public function deleteProvinceDetail($pointGoodsId = 0, $provinceId = 0){
		$this->load->model("District_model");
		$cityList = $this->District_model->getCityByProvinceId($provinceId);
		$cityIdList = array();
		foreach($cityList as $city){
			$cityIdList[] =  $city['district_id'];
		}
		$this->load->model("Point_goods_detail_model");
		$pointGoodsDetail = $this->Point_goods_detail_model->deleteByGoodsIdAndCityId($pointGoodsId, $cityIdList);
		$detailResult = $this->checkHasDetail($pointGoodsId);
		if($detailResult == false){
			$this->json_return(true, 1);
		}else{
			$this->json_return(true, 0);
		}
	}
	
	//按城市删除
	public function deleteCityDetail($pointGoodsId = 0, $cityId = 0){
		$cityIdList = array($cityId);
		$this->load->model("Point_goods_detail_model");
		$pointGoodsDetail = $this->Point_goods_detail_model->deleteByGoodsIdAndCityId($pointGoodsId, $cityIdList);
		$detailResult = $this->checkHasDetail($pointGoodsId);
		if($detailResult == false){
			$this->json_return(true, 1);
		}else{
			$this->json_return(true, 0);
		}
	}
	
	protected function checkHasDetail($pointGoodsId){
		$this->load->model("Point_goods_detail_model");
		$detailResult = $this->Point_goods_detail_model->checkByPointGoodsId($pointGoodsId);
		if($detailResult == false){
			$saveDisplay = array(
					'is_display' => 0
			);
			$this->load->model("Point_goods_model");
			$this->Point_goods_model->savePointGoodsById($pointGoodsId, $saveDisplay);
			return false;
		}
		return true;
	}
	
	
	
	
	
	//私有函数
	//验证商品参数有效性
	protected function verifyPointGoodsGlobal($pointGoods){
		$returnMessage = true;
		// if(!isset($pointGoods['goods_type']) || intval($pointGoods['goods_type']) == 0){
		// 	$returnMessage = "商品类型有误";
		// }else{
		// 	$this->load->model("Point_goods_type_model");
		// 	$result = $this->Point_goods_type_model->getPointGoodsTypeById($pointGoods['goods_type']);
		// 	if(empty($result)){
		// 		$returnMessage = "无此商品类型，请刷新页面重试";
		// 		return $returnMessage;
		// 	}
		// }
		
		if(!isset($pointGoods['goods_name']) || trim($pointGoods['goods_name']) == ""){
			$returnMessage = "商品名称有误";
		}else if(!isset($pointGoods['need_point']) || intval($pointGoods['need_point']) <= 0){
			$returnMessage = "首页展示积分有误";
		}else if(!isset($pointGoods['goods_count']) || intval($pointGoods['goods_count']) < 0){
			$returnMessage = "库存有误";
		}else if( !isset($pointGoods['is_display'])){
			$returnMessage = "是否展示有误";
		}
		return $returnMessage;
	}
	
	protected function verifyPointGoodsDetail($pointGoods){
		$returnMessage = true;
		//地区验证
		if(!isset($pointGoods['district']) || !is_array($pointGoods['district']) || empty($pointGoods['district'])){
			$returnMessage = "兑换地区有误";
			return $returnMessage;
		}else{
			$this->load->model("District_model");
			$allCity = $this->District_model->getAllCity();
			$allCityIdArr = array();
			foreach($allCity as $city){
				$allCityIdArr[] = $city['district_id'];
			}
			foreach($pointGoods['district'] as $districtId){
				if(!in_array($districtId, $allCityIdArr)){
					$returnMessage = "兑换地区有误";
					return $returnMessage;
				}
			}
		}
		
		//颜色验证
		if(!isset($pointGoods['color']) || !is_array($pointGoods['color']) || empty($pointGoods['color'])){
			$returnMessage = "颜色有误";
			return $returnMessage;
		}else{
			$POINT_GOODS_COLOR = POINT_GOODS_COLOR();
			if(!(count($pointGoods['color']) == 1 && $pointGoods['color'][0] == -1)){
				foreach($pointGoods['color'] as $colorId){
					if(!isset($POINT_GOODS_COLOR[$colorId])){
						$returnMessage = "颜色有误";
						return $returnMessage;
					}
				}
			}
		}
		
		if(!isset($pointGoods['title']) || trim($pointGoods['title']) == ""){
			$returnMessage = "商品子标题有误";
		}else if(!isset($pointGoods['need_point']) || intval($pointGoods['need_point']) <= 0){
			$returnMessage = "所需积分有误";
		}else if(!isset($pointGoods['carriage']) || intval($pointGoods['carriage']) < 0){
			$returnMessage = "商品运费有误";
		}
		return $returnMessage;
	}
	
	
	//商品参数处理
	protected function formPointGoodsGlobal($pointGoods){
		$saveData = array(
			'goods_name' => trim($pointGoods['goods_name']),
			'need_point' => $pointGoods ['need_point'],
			'goods_count' => $pointGoods ['goods_count'],
			'goods_img' => $pointGoods ['goods_img'],
			'detail' => $pointGoods ['goods_detail'],
			'start_time' => date("Y-m-d H:i:s"),
			'end_time' => date("Y-m-d H:i:s"),
			'carriage' => 0,
			'goods_type' => intval($pointGoods ['goods_type']),
			'is_delete' => 0,
			'is_display' => $pointGoods['is_display'] == 0 ? 0 : 1,
			'operator' => $this->session->operator_name,
			'sort' => isset($pointGoods ['sort']) ? intval($pointGoods ['sort']) : 0
		);
		return $saveData;
	}
	
	//商品细节参数处理
	protected function formPointGoodsDetail($pointGoods, $id){
		$saveData = array(
			'point_goods_id' => $id,
			'need_point' => $pointGoods['need_point'],
			'carriage' => $pointGoods['carriage'],
			'title' => $pointGoods['title'],
			'active' => "Y"
		);
		$POINT_GOODS_COLOR = POINT_GOODS_COLOR();
		$districtDataArr = array();
		
		foreach($pointGoods['district'] as $districtId){
			$districtDataArrTemp = array("district_id" => $districtId);
			$dataTemp = array_merge($saveData, $districtDataArrTemp);
			foreach($pointGoods['color'] as $colorId){
				if($colorId == -1){
					$colorDataArrTemp = array(
						'color_id' => $colorId,
						'color_value' => '',
						'color_name' => '',
					);
				}else{
					$colorDataArrTemp = array(
						'color_id' => $colorId,
						'color_value' => $POINT_GOODS_COLOR[$colorId]['value'],
						'color_name' => $POINT_GOODS_COLOR[$colorId]['name'],
					);
				}
				$dataTemp2 = array_merge($dataTemp, $colorDataArrTemp);
				$saveDataArr[] = $dataTemp2;
			}
		}
		return $saveDataArr;
	}
	
	//上传文件到本地与ichelaba的upload下
	public function uploadGoodsImg(){
		$maxSize = 1024*500;
		$ext_arr = array('jpg', 'jpeg', 'png', 'bmp');
		$fileElementName = 'upload_goods_img';
		if($_FILES[$fileElementName]['size'] > $maxSize){
			$status = false;
			$info = '文件过大，请小于'.($maxSize/1024)."Kb";
		}else if(!empty($_FILES[$fileElementName]['error'])){
			switch($_FILES[$fileElementName]['error']){
				case '1':
					$error = '上传失败';
					break;
				case '2':
					$error = '上传失败';
					break;
				case '3':
					$error = '上传失败';
					break;
				case '4':
					$error = '上传内容不能为空';
					break;
				case '6':
					$error = '系统缺少临时文件夹';
					break;
				case '7':
					$error = '写文件失败';
					break;
				case '8':
					$error = '上传文件类型不匹配';
					break;
				case '999':
				default:
					$error = '未知错误';
			}
			$status = false;
			$info = $error;
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$status = false;
			$info   ='没有上传文件...';
		}else{   
			//获得文件扩展名
			$file_name = htmlspecialchars($_FILES[$fileElementName]['name']);
			$temp_arr = explode(".", $file_name);
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_ext = strtolower($file_ext);
			if(in_array($file_ext, $ext_arr) === false) {
				$status = false;
				$info   ='文件类型不能上传';
			}else{
				$local_dirname = config_item('UPLOADS')."point_goods/";
				//$remote_dirname = config_item('FTP_UPLOADS_DIR')."point_goods/";
				$upfilename = "point_goods_".time().rand(0, 1000).".".$file_ext;
				
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $local_dirname.$upfilename);
				@chmod($local_dirname.$upfilename, 0777);
				
				//ftp传送
				//$result = ftp_upload($local_dirname.$upfilename, $remote_dirname, $upfilename);															
				$result = true;
				if($result == true){
					$status = true;
					$info = $upfilename;
				}else{
					$status = false;
					$info = "FTP上传有误";
				}
			}
		}
		if($status == true){
			$this->json_return(true, "", $info);
		}else{
			$this->json_return(false, "", $info);
		}
	}
	
	//上传文件到本地与ichelaba的upload下
	public function uploadGoodsDetailImg(){
		$maxSize = 1024*500;
		$ext_arr = array('jpg', 'jpeg', 'png', 'bmp');
		$fileElementName = 'imgFile';
		if($_FILES[$fileElementName]['size'] > $maxSize){
			$status = false;
			$info = '文件过大，请小于'.($maxSize/1024)."Kb";
		}else if(!empty($_FILES[$fileElementName]['error'])){
			switch($_FILES[$fileElementName]['error']){
				case '1':
					$error = '上传失败';
					break;
				case '2':
					$error = '上传失败';
					break;
				case '3':
					$error = '上传失败';
					break;
				case '4':
					$error = '上传内容不能为空';
					break;
				case '6':
					$error = '系统缺少临时文件夹';
					break;
				case '7':
					$error = '写文件失败';
					break;
				case '8':
					$error = '上传文件类型不匹配';
					break;
				case '999':
				default:
					$error = '未知错误';
			}
			$status = false;
			$info = $error;
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$status = false;
			$info   ='没有上传文件...';
		}else{   
			//获得文件扩展名
			$file_name = htmlspecialchars($_FILES[$fileElementName]['name']);
			$temp_arr = explode(".", $file_name);
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_ext = strtolower($file_ext);
			if(in_array($file_ext, $ext_arr) === false) {
				$status = false;
				$info   ='文件类型不能上传';
			}else{
				$local_dirname = config_item('UPLOADS')."point_goods_detail/";
				//$remote_dirname = config_item('FTP_UPLOADS_DIR')."point_goods/detail/";
				$upfilename = "point_goods_detail_".time().rand(0, 1000).".".$file_ext;
				
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $local_dirname.$upfilename);
				@chmod($local_dirname.$upfilename, 0777);
				
				//ftp传送
				//$result = ftp_upload($local_dirname.$upfilename, $remote_dirname, $upfilename);															
				$result = true;
				if($result == true){
					$status = true;
					$info = $upfilename;
				}else{
					$status = false;
					$info = "FTP上传有误";
				}
			}
		}
		$REMOTE_HOST = REMOTE_HOST();
		if($status == true){
			echo json_encode(array('error' => 0, 'url' => config_item('REMOTE_URL')."static/uploads/point_goods_detail/".$upfilename));
		}else{
			echo json_encode(array("error" => 1, 'message' => $info));
		}
	}
}
