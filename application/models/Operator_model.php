<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('operators');
		$this->set_id('operator_id');
	}
	
	public function getUserByName($admin_name){
		$cond = array(
			'operator_name' => $admin_name
		);
		$this->db->where($cond);
		$this->db->select();
		$query = $this->db->get($this->form_name);
        $result = $query->first_row('array');
		return $result;
	}
}
