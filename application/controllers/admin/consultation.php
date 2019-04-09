<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultation extends BaseController{

    function __construct(){
        parent::__construct();
        $this->load->model("Consultation_model");
        // $this->load->library('session');
    }

    public function index()
    {
        $viewData['_left_parent_page_name'] = '咨询管理';
        $viewData['_page_name'] = '咨询管理';
        $viewData['_page_detail'] = '';
        $this->view('consultation/index.php', $viewData);
    }

    public function add()
    {
        $this->view('consultation/add.php');
    }

    public function save()
    {
        $res = array();
        $res['result'] = false;

        $title = $_POST['title'];
        $content = $_POST['content'];
        if (empty($title) || empty($content)) {
            $res['message'] = '请完善信息';
            exit(json_encode($res));
        }

        $info = array();
        $info['title'] = $title;
        $info['content'] = $content;
        $info['type'] = 4;
        $info['create_time'] = date('Y-m-d H:i:s');

        $addId = $this->Consultation_model->add($info);
        if (!empty($addId)) {
            $res['result'] = true;
        } else {
            $res['message'] = '发布失败，请重试';
        }

        exit(json_encode($res));
    }
}