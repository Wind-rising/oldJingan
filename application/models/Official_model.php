<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Official_model extends MY_Model{
    function __construct(){
        parent::__construct();
        $this->set_table('official');
        $this->set_id('official_id');
    }

}
