<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Findorder extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->model("Paylog_model");
        $this->load->model("Order_model");
	}


	/**
     *查找后台订单
     */
    public function index()
    {
		$viewData['_left_parent_page_name'] = '查找后台订单';
		$viewData['_page_name'] = '查找后台订单';
		$viewData['_page_detail'] = '';

        $this->view('find_order/index.php');
    }

    public function find()
    {
        $res = array();
        $res['result'] = false;

        $logId = !empty($_POST['wx_no']) ? $_POST['wx_no'] : '';
        if (empty($logId)) {
            $res['message'] = '请输入商户订单号';
            exit(json_encode($res));
        }

        $logInfo = $this->Paylog_model->getById($logId);
        if (!empty($logInfo)) {
            $orderInfo = $this->Order_model->getById($logInfo['order_id']);
            if (!empty($orderInfo)) {
                $res['result'] = true;
                $res['order_no'] = $orderInfo['order_no'];
                exit(json_encode($res));
            }
        }

        $res['message'] = '未找到对应订单，请确认';
        exit(json_encode($res));
    }

    public function index2()
    {
        $viewData['_left_parent_page_name'] = '查找微信商户订单号';
        $viewData['_page_name'] = '查找微信商户订单号';
        $viewData['_page_detail'] = '';

        $this->view('find_order/index2.php');
    }

    public function find2()
    {
        $res = array();
        $res['result'] = false;

        $orderNo = !empty($_POST['order_no']) ? $_POST['order_no'] : '';
        if (empty($orderNo)) {
            $res['message'] = '请输入订单号';
            exit(json_encode($res));
        }

        $orderInfo = $this->Order_model->getByOrderno($orderNo);
        if (!empty($orderInfo)) {
            $logInfo = $this->Paylog_model->getByOrderid($orderInfo['order_id']);
            if (!empty($logInfo)) {
                $res['result'] = true;
                $res['wx_no'] = $logInfo['transaction_id'];
                exit(json_encode($res));
            }
        }

        $res['message'] = '未找到对应订单，请确认';
        exit(json_encode($res));
    }
}