<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends BaseController{
    function __construct(){
        parent::__construct();
        $this->load->model("Finance_model");
        $this->load->model("vehicle_sale_order_model");
        $this->load->model("Point_log_model");
    }
    
    public function indexpay(){
        $totalPoint= $this->Finance_model->getTotalPoint();
    
        $totalPointChange = $this->Finance_model->getTotalPointChange();
    
        $dateFrom = isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : '';
        $dateTo = isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : '';
        if($dateFrom == "" || strtotime($dateFrom) == false){
            $dateFrom = date('Y-m-d', time()-7*24*3600);
        }else{
            $dateFrom = date('Y-m-d', strtotime($dateFrom));
        }
        if($dateTo == "" || strtotime($dateTo) == false){
            $dateTo = date('Y-m-d');
        }else{
            $dateTo = date('Y-m-d', strtotime($dateTo));
        }
        if(strtotime($dateFrom) > strtotime($dateFrom)){
            $dateFrom = $dateTo;
        }
        $dateInfo = $this->Finance_model->getTotalDateInfo($dateFrom, $dateTo);
    
        $viewData['dateInfo'] = $dateInfo;
        $viewData['totalPoint'] = $totalPoint[0]['totalpoint'];
        $viewData['totalPointChange'] = $totalPointChange[0]['totalpointchange'];
        $viewData['date_from'] = $dateFrom;
        $viewData['date_to'] = $dateTo;
        $viewData['_page_name'] = '财务流水收入';
        $viewData['_page_detail'] = '';
        $this->view('finance/index.php', $viewData);
    }
    
    public function withdraw(){
        $viewData['_page_name'] = '提现管理';
        $viewData['_page_detail'] = '';
        $this->view('finance/withdraw.php', $viewData);
    }
    
    public function refund(){
        $viewData['_page_name'] = '退款管理';
        $viewData['_page_detail'] = '';
        $this->view('finance/refund.php', $viewData);
    }
    
    /**
     * 审核
     */
    public function checkrefund()
    {
        
        $refund_id = $this->input->post('refund_id');
        $refund = $this->Finance_model->get_data(array('form_name' => 'order_refund', 'where' => array('refund_id' => $refund_id)));
        $return = $this->vehicle_sale_order_model->softDeleteById($refund[0]['order_id'],$arr=array("status" => 5),'order_id');

        if($return == 1) {
            
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '审核成功';
        }
        exit(json_encode($return_struct));
    }
    
    /**
     * 审核
     */
    public function checkpoint()
    {
        
        $point_id = $this->input->post('point_id');
        $return = $this->send();
        var_dump($return);exit;
        if($return == 1) {
            
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '提现成功';
        }
        exit(json_encode($return_struct));
    }
    
    
    function send()
    {
    
        include 'D:/wamp/www/htqcadmin/vendor/weixin/WeixinPay.php';
        $weixin = $this->getWxpay();
        $weixin->sendredpack('红包', '红包', 'o8ei00vzK3B81Qs1V1hPhfQXKsok', '红包', 100, 1, '红包');
    }
    
    function getWxpay()
    {
        static $list = array();
        $configs = array();
        if (empty($list)) {
            $list = new WeixinPay(config_item('weixin_pay'));
        }
        
        return $list;
    }
    
   
}
?>