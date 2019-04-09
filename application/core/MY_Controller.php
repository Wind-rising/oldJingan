<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function json_return($flag = true, $data = "", $message = "")
	{
	    
		exit(json_encode(array('flag' => $flag, 'data' => $data, 'message' => $message)));
	}

	public function error_page($error_str)
	{
		$viewData['error_str'] = $error_str;
		$this->load->view('error/error_page.php', $viewData);
	}

	//获取全国地区
	public function getAllDistrict()
	{
		$this->load->model("District_model");
		$allCity = $this->District_model->getAllCity();
		$allProvince = $this->District_model->getAllProvince();
		$allArea = array();
		$provinceTemp = array();
		foreach ($allProvince as $province) {
			$allArea[$province['district_id']] = array();
			$provinceTemp[$province['district_id']] = $province;
		}
		foreach ($allCity as $city) {
			if (isset($allArea[$city['parent_id']])) {
				$allArea[$city['parent_id']][] = array_merge(
					$city, array(
					"province_name" => $provinceTemp[$city['parent_id']]['district_name']
					)
				);
			}
		}
		return $allArea;
	}

	public function formAreaInfo($cityIdList)
	{
		$this->load->model("District_model");
		$cityList = $this->District_model->getCityByCityId($cityIdList);
		$provenceIdList = array();
		foreach ($cityList as $city) {
			$provenceIdList[] = $city['parent_id'];
		}
		$provenceIdList = array_unique($provenceIdList);
		$provenceList = $this->District_model->getProvinceByProvinceId($provenceIdList);

		$allArea = array();
		$provinceTemp = array();
		foreach ($provenceList as $province) {
			$allArea[$province['district_id']] = array();
			$provinceTemp[$province['district_id']] = $province;
		}
		foreach ($cityList as $city) {
			if (isset($allArea[$city['parent_id']])) {
				$allArea[$city['parent_id']][] = array_merge(
					$city, array(
					"province_name" => $provinceTemp[$city['parent_id']]['district_name']
					)
				);
			}
		}
		return $allArea;
	}

	public function districtSelectPage($districtIdList = array())
	{
		$allArea = $this->getAllDistrict();
		$viewData['all_area'] = $allArea;
		$viewData['districtIdList'] = $districtIdList;
		$this->load->view('public/all_area.php', $viewData);
	}

	//获取品牌信息，id，name，拼音
	public function getAllBrandBaseInfo(){
		$this->load->model("Vehicle_brand_model");
		return $this->Vehicle_brand_model->getAllBrandBaseInfo();
	}

	//整理成拼音对应的键值对，不存在拼音则使用#
	public function formBrand($brands){
		$returnBrands = array();
		$otherBrands = array();
		foreach($brands as $brand){
			if(isset($brand['brand_name_py'][0])){
				$key = $brand['brand_name_py'][0];
			}else{
				$otherBrands[] = $brand;
				continue;
			}
			if(!isset($returnBrands[$key])){
				$returnBrands[$key] = array();
			}
			$returnBrands[$key][] = $brand;
		}
		ksort($returnBrands);
		if(count($otherBrands) > 0){
			$returnBrands['#'] = $otherBrands;
		}
		return $returnBrands;
	}

	//通过品牌id获取车系
	public function getSeriesByBrandId($brandId){
		$this->load->model("Vehicle_series_model");
		$allSeries = $this->Vehicle_series_model->getByBrandId($brandId);
		return $allSeries;
	}

    public function curl_post_bd($url,$data){

        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_TIMEOUT,3);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);

        $rs=curl_exec($curl);

        curl_close($curl);

        return $rs;
    }
}

class AdminController extends My_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('remind');
		$this->load->helper('url');
		$this->load->model("MY_Model");

		//验证登录
		if (false == $this->session->has_userdata('operator_name')) {
			header("location:".login_url());
		}
		//验证操作权限
		if (false == $this->MY_Model->check_menus()){
			if($this->input->is_ajax_request()){
				echo json_encode(array('flag' => FALSE, 'message' => '您无此操作权限'));exit;
			}else{
				$this->remind->set("index.php/index/index", "你没有权限", "error");
			}
		}
	}

    public function view($template = 'template', $data = NULL) {
	    $data['menu'] = $this->MY_Model->build_menu();
        $this->load->view('public/top.php', $data);
		$this->load->view('public/left.php');
        $this->load->view($template);
        $this->load->view('public/footer.php');
    }
}