<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pointactivity extends BaseController{
    public $STATUS = array(
        'POINT_UNUSED' => 0, //积分未使用兑换
        'POINT_SUCCESS' => 1, //积分兑换成功
        'POINT_FAILED' => 2 //积分兑换失败
    );
    

    function __construct(){
        parent::__construct();
        $this->load->model("Point_log_model");
    }
    
    public function index(){
        
        $totalPoint= $this->Point_log_model->getTotalPoint();

        $totalPointChange = $this->Point_log_model->getTotalPointChange();

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
        $dateInfo = $this->Point_log_model->getTotalDateInfo($dateFrom, $dateTo);

        $viewData['dateInfo'] = $dateInfo;
        $viewData['totalPoint'] = $totalPoint[0]['totalpoint'];
        $viewData['totalPointChange'] = $totalPointChange[0]['totalpointchange'];
        $viewData['date_from'] = $dateFrom;
        $viewData['date_to'] = $dateTo;
        $this->view('pointactivity/index.php', $viewData);
    }

}