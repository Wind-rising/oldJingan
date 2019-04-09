<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends BaseController{
    protected $_export_fileds = array(
    );

    function __construct(){
        parent::__construct();
        $this->load->model("Article_model");
    }

    /**
     * 后台用户列表
     * @return void
     */
    public function index(){
        $this->db->where('is_delete','0');
        $this->load->library('grid',array('db'=>$this->db,'form_name'=>'articles'));
        $result = $this->grid->to_array();

        $fileds = $this->_export_fileds;
        $filename = '文章列表' . date('Y-m-d H:i:s');
        $this->grid->export2excel($fileds, $result['rows'], $filename);
        exit(json_encode($result));
    }

	/**
     * 获取category的select
     *
     * @return array
     */
    public function searchoptions_category()
    {
       	$article_category = $this->Article_model->get_data(array('form_name' => 'categories','where'=> array('status'=>1)));
        foreach ($article_category as $key => $item) {
            $category_arr[$item['category_id']] = $item['category_name'];
        }
            $return_struct['code'] = 200;
            $return_struct['status'] = 1;
            $return_struct['content'] = $category_arr;
            exit(json_encode($return_struct));

    }

    /**
     * 获取category的select option
     *
     * @return array
     */
    public function searchoptions_categoryoption()
    {
       	$article_category = $this->Article_model->get_data(array('form_name' => 'categories','where'=> array('status'=>1)));
        foreach ($article_category as $key => $item) {
            $category_arr[$item['category_name']] = $item['category_id'];
        }
            $return_struct['code'] = 200;
            $return_struct['status'] = 1;
            $return_struct['content'] = $category_arr;
            exit(json_encode($return_struct));
    }
}
