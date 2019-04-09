<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Answer_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('answer');
		$this->set_id('id');
	}

    public function insert($info)
    {
        $this->db->insert('answer', $info);

        return $this->db->insert_id();
    }
}
