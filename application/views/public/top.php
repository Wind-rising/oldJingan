<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.1.1
Version: 2.0.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>网上竞赛后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<?php $this->load->view('public/html_header.php', "");?>
<script>
function _topCheckLogOut(){
	if(confirm("确认退出系统？")){
		location.href = "<?= app_url();?>Logout/";
	}
}
</script>
</head>
<body class="page-header-fixed page-sidebar-fixed">
	<div class="header navbar navbar-fixed-top">
		<div class="header-inner">
			<a class="navbar-brand">
				<span style="color:#fff; margin-left:10px; max-width:100%;">网上竞赛后台</span>
			</a>
			<a href="javascript:;" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <img src="<?= static_url();?>img/menu-toggler.png" alt="">
            </a>
            <ul class="nav navbar-nav pull-right">
              <!-- BEGIN USER LOGIN DROPDOWN -->
              <li class="dropdown user">
			    <span style="color:#fff;margin-right:45px;">管理员：<?= $this->session->operator_name;?>(<?=$this->session->role_name;?>)</span>
                <span style="color:#fff;float:right;text-decoration:underline; cursor:pointer;" onClick="_topCheckLogOut()">退出系统</span>
              </li>
              <!-- END USER LOGIN DROPDOWN -->
            </ul>

		</div>
	</div>
