<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visit extends BaseController{
    function __construct(){
        parent::__construct();
        $this->load->model("Visit_model");
    }
    
    public function index(){
        $viewData['_page_name'] = '用户访问日志';
        $viewData['_page_detail'] = '';
        $this->view('visit/index.php', $viewData);
    }
    
   
}
?>