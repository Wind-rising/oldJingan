<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommunityBanner extends BaseController{

	protected $_export_fileds = array(
       
    );

	function __construct(){
		parent::__construct();
		$this->load->model("Community_banner_model");
	}

	public function index(){
        $this->db = $this->Community_banner_model->db;
		
		$this->db->where('is_delete', '0');
		$this->load->library('grid',array('db'=>$this->db,'form_name'=>'community_banner'));
		$result = $this->grid->to_array();
	
        $fileds = $this->_export_fileds;
        $filename = '论坛首页banner图' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
		exit(json_encode($result));
	}
}
