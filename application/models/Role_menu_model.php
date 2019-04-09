<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_menu_model extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->set_table('roles_menus');
		$this->set_id('id');
	}
	
	 /**
     * 更新对应角色的操作权限
     *
     * @param $menus
     *
     * @throws Kohana_Exception
     */
    public function update_menus($menus,$roleid)
    {
        //删除id
		 $this->db->where('role_id', $roleid);
         $this->db->delete($this->form_name);
		 foreach ($menus as $menu){
			 $arr['role_id'] = $roleid;
			 $arr['menu_id'] = $menu['id'];
			 $flag = $this->addnolog($arr);
		 }

		 
    }


}
