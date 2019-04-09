<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->model("Shop_model");
        $this->load->model("User_model");
	}

    public function index(){
        $viewData['_page_name'] = '经销商列表';
        $viewData['_page_detail'] = '';
        if (!empty($_POST["type"]) && $_POST["type"] == "so_original") {
        
            if ($_POST["fileSuffix"] == "csv" || $_POST["fileSuffix"] == "CSV" || strpos($_FILES['so_file']['name'],'.csv')) {
                $csv_file = $_FILES['so_file']['tmp_name'];
            
                if (is_file($csv_file)) {
                    $csv_content = file_get_contents($csv_file);
                    $csv_content = iconv('GBK', 'UTF-8//IGNORE', $csv_content);
                
                    $csv = csvdecode($csv_content);
                
                    $i = 0;
                    foreach ($csv as $key => $item) {
                    
                    
                        if($i == 0){ $i++;continue;}
                        $arr = array();
                        $province_name = trim($item[0]);
                        $province = $this->Shop_model->get_data(array('form_name' => 'districts', 'where' => array('district_name' => $province_name)));
                     
                        $arr['pid'] = $province[0]['district_id'];
                        $city_name = trim($item[1]);
                        $city_name = str_replace('市','',$city_name);
                        $province = $this->Shop_model->get_data(array('form_name' => 'districts', 'where' => array('district_name' => $city_name)));
                        $arr['cid'] = $province[0]['district_id'];
                        $arr['shop_name'] = trim($item[3]);
                        $arr['shop_address'] = trim($item[5]);
                        $arr['contacts'] = trim($item[7]);
                        $arr['contacts_phone'] = trim($item[8]);
                        $this->Shop_model->set_table('shop');
                        $return = $this->Shop_model->add($arr);
                        $arruser['shop_id'] = $return;
                        $arruser['mobile'] = trim($item[8]);
                        $arruser['full_name'] = trim($item[7]);
                        $arruser['qq'] = trim($item[9]);
                        $arruser['password'] = md5(substr(trim($item[8]),-6));
                        $arruser['type'] = 3;
                        $arruser['create_time'] = date("Y-m-d H:i:s",time());
                        $this->User_model->set_table('user');
                        $returnuser = $this->User_model->add($arruser);

                        $i++;
                    }
                    $this->remind->set("/shop/index", ($i-1).'条记录导入成功',"success");
                } else {
                    $this->remind->set("/shop/index", '请先选择要导入的CSV',"error");
                }
            } else {
                $this->remind->set("/shop/index", '数据文件必须为CSV格式',"error");
            }
        }
		$this->view('shop/index.php', $viewData);   
	}

	/**
     *添加
     */
    public function add()
    {
		$viewData['_left_parent_page_name'] = '添加';
		$viewData['_page_name'] = '添加';
		$viewData['_page_detail'] = '';
        $viewData['all_city'] = $this->getAllDistrict();
        $this->view('shop/add.php', $viewData);
    }

    public function saveAdd()
    {
        //验证手机号是否重复
        $user = $this->User_model->get_data(array('form_name' => 'user', 'where' => array('mobile' => trim($_REQUEST['phone']))));
        if(!empty($user[0]['mobile'])){
            $result['message'] = '已有此经销商';
            exit(json_encode($result));
        }
        $updateData = array();
        $updateData['shop_name'] = trim($_REQUEST['shop_name']);
        $my['password'] = md5(intval($_REQUEST['password']));
        $updateData['contacts'] = trim($_REQUEST['contact']);
        $updateData['cid'] = intval($_REQUEST['district_id']);
        $province = $this->Shop_model->get_data(array('form_name' => 'districts', 'where' => array('district_id' => $updateData['cid'])));
        $updateData['pid'] = $province[0]['parent_id'];
        $updateData['status'] = trim($_REQUEST['status']);
        $updateData['contacts_phone'] = trim($_REQUEST['phone']);
        $updateData['shop_address'] = trim($_REQUEST['shop_address']);
        $updateData['shop_desc'] = trim($_REQUEST['shopdesc']);
        $updateData['shop_phone'] = trim($_REQUEST['shop_phone']);
        $updateData['shop_img'] = trim($_REQUEST['img_upload_goods_img']);
        
        if($updateData['status'] == '禁用'){
            $updateData['status'] == 0;
        }elseif($updateData['status'] == '启用'){
            $updateData['status'] = 1;
        }
        $this->Shop_model->set_table('shop');
        $query = $this->Shop_model->add($updateData);
        //插入用户表
        $user['shop_id'] = $query;
        $user['full_name'] = $updateData['contacts'];
        $user['password'] = md5('123456');
        $user['mobile'] = $updateData['contacts_phone'];
        $user['type'] = 3;
        $user['create_time'] = date("Y-m-d H:i:s",time());
        $this->User_model->set_table('user');
        $query = $this->User_model->add($user);
        if($query){
            $result['message'] = '保存成功！';
        }
        exit(json_encode($result));
    }

    /**
     *4S店编辑
     */
    public function edit()
    {
		$viewData['_left_parent_page_name'] = '4S店修改';
		$viewData['_page_name'] = '4S店修改';
		$viewData['_page_detail'] = '';
		$shop_id = $this->input->get('shop_id');
        $shopInfo = $this->Shop_model->getByIdList($shop_id);
        $allBrand = $this->getAllBrandBaseInfo();
        $allBrand = $this->formBrand($allBrand);
        $viewData['shopInfo'] = $shopInfo[0];
        $viewData['all_city'] = $this->getAllDistrict();
        $viewData['all_brand'] = $allBrand;
		$this->view('shop/edit.php', $viewData);
    }

    public function saveEdit(){
        $updateData = array();
        $id = intval($_REQUEST['id']);
        $updateData['shop_name'] = trim($_REQUEST['shop_name']);
        $updateData['password'] = empty($_REQUEST['password'])? '':md5(intval($_REQUEST['password']));
        $updateData['brand_id'] = empty($_REQUEST['brand_id'])? '':intval($_REQUEST['brand_id']);
        $updateData['contact'] = empty($_REQUEST['contact'])? '':trim($_REQUEST['contact']);
        $updateData['district_id'] = empty($_REQUEST['district_id'])? '':intval($_REQUEST['district_id']);
        $updateData['status'] = empty($_REQUEST['status'])? '':trim($_REQUEST['status']);
        $updateData['phone'] = empty($_REQUEST['phone'])? '':trim($_REQUEST['phone']);
        $updateData['shop_address'] = trim($_REQUEST['shop_address']);
        if($updateData['status'] == '禁用'){
            $updateData['status'] == 0;
        }elseif($updateData['status'] == '启用'){
            $updateData['status'] == 1;
        }elseif($updateData['status'] == '填写资料'){
            $updateData['status'] == 2;
        }elseif($updateData['status'] == '待审核'){
            $updateData['status'] == 3;
        }elseif($updateData['status'] == '审核失败'){
            $updateData['status'] == 4;
        }
        $query = $this->Shop_model->updateById($updateData ,$id);
        if($query){
            $result['message'] = '保存成功！';
        }
        exit(json_encode($result));
    }

    /**
     * 删除
     */
    public function shop_delete()
    {
       $shop_id = $this->input->get('shop_id');
       $backdata = $this->Shop_model->deleteById($shop_id);
       //删除用户
        exit(json_encode($backdata));
    }

    /**
     * 测试
     */
    public function test()
    {
        $this->load->library('grid', array('db' => $this->db, 'form_name' => 'shop'));
        $result = $this->grid->to_array();
        // var_dump($result);
        $brandIdList = array();
        foreach ($result['rows'] as &$rows) {
            $brandIdList[] = $rows->brand_id;
        }
        // var_dump($brandIdList);
        foreach ($result['rows'] as &$rows) {
            $shopIdList[] = $rows->shop_id;
        }
        // var_dump($shopIdList);
        //车型名称
        $brandListTemp = $this->Shop_model->getbrandByBrandIdList($brandIdList);
        // var_dump( $brandListTemp);
        $salerListTemp = $this->Shop_model->getSalerByShopIdList($shopIdList);
        $salerCount = count($salerListTemp);
        echo $salerCount;
        var_dump($salerListTemp);
        // $brandList[$result['rows']] = $brand;
        $brandList = array();
        $salerList = array();
        foreach ($brandListTemp as $brand) {
            $brandList[$brand['brand_id']] = $brand;
        }
        foreach ($salerListTemp as $saler) {
            $salerList[$saler['shop_id']] = array('saler_count' => $salerCount);
        }
        var_dump($brandList);
        var_dump($salerList);
    
        foreach ($result['rows'] as &$rows) {
            $rows->brand_name = $brandList[$rows->brand_id]['brand_name'];
            // var_dump($result['rows']);
        }
        foreach ($result['rows'] as &$rows) {
            // var_dump($rows);
            if (!empty($salerList[$rows->shop_id])) {
                $rows->saler_count = $salerList[$rows->shop_id]['saler_count'];
            }
        
        }
    }
    
        public function uploadImg(){
            $maxSize = 1024*500;
            $ext_arr = array('jpg', 'jpeg', 'png', 'bmp');
            $fileElementName = 'pic';
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
                    $local_dirname = config_item('SITE_ROOT')."static/uploads/article/detail/";
                    $remote_dirname = config_item('FTP_UPLOADS_DIR')."article/detail/";
                    $upfilename = "article_".time().rand(0, 1000).".".$file_ext;
                
                    $result = move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $local_dirname.$upfilename);
                    @chmod($local_dirname.$upfilename, 0777);
                
                    if($result == true){
                        $status = true;
                        $info = '/uploads/article/detail/'.$upfilename;
                    }
                }
            }
            if($status == true){
                $this->json_return(true, "", $info);
            }else{
                $this->json_return(false, "", $info);
            }
        }
    
    /**
     * 审核
     */
    public function checkShop()
    {
        
        $shop_id = $this->input->post('shop_id');
        $tag = $this->input->post('tag');
        
        if($tag==1){
            $return = $this->Shop_model->softDeleteById($shop_id,$arr=array("is_shopshow" => 1),'shop_id');
        }else{
            $return = $this->Shop_model->softDeleteById($shop_id,$arr=array("is_shopshow" => 0),'shop_id');
        }
        if($return == 1) {
            
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '修改成功';
        }
        exit(json_encode($return_struct));
    }

    }

