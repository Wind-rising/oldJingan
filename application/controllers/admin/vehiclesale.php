<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleSale extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->model("Vehicle_sale_model");
		$this->load->model("User_model");
		$this->load->model("Consultation_model");
	}

	public function index(){
		$viewData['_left_parent_page_name'] = 'vehicle_sale';
		$viewData['_page_name'] = '已审核商品列表';
		$viewData['_page_detail'] = '';
        $this->view('vehicle_sale/index.php', $viewData);
	}

	public function index2()
	{
		$viewData['_left_parent_page_name'] = 'vehicle_sale';
		$viewData['_page_name'] = '待审核商品列表';
		$viewData['_page_detail'] = '';
        $this->view('vehicle_sale/index2.php', $viewData);
	}

	public function index3()
	{
		$viewData['_left_parent_page_name'] = 'vehicle_sale';
		$viewData['_page_name'] = '未审核通过商品列表';
		$viewData['_page_detail'] = '';
        $this->view('vehicle_sale/index3.php', $viewData);
	}

	public function edit()
	{
		$productId = isset($_GET['id']) ? $_GET['id'] : 0;
		if (empty($productId)) {
			echo "<script>";
			echo "  alert('没有对应产品')";
			echo "</script>";
		}

		$this->view('vehicle_sale/back_reason.php', array(
					'product_id' => $productId
				));
	}

	public function saveReason()
	{
		$res = array();
		$res['result'] = false;

		$productId = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
		$reason = isset($_POST['back_reason']) ? $_POST['back_reason'] : '';
		if (empty($productId) || empty($reason)) {
			$res['message'] = '缺少信息';
			exit(json_encode($res));
		}
       
		$this->Vehicle_sale_model->updateReason($productId, $reason);
		$this->sendMessageToShop($productId, $reason);



        $res['result'] = true;
		exit(json_encode($res));
	}

	public function sendMessageToShop($productId, $reason)
	{
		$product = $this->Vehicle_sale_model->getById($productId);
		$userId = $this->User_model->getUserIdByShopId($product['shop_id']);

		$info = array();
		$info['saler_id'] = $userId;
		$info['parent_id'] = 0;
		$info['user_id'] = 0;
		$info['content'] = $product['product_name'].' 审核不通过，原因：'.$reason;
		$info['type'] = 5;
		$info['create_time'] = date('Y-m-d H:i:s');

        $user_arr = $this->User_model->getById($userId);

        $openid=$user_arr['openid'];

        $this->PushMessage($openid);

		$this->Consultation_model->add($info);

	}

    //获取access_token
    public function getAccessToken($flag = true)
    {
        $accessToken = '';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
        $url .= '&appid=wxf61047cb9ce14874'. '&secret=2e88fc7881550738aafcb05c2bfc06dc';
        $html = getHtml($url);
        if (!empty($html)) {
            $result = json_decode($html, true);
            $accessToken = get($result, 'access_token', '');
        }
        return $accessToken;
    }

    public function PushMessage($openid){

        $time=date('Y-m-d H:i:s',time());

        $access_token=$this->getAccessToken();

            $template = array(
                'touser' =>$openid,
                'template_id' => "bFQiIbwAN-vlcEuJECBvqLI6DrbMY_WwZw4icm2F5cc",
                'url' => "http://hanteng.ichelaba.com/index.php/shop/user",
                'data' => array(
                    'first'    => array('value' => "您的消息中心有新的动态!",
                        'color' => "#743A3A",
                    ),
                    'keyword1' => array('value' => "网上竞赛商城微论坛",
                        'color' => "#173177",
                    ),
                    'keyword2' => array('value' => $time,
                        'color' => "#173177",
                    ),
                    'remark'   => array('value' => "请先登录查看！",
                        'color' => "#FF0000",
                    ),

                )
            );

            $data=json_encode($template,true);

            $this->curl_post_bd('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,$data);
    }

	public function detail($saleId = 0){
		$viewData['_left_parent_page_name'] = 'vehicle_sale';
		$viewData['_page_name'] = '商品明细';
		$viewData['_page_detail'] = '';
		//全局属性
		$sale = $this->Vehicle_sale_model->getById($saleId);
		if(empty($sale)){
			$this->error_page('暂无此商品');
			return false;
		}
		//车系属性属性
		$seriesList = $this->Vehicle_sale_model->getSeriesBySaleIdList(array($sale['sale_id']));

		//旗舰店属性
		$this->load->model("Flag_shop_model");
		$flagShopList = $this->Flag_shop_model->getAllFlagShop();

		//车款属性
		$this->load->model("Vehicle_spec_model");
		$specList = $this->Vehicle_spec_model->getBySeriesId($sale['series_id']);
		//车款对应城市数
		$this->load->model("Vehicle_sale_detail_model");
		foreach($specList as &$spec){
			$spec['district_count'] = $this->Vehicle_sale_detail_model->getDistrictCountBySeriesAndSpec($sale['sale_id'], $spec['spec_id']);
		}
		//颜色属性
		$this->load->model("Vehicle_series_color_model");
		$colorList = $this->Vehicle_series_color_model->getBySeriesId($sale['series_id']);

		$viewData['sale'] = $sale;
		$viewData['spec_list'] = $specList;
		$viewData['flag_shop_list'] = $flagShopList;
		$viewData['series'] = $seriesList[0];
		$viewData['color_list'] = $colorList;
		$this->view('vehicle_sale/detail.php', $viewData);
	}

	public function showAdd(){
		//旗舰店属性
		$this->load->model("Flag_shop_model");
		$flagShopList = $this->Flag_shop_model->getAllFlagShop();

		$allBrand = $this->getAllBrandBaseInfo();
		$allBrand = $this->formBrand($allBrand);

		$viewData['flag_shop_list'] = $flagShopList;
		$viewData['all_brand'] = $allBrand;
		$viewData['_left_parent_page_name'] = 'vehicle_sale';
		$viewData['_page_name'] = '新增商品';
		$viewData['_page_detail'] = '';
		$this->view('vehicle_sale/add.php', $viewData);
	}

	public function checkOnSale(){
		$seriesId = intval($_REQUEST['series_id']);
		$saleList = $this->Vehicle_sale_model->getBySeriesId($seriesId);
		$this->load->model("Vehicle_series_model");
		$series = $this->Vehicle_series_model->getById($seriesId);
		if(empty($saleList)){
			$this->json_return(true, $series);
			return false;
		}else{
			$this->json_return(false, $series);
			return false;
		}
	}

	public function getSeriesListByBrandId(){
		$brandId = intval($_REQUEST['brand_id']);
		$allSeries = $this->getSeriesByBrandId($brandId);
		$this->json_return(true, $allSeries);
	}

	public function setDisplay(){
		$product_id = intval($_REQUEST['product_id']);
		$status = intval($_REQUEST['status']) == 0 ? 0 : 1;
		if($status==1) {
            $this->Vehicle_sale_model->updateById(array('status' => $status,'onsale_time'=>date("Y-m-d H:i:s",time())), $product_id);
        }else{
            $this->Vehicle_sale_model->updateById(array('status' => $status,),$product_id);
        }
		$this->json_return();
	}
    
    public function setTop(){
        $product_id = intval($_REQUEST['product_id']);
        $product = $this->Vehicle_sale_model->get_data(array('form_name' => 'product', 'where' => array('product_id' => $product_id)));
        //查询有没有已经置顶的
        $top = $this->Vehicle_sale_model->get_data(array('form_name' => 'product', 'where' => array('shop_id' => $product[0]['shop_id'],'top_time<>' => '')));

        if(!empty($top)){
            $this->json_return(false,"","此经销商已有置顶");
        }
        $toptime = !empty($_REQUEST['top_time'])==1? "" : date("Y-m-d H:i:s",time());
        $this->Vehicle_sale_model->updateById(array('top_time' => $toptime), $product_id);
        $this->json_return();
    }

	public function getSpecCity(){
		$sale_id = intval($_REQUEST['sale_id']);
		$spec_id = intval($_REQUEST['spec_id']);
		$districtDetail = $this->Vehicle_sale_detail_model->getDistrictBySaleAndSpec($sale_id, $spec_id);
		$provinceList = array();
		foreach($districtDetail as $district){
			$provinceList[$district['district_parent_id']] = $district['district_parent_name'];
		}
		$this->json_return(true, array('data'=>$districtDetail, 'province'=>$provinceList));
	}

	public function getGlobal(){
		$saleId = intval($_REQUEST['sale_id']);
		$sale = $this->Vehicle_sale_model->getById($saleId);
		if(empty($sale)){
			$this->json_return(false);
			return false;
		}
		$this->json_return(true, $sale);
	}

	public function getDetail(){
		$sale_id = intval($_REQUEST['sale_id']);
		$spec_id = intval($_REQUEST['spec_id']);
		$district_id = intval($_REQUEST['district_id']);
		$detailList = $this->Vehicle_sale_detail_model->getDetail($sale_id, $spec_id, $district_id);
		if(empty($detailList)){
			$this->json_return(false);
			return false;
		}
		$detailOne = $detailList[0];
		$colorList = array();
		$colorQuantityList = array();
		foreach($detailList as $detail){
			$colorIdList[] = $detail['color_id'];
			$colorQuantityList[$detail['color_id']] = $detail['quantity'];
		}
		$this->json_return(true, array('detail' => $detailOne, 'color_id_list' => $colorIdList, 'colorQuantityList' => $colorQuantityList));
	}

	//上传文件到本地与ichelaba的upload下
	public function uploadSaleImg(){
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
				$local_dirname = config_item('SITE_ROOT')."admin/static/uploads/sales/";
				$remote_dirname = config_item('FTP_UPLOADS_DIR')."sales/";
				$upfilename = "sales_".time().rand(0, 1000).".".$file_ext;

				move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $local_dirname.$upfilename);
				@chmod($local_dirname.$upfilename, 0777);

				//ftp传送
				//$result = true;
				$result = ftp_upload($local_dirname.$upfilename, $remote_dirname, $upfilename);
				if($result == true){
					$status = true;
					$info = 'sales/'.$upfilename;
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

	public function uploadDetailSaleImg(){
		$max_size = 1024*1024;
		$series_id = $_REQUEST['series_id'];
		if($series_id <= 0){
			$this->alert("请选择车型");
		}
		$save_path =  config_item('SITE_ROOT')."admin/static/uploads/sale/series_id_".$series_id;

		if (!empty($_FILES['imgFile']['error'])) {
			switch($_FILES['imgFile']['error']){
				case '1':
					$error = '超过系统允许的大小。';
					break;
				case '2':
					$error = '超过表单允许的大小。';
					break;
				case '3':
					$error = '图片只有部分被上传。';
					break;
				case '4':
					$error = '请选择图片。';
					break;
				case '6':
					$error = '找不到临时目录。';
					break;
				case '7':
					$error = '写文件到硬盘出错。';
					break;
				case '8':
					$error = 'File upload stopped by extension。';
					break;
				case '999':
				default:
					$error = '未知错误。';
			}
			$this->alert($error);
		}
		//定义允许上传的文件扩展名
		$ext_arr = array(
			'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
			'flash' => array(),
			'media' => array(),
			'file' => array(),
		);

		//有上传文件时
		if (empty($_FILES) === false) {
			//原文件名
			$file_name = $_FILES['imgFile']['name'];
			//服务器上临时文件名
			$tmp_name = $_FILES['imgFile']['tmp_name'];
			//文件大小
			$file_size = $_FILES['imgFile']['size'];
			//检查文件名
			if (!$file_name) {
				$this->alert("请选择文件。");
			}
			//检查目录
			if (@is_dir($save_path) === false) {
				mkdir($save_path);
			}
			//检查目录写权限
			if (@is_writable($save_path) === false) {
				$this->alert("上传目录没有写权限。");
			}
			//检查是否已上传
			if (@is_uploaded_file($tmp_name) === false) {
				$this->alert("上传失败。");
			}
			//检查文件大小
			if ($file_size > $max_size) {
				$this->alert("上传文件大小超过限制。");
			}
			//检查目录名
			$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
			if (empty($ext_arr[$dir_name])) {
				$this->alert("目录名不正确。");
			}
			//获得文件扩展名
			$temp_arr = explode(".", $file_name);
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_ext = strtolower($file_ext);
			//检查扩展名
			if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
				$this->alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
			}

			//新文件名
			$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
			//移动文件
			$file_path = $save_path."/".$new_file_name;
			$remote_dirname = config_item('FTP_UPLOADS_DIR')."sale/detail/";
			if (move_uploaded_file($tmp_name, $file_path) === false) {
				$this->alert("上传文件失败。");
			}
			//ftp传送
			//$result = true;
			$result = ftp_upload($file_path, $remote_dirname, $new_file_name);
			if($result == false){
				$this->alert("FTP上传有误。");;
			}
			@chmod($file_path, 0644);
			$file_url = $new_file_name;

			header('Content-type: text/html; charset=UTF-8');
			echo json_encode(array('error' => 0, 'url' => 'http://'.REMOTE_HOST().'/uploads/sale/detail/'.$file_url));
			exit;
		}
	}

	public function alert($msg = '') {
		header('Content-type: text/html; charset=UTF-8');
		echo json_encode(array('error' => 1, 'message' => $msg));
		exit;
	}

	//编辑全局
	public function editGlobal(){
		$saveData = array();
		$sale_id = intval($_POST['sale_id']);
		$saveData['flag_shop_id'] = max(0, intval($_POST['flag_shop_id']));//旗舰店id
		$saveData['sale_sort'] = max(0, intval($_POST['sale_sort']));//排序
		$saveData['discount'] = max(0, intval($_POST['g_discount']));//特价车页面显示便宜多少元//新版本已经不使用，taodeyu 2016-09-21
		$saveData['activity'] = trim($_POST['g_activity']);//特价车页面显示的标题
		$saveData['affiliate_amount'] = max(0, intval($_POST['affiliate_amount']));//分销多少
		$saveData['user_amount'] = max(0, intval($_POST['user_amount']));//分销多少
		$saveData['user_show'] = max(0, intval($_POST['user_show']));//用户是否显示该分销信息，0：不显示，1：显示
		$saveData['saler_show'] = max(0, intval($_POST['saler_show']));//顾问是否显示该分销信息，0：不显示，1：显示
		$saveData['status'] = max(0, intval($_POST['status']));//状态，0：禁用，1：有用
		$saveData['sale_type'] = $saveData['flag_shop_id'] == 0 ? 0 : 1 ;//特价车or旗舰店
		$saveData['image'] = trim($_POST['goods_img']);
		$saveData['thumb'] = trim($_POST['goods_img']);
		$saveData['image2'] = trim($_POST['goods_img2']);
		$saveData['image3'] = trim($_POST['goods_img3']);
		$saveData['image4'] = trim($_POST['goods_img4']);

		$saveData['sale_png'] = trim($_POST['goods_img5']);
		$saveData['sale_icon'] = trim($_POST['goods_img6']);

		$saveData['user_max_point'] = max(0, intval($_POST['user_max_point']));
		$saveData['saler_max_point'] = max(0, intval($_POST['saler_max_point']));

		$saveData['sale_detail'] = trim($_POST['sale_detail']);
		$saveData['sale_attr'] = trim($_POST['sale_attr']);
		$saveData['edited_operator'] = $this->session->operator_name;
		$existFlag = $this->Vehicle_sale_detail_model->checkDetailExist($sale_id);
		if($existFlag == false){
			$saveData['status'] = 0;
		}
		$this->Vehicle_sale_model->updateById($saveData, $sale_id);
		$this->json_return(true, array('exist'=>$existFlag));
	}

	//保存全局
	public function addGlobal(){
		$saveData = array();
		$saveData['series_id'] = intval($_POST['series_id']);
		$saveData['flag_shop_id'] = max(0, intval($_POST['flag_shop_id']));//旗舰店id
		$saveData['sale_sort'] = max(0, intval($_POST['sale_sort']));//排序
		$saveData['discount'] = max(0, intval($_POST['g_discount']));//特价车页面显示便宜多少元//新版本已经不使用，taodeyu 2016-09-21
		$saveData['activity'] = trim($_POST['g_activity']);//特价车页面显示的标题
		$saveData['affiliate_amount'] = max(0, intval($_POST['affiliate_amount']));//分销多少
		$saveData['user_amount'] = max(0, intval($_POST['user_amount']));//分销多少
		$saveData['sale_type'] = $saveData['flag_shop_id'] == 0 ? 0 : 1 ;//特价车or旗舰店
		$saveData['thumb'] = trim($_POST['goods_img']);
		$saveData['image'] = trim($_POST['goods_img']);
		$saveData['image2'] = trim($_POST['goods_img2']);
		$saveData['image3'] = trim($_POST['goods_img3']);
		$saveData['image4'] = trim($_POST['goods_img4']);

		$saveData['sale_png'] = trim($_POST['goods_img5']);
		$saveData['sale_icon'] = trim($_POST['goods_img6']);
		$saveData['sale_bg'] = 'images/carbg/bg'.rand(1,12).'.png';

		$saveData['sale_detail'] = trim($_POST['sale_detail']);
		$saveData['sale_attr'] = trim($_POST['sale_attr']);
		$saveData['status'] = 0;//状态默认禁用
		$saveData['added_time'] = date('Y-m-d H:i:s');
		$saveData['saler_id'] = 182;
		$saveData['added_operator'] = $this->session->operator_name;

		$saveData['user_max_point'] = max(0, intval($_POST['user_max_point']));
		$saveData['saler_max_point'] = max(0, intval($_POST['saler_max_point']));

		$this->load->model("Vehicle_series_model");
		$series = $this->Vehicle_series_model->getById($saveData['series_id']);
		if(empty($series)){
			$this->json_return(false, array(), '暂无该车型');
			return false;
		}
		if(trim($saveData['image']) == ''){
			$this->json_return(false, array(), '商品图片必须上传');
			return false;
		}
		//特价车显示题目
		$saveData['sale_name'] = $series['series_name'];

		$this->load->model("Vehicle_spec_model");
		$spec_list = $this->Vehicle_spec_model->getBySeriesId($saveData['series_id']);
		$specIdList = array();
		foreach($spec_list as $spec){
			$specIdList[] = $spec['spec_id'];
		}
		$saveData['spec_id_list'] = implode(',', $specIdList);
		$sale_id = $this->Vehicle_sale_model->add($saveData);
		$this->json_return(true, array('sale_id' => $sale_id));
	}

	//删除全部细节
	public function deleteAllDetailBySpecId(){
		$spec_id = intval($_REQUEST['spec_id']);
		$sale_id = intval($_REQUEST['sale_id']);
		$this->Vehicle_sale_detail_model->deleteAllDetailBySpecId($spec_id, $sale_id);
		$existFlag = $this->Vehicle_sale_detail_model->checkDetailExist($sale_id);
		$saveData = array();
		if($existFlag == false){
			$saveData['status'] = 0;
		}
		$this->Vehicle_sale_model->updateById($saveData, $sale_id);
		$this->json_return(true, array('exist'=>$existFlag));
	}

	//删除省份细节
	public function deleteProvinceDetailBySpecId(){
		$spec_id = intval($_REQUEST['spec_id']);
		$sale_id = intval($_REQUEST['sale_id']);
		$district_parent_id = intval($_REQUEST['district_parent_id']);
		$this->Vehicle_sale_detail_model->deleteProvinceDetailBySpecId($spec_id, $district_parent_id, $sale_id);
		$existFlag = $this->Vehicle_sale_detail_model->checkDetailExist($sale_id);
		$saveData = array();
		if($existFlag == false){
			$saveData['status'] = 0;
		}
		$this->Vehicle_sale_model->updateById($saveData, $sale_id);
		$this->json_return(true, array('exist'=>$existFlag));
	}

	//删除城市细节
	public function deleteCityDetailBySpecId(){
		$spec_id = intval($_REQUEST['spec_id']);
		$sale_id = intval($_REQUEST['sale_id']);
		$district_id = intval($_REQUEST['district_id']);
		$this->Vehicle_sale_detail_model->deleteCityDetailBySpecId($spec_id, $district_id, $sale_id);
		$existFlag = $this->Vehicle_sale_detail_model->checkDetailExist($sale_id);
		$saveData = array();
		if($existFlag == false){
			$saveData['status'] = 0;
		}
		$this->Vehicle_sale_model->updateById($saveData, $sale_id);
		$this->json_return(true, array('exist'=>$existFlag));
	}

	//保存细节
	public function saveDetail(){
		//step 1:数据基本验证 & 数据基本组合
		$saleId = $_REQUEST['sale_id'];
		$sale = $this->Vehicle_sale_model->getById($saleId);
		if(empty($sale)){
			$this->json_return(false, '', '此商品无基本属性');
		}
		//城市信息
		$district_id_list = $_REQUEST['district_id_list'];
		$cityList = $this->formCityInfo($district_id_list);
		if(empty($cityList)){
			$this->json_return(false, '', '城市信息有误');
		}
		//颜色信息
		$color_id_list = $_REQUEST['color_id_list'];
		$colorList = $this->formColorInfo($color_id_list);
		if(empty($cityList)){
			$this->json_return(false, '', '颜色信息有误');
		}
		//车款信息
		$spec_id_list = $_REQUEST['spec_id_list'];
		$specList = $this->formSpecInfo($spec_id_list);
		if(empty($cityList)){
			$this->json_return(false, '', '车款信息有误');
		}

		$saveData = array();
		$saveData['price_type'] = intval($_REQUEST['price_type']) == 0 ? 0 : 1;//商品售卖类型
		$saveData['sale_name'] = $_REQUEST['sale_name'];//活动标题
		$saveData['quantity'] = is_array($_REQUEST['quantity']) ? $_REQUEST['quantity'] : array();//库存
		$saveData['discount'] = max(0, intval($_REQUEST['discount']));//一口价
		$saveData['price'] = max(0, intval($_REQUEST['price']*100)/100);//定金
		$saveData['brief'] = $_REQUEST['brief'];//优惠政策
        if(!empty($_REQUEST['remark'][0])){
            $saveData['remark'] = implode(',',$_REQUEST['remark']);//备注
        }else{
            $saveData['remark'] ="";
        }
		
		$saveData['is_lottery'] = intval($_REQUEST['is_lottery']) == 0 ? 0 : 1;//是否抽奖
		if($saveData['price_type'] == 0 && $saveData['discount'] == 0){
			$this->json_return(false, '', '商品售卖类型若为【一口价】，一口价必须填写');
		}
		if($saveData['price_type'] == 1 && $saveData['brief'] == ''){
			$this->json_return(false, '', '商品售卖类型若为【定金活动】，优惠政策填写');
		}
		$start_time = microtime(1);
		//这里要考虑性能，特别是内存，所以不能一下子全部整理好，要分批插入。
		$specXcolorList = array();
		//先将车款和颜色组成一组
		foreach($specList as $spec){
			foreach($colorList as $color){
				$specXcolorList[] = array(
					'spec_id' => $spec['spec_id'],
					'spec_name' => $spec['spec_name'],
					'market_price' => $spec['market_price'],
					'color_id' => $color['color_id'],
					'color_name' => $color['color_name'],
					'color_value' => $color['color_value'],
					'quantity' => isset($saveData['quantity'][$color['color_id']]) ? max(0, $saveData['quantity'][$color['color_id']]) : 0,
					'sale_num' => $this->Vehicle_sale_detail_model->getOneSaleNumByColor($saleId, $spec['spec_id'], $color['color_id']),
				);
			}
		}
		//删除车款和城市结合的条目
		$this->Vehicle_sale_detail_model->deleteDetailBySpecListAndDistrictList(array_unique($spec_id_list), array_unique($district_id_list), $saleId);
		//再结合城市，进行添加
		foreach($specXcolorList as $specXcolor){
			$saveDataList = array();
			foreach($cityList as $city){
				$saveDataList[] = array(
					'sale_id' => $saleId,
					//城市
					'district_parent_id' => $city['district_parent_id'],
					'district_parent_name' => $city['district_parent_name'],
					'district_id' => $city['district_id'],
					'district_name' => $city['district_name'],
					//车款
					'spec_id' => $specXcolor['spec_id'],
					'spec_name' => $specXcolor['spec_name'],
					//颜色
					'color_id' => $specXcolor['color_id'],
					'color_name' => $specXcolor['color_name'],
					'color_value' => $specXcolor['color_value'],
					//详细数据
					'discount_price' => $saveData['discount'],
					'is_lottery' => $saveData['is_lottery'],
					'discount_detail' => '',
					'price' => $saveData['price'],
					'quantity' => $specXcolor['quantity'],
					'brief' => $saveData['brief'],
					'remark' => $saveData['remark'],
					'sale_name' => $saveData['sale_name'],
					'price_type' => $saveData['price_type'],
					'market_price' => $specXcolor['market_price'],
					'sale_num' => $specXcolor['sale_num'],
				);
			}
			$this->Vehicle_sale_detail_model->batchInsert($saveDataList);
		}
		$end_time = microtime(1);
		$this->json_return(true);
		//p($end_time-$start_time);
	}

	protected function formCityInfo($district_id_list){
		$this->load->model("District_model");
		$cityTempList = $this->District_model->getCityByCityId($district_id_list);
		$provinceIdList = array();
		foreach($cityTempList as $city){
			$provinceIdList[] = $city['parent_id'];
		}
		$provinceIdList = array_unique($provinceIdList);
		$provinceTempList = $this->District_model->getProvinceByProvinceId($provinceIdList);
		$provinceList = array();
		foreach($provinceTempList as $province){
			$provinceList[$province['district_id']] = $province;
		}
		$cityList = array();
		foreach($cityTempList as $city){
			$cityList[] = array(
				'district_parent_id' => $city['parent_id'],
				'district_parent_name' => $provinceList[$city['parent_id']]['district_name'],
				'district_id' => $city['district_id'],
				'district_name' => $city['district_name'],
			);
		}
		return $cityList;
	}
	protected function formColorInfo($color_id_list){
		$this->load->model("Vehicle_series_color_model");
		return $this->Vehicle_series_color_model->getByColorList($color_id_list);
	}
	protected function formSpecInfo($specList){
		$this->load->model("Vehicle_spec_model");
		return $this->Vehicle_spec_model->getBySpecList($specList);
	}

	public function vs_districtSelectPage(){
		$saleId = intval($_REQUEST['sale_id']);
		$specId = intval($_REQUEST['spec_id']);
		$districtList = $this->Vehicle_sale_detail_model->getDistrictId($saleId, $specId);
		$this->districtSelectPage($districtList);
	}

	public function uploadDistrict(){
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
				$result = $this->checkDistrictExcel($_FILES[$fileElementName]['tmp_name']);
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

	protected function checkDistrictExcel($file){
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
		$districtList = array();
		for($i = 1; $i < 1000; $i++){
			$districtNameTemp = strtoupper(trim($sheet->getCell('A'.$i)->getValue()));
			if($districtNameTemp == ''){
				break;
			}
			$districtList[] = $districtNameTemp;
		}
		if(count($districtList) == 0){
			$returnArr = array(
				'flag' => false,
				'message' => '城市不可为空'
			);
			return $returnArr;
		}
		$this->load->model('District_model');
		$inDBdistrictList = $this->District_model->getInDistrict($districtList);
		$inDBdistrictNameList = array();
		$inDBdistrictIdList = array();
		foreach($inDBdistrictList as $inDBdistrict){
			$inDBdistrictNameList[] = $inDBdistrict['district_name'];
			$inDBdistrictIdList[] = $inDBdistrict['district_id'];
		}
		$notInDBdistrictList = array();
		foreach($districtList as $districtName){
			if(!in_array($districtName, $inDBdistrictNameList)){
				$notInDBdistrictList[] = $districtName;
			}
		}
		if(count($notInDBdistrictList) > 0){
			$returnArr = array(
				'flag' => false,
				'message' => '这些城市有误【'.implode(',', $notInDBdistrictList).'】。请检查！'
			);
			return $returnArr;
		}
		$returnArr = array(
			'flag' => true,
			'message' => $inDBdistrictIdList
		);
		return $returnArr;
	}
}