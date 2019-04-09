<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visit extends BaseController{
    protected $_export_fileds = array(
        
        'full_name' => '姓名',
        'mobile' => '手机号',
        'user_type' => '类型',
        'visit_name' => '访问路径'
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Visit_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'visit_log'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $user = $this->Visit_model->get_data(array('form_name' => 'user', 'where' => array('user_id' => $rv->user_id)));
            if (!empty($user[0]['full_name'])) {
              $rv->full_name = $user[0]['full_name'];
            }
            if (!empty($user[0]['type'])) {
                $rv->user_type = $user[0]['type'];
            }
            $rv->visit_name = $this->getVisitName($rv->visit_url);
        }
        $fileds = $this->_export_fileds;
        $filename = '访问列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }

    public function getVisitName($url) {
        $changeArr = array(
                            'user/info' => '账户管理',
                            'user/comment' => '评论',
                            'user/apply' => '全民经纪人',
                            'user/affiliate' => '分销管理',
                            'store/store/index' => '云店',
                            'user/user/index' => '用户中心',
                            'user/address' => '地址管理',
                            'user/make' => '我的预约',
                            'user/comment' => '评论管理',
                            'user/order' => '订单管理',
                            'store/index' => '商城首页',
                            'store/sale' => '产品详情/下单',
                            'store/product_list' => '全部产品',
                        );

        foreach ($changeArr as $ck => &$cv) {
            if (strstr($url, $ck)) {
                if($ck = 'store/sale') {
                    $num = preg_replace('/\D/s', '', $url);
                    $product = $this->Visit_model->get_data(array('form_name' => 'product', 'where' => array('product_id' => $num)));
                    if (!empty($product[0]['product_name'])) {
                        $cv = $cv . '-' . $product[0]['product_name'];
                    }
                }
                return $cv;
            }                
        }  

        return '';
      }
}
