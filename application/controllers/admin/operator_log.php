<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator_log extends BaseController{
    function __construct(){
        parent::__construct();
        $this->load->model("Operator_log_model");
    }
    
    public function index(){
        $viewData['_page_name'] = '后台操作日志';
        $viewData['_page_detail'] = '';
        $this->view('operator_log/index.php', $viewData);
    }
    
   
}
?>