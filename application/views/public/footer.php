</div>
</div>
</div>
</div>
</body>
<script type="text/javascript">
    var isModify = false;
    var baseUrl = '/index.php/';
    var frontBaseUrl = 'http://admin.ichelaba.com/';
    var themeUrl = '/admin/static/';
    var sUrl = themeUrl + 'plugins/data-tables/i18n/zh.txt';
	var startDate = '2010-01-01';
    var endDate = '2020-01-01';
    var WEB_ROOT = '/index.php/';
</script>
<script src="<?= static_url(); ?>plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js?cache=v1.0.0.1" type="text/javascript"></script>
<script src="<?= static_url(); ?>plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?= static_url(); ?>scripts/core/app.js" type="text/javascript"></script>

<!-- BEGIN pinyin -->
<script type="text/javascript" src="<?= static_url(); ?>plugins/pinyin.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/JSPinyin.js"></script>
<!-- END pinyin -->

<!-- BEGIN GRID -->
<script type="text/javascript" src="<?= static_url(); ?>plugins/jqgrid/plugins/ui.multiselect.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/jqgrid/src/i18n/grid.locale-cn.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/jqgrid/js/jquery.jqGrid.src.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/jqgrid/jqgrid.custom.js"></script>
<!-- END GRID -->
<script type="text/javascript" src="<?= static_url(); ?>plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/grid.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/form-validation.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/page.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/biz.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/util.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/global.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/bootstrap-toastr/toastr.min.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/metronic.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/jquery.blockui.min.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?= static_url(); ?>css/metroStyle.css" type="text/css">
<script type="text/javascript" src="<?= static_url(); ?>js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="<?= static_url(); ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js?cache=1.0"></script>
<!-- BEGIN JQGRID STYLES -->
<link rel="stylesheet" type="text/css" href="<?= static_url(); ?>css/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" type="text/css" href="<?= static_url(); ?>css/ui.multiselect.css">
<link rel="stylesheet" type="text/css" href="<?= static_url(); ?>css/ui.jqgrid.css">
<link rel="stylesheet" type="text/css" href="<?= static_url(); ?>css/bootstrap-jqgrid.css">

<link rel="stylesheet" type="text/css" href="<?= static_url(); ?>css/tooltipster.css">
<link rel="stylesheet" type="text/css" href="<?= static_url(); ?>css/tooltipster-punk.css">
<link rel="stylesheet" type="text/css" href="<?= static_url(); ?>plugins/bootstrap-daterangepicker/daterangepicker-bs3.css">
<!-- END JQGRID STYLES -->



<script>
    jQuery(document).ready(function () {
        App.init(); // initlayout and core plugins
        Metronic.init(); // init metronic core componets
        Metronic.unblockUI();
		Util.init();
        Global.init();
    });
	function dateRangePick(el) {
            $(el).daterangepicker($.fn.daterangepicker.defaults,
                function (start, end) {
                    $("#jqgrid_table")[0].triggerToolbar();
                }
            );
    }
</script>
</html>