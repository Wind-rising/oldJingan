<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends BaseController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("Menu_model");
	}

	/**
	 * 后台用户列表
	 * @return void
	 */
	public function index()
	{
		$viewData['_left_parent_page_name'] = '后台用户管理';
		$viewData['_page_name'] = '后台用户管理';
		$viewData['_page_detail'] = '';
		$menu_root_arr = $this->Menu_model->get_data(array('form_name' => 'menus', 'where' => array('parent_id' => 0, 'active' => 'Y')));
		$all_menu = $this->Menu_model->descendants($menu_root_arr[0]);
		$items_arr = array();
		$menu_root_arr['pId'] = $menu_root_arr[0]['parent_id'];
		foreach ($all_menu as $key => $item) {
			$item['pId'] = $item['parent_id'];
			$items_arr[] = $item;
		}
		$viewData['items_arr'] = json_encode($items_arr);
		$viewData['menu_root_arr'] = $menu_root_arr;
		$this->view('menu/index.php', $viewData);
	}

	/**
	 * 后台用户列表修改
	 * @return void
	 */
	public function get()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$menu = $this->Menu_model->get_data(array('form_name' => 'menus', 'where' => array('id' => $id, 'active' => 'Y')));
			$return_struct['content'] = $menu[0];
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			exit(json_encode($return_struct));
		} else {
			throw new HTTP_Exception_404('404 Not Found.');
		}
	}

	    /**
     * 移动节点
     * @return void
     */
    public function move()
    {

            $id = intval($this->input->get('id'));
            $target = intval($this->input->get('target'));
            $type = $this->input->get('type');

            if ($type == 'prev') {
                $this->Menu_model->move($target,$id, TRUE, 0, 0, FALSE);
            } elseif ($type == 'next') {
                $this->Menu_model->move($target,$id, FALSE, 1, 0, FALSE);
            } elseif ($type == 'inner') {
				$this->Menu_model->move($target,$id, TRUE, 1, 1, TRUE);
            }
            if ($this->input->is_ajax_request()) {
                $return_struct['status'] = 1;
                $return_struct['code'] = 200;
                $return_struct['msg'] = '修改成功！';
                exit(json_encode($return_struct));
            }
    }



	 /**
     * 菜单项添加
     */
    public function itemupdate()
    {

		if ($this->input->is_ajax_request()) {
			$current_id = $this->input->post('current_id');
			$target = $this->input->post('target');
			$menu = $this->Menu_model->create($_POST);
			//判断不能为空
			if (empty($menu['is_verify']) || empty($menu['name'])) {
				$this->remind->set("index.php/menu/", "修改失败，请联系管理员", "error");
			}

			if ($current_id > 0) {
				$menu['lang_key'] = $menu['name'];
                $flag = $this->Menu_model->save(array('id' => $current_id), $menu);
            } else {
                $flag = $this->Menu_model->insert_as_last_child($target,'rgt', 0, 1,$menu);
            }

			if($flag & $current_id > 0){
			  $return_struct['content'] = $menu;
			  $return_struct['status'] = 2;
			  $return_struct['code'] = 200;
			}else{
		      $return_struct['content'] = $menu;
			  $return_struct['status'] = 1;
			  $return_struct['code'] = 200;
			}
			exit(json_encode($return_struct));
		} else {
			throw new HTTP_Exception_404('404 Not Found.');
		}
    }

	 /**
     * 删除
     * @return void
     */
    public function delete()
    {
        try {
            $id = $this->input->get('id');
			$menu = $this->Menu_model->get_data(array('form_name' => 'menus', 'where' => array('id' => $id, 'active' => 'Y')));
			if (($menu[0]['rgt'] - $menu[0]['lft']) > 1) {
				$children = $this->Menu_model->descendants($menu[0]);
                if ($children) {
                    foreach ($children as $key => $child) {
                        $this->Menu_model->save(array('id' => $child['id']), array('active'=>'N'));
                    }
                }
            }
			$this->Menu_model->save(array('id' => $menu[0]['id']), array('active'=>'N'));
            if ($this->input->is_ajax_request()) {
                $return_struct['status'] = 1;
                $return_struct['code'] = 200;
                exit(json_encode($return_struct));
            }
        } catch (Exception_App $ex) {
            $this->_ex($ex, $return_struct);
        }
    }
}