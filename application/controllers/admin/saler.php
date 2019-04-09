<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saler extends BaseController{
    function __construct(){
        parent::__construct();
        $this->load->model("User_model");
    }
    
    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $viewData['_left_parent_page_name'] = '销售顾问管理';
        $viewData['_page_name'] = '销售顾问管理';
        $viewData['_page_detail'] = '';
        $this->view('saler/index.php', $viewData);
    }
    
    /**
     * 审核
     */
    public function checkUser()
    {
    
        $user_id = $this->input->post('user_id');
        $tag = $this->input->post('tag');
      
        if($tag==1){
            $return = $this->User_model->softDeleteById($user_id,$arr=array("issaler" => 1),'user_id');
        }elseif($tag==2){
            
            $return = $this->User_model->softDeleteById($user_id,$arr=array("issaler" => 0),'user_id');
        }
        if($return == 1) {
    
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '审核成功';
        }
        exit(json_encode($return_struct));
    }
    
    /**
     * 后台用户列表修改
     * @return void
     */
    public function edit()
    {
        $viewData['_left_parent_page_name'] = '修改后台用户';
        $viewData['_page_name'] = '修改后台用户';
        $viewData['_page_detail'] = '';
        $operator_id = $this->input->get("id");
        //查询权限组
        $viewData['operator'] = $this->Operator_model->get_data(array('form_name' => 'operators','where'=> array('operator_id'=>$operator_id,'status'=>1)));
        $viewData['operator'] = $this->Operator_model->get_data(array('form_name' => 'operators','where'=> array('operator_id'=>$operator_id,'status'=>1)));
        if($_POST){
            if(empty($_POST['password'])){
                unset($_POST['password']);
            }else{
                $_POST['password'] = md5($_POST['password']);
            }
            
            $operator = $this->Operator_model->create($_POST);
            
            //判断不能为空
            if(empty($operator['operator_id']) || empty($operator['name']) || empty($operator['email'])){
                $this->remind->set("index.php/operator/", "修改失败，请联系管理员","error");
            }
            
            $flag = $this->Operator_model->save(array('operator_id'=>$operator['operator_id']), $operator);
            if($flag){
                $this->remind->set("index.php/operator/", "修改成功","success");
            }else{
                $this->remind->set("index.php/operator/", "修改失败，请联系管理员","error");
            }
        }
        $this->view('operator/form.php', $viewData);
        
    }
    
    /**
     * 后台用户列表修改
     * @return void
     */
    public function changepassword()
    {
        $viewData['_left_parent_page_name'] = '修改密码';
        $viewData['_page_name'] = '修改密码';
        $viewData['_page_detail'] = '';
        $operator_id = $this->input->post("id");
        $viewData['operator'] = $this->Operator_model->get_data(array('form_name' => 'operators','where'=> array('operator_id'=>$operator_id,'status'=>1)));
        if($_POST){
            if(empty($_POST['password'])){
                unset($_POST['password']);
            }else{
                $_POST['password'] = md5($_POST['password']);
            }
            $operator = $this->Operator_model->create($_POST);
            //判断不能为空
            if(empty($operator['operator_id'])){
                $this->remind->set("index.php/", "修改失败，请联系管理员","error");
            }
            $flag = $this->Operator_model->save(array('operator_id'=>$operator['operator_id']), $operator);
            if($flag){
                $this->remind->set("index.php/", "修改成功","success");
            }else{
                $this->remind->set("index.php/", "修改失败，请联系管理员","error");
            }
        }
        $this->view('operator/changepassword.php', $viewData);
        
    }
    
    /**
     * 后台用户列表修改
     * @return void
     */
    public function add()
    {
        $viewData['_left_parent_page_name'] = '添加后台用户';
        $viewData['_page_name'] = '添加后台用户';
        $viewData['_page_detail'] = '';
        
        if($_POST){
            if(empty($_POST['password'])){
                unset($_POST['password']);
            }else{
                $_POST['password'] = md5($_POST['password']);
            }
            
            $operator = $this->Operator_model->create($_POST);
            //判断不能为空
            if(empty($operator['operator_name']) || empty($operator['name']) || empty($operator['email'])){
                $this->remind->set("index.php/operator/", "修改失败，请联系管理员!","error");
            }
            //判断不能重新登录名
            $is_operator = $this->Operator_model->get_data(array('form_name' => 'operators','where'=> array('operator_name'=>$operator['operator_name'],'status'=>1)));
            if(!empty($is_operator)){
                $this->remind->set(current_url(), "用户名重复，请重新添加","error");
            }
            unset($operator['operator_id']);
            $flag = $this->Operator_model->add($operator);
            
            if($flag){
                $this->remind->set("index.php/operator/", "修改成功","success");
            }else{
                $this->remind->set("index.php/operator/", "修改失败，请联系管理员","error");
            }
        }
        //查询权限组
        $viewData['roles'] = $this->Operator_model->get_data(array('form_name' => 'roles','where'=> array('active'=>'Y')));
        $this->view('operator/form.php', $viewData);
    }
}
