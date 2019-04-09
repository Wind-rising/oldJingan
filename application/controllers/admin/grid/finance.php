<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends BaseController{
    protected $_export_fileds = array(
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Finance_model");
    }

    /**
     * 支付
     * @return void
     */
    public function index(){
        $this->db->where('transaction_id<>', '');
        $this->db->join('pay_log', 'pay_log.order_id = order.order_id', 'left');
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'order'));
        $result = $this->grid->to_array();

        $fileds = $this->_export_fileds;
        $filename = '支付列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
    
    /**
     * 退款
     * @return void
     */
    public function refund(){
        $this->db->join('order_refund', 'order_refund.order_id = order.order_id', 'right');
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'order'));
        $result = $this->grid->to_array();
        
        $fileds = $this->_export_fileds;
        $filename = '退款列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
    
    /**
     * 提现
     * @return void
     */
    public function withdraw(){
        $this->db->where('target_type', '提现');
        $this->db->select('point_log.point_log_id as point_log_id,point_log.amount as amount,point_log.status as status,point_log.create_time as create_time, user.amount as amountlast, user.full_name as full_name, user.mobile as mobile, user.type as type');
        $this->db->join('user', 'user.user_id = point_log.user_id', 'right');
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'point_log'));
        $result = $this->grid->to_array();
        
        $fileds = $this->_export_fileds;
        $filename = '提现列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }
    


}
