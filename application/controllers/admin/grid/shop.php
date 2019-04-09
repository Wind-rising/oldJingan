<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends BaseController{
    protected $_export_fileds = array(
        'shop_id' => 'ID',
        'shop_name' => '公司名称',
        'shop_phone' => '电话',
        'shop_address' => '公司地址',
        'contacts' => '联系人',
        'contacts_phone' => '联系电话',
        'createtime' => '生成时间',
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Shop_model");
        $this->load->model("Vehicle_sale_model");
    }

    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'shop'));
        $result = $this->grid->to_array();

//        $userList = $this->db->select('user_id, shop_id, amount')
//                             ->where('type', 3)
//                             ->get('user')
//                             ->result_array();
//        foreach ($result['rows'] as $key => $item) {
//            //读取地区
//            $districtname = $this->Vehicle_sale_model->getByDistrictsId($item->pid);
//            if(!empty($districtname[0]['district_name'])) {
//                $result['rows'][$key]->province = $districtname[0]['district_name'];
//            }
//            $districtname = $this->Vehicle_sale_model->getByDistrictsId($item->cid);
//            if(!empty($districtname[0]['district_name'])) {
//                $result['rows'][$key]->city = $districtname[0]['district_name'];
//            }
//
//            // 获取佣金
//            foreach ($userList as $uv) {
//                if ($uv['shop_id'] == $item->shop_id) {
//                    $result['rows'][$key]->amount = $uv['amount'];
//                }
//            }
//        }


        $fileds = $this->_export_fileds;
        $filename = '经销商列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}
