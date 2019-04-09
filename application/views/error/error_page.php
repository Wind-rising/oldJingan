<?php $this->load->view('public/top.php', "");?>
<?php
$left_view_data = array(
	'_left_parent_page_name' => '', //页面配置父导航标示
	'_page_name' => '错误页面',//当前页面名称
	'_page_detail' => '',//当前页面说明
	'menu' => '',
);
?>
<?php $this->load->view('public/left.php', $left_view_data);?>
<div>
	<?= $error_str;?>
</div>
<?php $this->load->view('public/footer.php', "");?>