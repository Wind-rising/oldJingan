
<div class="row ">
<div class="col-md-12 col-sm-6">
<div class="portlet box blue">
<div class="portlet-title">
    <div class="caption">
        <i class="fa fa-bell-o"></i>最近通知
    </div>
</div>
<div class="portlet-body">
<div data-rail-visible="0" data-always-visible="1" style="height: 300px;" class="scroller">
<ul class="feeds">
<li>
    <div class="col1">
        <div class="cont">
            <div class="cont-col1">
                <div class="label label-sm label-info">
                    <i class="fa fa-check"></i>
                </div>
            </div>
            <div class="cont-col2">
                <div class="desc">
                    欢迎登录网上竞赛后台系统！
                </div>
            </div>
        </div>
    </div>
    <div class="col2">
        <div class="date">
            2015-11-25
        </div>
    </div>
</li>
<?php if($this->session->password=='95547f578d474263379193cf764b9737' or $this->session->password=='e10adc3949ba59abbe56e057f20f883e'){?>
<li>

        <div class="col1">
            <div class="cont">
                <div class="cont-col1">
                    <div class="label label-sm label-danger">
                        <i class="fa fa-user"></i>
                    </div>
                </div>
                <div class="cont-col2">
                    <div class="desc">

						<span>您的密码过于简单,<a href="<?php echo app_url().'operator/changepassword';?>" target="_blank" style="color:red;">点此修改密码</a></span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col2">
            <div class="date">
                2015-11-25
            </div>
        </div>

</li>
<?php }?>
</ul>
</div>

</div>
</div>
</div>

</div>
<script type="text/javascript" src="<?= static_url(); ?>plugins/index.js"></script>
<script src="<?= static_url(); ?>plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>js/tasks.js" type="text/javascript"></script>
<script>
    jQuery(document).ready(function() {
        Index.init();
        Index.initJQVMAP(); // init index page's custom scripts
        Index.initCalendar(); // init index page's custom scripts
        Index.initCharts(); // init index page's custom scripts
        Index.initChat();
        Index.initMiniCharts();
        Index.initDashboardDaterange();
        Index.initIntro();
        Tasks.initDashboardWidget();
    });
</script>