<!-- BEGIN SIDEBAR -->
<div class="page-container">
	<div class="page-sidebar-wrapper" style="background: #3E3E3E;margin-top:-0px;">
		<div class="page-sidebar navbar-collapse collapse">
			<!-- add "navbar-no-scroll" class to disable the scrolling of the sidebar menu -->
			<!-- BEGIN SIDEBAR MENU -->
			<div class="slimScrollDiv" style="position: relative; width: auto; height: 329px;">
				<ul class="page-sidebar-menu" style="width: auto; height: 329px;">
					<li class="sidebar-toggler-wrapper">
						<div class="sidebar-toggler hidden-phone">
						</div>
					</li>
					
                    
					<?php echo $menu;?>

				</ul>

				<div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.3; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 138.593px; background: rgb(161, 178, 189);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div>
		        	<!-- END SIDEBAR MENU -->
		        </div>
	</div>
	<!-- END SIDEBAR -->

	<!-- RIGHT STA -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<div class="col-md-12">
				<h3 class="page-title">
                        <?= isset($_page_name) ? $_page_name : ''; ?>
                    </h3>
				<ul class="page-breadcrumb breadcrumb">
					<li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo app_url();?>index.php/">主面板</a>
                            <i class="fa fa-angle-right"></i>
                    </li>
					<li>
						<a href="javascript:;">
							<?= isset($_page_name) ? $_page_name : ''; ?><small><?= isset($_page_detail) ? $_page_detail : ''; ?></small>
						</a>
					</li>
					<li style="color:#fff; float:right;">
						<a class="javascript:;" onclick="history.back()" style="cursor:pointer;">
							返回
						</a>
					</li>

				</ul>
			</div>
			<div class="form-horizontal form-row-seperated">
				
				<div class="col-md-12">
					<?php echo $this->remind->get(); ?>
