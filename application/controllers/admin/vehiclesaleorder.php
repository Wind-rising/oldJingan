<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleSaleOrder extends BaseController{
	public $ORDER_STATUS = array();
	function __construct(){
		parent::__construct();
		$this->load->model("Vehicle_sale_order_model");
		$this->ORDER_STATUS = SALE_ORDER_STATUS();
	}

    /**
	 * 后台用户列表
	 * @return void
	 */
	public function index(){

	    //当前月份
        $y=date('m');

        //循环查询当月每天的数量
        $add_num=array();
        $pay_num=array();
        for($i=1;$i<32;$i++){

            $TArray = $this->Vehicle_sale_order_model->get_data(array('form_name' => 'order', 'where' => array('create_time>=' => date("Y-$y-$i 00:00:00",time()+24*60*60),'create_time<=' => date("Y-$y-$i 23:59:59",time()+24*60*60))));
            $ZArray = $this->Vehicle_sale_order_model->get_data(array('form_name' => 'order', 'where' => array('paid_time>=' => date("Y-$y-$i 00:00:00",time()+24*60*60),'paid_time<=' => date("Y-$y-$i 23:59:59",time()+24*60*60))));

             array_push($add_num,count($TArray));
            array_push($pay_num,count($ZArray));
        }

        $add_num_js=json_encode($add_num);
        $pay_num_js=json_encode($pay_num);

		$viewData['_left_parent_page_name'] = 'goods';
		$viewData['_page_name'] = '汽车订单列表';
		$viewData['_page_detail'] = '';
        $viewData['add_num_js'] = $add_num_js;
        $viewData['pay_num_js'] = $pay_num_js;

		$this->view('vehicle_sale_order/index.php', $viewData);
	}

	public function checkOrder(){
		$orderId = intval($_REQUEST['order_id']);
		$checkCode = $_REQUEST['check_code'];
		$this->load->model('vehicle_sale_order_model');
		$order = $this->vehicle_sale_order_model->getById($orderId);
		if(empty($order)){
			$this->json_return(false, array(), '订单不存在！');
			return false;
		}
		if($order['status'] != $this->ORDER_STATUS['SALE_ORDER_STATUS_CHECKING']){
			$this->json_return(false, array(), '订单号为【'.$order['order_code'].'】的订单状态不是【待核销】，暂时无法核销！');
			return false;
		}
		if($order['check_code'] != $checkCode){
			$this->json_return(false, array(), '订单号为【'.$order['order_code'].'】的核销码错误！');
			return false;
		}
		$this->vehicle_sale_order_model->checkOrder($orderId, $this->session->operator_name, $this->session->operator_id);

		//分销积分增减。
		// 用户分销订单
		$this->load->model('Point_model');
		$this->load->model('Point_log_model');
        if (isset($order['affiliate_id'])) {
            // 顾问增加相应积分
            if ($order['affiliate_id'] > 0) {
                if ($orderId > 324) { // 在324之前分销给现金，之后给积分
                    $salerId = $order['affiliate_id'];
                    $point = intval($order['affiliate_amount']);
                    $this->Point_model->incPoint($salerId, 1, $point);

					$log = array();
					$log['user_id'] = $salerId;
					$log['user_type'] = 1;
					$log['event_id'] = $orderId;
					$log['event_type'] = 'affiliate';
					$log['point'] = $point;
                    $log['brand_id'] = $order['brand_id'];
                    $log['brand_name'] = $order['brand_name'];
                    $log['series_id'] = $order['series_id'];
                    $log['series_name'] = $order['series_name'];
                    $log['added_time'] = date('Y-m-d H:i:s');
					$this->Point_log_model->addPointLog($log);
                }
            } else if ($order['affiliate_id'] < 0) { // 用户增加相应积分
                $userId = -$order['affiliate_id'];
                $point = intval($order['affiliate_amount']);
                $this->Point_model->incPoint($userId, 0, $point);

				$log = array();
				$log['user_id'] = $userId;
				$log['user_type'] = 0;
				$log['event_id'] = $orderId;
				$log['event_type'] = 'affiliate';
				$log['point'] = $point;
                $log['brand_id'] = $order['brand_id'];
                $log['brand_name'] = $order['brand_name'];
                $log['series_id'] = $order['series_id'];
                $log['series_name'] = $order['series_name'];
				$log['added_time'] = date('Y-m-d H:i:s');
				$this->Point_log_model->addPointLog($log);
            }
        }
        // 购买特价车或旗舰店车，用户增加相应积分
        // 出于公司运营成本的考虑，暂不发放用户积分
   //      if (isset($order['user_id']) && $order['user_id'] > 0) {
   //          $buyId = $order['user_id'];
   //          $this->Point_model->incPoint($buyId, 0, 100);

			// $log = array();
			// $log['user_id'] = $buyId;
			// $log['user_type'] = 0;
			// $log['event_id'] = $orderId;
			// $log['event_type'] = 'buy_success';
			// $log['point'] = '100';
			// $log['added_time'] = date('Y-m-d H:i:s');
			// $this->Point_log_model->addPointLog($log);
   //      }


		$this->json_return(true);
	}

	public function changeOrder($orderId){
		$this->load->model('vehicle_sale_order_model');
		$order = $this->vehicle_sale_order_model->getById($orderId);
        if(empty($order)){
			$this->error_page('订单不存在！');
			return false;
		}
		if($_POST){

            $arr['contact'] = $_POST['contact'];
            $arr['mobile'] = $_POST['mobile'];
            $arr['status'] = $_POST['status'];
            $flag = $this->vehicle_sale_order_model->save(array('order_id' => $_POST['order_id']), $arr);
            if($flag){
                $this->remind->set("index.php/vehiclesaleorder/index", "修改成功","success");
            }else{
                $this->remind->set("index.php/vehiclesaleorder/index", "修改失败，请联系管理员","error");
            }
        }
        $viewData['order'] = $order;
		$this->view('vehicle_sale_order/change.php', $viewData);
	}

	function getSeriesList(){
		$brand_id = intval($_REQUEST['brand_id']);
		$seriesList = $this->getSeriesByBrandId($brand_id);
		$this->json_return(true, $seriesList, '');
	}

	function getSpecList(){
		$series_id = intval($_REQUEST['series_id']);

		$this->load->model('Vehicle_spec_model');
		$allSpec = $this->Vehicle_spec_model->getBySeriesId($series_id);

		$this->load->model('Vehicle_series_color_model');
		$allColor = $this->Vehicle_series_color_model->getBySeriesId($series_id);
		$returnData = array(
				'spec' =>$allSpec,
				'color' =>$allColor
			);
		$this->json_return(true, $returnData, '');
	}

	function saveOrder(){
		$orderId = intval($_REQUEST['order_id']);
		$saveData = array();
		$saveData['contact'] = trim($_REQUEST['contact']);
		$saveData['phone'] = trim($_REQUEST['phone']);
		$saveData['district_id'] = intval($_REQUEST['district_id']);
		$saveData['brand_id'] = intval($_REQUEST['brand_id']);
		$saveData['series_id'] = intval($_REQUEST['series_id']);
		$saveData['spec_id'] = intval($_REQUEST['spec_id']);
		$saveData['color_id'] = intval($_REQUEST['color_id']);
		$saveData['final_price'] = intval($_REQUEST['final_price']);
		$saveData['down_payment'] = intval($_REQUEST['down_payment']);

		if($saveData['contact'] == ''){
			$this->json_return(false, array(), '联系人不可为空');
			return false;
		}
		if($saveData['phone'] == ''){
			$this->json_return(false, array(), '手机不可为空');
			return false;
		}

		$this->load->model('District_model');
		$district = $this->District_model->getById($saveData['district_id']);
		if(empty($district) || $district['parent_id'] == 0){
			$this->json_return(false, array(), '地区有误');
			return false;
		}

		//验证车款
		$this->load->model('Vehicle_spec_model');
		$spec = $this->Vehicle_spec_model->getById($saveData['spec_id']);
		if(empty($spec)){
			$this->json_return(false, array(), '无此车款');
			return false;
		}
		if($spec['series_id'] != $saveData['series_id']){
			$this->json_return(false, array(), '车型与车款无法对应');
			return false;
		}

		//验证颜色
		$this->load->model('Vehicle_series_color_model');
		$color = $this->Vehicle_series_color_model->getById($saveData['color_id']);
		if(empty($color)){
			$this->json_return(false, array(), '无此颜色');
			return false;
		}
		if($color['series_id'] != $saveData['series_id']){
			$this->json_return(false, array(), '车型与颜色无法对应');
			return false;
		}

		//验证车型
		$this->load->model('Vehicle_series_model');
		$series = $this->Vehicle_series_model->getById($saveData['series_id']);
		if(empty($series)){
			$this->json_return(false, array(), '无此车型');
			return false;
		}
		if($series['brand_id'] != $saveData['brand_id']){
			$this->json_return(false, array(), '品牌与车型无法对应');
			return false;
		}

		//验证品牌
		$this->load->model('Vehicle_brand_model');
		$brand = $this->Vehicle_brand_model->getById($saveData['brand_id']);
		if(empty($brand)){
			$this->json_return(false, array(), '无此品牌');
			return false;
		}
		$saveData['brand_name'] = $brand['brand_name'];
		$saveData['series_name'] = $series['series_name'];
		$saveData['district_name'] = $district['district_name'];
		$saveData['spec_name'] = $spec['spec_name'];
		$saveData['color_name'] = $color['color_name'];
		$saveData['color_value'] = $color['color_value'];

		$this->Vehicle_sale_order_model->updateById($saveData, $orderId);
		$this->json_return(true);
	}

	public function invoiceUpload(){
		$maxSize = 1024*1024;
		$ext_arr = array('jpg', 'jpeg', 'png', 'bmp');
		$order_id = $_REQUEST['order_id'];
		$fileElementName = 'Filedata';
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
				$local_dirname = config_item('SITE_ROOT')."admin/static/uploads/sale_order_invoice/";
				$remote_dirname = config_item('FTP_UPLOADS_DIR')."sale_order_invoice/";
				$upfilename = "sale_order_invoice_".$order_id."_".(microtime(true)*10000).'_'.rand(0, 1000).".".$file_ext;

				move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $local_dirname.$upfilename);
				@chmod($local_dirname.$upfilename, 0777);

				//ftp传送
				$result = ftp_upload($local_dirname.$upfilename, $remote_dirname, $upfilename);
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
			$REMOTE_HOST = REMOTE_HOST();
			$this->json_return(true, "", "http://".$REMOTE_HOST."/uploads/sale_order_invoice/".$upfilename);
		}else{
			$this->json_return(false, "", $info);
		}
	}

	function getOrderInvoice(){
		$order_id = intval($_REQUEST['order_id']);
		$this->load->model("Vehicle_sale_order_invoice_model");
		$invoiceList = $this->Vehicle_sale_order_invoice_model->getByOrderId($order_id);
		$this->json_return(true, $invoiceList);
	}

	function saveOrderInvoice(){
		$invoiceList = array_unique($_REQUEST['invoiceList']);
		$order_id = intval($_REQUEST['order_id']);
		$this->load->model("Vehicle_sale_order_invoice_model");
		$updateData = array('is_delete' => 1);
		$this->Vehicle_sale_order_invoice_model->updateByOrderId($updateData, $order_id);
		$insertData = array();
		foreach($invoiceList as $invoice){
			$insertData[] = array(
				'order_id' => $order_id,
				'invoice' => $invoice,
				'added_time' => date('Y-m-d H:i:s'),
			);
		}
		$this->Vehicle_sale_order_invoice_model->batchInsert($insertData);
		$this->json_return(true);
	}

	function bindSaler($shopIdList){
		$salerList = array();
		$this->load->model('Shop_model');
		$shopList = $this->Shop_model->getByIdList($shopIdList);
		$this->load->model('Saler_model');
		$salerList = $this->Saler_model->getByShopIdList($shopIdList);
		$salerListTemp = array();
		foreach($salerList as $saler){
			if(!isset($salerListTemp[$saler['shop_id']])){
				$salerListTemp[$saler['shop_id']] = array();
			}
			$salerListTemp[$saler['shop_id']][] = $saler;
		}
		foreach($shopList as &$shop){
			if(isset($salerListTemp[$shop['shop_id']])){
				$shop['saler_list'] = $salerListTemp[$shop['shop_id']];
			}else{
				$shop['saler_list'] = array();
			}
		}
		return $shopList;
	}

	function sendGrab(){
		set_time_limit(0);
		$saler_id_list = isset($_REQUEST['saler_id_list']) ? $_REQUEST['saler_id_list'] : array();
		if(empty($saler_id_list)){
			$this->json_return(true);
		}
		$order_id = intval($_REQUEST['order_id']);
		$real_price = intval($_REQUEST['real_price']);
		$updateData['real_price'] = $real_price;
		$this->Vehicle_sale_order_model->updateById($updateData, $order_id);
		$order = $this->Vehicle_sale_order_model->getById($order_id);
		if(empty($order)){
			$this->json_return(false, array(), '无此订单');
			return false;
		}
		//等待核销状态
		if($order['status'] != $this->ORDER_STATUS['SALE_ORDER_STATUS_CHECKING']){
			$this->json_return(false, array(), '该订单状态暂时不可推送');
			return false;
		}
		//是否推送过
		if($order['is_send_grab'] != 0){
			$this->json_return(false, array(), '该订单已经推送，请勿重复推送');
			return false;
		}

		$this->load->model('Saler_model');
		foreach($saler_id_list as $salerId){
			$saler = $this->Saler_model->getById($salerId);
			if(empty($saler) || $saler['weixin_openid'] == ''){
				continue;
			}
			//销售顾问分销的情况下，顾问id不相同，则continue
			if($order['affiliate_id'] > 0 && $saler['saler_id'] != $order['affiliate_id']){
				continue;
			}

			$grabList[] = array(
						'order_id' => $order_id,
						'saler_id' => $saler['saler_id'],
						'status' => 0
					);
		}

		//批量插入到抢单表
		if(empty($grabList)){
			$this->json_return(true);
		}
		$this->load->model('Saler_grab_model');
		$this->Saler_grab_model->batchInsert($grabList);

		$saveData = array();
		$saveData['is_send_grab'] = 1;
		$this->Vehicle_sale_order_model->updateById($saveData, $order_id);

		$this->load->model('Saler_send_massage_log_model');

		foreach($saler_id_list as $salerId){
			$saler = $this->Saler_model->getById($salerId);
			if(empty($saler) || $saler['weixin_openid'] == ''){
				continue;
			}
			//销售顾问分销的情况下，顾问id不相同，则continue
			if($order['affiliate_id'] > 0 && $saler['saler_id'] != $order['affiliate_id']){
				continue;
			}
			$msg = array();
			//发送微信
			$msg['toUser'] = $saler['weixin_openid'];
			$msg['url'] = 'http://'.config_item('REMOTE_HOST').'/saler2/index.php/saler/salergrab/salerGrabFlagShopOrder?order_id='.$order_id;
			$msg['first'] = "您有一个新的旗舰店订单，请点击抢单";
			$msg['tplId'] = 11;
			$msg['keyword1'] = '暂不显示';
			$msg['keyword2'] =  '********';
			$msg['keyword3'] = '未知';
			$msg['keyword4'] = $order['series_name'] . ' ' . $order['spec_name'];
			$msg['keyword5'] = date('Y-m-d H:i:s');
			$msg['remark'] = '';
			$weixin = new Weixin('','','');
			$weixin->salerTplMsg($msg);

			//发送信息写入日志表
			$saveLogData = array();
			$saveLogData['saler_id'] = $saler['saler_id'];
			$saveLogData['saler_name'] = $saler['contact'];
			$saveLogData['weixin_openid'] = $saler['weixin_openid'];
			$saveLogData['message'] = $msg['first'].' '.$msg['keyword4'];
			$saveLogData['operator_id'] = $this->session->operator_id;
			$saveLogData['order_id'] = $order_id;
			$saveLogData['operator_name'] = $this->session->operator_name;
			$saveLogData['added_time'] = date('Y-m-d H:i:s');
			$this->Saler_send_massage_log_model->add($saveLogData);
		}
		$this->json_return(true);
	}

	function downloadInvoice($orderId = 0){
		$order = $this->Vehicle_sale_order_model->getById($orderId);
		if(empty($order)){
			$this->error_page('订单不存在！');
			return false;
		}
		$this->load->model("Vehicle_sale_order_invoice_model");
		$invoiceList = $this->Vehicle_sale_order_invoice_model->getByOrderId($orderId);
		if(empty($invoiceList)){
			$this->error_page('该订单暂无发票！');
			return false;
		}
		foreach($invoiceList as $key => $invoice){
			echo "<script>";
			echo "window.open('".app_url().'VehicleSaleOrder/downloadEachInvoice?url='.urlencode($invoice['invoice'])."')";
			echo "</script>";
		}
		$updateData = array();
		$updateData['is_download_invoice'] = 1;
		$this->Vehicle_sale_order_model->updateById($updateData, $orderId);
	}

	function downloadEachInvoice(){
		$url = $_REQUEST['url'];
		$arr = explode('.', $url);
		$extension = strtolower(array_pop($arr));
		if(!in_array($extension, array('jpg', 'gif', 'jpeg', 'png'))){
			exit;
		}

		$temp = "temp".microtime(1).".".$extension;
		$path = "./admin/static/uploads/sale_order_invoice/temp/";
		$tempImg = $path.$temp;
		file_put_contents($tempImg, file_get_contents($url));

		header('Content-Type:application/octet-stream"'); //指定下载文件类型
		header("Accept-Ranges: bytes" );
		header('Content-Disposition: attachment; filename="'.$temp.'"'); //指定下载文件的描述
		header('Accept-Length:'.filesize($tempImg)); //指定下载文件的大小
		readfile($tempImg);
	}

	public function sendPoint($orderId){
		$this->load->model('vehicle_sale_order_model');
		$order = $this->vehicle_sale_order_model->getById($orderId);
		if(empty($order)){
			$this->error_page('订单不存在！');
			return false;
		}
		$saleId = $order['sale_id'];
		$this->load->model('vehicle_sale_model');
		$sale = $this->vehicle_sale_model->getById($saleId);

		$userId = $order['user_id'];
		$this->load->model("User_model");
		$user = $this->User_model->getById($userId);

		$salerId = $order['saler_id'];
		$this->load->model("Saler_model");
		$saler = $this->Saler_model->getById($salerId);


		$viewData = array();
		$viewData['order'] = $order;
		$viewData['sale'] = $sale;
		$viewData['user'] = $user;
		$viewData['saler'] = $saler;

		$viewData['_left_parent_page_name'] = 'VehicleSaleOrder';
		$viewData['_page_name'] = '积分发放';
		$viewData['_page_detail'] = '';
		$this->view('vehicle_sale_order/send_point.php', $viewData);
	}

	function changePoint(){
		$orderId = requestGetInt('order_id');
		$user_type = requestGetInt('user_type');
		$send_point = max(0, requestGetInt('send_point'));
		if($send_point == 0){
			$this->json_return(false, '', '申请积分不可为0');
		}
		$order = $this->Vehicle_sale_order_model->getById($orderId);
		if(empty($order)){
			$this->json_return(false, '', '订单不存在！');
		}
		$saleId = $order['sale_id'];
		$this->load->model('vehicle_sale_model');
		$sale = $this->vehicle_sale_model->getById($saleId);

		$saveData = array();
		if($user_type == 0){
			if($order['send_saler_point_status'] == 2){
				$this->json_return(false, '', '积分已经发放，不可重复申请');
			}
			if($send_point > $sale['saler_max_point']){
				$this->json_return(false, '', '申请发放的积分超过该车型可发放最大积分');
			}
			$saveData['send_saler_point'] = $send_point;
			$saveData['send_saler_point_status'] = 1;
			$saveData['send_saler_point_operator'] = $this->session->operator_name;
		}else{
			if($order['send_user_point_status'] == 2){
				$this->json_return(false, '', '积分已经发放，不可重复申请');
			}
			if($send_point > $sale['user_max_point']){
				$this->json_return(false, '', '申请发放的积分超过该车型可发放最大积分');
			}
			$saveData['send_user_point'] = $send_point;
			$saveData['send_user_point_status'] = 1;
			$saveData['send_user_point_operator'] = $this->session->operator_name;
		}
		$this->Vehicle_sale_order_model->updateById($saveData, $orderId);
		$this->json_return(true);
	}

	function submitInvoice($orderId){
		$order = $this->Vehicle_sale_order_model->getById($orderId);
		if(empty($order)){
			$this->error_page('订单不存在！');
			return false;
		}
		$this->load->model("Shop_model");
		$shop = $this->Shop_model->getById($order['shop_id']);
		$this->load->model("Vehicle_sale_order_finance_model");
		$showData = array();
		$orderFinance = $this->Vehicle_sale_order_finance_model->getByOrderId($orderId);
		if(empty($orderFinance) && empty($shop)){
			$showData['shop_name'] = '';
			$showData['price'] = '0';
			$showData['remark'] = '';
			$showData['added_operator'] = '';
			$showData['added_time'] = '';
		}else if(empty($orderFinance)){
			$showData['shop_name'] = $shop['shop_name'];
			$showData['price'] = '0';
			$showData['remark'] = '';
			$showData['added_operator'] = '';
			$showData['added_time'] = '';
		}else{
			$orderFinance = $orderFinance[0];
			$showData['shop_name'] = $orderFinance['shop_name'];
			$showData['price'] = $orderFinance['price'];
			$showData['remark'] = $orderFinance['remark'];
			$showData['added_operator'] = $orderFinance['added_operator'];
			$showData['added_time'] = $orderFinance['added_time'];
		}

		$viewData['_left_parent_page_name'] = 'VehicleSaleOrder';
		$viewData['_page_name'] = '申请开票';
		$viewData['_page_detail'] = '';
		$viewData['showData'] = $showData;
		$viewData['order'] = $order;
		$this->view('vehicle_sale_order_finance/oper_detail.php', $viewData);
	}

	function operSubmitInvoice(){
		$order_id = requestGetInt('order_id');
		$order = $this->Vehicle_sale_order_model->getById($order_id);
		if(empty($order)){
			$this->json_return(false, '', '订单不存在！');
			return false;
		}
		$saveData['order_id'] = $order_id;
		$saveData['shop_name'] = requestGetString('shop_name');
		$saveData['price'] = requestGetString('price');
		$saveData['remark'] = requestGetString('remark');
		$saveData['added_time'] = date('Y-m-d H:i:s');
		$saveData['added_operator'] = $this->session->operator_name;
		$this->load->model("Vehicle_sale_order_finance_model");
		$orderFinance = $this->Vehicle_sale_order_finance_model->add($saveData);

		$saveOrderData['submit_invoice_status'] = 1;
		$this->Vehicle_sale_order_model->updateById($saveOrderData, $order_id);
		$this->json_return(true);
	}
}
