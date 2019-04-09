<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * jqGrid 服务端
 *
 * @copyright Copyright (c) 2015, Chelaba inc.
 * @package Core
 * @since 2015-11-11 下午2:38
 * @author cxf
 * @version   $Id$
 */
class Grid
{
    /**
     * 排除不过滤的列
     * @var array
     */
    protected $_exclude_filter_fields = array();
    /**
     * @var array
     * data转换
     * $_data_exchanges = array('category_id'=>array(array('from'=>'0','to'=>''),));
     */
    protected $_data_exchanges = array();
    /**
     * 定义指定的列为日期型的，过滤的时候要特殊处理
     *
     * @var array
     */
    protected $_date_fields = array('creattime','date_upd','added_time','paid_time','refund_time','checked_time', 'date_add', 'created', 'updated', 'date', 'use_date', 'return_date','last_update_date','real_exchange_time');
    /**
     * 有些列需要通过having来查询
     *
     * @var array
     */
    protected $_having_fields = array();
    /**
     *  表名
     *
     * @var string
     */
    protected $_form_name = NULL;
    /**
     * 是否是导出模式
     *
     * @var bool
     */
    protected $_is_export = false;
    /**
     * 总记录数
     *
     * @var int
     */
    protected $_records = NULL;

    /**
     * 默认排序字段
     * @var string
     */
    protected $_default_sidx = 'id';
    /**
     * 查询对应的数据库配置
     *
     * @var string
     */
    protected $_db_config = '';
    /**
     * 查询的汇总
     *
     * @var string
     */
    protected $_groups = '';

	/**
     * 构造函数
     *
     * @param array $arr
     */
    public function __construct($arr)
    {

        $oper = !empty($_GET['oper'])?$_GET['oper']:"";
        if ($oper == 'excel') {
            $this->export(true);
        }

        $this->db = $arr['db'];
        $this->_form_name = $arr['form_name'];
        $this->filters = !empty($_GET['filters'])?$_GET['filters']:"";
    }

    /**
     * 数据库配置
     *
     * @param string $val
     */
    public function db_config($val = '')
    {
        $this->_db_config = $val;
        return $this;
    }

    /**
     * group by 的count取法不一样
     *
     * @param array $groups
     * @return $this
     */
    public function groups($groups = array()){
        $this->_groups = $groups;
        return $this;
    }

    /**
     * 关闭过滤
     *
     * @return bool
     */
    public function disable_filter()
    {
        $this->filters = '';
        return $this;
    }

    /**
     * 设定having查询的列
     *
     * @param array $fields
     * @return $this
     */
    public function having_fields($fields = array())
    {
        $this->_having_fields = $fields;
        return $this;
    }

    /**
     * 转换规则
     * @param array $arr
     */
    public function date_exchange($arr = array())
    {
        $this->_data_exchanges = $arr;
        return $this;
    }

    /**
     * 设定指定的列为时间型号，用于进行时间筛选
     *
     * @param $fields
     */
    public function date_fields_set($fields = array())
    {
        if (is_array($this->_date_fields) && count($this->_date_fields) > 0) {
            $this->_date_fields = array_merge($this->_date_fields, $fields);
        } else {
            $this->_date_fields = $fields;
        }
        return $this;
    }

    /**
     * 获取当期页号
     *
     * @return int
     */
    public function page()
    {
        return !empty($_GET['page'])?$_GET['page']: 1;
    }

    /**
     * 获取每页显示数量
     *
     * @return int
     */
    public function rows()
    {
        return !empty($_GET['rows'])?$_GET['rows']: 20;
    }

    /**
     * 设定是否有导出模式
     *
     * @return int
     */
    public function export($flag = false)
    {
        $this->_is_export = $flag;
    }

    /**
     * 获取是否有导出模式
     *
     * @return int
     */
    public function get_export()
    {
        return $this->_is_export;
    }

    /**
     * 获取总分页数
     *
     * @return int
     */
    public function total()
    {
        return ceil($this->records() / $this->rows());
    }

    /**
     * 获取总记录数
     *
     * @return int
     */
    public function records()
    {


        if (is_null($this->_records)) {

            //高级检索
            if (!empty($this->filters)) {
				$this->db_filters();
            }
			$this->dbnew->limit(0);
		    $this->_records = $this->dbnew->count_all_results($this->_form_name);
        }

        return $this->_records;
    }

    /**
     * 设定默认排序字段
     *
     * @return string
     */
    public function default_sidx($sidx = 'id')
    {
        $this->_default_sidx = $sidx;
        return $this;
    }

    /**
     * 获取排序字段名称
     *
     * @return string
     */
    public function sidx()
    {
		$sidx = !empty($_GET['sidx'])?$_GET['sidx']: "";
        if (empty($sidx)) {
            $sidx = $this->_default_sidx;
        }
        return $sidx;
    }

    /**
     * 获取排序方式
     *
     * @return string
     */
    public function sord()
    {
       return !empty($_GET['sord'])?$_GET['sord']: "DESC";
    }

    /**
     * 将结果集转换为数组
     *
     * @param array $related
     * @return array
     */
    public function to_array(array $related = array())
    {

        //高级检索

        if (!empty($this->filters)) {
			$this->db_filters();
            }
        if ($this->_is_export == false) {
            $this->db->limit($this->rows());
            if ($this->page() > 1) {
                $this->db->offset(($this->page() - 1) * $this->rows());
            }
        }

        $this->db->order_by($this->sidx(), $this->sord());

        $rows = array();
		$this->dbnew = clone $this->db;
        $query = $this->db->get($this->_form_name);
		foreach ($query->result() as $row)
		{
			$rows[] = $row;
		}

        return array(
            'total' => $this->total(),
            'page' => $this->page(),
            'records' => $this->records(),
            'rows' => $rows,
            'row' => $this->rows(),
        );
    }

    /**
     * 将结果集转换为 JSON
     *
     * array('detail')
     *
     * @return JSON
     */
    public function to_json(array $related = array())
    {
        return json_encode($this->to_array($related));
    }

    /**
     * 设置排除不过滤的列
     */
    public function exclude_filter_fields($fields = array())
    {
        $this->_exclude_filter_fields = $fields;
        return $this;
    }

    /**
     * @param Database_Query $model
     */
    public function db_filters($data = NULL)
    {
        return $this->_filters($data);
    }

    /**
     * @param ORM $model
     */
    public function filters($data = NULL)
    {
        return $this->_filters($data);
    }


    /**
     * 日期型的要特殊处理
     *
     * @param $model
     */
    public function _date_filter($rule, $group_op)
    {
        $date = $rule['data'];
        $date_arr = explode(' - ', $date);

        if (isset($date_arr[0]) && isset($date_arr[1]) && strtotime($date_arr[0]) && strtotime($date_arr[1])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($date_arr[0]));
            $end_date = date('Y-m-d 23:59:59', strtotime($date_arr[1]));

            if ($group_op == 'AND') {
                $this->db->where($rule['field']. '>=', $start_date);
                $this->db->where($rule['field']. '<=', $end_date);
            } else {
                $this->db->where($rule['field']. '>=', $start_date);
                $this->db->where($rule['field']. '<=', $end_date);
            }
        }
    }

    /**
     * 高级检索功能
     * @access public
     * @param ORM $model
     * @return ORM object
     * @author fanchongyuan
     * @example
     */
    protected function _filters($data = NULL)
    {
        if (!empty($data)) {
            $this->filters = $data;
        }
        if (!empty($this->filters)) {
            $filters = json_decode($this->filters, true);
            if (!empty($filters) && is_array($filters)) {
                $search_rules = !empty($filters['rules']) ? $filters['rules'] : null;
                $group_op = !empty($filters['groupOp']) ? strtoupper($filters['groupOp']) : 'AND';
                if ($group_op == 'OR') {
                    $where_op = 'or_where';
                } else {
                    $where_op = 'and_where';
                }
                if (!empty($search_rules)) {
                    $i = 1;
                    foreach ($search_rules as $rule) {
                        $rule_field = $rule['field'];
                        if ($len = strpos($rule_field, '.')) {
                            $rule_field = substr($rule_field, $len + 1);
                        }
                        $where = $i == 1 ? 'where' : $where_op;
                        if (in_array($rule['field'], $this->_having_fields)) {
                            $where = str_ireplace('where', 'having', $where);
                        }
                        if (!empty($rule['field']) && !empty($rule['op']) && !in_array($rule_field, $this->_exclude_filter_fields)) {
                            if (isset($rule['data'])) {

                                //对查询值做转换
                                if (isset($this->_data_exchanges[$rule_field]) && is_array($this->_data_exchanges[$rule_field])) {
                                    foreach ($this->_data_exchanges[$rule_field] as $key => $exchanges) {
                                        if (isset($exchanges['from']) && isset($exchanges['to'])) {
                                            if ($rule['data'] == $exchanges['from']) {
                                                $rule['data'] = $exchanges['to'];
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($rule['data'] !== '') {
                                //日期的特殊处理
                                if (in_array($rule_field, $this->_date_fields)) {

                                    $this->_date_filter($rule, $group_op);
                                    continue;
                                }

                                switch (strtolower($rule['op'])) {
                                    case 'bt':
                                        $this->_date_filter($rule, $group_op);
                                        break;
                                    case 'ne':
                                        $this->db->where($rule['field'].' !=', $rule['data']);
                                        break;
                                    case 'bw':
										$this->db->like($rule['field'],$rule['data'], 'after');
                                        break;
                                    case 'bn':
										$this->db->not_like($rule['field'],$rule['data'], 'after');
                                        break;
                                    case 'ew':
										$this->db->like($rule['field'],$rule['data'], 'before');
                                        break;
                                    case 'en':
                                        $this->db->not_like($rule['field'],$rule['data'], 'before');
                                        break;
                                    case 'cn':
                                        $this->db->like($rule['field'],$rule['data'], 'both');
                                        break;
                                    case 'nc':
                                        $this->db->not_like($rule['field'],$rule['data'], 'both');
                                        break;
                                    case 'lt':
                                        $this->db->where($rule['field']. '<', $rule['data']);
                                        break;
                                    case 'le':
                                        $this->db->where($rule['field']. '<=', $rule['data']);
                                        break;
                                    case 'gt':
                                        $this->db->where($rule['field']. '>', $rule['data']);
                                        break;
                                    case 'ge':
                                        $this->db->where($rule['field']. '>=', $rule['data']);
                                        break;
                                    default:
                                        $this->db->where($rule['field']. '=', $rule['data']);
                                        break;
                                }
                            }
                            $i++;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $fields
     * @param $items导出为excel
     */
    public function export2excel($fields, $items, $filename)
    {
	    //加载工厂类
		include 'vendor/PHPExcel/IOFactory.php';
		include 'vendor/PHPExcel/PHPExcel.php';
        set_time_limit(0);
        if ($this->_is_export == true) {
            //$filename = iconv('UTF-8', 'GB2312//IGNORE', $filename);
            $headArr = array_values($fields);
            //设定缓存模式为经gzip压缩后存入cache（还有多种方式请百度）
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize' => '256MB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            //创建新的PHPExcel对象
            $objPHPExcel = new PHPExcel();
            $objProps = $objPHPExcel->getProperties();
            //设置表头
            $key = ord("A");
            foreach ($headArr as $v) {
                $tmp_key = (int)(($key - 65) / 26);
                if ($tmp_key > 0) {
                    $field_name = chr($tmp_key + 64) . chr(($key - 65) % 26 + 65);
                } else {
                    $field_name = chr(($key - 65) % 26 + 65);
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($field_name . '1', $v);
                $key += 1;
            }
            $column = 2;
            $objActSheet = $objPHPExcel->getActiveSheet();
            foreach ($items as $key => $rows) { //行写入
                $span = ord("A");
                foreach ($fields as $keyName => $value) { // 列写入
                    $tmp_key = (int)(($span - 65) / 26);
                    if ($tmp_key > 0) {
                        $field_name = chr($tmp_key + 64) . chr(($span - 65) % 26 + 65);
                    } else {
                        $field_name = chr(($span - 65) % 26 + 65);
                    }

                    $objActSheet->setCellValueExplicit($field_name . $column, $rows->$keyName, PHPExcel_Cell_DataType::TYPE_STRING);
                    $span++;
                }
                $column++;
            }
            //重命名表
            $objPHPExcel->getActiveSheet()->setTitle('Report');
            //设置活动单指数到第一个表,所以Excel打开这是第一个表
            $objPHPExcel->setActiveSheetIndex(0);
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=\"$filename.xlsx\"");
            header("Cache-Control: max-age=0");
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save('php://output'); //文件通过浏览器下载
            exit();
        }
    }
}
