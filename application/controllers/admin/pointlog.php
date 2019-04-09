<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PointLog extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->model("User_model");
	}
	
	// 获取前十条积分日志
	public function index2(){
	    $viewData['_left_parent_page_name'] = 'point';
		$viewData['_page_name'] = '积分日志';
		$viewData['_page_detail'] = '';
		$topNum = 10;
		$this->load->model("Point_log_model");
		$pointLog = $this->Point_log_model->getTopPointLog($topNum);
		$viewData['point_log'] = $pointLog;
		$this->view('point_log/index.php', $viewData);
	}
	
	// 获取前十条积分日志
	public function index(){
	    $viewData['_left_parent_page_name'] = 'point';
		$viewData['_page_name'] = '积分日志';
		$viewData['_page_detail'] = '';
		$this->view('point_log/index2.php', $viewData);
	}
	
	// 获取用户积分日志
	public function userPointLogDetail($para_mobile = false, $para_user_type = false, $changeFlag = 0){
	    $viewData['_left_parent_page_name'] = 'point';
		$viewData['_page_name'] = '用户积分详情';
		$viewData['_page_detail'] = '';
		$mobile = $para_mobile == false ? ( isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : "" ) : $para_mobile;
		$user_type = $para_user_type == false ? ( isset($_REQUEST['user_type']) ? $_REQUEST['user_type'] : 0 ) : $para_user_type;
		$userInfo = array();
		$user = $this->User_model->getByMobile($mobile);
		if(empty($user)){
			$this->error_page("无此用户");
			return false;
		}
		$userInfo['user_id'] = $user['user_id'];
		$userInfo['user_type'] = $user_type;
		$userInfo['mobile'] = $user['mobile'];
		$userInfo['contact'] = $user['nick_name'];
		
		$this->load->model("Point_log_model");
		$userPointLog = $this->Point_log_model->getByUser($userInfo['user_id'], 1);
		
		$viewData['point_log'] = $userPointLog;
		$viewData['userPoint'] = $user['point'];
		$viewData['userInfo'] = $userInfo;
		$viewData['changeFlag'] = $changeFlag;
		$this->view('point_log/user_detail.php', $viewData);
	}
	
	//修改用户积分
	public function PointChange(){
		$changePoint = $_POST;
		//验证
		$verifyMessage = $this->verifyPointChange($changePoint);
		if($verifyMessage !== true){
			$this->json_return(false, "" , $verifyMessage);
			return false;
		}
		//数据处理
		$changePoint['event_type'] = "system_change";
		$saveData = $this->formPointChange($changePoint);
		
		//查看积分修改之后是否为负数
		$this->load->model('Point_model');
		$user_point = $this->User_model->getById($saveData['user_id']);
		$user_point['point'] = $user_point['point'] + $saveData['point'];//积分计算
		if($user_point['point'] < 0){
			$this->json_return(false, "" , "用户积分修改后不可小于0，请重新修改");
			return false;
		}
		
		//积分日志表修改
		$this->load->model("Point_log_model");
		$result = $this->Point_log_model->addPointLog($saveData);
		if($result == false){
			$this->json_return(false, "" , "修改失败");
			return false;
		}
		
		//积分表修改
		$savePointData['point'] = $user_point['point'];
		$this->User_model->updateById($savePointData, $user_point['user_id']);
		$this->json_return(true);
	}
	
	//批量修改销售顾问的积分
	public function batchChangePoint(){
		$salerChangeList = $_REQUEST['data'];
		$this->load->model("Saler_model");
		
		$saveDataList = array();
		$this->load->model('Point_model');
		$userPointTemp = array();
		foreach($salerChangeList as $salerChange){
			$saler = $this->Saler_model->getByMobile($salerChange['0']);
			$changePoint = array(
				'change_point' => $salerChange[1],
				'change_reason' => $salerChange[2],
				'user_id' => $saler['saler_id'],
				'user_type' => 1,//销售顾问
			);
			//验证
			$verifyMessage = $this->verifyPointChange($changePoint);
			if($verifyMessage !== true){
				$this->json_return(false, "" , $verifyMessage);
				return false;
			}
			//数据处理
			$changePoint['event_type'] = "system_change";
			$saveData = $this->formPointChange($changePoint);
	
			//查看积分修改之后是否为负数
			$user_point = $this->Point_model->getByUser($saveData['user_id'], $saveData['user_type']);
			//有重复的销售顾问的时候
			//如果第一次到这个销售顾问，则缓存使用该point，第二次第三次，则使用缓存的基础上进行计算
			if(isset($userPointTemp[$saveData['user_id']])){
				$user_point = $userPointTemp[$saveData['user_id']];
			}
			$user_point['point'] = $user_point['point'] + $saveData['point'];//积分计算
			
			$userPointTemp[$saveData['user_id']] = $user_point;
			
			if($user_point['point'] < 0){
				$this->json_return(false, "" , "用户积分修改后不可小于0，请重新修改");
				return false;
			}
			$saveDataList[] = array(
				'saveData' => $saveData,
				'user_point' => $user_point
			);
		}
		
		$this->load->model("Point_log_model");
		foreach($saveDataList as $value){
			$saveData = $value['saveData'];
			$user_point = $value['user_point'];
			
			//积分日志表修改
			$result = $this->Point_log_model->addPointLog($saveData);
			
			//积分表修改
			$savePointData = array();
			$savePointData['point'] = $user_point['point'];
			$this->Point_model->saveById($user_point['point_id'], $savePointData);
		}
		$this->json_return(true);
	}
	
	//私有函数
	//验证参数有效性
	protected function verifyPointChange($changePoint){
		$returnMessage = true;
		if(!isset($changePoint['change_point']) || intval($changePoint['change_point']) == 0){
			$returnMessage = "新增积分不可为0或积分有误";
		}else if(!isset($changePoint['change_reason']) || $changePoint['change_reason'] == ''){
			$returnMessage = "修改理由不可为空";
		}else if(!isset($changePoint['user_id']) || !isset($changePoint['user_type'])){
			$returnMessage = "修改用户有误";
		}else{
		}
		return $returnMessage;
	}
	
	
	//整合参数
	protected function formPointChange($changePoint){
		$saveData = array(
			'user_id' => $changePoint ['user_id'],
			'user_type' => 1,
			'point' => intval($changePoint['change_point']),
			'change_reason' => $changePoint ['change_reason'],
			'event_type' => $changePoint ['event_type'],
			'added_time' => date("Y-m-d H:i:s"),
			'operator' => $this->session->operator_name,
		);
		return $saveData;
	}
	
	public function uploadBatchSaler(){
		$maxSize = 1024*1024;
		$ext_arr = array('xls','xlsx');
		$fileElementName = 'upload_district';
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
				//这里开始检测
				$result = $this->checkSalerExcel($_FILES[$fileElementName]['tmp_name']);
				if($result['flag'] == false){
					$status = false;
					$info = $result['message'];
				}else{
					$status = true;
					$info = '';
					$returnData = $result['message'];
				}
			}
		}
		if($status == true){
			$this->json_return(true, $returnData, $info);
		}else{
			$this->json_return(false, "", $info);
		}
	}
	
	protected function checkSalerExcel($file){
		//参数设置
		$returnArr = array(
			'flag' => true,
			'message' => 'success'
		);

		//读取excel
		$this->load->library('PHPExcel/IOFactory');
		IOFactory::createReader('Excel5');
		$objPHPExcel = IOFactory::load($file);
		$sheet = $objPHPExcel->getActiveSheet(0);
		
		//基本分析
		$salerList = array();
		$returnTrueData = array();
		for($i = 2; $i < 1000; $i++){
			$salerMobileTemp = strtoupper(trim($sheet->getCell('A'.$i)->getValue()));
			$changePointTemp = intval(trim($sheet->getCell('B'.$i)->getValue()));
			$changeReasonTemp = trim($sheet->getCell('C'.$i)->getValue());
			if($salerMobileTemp == ''){
				break;
			}
			$salerList[] = $salerMobileTemp;
			$returnTrueData[] = array(
				$salerMobileTemp,
				$changePointTemp,
				$changeReasonTemp
			);
		}
		if(count($salerList) == 0){
			$returnArr = array(
				'flag' => false,
				'message' => '销售顾问不可为空'
			);
			return $returnArr;
		}
		$this->load->model('Saler_model');
		$inDBsalerList = $this->Saler_model->getInMobile($salerList);
		
		$inDbSalerMobile = array();
		$inDbSalerIdList = array();
		foreach($inDBsalerList as $value){
			$inDbSalerMobile[] = $value['mobile'];
			$inDbSalerIdList[] = $value['saler_id'];
		}
		$notInDbSalerMobile = array();
		foreach($salerList as $mobile){
			if(!in_array($mobile, $inDbSalerMobile)){
				$notInDbSalerMobile[] = $mobile;
			}
		}
		if(count($notInDbSalerMobile) > 0){
			$returnArr = array(
				'flag' => false,
				'message' => '不存在这些销售顾问【'.implode('，', $notInDbSalerMobile).'】。请检查！'
			);
			return $returnArr;
		}
		$returnArr = array(
			'flag' => true,
			'message' => $returnTrueData
		);
		return $returnArr;
	}

	function chuanqi(){
		exit();
		set_time_limit(0);
		$file = './chuanqi.xlsx';

		//读取excel
		$this->load->library('PHPExcel/IOFactory');
		IOFactory::createReader('Excel5');
		$objPHPExcel = IOFactory::load($file);
		$sheet = $objPHPExcel->getActiveSheet(0);

		$saveData = array();
		for($i = 2; $i < 1000; $i++){
			$saveDataDetail = array();
			$saveDataDetail['brand_id'] = 42;
			$saveDataDetail['district_name'] = trim($sheet->getCell('B'.$i)->getValue());
			$saveDataDetail['shop_name'] = trim($sheet->getCell('D'.$i)->getValue());
			$saveDataDetail['shop_address'] = trim($sheet->getCell('E'.$i)->getValue());

			if($saveDataDetail['district_name'] == ''){
				break;
			}
			$saveData[] = $saveDataDetail;
		}
		$this->load->model('Shop_model');
		$this->load->model('District_model');
		$errorCityName = array();
		//pe($saveData);
		foreach($saveData as &$data){
			$district = $this->District_model->getByDistrictName($data['district_name']);
			if(empty($district)){
				$errorCityName[] = $data['district_name'];
			}
			$data['district_id'] = $district['district_id'];
			$data['status'] = 1;
			$data['password'] = 1;
			unset($data['district_name']);
		}unset($data);
		$errorCityName = array_unique($errorCityName);
		//pe($saveData);
		$this->Shop_model->batchInsert($saveData);
	}

	function series(){
		exit();
		set_time_limit(0);
		$seriesStr = file_get_contents('./series.txt');
		$seriesList = explode("\r\n", $seriesStr);

		$this->load->model('Autohome_series_model');
		$errorSeriesName = array();
		foreach($seriesList as $series_name){
			$series = $this->Autohome_series_model->getBySeriesName($series_name);
			if(!empty($series)){
				$errorSeriesName[] = $series_name;
				$saveData = array('is_display' => 1);
				$this->Autohome_series_model->updateById($saveData, $series['series_id']);
			}
		}
		pe($errorSeriesName);
	}

	public function curl_get($autohome_spec_id){
        $url = 'http://j.autohome.com.cn/loan/car/getChange';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Cookie:jChexingId='.$autohome_spec_id.';'
        ));
        $temp = curl_exec($ch);
        $temp = json_decode($temp, true);
        curl_close($ch);
        return $temp;
    }

    function qiya(){
		set_time_limit(0);
		$file = './dyk.xls';

		//读取excel
		/*
		$this->load->library('PHPExcel/IOFactory');
		IOFactory::createReader('Excel5');
		$objPHPExcel = IOFactory::load($file);
		$sheet = $objPHPExcel->getActiveSheet(0);

		$saveData = array();
		$index = 0;
		for($i = 1; $i < 1000; $i++){
			$saveDataDetail = array();
			$saveDataDetail['district_parent_name'] = trim($sheet->getCell('A'.$i)->getValue());
			$saveDataDetail['district_name'] = trim($sheet->getCell('B'.$i)->getValue()) == '' ? $saveData[$index - 1]['district_name'] : trim($sheet->getCell('B'.$i)->getValue());
			$saveDataDetail['shop_name'] = trim($sheet->getCell('C'.$i)->getValue());
			$saveDataDetail['shop_address'] = trim($sheet->getCell('D'.$i)->getValue());
			$saveDataDetail['mobile'] = trim($sheet->getCell('E'.$i)->getValue());

			if($saveDataDetail['district_parent_name'] == ''){
				break;
			}
			$saveData[] = $saveDataDetail;
			$index ++;
		}
		$_SESSION['qiya'] = $saveData;*/

		$saveData = $_SESSION['qiya'];
		$this->load->model('Shop_model');
		foreach($saveData as &$data){
			$data['province'] = $data['district_parent_name'];
			if(strpos($data['district_name'], '市辖区')){
				$data['district_name'] = str_replace('市辖区', '', $data['district_name']);
			}
			$data['city'] = $data['district_name'];
			$data['shop_address'] = $data['shop_addres'];
			unset($data['district_name']);
			unset($data['shop_addres']);
			unset($data['district_parent_name']);
		}unset($data);
		pe($saveData);
		$this->Shop_model->batchInsert($saveData);
	}
}
