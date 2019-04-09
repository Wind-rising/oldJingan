<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Point extends BaseController{

	protected $_export_fileds = array(
    );

	function __construct(){
		parent::__construct();
		$this->load->model("Point_model");
	}

	public function index(){
		$this->Point_model->dbselect('chelaba_slave');
        $this->db = $this->Point_model->db;
		
		$this->db->where('1', '1');
		$this->db->order_by('point desc');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'point'));
		$result = $this->grid->to_array();
        $fileds = $this->_export_fileds;
        $filename = '积分列表' . date('Y-m-d H:i:s');
		foreach($result['rows'] as &$data){
			$data->contact = htmlspecialchars($data->contact);
		}
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
	}
}
