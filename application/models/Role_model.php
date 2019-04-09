<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('roles');
		$this->set_id('id');
	}
	



}
