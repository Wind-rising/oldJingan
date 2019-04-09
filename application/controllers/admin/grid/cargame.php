<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargame extends BaseController{
    protected $_export_fileds = array(
        'activity_id' => 'ID',
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Car_game_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'car_game'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $rv->id = $rv->activity_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '赛车游戏管理表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
}