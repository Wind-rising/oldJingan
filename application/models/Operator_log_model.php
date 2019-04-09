<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator_log_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('operator_log_new');
		$this->set_id('id');
	}


}
