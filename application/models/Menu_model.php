<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends MY_Model{

	    /**
     * 左值字段名称
     *
     * @var string
     */
    public $left_column = 'lft';

    /**
     * 右值字段名称
     *
     * @var string
     */
    public $right_column = 'rgt';

    /**
     * 层级字段名称
     *
     * @var string
     */
    public $level_column = 'level';

    /**
     * 域字段名称
     *
     * @var null
     */
    public $scope_column = 'scope';

    /**
     * 父分类ID字段名称
     *
     * @var string
     */
    public $pid_column = 'parent_id';

    /**
     * @access protected
     * @var string mptt view folder.
     */
    protected $_directory = 'mptt';

    /**
     * @access protected
     * @var string default view folder.
     */
    protected $_style = 'default';

	function __construct(){
		parent::__construct();
		$this->set_table('menus');
		$this->set_id('id');
	}

	 /**
     * 获取当前节点的子节点
     *
     * @access public
     * @param bool $self 是否包含自身
     * @param string $direction direction to order the left column by.
     * @return MPTT
     */
    public function descendants($menu,$self = FALSE, $direction = 'ASC', $direct_children_only = FALSE, $leaves_only = FALSE, $limit = FALSE)
    {
		$this->form_name = 'menus';
        $left_operator = $self ? '>=' : '>';
        $right_operator = $self ? '<=' : '<';

        $this->db->where($this->left_column.$left_operator, $menu['lft'])
            ->where($this->right_column.$right_operator, $menu['rgt'])
            ->where($this->scope_column, $menu['scope'])
            ->order_by($this->left_column, $direction);

        if ($direct_children_only) {
            if ($self) {
            $this->db->where($this->level_column, $menu['level'])
                    ->or_where($this->level_column, $menu['level']+ 1);
            } else {
                $this->db->where($this->level_column, $menu['level'] + 1);
            }
        }

        if ($leaves_only) {
            $this->db->where($this->right_column, ($menu['lft']+ 1));
        }
        $this->db->where('active','Y');
        if ($limit == 1) {
            $this->db->limit($limit);
			$children = $this->find();
        } else {
            $children = $this->findAll();
        }
		//echo $this->db->last_query();exit;
        return $children;
    }


	 /**
     * Insert the object
     *
     * @param MPTT|mixed $target target node primary key value or MPTT object.
     * @param string $copy_left_from target object property to take new left value from
     * @param integer $left_offset offset for left value
     * @param integer $level_offset offset for level value
     * @access protected
     * @return MPTT
     * @throws Validation_Exception
     */

    public function insert_as_last_child($target, $copy_left_from, $left_offset, $level_offset,$menu)
    {

        // Insert should only work on new nodes.. if its already it the tree it needs to be moved!
        $this->db->where('id', $target);
        $target = $this->find();
		$arr = $menu;
        $arr['lft'] = $target['rgt'] + $left_offset;
        $arr['rgt'] = $arr['lft'] + 1;
        $arr['level'] = $target['level'] + $level_offset;
        $arr['scope'] = $target['scope'];
        $arr['parent_id'] = $target['id'];
		$arr['lang_key'] = $menu['name'];
		$arr['action'] = !empty($arr['action'])?$arr['action']:'index';
        $this->create_space($arr['lft'],$arr['scope'],$arr['rgt']);
        $flag = $this->add($arr);
        return $flag;
    }

	    /**
     * Create a gap in the tree to make room for a new node
     *
     * @access private
     * @param integer $start start position.
     * @param integer $size the size of the gap (default is 2).
     */
    private function create_space($start,$scope_column,$right_column, $size = 2)
    {
        // Update the left values, then the right.
		$where = $this->left_column.'>='.$start;
		$where .= ' and '.$this->scope_column.'='.$scope_column;
		$sql = "update $this->form_name set lft=lft+ '$size' where $where";
		$this->db->query($sql);

	    $where = $this->right_column.'>='.$start;
		$where .= ' and '.$this->scope_column.'='.$scope_column;
		$sql = "update $this->form_name set rgt=rgt+ '$size' where $where";
		$this->db->query($sql);
    }

	 /**
     * 移动节点
     *
     * @param MPTT|integer $target 目标节点的ID或者对象
     * @param bool $left_column 目标使用左列或右列
     * @param integer $left_offset 新节点左节点的位置
     * @param integer $level_offset 节点的级别
     * @param bool $allow_root_target 让这个动作可以在根节点
     *
     * @example
     * <pre>
     *                   1[ 1 ]22                                                             1[ 12 ]6
     *                      |                                                                    |
     *     -----------------------------------                                            -------------
     *     |          |          |           |                                            |            |
     *  2[ 2 ]3    4[ 3 ]7    8[ 5 ]9    10[ 6 ]21                                     2[ 13 ]3    4[ 14 ]5
     *                |                      |
     *             5[ 4 ]6      ---------------------------            => 8->13
     *                          |            |            |
     *                      11[ 7 ]12    13[ 8 ]18    19[ 11 ]20
     *                                       |
     *                                 --------------
     *                                 |            |
     *                             14[ 9 ]15    16[ 10 ]17
     *
     *
     *
     *                   1[ 1 ]16                                                      1[ 12 ]12
     *                      |                                                              |
     *     -----------------------------------                                 ----------------------
     *     |          |          |           |                                 |           |         |
     *  2[ 2 ]3    4[ 3 ]7    8[ 5 ]9    10[ 6 ]15                         2[ 13 ]3    4[ 8 ]9  10[ 14 ]11
     *                |                      |                                             |
     *             5[ 4 ]6             --------------        => 8->13               --------------
     *                                 |            |                               |             |
     *                             11[ 7 ]12    13[ 11 ]14                       5[ 9 ]6      7[ 10 ]8
     * this = 8
     * target =12
     * move(13,FALSE,1,0,FALSE);
     * </pre>
     */
    public function move($target,$id, $left_column, $left_offset, $level_offset, $allow_root_target)
    {

        // Catch any database or other excpetions and unlock
        try {
			$target_array = $this->get_data(array('form_name' => 'menus', 'where' => array('id' => $target)));
			$array = $this->get_data(array('form_name' => 'menus', 'where' => array('id' => $id)));

            //如果不在同级移动则要更新PID
            if ($level_offset > 0) {
                $parent_id = $target_array['0']['id'];
				$this->save(array('id' => $id), array('parent_id'=>$parent_id));
            } else {
                $parent_id = $target_array['0']['parent_id'];
                $this->save(array('id' => $id), array('parent_id'=>$parent_id));
            }

            $left_offset = ($left_column === TRUE
                    ? $target_array['0']['lft']
                    : $target_array['0']['rgt']) + $left_offset;

            $level_offset = $target_array['0']['level']
                - $array[0]['level'];
                + $level_offset;

            $size = ($array[0]['rgt'] - $array[0]['lft']) + 1;
            $scope = $array[0]['scope'];
            if ($target_array['0']['scope'] == $array[0]['scope']) {
                $this->create_space($left_offset,$array[0]['scope'],$array[0]['rgt'], $size);

                $offset = ($left_offset - $array[0]['lft']);
                $where = $this->left_column.'>='.$array[0]['lft'];
				$where .= ' and '.$this->right_column.'<='.$array[0]['rgt'];
				$where .= ' and '.$this->scope_column.'='.$array[0]['scope'];
		        $sql = "update $this->form_name set lft=lft+ '$offset',rgt=rgt+ '$offset',level=level+ '$level_offset',scope='$scope' where $where";

				$this->db->query($sql);
                $this->delete_space($array[0]['lft'],$array[0]['rgt'],$array[0]['scope'], $size);
            } else {

                $target->create_space($left_offset,$array[0]['scope'],$array[0]['rgt'], $size);

                $offset = ($left_offset - $array[0]['lft']);

                $where = $this->left_column.'>='.$array[0]['lft'];
				$where .= ' and '.$this->right_column.'<='.$array[0]['rgt'];
				$where .= ' and '.$this->scope_column.'='.$array[0]['scope'];
		        $sql = "update $this->form_name set lft=lft+ '$offset',rgt=rgt+ '$offset',level=level+ '$level_offset',scope='$scope' where $where";
				$this->db->query($sql);

                $this->delete_space($array[0]['lft'],$array[0]['rgt'],$array[0]['scope'], $size);
            }
        } catch (Exception $e) {
            //Unlock table and re-throw exception
            throw $e;
        }

    }

	    /**
     * Closes a gap in a tree. Mainly used after a node has
     * been removed.
     *
     * @access private
     * @param integer $start start position.
     * @param integer $size the size of the gap (default is 2).
     */
    private function delete_space($start,$right_column,$scope_column, $size = 2)
    {

		$where = $this->left_column.'>='.$start;
		$where .= ' and '.$this->scope_column.'='.$scope_column;
		$sql = "update $this->form_name set lft=lft- '$size' where $where";
		$this->db->query($sql);

	    $where = $this->right_column.'>='.$start;
		$where .= ' and '.$this->scope_column.'='.$scope_column;
		$sql = "update $this->form_name set rgt=rgt- '$size' where $where";
		$this->db->query($sql);
    }



}
