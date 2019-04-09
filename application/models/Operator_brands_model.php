<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator_brands_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('operators_brands');
		$this->set_id('id');
	}
	
	 /**
     * 更新对应用户的品牌
     *
     * @param $menus
     *
     * @throws Kohana_Exception
     */
    public function update_brands($brands,$operatorid)
    {
        //删除id
		 $this->db->where('operator_id', $operatorid);
         $this->db->delete($this->form_name);
		 foreach ($brands as $brand){
			 $arr['operator_id'] = $operatorid;
			 $arr['brand_id'] = $brand;
			 $flag = $this->addnolog($arr);
		 }
    }
}
