<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TokenCache_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('token_cache');
		$this->set_id('id');
	}
}
