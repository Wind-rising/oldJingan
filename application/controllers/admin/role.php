<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends BaseController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("Role_model");
		$this->load->model("Menu_model");
		$this->load->model("Role_menu_model");
	}

	/**
	 * 角色管理
	 * @return void
	 */
	public function index()
	{
		$viewData['_page_name'] = '角色管理';
		$viewData['_page_detail'] = '';
		$this->view('role/index.php', $viewData);
	}

	 /**
     *角色添加
     */
    public function add()
    {
		$viewData['_left_parent_page_name'] = '角色添加';
		$viewData['_page_name'] = '角色添加';
		$viewData['_page_detail'] = '';
		if ($_POST) {
			$menus = $this->_get_checked_menus();
			$rule = $this->Role_model->create($_POST);
			$flag = $this->Role_model->add($rule);
            $this->Role_menu_model->update_menus($menus,$flag);
            $this->remind->set("index.php/role/", "保存成功", "success");
		}
		$menu_root_arr = $this->Menu_model->get_data(array('form_name' => 'menus', 'where' => array('parent_id' => 0, 'active' => 'Y')));
		$all_menu = $this->Menu_model->descendants($menu_root_arr[0]);
		$items_arr = array();
		$menu_root_arr['pId'] = $menu_root_arr[0]['parent_id'];
		foreach ($all_menu as $key => $item) {
			$item['pId'] = $item['parent_id'];
			$items_arr[] = $item;
		}
		$viewData['items_arr'] = $items_arr;
		$viewData['menu_root_arr'] = $menu_root_arr;
		$this->view('role/form.php', $viewData);

    }

     /**
     *角色修改
     */
    public function edit()
    {
		$viewData['_left_parent_page_name'] = '角色修改';
		$viewData['_page_name'] = '角色修改';
		$viewData['_page_detail'] = '';
		$role_id = $this->input->get('id');
		if ($_POST) {
			$menus = $this->_get_checked_menus();
			$rule = $this->Role_model->create($_POST);
            $flag = $this->Role_model->save(array('id' => $role_id),$rule);
            $this->Role_menu_model->update_menus($menus,$role_id);
            $this->remind->set("index.php/role/", "保存成功", "success");
		}

        //权限列表
        $items_arr = $this->_get_menus_tree($role_id);
        $role = $this->Role_model->get_data(array('form_name' => 'roles', 'where' => array('id' => $role_id, 'active' => 'Y')));
		$viewData['items_arr'] = $items_arr;
		$viewData['role'] = $role;
		$this->view('role/form.php', $viewData);

    }

	 /**
     *查询具体用户
     */
    public function users()
    {
		$viewData['_left_parent_page_name'] = '具体角色用户';
		$viewData['_page_name'] = '具体角色用户';
		$viewData['_page_detail'] = '';
		$viewData['id'] = $this->input->get('id');
		$this->view('role/users.php', $viewData);

    }

	 /**
     * 获取POST提交的权限
     * @return mixed
     */
    private function _get_checked_menus()
    {
        $menus = $this->input->post('menus');
        $menus_arr = json_decode($menus, true);
        foreach ($menus_arr as $key => $data) {

            if ($data['checked'] == false) {
                unset($menus_arr[$key]);
            }
        }

        return $menus_arr;
    }

	 /**
     * 获取权限树
     * @return array
     */
    private function _get_menus_tree($role_id)
    {
  		$menu_root_arr = $this->Menu_model->get_data(array('form_name' => 'menus', 'where' => array('parent_id' => 0, 'active' => 'Y')));
		$role_arr = $this->Menu_model->get_data(array('form_name' => 'roles_menus', 'where' => array('role_id' => $role_id)));
		foreach ($role_arr as $key => $value) {
            $current_menus[$value['menu_id']] = $value['menu_id'];
		}

		$all_menu = $this->Menu_model->descendants($menu_root_arr[0]);
		$items_arr = array();
		$menu_root_arr['pId'] = $menu_root_arr[0]['parent_id'];

		foreach ($all_menu as $key => $item) {
			$item['pId'] = $item['parent_id'];
			if (isset($current_menus[$item['id']])) {
                $item['checked'] = true;
            } else {
                $item['checked'] = false;
            }
			$items_arr[] = $item;
		}


        return $items_arr;
    }


}