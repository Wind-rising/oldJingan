<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_banner extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->model("Index_banner_model");

	}

	public function index(){
		$viewData['_page_name'] = '首页banner图';
		$viewData['_page_detail'] = '';
		$this->view('indexBanner/index.php', $viewData);
	}
	
	//删除banner图
	public function delete(){
		$banner_id = isset($_REQUEST['banner_id']) ? intval($_REQUEST['banner_id']) : 0;
		$updateData = array(
			'edit_time' => date('Y-m-d H:i:s'),
			'operator' => $this->session->operator_name,
			'is_delete' => 1
		);
		$this->Index_banner_model->updateById($updateData, $banner_id,'htqc');
		$this->json_return(true);
	}
	
	public function showEdit($banner_id = 0){
		$banner = $this->Index_banner_model->getById($banner_id,'htqc');
		if(empty($banner)){
			$this->error_page('无此banner图');
			return false;
		}
		$viewData['_page_name'] = '编辑首页banner图';
		$viewData['_page_detail'] = '';
		$viewData['banner'] = $banner;
		$this->view('indexBanner/edit.php', $viewData);
	}
	
	public function showAdd(){
		$viewData['_page_name'] = '新增首页banner图';
		$viewData['_page_detail'] = '';
		$this->view('indexBanner/add.php', $viewData);
	}
	
	public function uploadBannerImg(){
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
				$local_dirname = config_item('SITE_ROOT')."static/uploads/index_banner/base/";
				$remote_dirname = config_item('FTP_UPLOADS_DIR')."index_banner/base/";
				$upfilename = "banner_".time().rand(0, 1000).".".$file_ext;
				$result = move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $local_dirname.$upfilename);
				@chmod($local_dirname.$upfilename, 0777);
				
															
				if($result == true){
					$status = true;
					$info = 'http://www.ichelaba.cn'.'/static/uploads/index_banner/base/'.$upfilename;
				}
			}
		}
		if($status == true){
			$this->json_return(true, "", $info);
		}else{
			$this->json_return(false, "", $info);
		}
	}
	
	public function edit($banner_id = 0){
		if (empty($banner_id)) {
			$banner_id = isset($_POST['banner_id']) ? $_POST['banner_id'] : 0;
		}
		$saveData = array(
			'sort' => isset($_REQUEST['sort']) ? max(0, intval($_REQUEST['sort'])) : 0,
			'banner_title' => isset($_REQUEST['banner_title']) ? trim($_REQUEST['banner_title']) : '',
			'banner_img' => isset($_REQUEST['banner_img']) ? trim($_REQUEST['banner_img']) : '',
			'banner_href' => isset($_REQUEST['banner_href']) ? trim($_REQUEST['banner_href']) : '',
			'edit_time' => date('Y-m-d H:i:s'),
			'operator' => $this->session->operator_name
		);
		$banner = $this->Index_banner_model->getById($banner_id,'htqc');
		if(empty($banner) || $banner['is_delete'] == 1){
			$this->json_return(false, '', '无此banner图');
			return false;
		}
		$this->Index_banner_model->updateById($saveData, $banner_id,'htqc');
		$this->json_return(true);
	}
	
	public function add(){
		$saveData = array(
			'sort' => isset($_REQUEST['sort']) ? max(0, intval($_REQUEST['sort'])) : 0,
			'banner_title' => isset($_REQUEST['banner_title']) ? trim($_REQUEST['banner_title']) : '',
			'banner_img' => isset($_REQUEST['banner_img']) ? trim($_REQUEST['banner_img']) : '',
			'banner_href' => isset($_REQUEST['banner_href']) ? trim($_REQUEST['banner_href']) : '',
			'edit_time' => date('Y-m-d H:i:s'),
			'operator' => $this->session->operator_name
		);
		$this->Index_banner_model->addBanner($saveData);
		$this->json_return(true);
	}
}
