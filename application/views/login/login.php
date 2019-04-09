<html><!DOCTYPE html>
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
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="<?= static_url();?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?= static_url();?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?= static_url();?>plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?= static_url();?>/plugins/select2/select2.css" />
<link rel="stylesheet" type="text/css" href="<?= static_url();?>/plugins/select2/select2-metronic.css" />
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?= static_url();?>css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?= static_url();?>css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?= static_url();?>css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?= static_url();?>css/plugins.css" type="text/css"/>
<link href="<?= static_url();?>css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?= static_url();?>css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="<?= static_url();?>css/custom.css" rel="stylesheet" type="text/css"/>
<script src="<?= static_url();?>js/common.js"/></script>
<script src="<?= static_url();?>js/jquery-1.11.3.min.js"/></script>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- BEGIN BODY -->
<body class="login" onkeydown="keyEnterdown();">
<!-- BEGIN LOGO -->
<div class="logo">
	<h1>网上竞赛后台<span>管理系统</span></h1>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
		<h3 class="form-title"><span class="title-icon">|</span>请登录</h3>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">请输入用户名</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" id="username" placeholder="请输入用户名" name="username" onBlur="checkNamepass(this.value,this.getAttribute('placeholder'));" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">请输入密码</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" id="password" autocomplete="off" placeholder="请输入密码" name="password" onBlur="checkNamepass(this.value,this.getAttribute('placeholder'));"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="button" class="btn green pull-right" onClick="successHref();">
			 登陆    <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
        <div id="form-error">
        </div>
	<!-- END LOGIN FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 2014 &copy; Metronic. Admin Dashboard Template.
</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<script type="text/javascript">
    var loginflag=false;
    var obj=document.getElementById('form-error');//获取错误提示框

    //验证用户名密码
	function checkNamepass(val,msg){
      if(val==''){
      	checkError(obj,msg);
      }else{
      	obj.innerHTML='';
      }
	}

    //错误方法
	function checkError(obj,msg){
      	obj.innerHTML='* '+msg;
	}

    //登陆ajax
	function successHref(){
        var user=document.getElementById('username').value;
        var pass=document.getElementById('password').value;
        if(user!=''&&pass!=0){
        	loginflag=true;
        }
        if(!loginflag){
            checkError(obj,'用户名密码不能为空！');
        }else{
        	var url='<?= default_url();?>login/ajax_login';
        	var type='post';
        	var data={admin_name:user, password:pass};
        	var dataType='json';
            ajaxRequest(url,type,data,dataType,"successFun(backdata)");
        }
	}

	//ajax回调函数
	function successFun(backdata){
		if(backdata.flag == false){
			alert("用户名密码错误！");
		}else{
			location.href=location.href;
		}
	}

    //回车登陆
    function keyEnterdown(){
        if (event.keyCode == 13)
        {
          event.returnValue=false;
          event.cancelBubble=true;
          successHref();
        }
    }
</script>
</body>
<!-- END BODY -->
</html>