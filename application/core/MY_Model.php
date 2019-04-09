<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class MY_Model extends CI_Model
{
    protected $form_name; //定义表名字
    protected $id_name; //定义自增主键名字

    public function __construct()
    {
        parent::__construct();
		$this->load->database();
    }

    /**
     * @param Str $form_name  设置表名
     * @return true
     */
    public function set_table($form_name)
    {
        $this->form_name = $form_name;
    }

	/**
     * @param Str $form_name  根据主键获取一条数据
     * @return true
     */
	public function getById($id){
		$cond = array(
				'where' => array(
					$this->id_name => $id,
				)
		);
		$result = $this->get_data($cond);
		if(!empty($result)){
			$data = $result[0];
		}else{
			$data = array();
		}
		return $data;
	}

    public function getList($flag = false)
    {
        $cond = array();
        if ($flag) {
            $cond = array(
                'where' => array(
                    'status' => 1,
                )
            );
        }
        $result = $this->get_data($cond);
        if(!empty($result)){
            $data = $result;
        }else{
            $data = array();
        }
        return $data;
    }

	public function updateById($updateData, $id){
		$cond = array($this->id_name => $id);
		$this->save($cond, $updateData);
	}

	function getByIdList($idList){
		$cond =array('where_in'=>array(
			0 => $this->id_name,
			1 => empty($idList) ? array(null) : $idList
		));
		return $this->get_data($cond);
	}

    /**
     * @param Str $form_name  设置主键名 add by taodeyu
     * @return true
     */
    public function set_id($id_name)
    {
        $this->id_name = $id_name;
    }

	//根据主键删除 add by taodeyu
    public function deleteById($id)
    {
        $id = intval($id);
        if (is_numeric($id) && $id > 0) {
            $this->db->where($this->id_name, $id);
            $this->db->delete($this->form_name);
            if (($this->db->affected_rows()) >= 1) {
                $result = 1;      //如果删除成功，则返回1
            } else {
                $result = 0;    //如果删除失败，返回0
            }
        } else {
            $result = 0;
        }
        return $result;
	}

	//根据主键软删除 add by taodeyu
    public function softDeleteById($id,$arr=array("is_delete" => 1),$id_name='')
    {
        $id = intval($id);
        if (is_numeric($id) && $id > 0) {
            if(!empty($id_name)){
                $this->id_name = $id_name;
            }
            $this->db->where($this->id_name, $id);
            if ($return = $this->db->update($this->form_name, $arr )) {
                $result = 1; //软删除成功，返回1
				//记录日志
				//$this->form_name = 'operator_log_new';
				$arrlog['operator_name'] = $this->session->operator_name;
				$arrlog['operator_id'] = $this->session->operator_id;
				$arrlog['control'] = $this->uri->segment(1).'/'.$this->uri->segment(2);
				$arrlog['action'] = $this->uri->segment(3);
				$arrlog['target_id'] = $id;
				$arrlog['target_content'] = json_encode($arr);
				$arrlog['ip'] = getIP();
				$arrlog['addtime'] = date("Y-m-d H:i:s");
                $this->addlog($arrlog);
            } else {
                $result = 0; //软删除失败，返回0
            }
        } else {
            $result = 0;
        }
        return $result;
	}

    //批量插入
    function batchInsert($data){
        $this->db->insert_batch($this->form_name, $data);
    }

       /**
     * @param Str $form_name
     * @param Array $data
     * @return insert_id or 0
     */
    public function add($data) {
        if (empty($data) || !is_array($data)) {
            $result = 0;
        } else {
            $this->db->insert($this->form_name, $data);    //插入数据
            if (($this->db->affected_rows()) >= 1) {
                $result = $this->db->insert_id();      //如果插入成功，则返回插入的id
            } else {
                $result = 0;    //如果插入失败,返回0
            }
        }
        return $result;
    }

	       /**
     * @param Str $form_name
     * @param Array $data
     * @return insert_id or 0
     */
    public function addnolog($data) {
        if (empty($data) || !is_array($data)) {
            $result = 0;
        } else {
            $this->db->insert($this->form_name, $data);    //插入数据
            if (($this->db->affected_rows()) >= 1) {
                $result = $this->db->insert_id();      //如果插入成功，则返回插入的id
            } else {
                $result = 0;    //如果插入失败,返回0
            }
        }
        return $result;
    }

    /**
     * @param Array $data
     * @ int $id 大于0
     * @return 1 or 0
     */
    public function save($arr, $data) {
        if (!empty($data) && is_array($data) && !empty($arr) && is_array($arr)) {
            $this->db->where($arr);
            if ($return = $this->db->update($this->form_name, $data)) {
                $result = 1; //更新成功，返回1
            } else {
                $result = 0; //失败，返回0
            }
			//记录日志
			//$this->form_name = 'operator_log_new';
			$arrlog['operator_name'] = $this->session->operator_name;
			$arrlog['operator_id'] = $this->session->operator_id;
			$arrlog['control'] = $this->uri->segment(1);
			$arrlog['action'] = $this->uri->segment(2);
			foreach($arr as $k=>$v){
				$arrlog['target_id'] = $v;
			}
			$arrlog['target_content'] = json_encode($data);
			$arrlog['ip'] = getIP();
			$arrlog['addtime'] = date("Y-m-d H:i:s");
			$this->addlog($arrlog);
        } else {
            $result = 0;
        }
        return $result;
    }

	    public function savenolog($arr, $data) {
        if (!empty($data) && is_array($data) && !empty($arr) && is_array($arr)) {
            $this->db->where($arr);
            if ($return = $this->db->update($this->form_name, $data)) {
                $result = 1; //更新成功，返回1
            } else {
                $result = 0; //失败，返回0
            }
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     * 删除takeaway
     *
     * @param int $id
     * @return 0 or 1
     */
    public function del($id) {
        $id = intval($id);
        if (is_numeric($id) && $id > 0) {
            $this->db->where('id', $id);
            $this->db->delete($this->form_name);
            if (($this->db->affected_rows()) >= 1) {
                $result = 1;      //如果删除成功，则返回1
            } else {
                $result = 0;    //如果删除失败，返回0
            }
        } else {
            $result = 0;
        }
        return $result;
    }


     /**
     * 批量删除takeaway
     *
     * @param int $id
     * @return 0 or 1
     */
    public function del_mul($string,$name) {
        $tmp_arr = explode(',',$string);
        if ($tmp_arr) {
            $this->db->where_in($name, $tmp_arr);
            $this->db->delete($this->form_name);
            if (($this->db->affected_rows()) >= 1) {
                $result = 1;      //如果删除成功，则返回1
            } else {
                $result = 0;    //如果删除失败，返回0
            }
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     *
     * @return Array
     */
    public function findAll() {
        $list = array();
        $query = $this->db->get($this->form_name);
        $result = $query->result_array();
		if(empty($result)){
			return $list;
		}
        foreach ($result as $key1 => $val1) {
            foreach ($val1 as $key2 => $val2) {
                $row[$key2] = stripslashes($val2);
            }
            $list[] = $row;
            $row = array();
        }
        return $list;
    }

    /**
     *
     * @return Array
     */
    public function find() {
        $list = array();
        $query = $this->db->get($this->form_name);

        $result = $query->first_row('array');
		if(empty($result)){
			return $list;
		}
        foreach ($result as $key => $val) {
            $list[$key] = stripslashes($val);
        }
        return $list;
    }

    /**
     * 匹配表单上传数据
     * @param array $data
     * @return Array
     */
    public function create($data) {

        $list = array();
        if (!empty($data) && is_array($data)) {   //如果存在$_POST数据 则进行数据筛选
            foreach ($this->db->list_fields($this->form_name) as $field) {
                foreach ($data as $key => $val) {
                    if ($field == $key) {   //取出与数据库字段名相同的POST值
                        if (!get_magic_quotes_gpc())
                            $list[$key] = addslashes(strip_tags($val));
                        else
                            $list[$key] = strip_tags($val);
                    }
                }
            }
            return $list;
        }else {
            return false;
        }
    }

    //递归获取到所有数据
    public function getChildren($array, $pid = 0, &$tdata = array()) {
        if (!empty($array)) {
            foreach ($array as $value) {
                if ($value['pid'] == $pid) {
                    $tdata[] = $value;

                    //递归调用
                    $this->getChildren($array, $value['id'], $tdata);
                } elseif ($value['id'] == $pid) {
                    $tdata[] = $value;
                }
            }

            return $tdata;
        } else {
            return array();
        }
    }

	function get_one($data = array()){
		$mergeData = array(
			'offset' => 1,
			'limit' => 0
		);
		$data = array_merge($mergeData, $data);
		$result = $this->get_data($data,$con);
		if(empty($result)){
			return array();
		}else{
			return $result[0];
		}
	}

    /**
     * @param Array( 'form_name' = 表单名,'where'=where条件格式 array('id'=>$id, 'status'=>1);
     *
     *
     * @return Array
     */
    public function get_data($data = array()) {

        if (!empty($data['form_name']) AND isset($data['form_name']))
            $this->form_name = $data['form_name'];

        if (isset($data['select']) AND !empty($data['select'])) {
            $data['select_str'] = implode($data['select'],',');
            $this->db->select($data['select_str']);
        }
        if (isset($data['where']) AND !empty($data['where'])) {
            $this->db->where($data['where']);
        }
         if (isset($data['where_in']) AND !empty($data['where_in'])) {
            $this->db->where_in($data['where_in'][0],$data['where_in'][1]);
        }
        if(isset($data['order_by']) AND !empty($data['order_by']))
        {
             $this->db->order_by($data['order_by']);
        }
        if(isset($data['group_by']) AND !empty($data['group_by']))
        {
             $this->db->group_by($data['group_by']);
        }
        if (isset($data['offset']) && strlen($data['offset']) > 0 && is_numeric($data['offset']))
        {
            if ($data['offset'] > 0)
            {
                if (strlen($data['limit']) && is_numeric($data['limit']))
                {
                    ($data['limit'] > 0) ? $this->db->limit($data['offset'], $data['limit']) : $this->db->limit($data['offset']);
                }
                else
                {
                    $this->db->limit($data['offset']);
                }
            }
            else
            {
                if (strlen($data['limit']) && is_numeric($data['limit']))
                {
                    if ($data['limit'] > 0)
                        $this->db->limit($data['limit']);
                }
            }
        }elseif (isset($data['limit']) && strlen($data['limit']) > 0 && is_numeric($data['limit']))
        {
            if ($data['limit'] > 0)
                $this->db->limit($data['limit']);
        }

        $result = $this->findAll();

//echo $this->db->last_query();exit;
        return $result;
    }

	 /**
     * 生成系统菜单
     */
    public function build_menu()
    {
        if (false == $this->session->has_userdata('operator_name')) {
            return false;
        }
		   if(null == $this->uri->segment(2)){
			   $uri = 'index';
		   }else{
			   $uri = $this->uri->segment(2);
		   }

            $current_rt = $this->uri->segment(1) . '/' . $uri;

		//获取上级菜单
	    $parent_rt ="";
		$now_menu = $this->get_data(array('form_name' => 'menus', 'where' => array('controller' => $this->uri->segment(1),'action'=>$uri,'active'=>'Y')));
		if(!empty($now_menu)){
		$parent_menu = $this->get_data(array('form_name' => 'menus', 'where' => array('id' => $now_menu[0]['parent_id'],'active'=>'Y')));
		$parent_rt = $parent_menu[0]['controller'] . '/' .$parent_menu[0]['action'];
		}

        if ($this->session->operator_name=='admin') {
            $items_arr = $this->get_all_menus();
        } else {
            $items_arr = $this->get_menus($this->session->operator_name);
        }

        $items_tree_arr = $this->tree_array($items_arr,1);
        $menu_html = $this->renderAdminMenu($items_tree_arr, 0, $current_rt,$parent_rt);
		return $menu_html;
    }

	/**
     * 返回用户的权限
     *
     * @return mixed
     * @throws Cache_Exception
     */
    public function check_menus()
    {
		//根据权限查出菜单
	    $menu_items = $this->get_data(array('form_name' => 'roles_menus', 'where' => array('role_id' => $this->session->role_id)));
		foreach ($menu_items as $key => $value) {
			$menus[$value['menu_id']] = $value['menu_id'];
		}
		
		if(null == $this->uri->segment(1) and null == $this->uri->segment(2)){
			return TRUE;exit;
		}
		$firsturl = (null == $this->uri->segment(1))?'index':$this->uri->segment(1);
		$secondurl = (null == $this->uri->segment(2))?'index':$this->uri->segment(2);
		$menudetail = $this->get_data(array('form_name' => 'menus', 'where' => array('controller' =>$firsturl,'action'=>$secondurl,'active'=>'Y','is_verify'=>'Y' )));

		if($this->session->operator_name=='admin'){
			return TRUE;
		}else{
			return (empty($menudetail[0]) or !empty($menus[$menudetail[0]['id']]))?TRUE:FALSE;
		}

    }

	 /**
     * 获取用户菜单
     *
     * @return mixed
     * @throws Cache_Exception
     */
    public function get_menus()
    {
		//根据权限查出菜单
	    $menu_items = $this->get_data(array('form_name' => 'roles_menus', 'where' => array('role_id' => $this->session->role_id)));
		foreach ($menu_items as $key => $value) {
			$menus[] = $value['menu_id'];
		}

		$items = $this->get_data(array('form_name' => 'menus', 'where' => array('active' => 'Y','is_menu' => 'Y'),'where_in'=>array('id',$menus),'order_by'=>'lft asc'));

		$items_arr = array();
            foreach ($items as $key => $item) {
                $tmp_arr = $item;
				if($tmp_arr['id']!=1){
					$items_arr[$tmp_arr['id']] = $tmp_arr;
				}
            }
            $data = $items_arr;
        return $data;
    }

	/**
     * 获取全部菜单
     *
     * @return array|mixed
     * @throws Cache_Exception
     */
    public function get_all_menus()
    {
		if($this->session->operator_name=='admin'){
			$items = $this->get_data(array('form_name' => 'menus', 'where' => array('active' => 'Y','is_menu' => 'Y'),'order_by'=>'id asc'));
		}else{
			$items = $this->get_data(array('form_name' => 'menus', 'where' => array('active' => 'Y','is_menu' => 'Y','id !='=>'272'),'order_by'=>'id asc'));
		}
		$items_arr = array();
            foreach ($items as $key => $item) {
                $tmp_arr = $item;
				if($tmp_arr['id']!=1){
					$items_arr[$tmp_arr['id']] = $tmp_arr;
				}
            }
            $data = $items_arr;
        return $data;
    }

	 /**
     * 生成结构为分类树
     *
     * @param     $arr
     * @param int $myid
     *
     * @return array
     */
    public function tree_array($items_arr,$myid = 0)
    {

        $newarr = array();
        $tmparr = array();
        if (is_array($items_arr)) {
            foreach ($items_arr as $key => $a) {
                if ($a['parent_id'] == $myid) {
                    $tmparr[$a['id']] = $a;
                }
            }
        }

        if (!empty($tmparr) && is_array($tmparr)) {
            foreach ($tmparr as $key => $a) {
                $a['children'] = $this->tree_array($items_arr,$a['id']);
                $newarr[] = $a;
            }
        }

        return $newarr;
    }

	/**
     * @param $menu
     * @param int $level
     * @param string $current_rt
     * @return string
     */
    public static function renderAdminMenu($menu, $level = 0, $current_rt = '', $parent_rt = '')
    {

        $result = '';
        if ($level) {
            $result .= "<ul class=\"sub-menu children child$level\">\r\n";
        }
        foreach ($menu as $item) {
            if (!empty($item['addition'])) {
                $url = $item['controller'] . '/' . $item['action'] . '?' . $item['addition'];
            }else {
                $url = $item['controller'] . '/' . $item['action'];
            }
            $id = (empty($item['id']) ? '' : ' id="menu_' . $item['id'] . '" '); // li ID
            $class = $level != 0 ? empty($item['children']) ? '' : ' class="parent" ' : ' class="top" '; //a class
            if (empty($item['children'])) {
                $href = 'href="'.app_url(). $url . '""';
            } else {
                $href = 'href="javascript:;"';
            }
            $onclick = empty($item['onclick']) ? '' : ' onclick="' . $item['onclick'] . '" '; //a href

            $child_class = "level$level ";
            if (!empty($item['children'])) {
                $child_class .= 'nav-parent ';
            }
            if (empty($item['action'])) {
                //通配符匹配
                $rt = $item['controller'] . '/*';

                if (self::compare_by_wildcard($rt, $current_rt) or self::compare_by_wildcard($rt, $parent_rt)) {
                    $child_class .= 'active  ';
                }
            } else {
                $rt = $item['controller'] . '/' . $item['action'];
                if (self::compare_by_wildcard($rt, $current_rt) or self::compare_by_wildcard($rt, $parent_rt)) {
                    $child_class .= 'active ';
                }
            }
            if ($child_class) {
                $child_class = ' class="' . $child_class . '"';
            }

            $result .= '<li' . $id . $child_class . '>';
            $result .= '<a ' . $class . $href . $onclick . '>';

            if (!empty($item['icon_class'])) {
                $result .= '<i class="fa ' . $item['icon_class'] . '"></i> ';
            } else {
                $result .= '<i class="fa fa-caret-right"></i> ';
            }
            $result .= '<span class="menu_text title">' . $item['name'];
            if (empty($item['children'])) {
                $result .= '</span></a>';
            } else {
                $result .= '</span><span class="arrow"></span></a>';
            }
            //if children build inner clild trees
            if (!empty($item['children'])) {
                $result .= "\r\n" . self::renderAdminMenu($item['children'], $level + 1, $current_rt);
            }
            $result .= "</li>\r\n";
        }
        if ($level) {
            $result .= "</ul>\r\n";
        }

        return $result;
    }

	    /**
     * 通配符比较
     *
     * @param $source
     * @param $target
     *
     * @return bool
     * <?php
     * $a = 'http://www.qinbin.me/forum/viewthread.php*';
     * $b = str_replace('*', '===x===', $a);
     * $c = preg_quote($b);
     * $d = str_replace('/', '\/', $c);
     * $e = '/' . str_replace('===x===', '.+', $d) . '/is';
     * preg_match($e, 'http://www.qinbin.me/forum/viewthread.php?tid=12345', $m);
     * print_r($m);
     * ?>
     */
    public static function compare_by_wildcard($source, $target)
    {
        $b = str_replace('*', '@@x@@', $source);
        $c = preg_quote($b);
        $d = str_replace('/', '\/', $c);
        $e = '/^' . str_replace('@@x@@', '.+', $d) . '/is';
        if (preg_match($e, $target, $m)) {
            return true;
        } else {
            return false;
        }
    }

	 /**
     * @param Str $form_name
     * @param Array $data
     * @return insert_id or 0
     */
    public function addlog($data) {
        if (empty($data) || !is_array($data)) {
            $result = 0;
        } else {
            $this->db->insert("operator_log_new", $data);    //插入数据
            if (($this->db->affected_rows()) >= 1) {
                $result = $this->db->insert_id();      //如果插入成功，则返回插入的id
				//记录日志

            } else {
                $result = 0;    //如果插入失败,返回0
            }
        }
        return $result;
    }

}
?>
