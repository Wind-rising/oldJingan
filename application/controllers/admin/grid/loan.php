<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends BaseController{

    protected $_export_fileds = array(
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Loan_model");
    }

    public function index(){
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'loan'));
        $result = $this->grid->to_array();
        foreach ($result['rows'] as $rk => &$rv) {
            $rv->id = $rv->loan_id;
        }
        $fileds = $this->_export_fileds;
        $filename = '金融产品' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));

    }

}
