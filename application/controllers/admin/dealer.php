<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dealer extends CI_controller{

	function __construct(){
		parent::__construct();
		$this->load->model("Dealer_model");
		$this->load->model("Agency_model");
        $this->load->model("Region_model");
        $this->load->library('session');
	}

	// 定义一个函数getIP()
    protected function get_ip() {
        $ip = '';
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "unknow";
        }

        return $ip;
    }

	public function index(){
        $city = '南京'; // 默认是南京
        $province = '江苏';
        $city_info = $this->Dealer_model->getByRegionName($city);

        if (empty($city_info)) {
            $city_id = 220;
            $pro_id = 16;
        } else {
            $city_id = $city_info['region_id'];
            $pro_id = $city_info['parent_id'];
            $province = $this->Dealer_model->getById($pro_id);
            $province = $province['region_name'];
        }
        // 查找所在城市下的经销商
        $agency_list = $this->Agency_model->getDealerListByCid($city_id);
        foreach ($agency_list as $ak => &$av) {
            $av['add_encode'] = urlencode($av['address']);
        }

        // iframe中百度地图搜索关键字
        if (empty($city_info['region_name'])) {
            $city_info['region_name'] = '南京';
        }
        $search_name = urlencode($city_info['region_name'] . '悦达起亚');

        // 获得省份信息
        $pidList = $this->Region_model->getAgentProvence();
        $pidList = arrayValueList($pidList, 'pid');
        $province_list = $this->Region_model->getListByIdList($pidList);

        $cidList = $this->Region_model->getAgentCity($pro_id);
        $cidList = arrayValueList($cidList, 'cid');
        $city_list = $this->Region_model->getListByIdList($cidList);
        $new_city_list = array();
        foreach ($city_list as $ck => &$cv) {
            if ($city_id != $cv['region_id']) {
                $new_city_list[] = $cv;
            }
        }

        $data['province'] = $province;
        $data['search_name'] = $search_name;
        $data['city'] = $city;
 		$data['pro_id'] = $pro_id;
 		$data['city_id'] = $city_id;
 		$data['agency_list'] = $agency_list;
 		$data['province_list'] = $province_list;
        $data['city_list'] = $new_city_list;
		$this->load->view('dealer/index.tpl',$data);
	}

    /**
     * 获得某省份下的城市信息
     */
    public function get_city_info() {
        $pid = intval($_POST['pid']);
        if (!empty($pid)) {
            $cidList = $this->Region_model->getAgentCity($pid);
            $cidList = arrayValueList($cidList, 'cid');
            $city_list = $this->Region_model->getListByIdList($cidList);

            die(json_encode($city_list));
        }
        exit();
    }

    function getAgency() {
        $cid = $_POST['cid'];
        if (empty($cid)) {
            exit(array());
        }
        $city_info = $this->Dealer_model->getById($cid);
        $search_name = urlencode($city_info['region_name'] . '悦达起亚');
        $agency_list = $this->Agency_model->getDealerListByCid($cid);
        foreach ($agency_list as $ak => &$av) {
            $av['add_encode'] = urlencode($av['address']);
        }

        exit(json_encode(array('search_name' => $search_name, 'agency_list' => $agency_list)));
    }

}