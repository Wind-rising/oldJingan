<div class="clearfix"></div>
<table id="jqgrid_table"></table>
<div id="pager"></div>
<div class="clearfix"></div>
<!-- Modal -->


<script type="text/javascript">
    jQuery(function ($) {
        $("#jqgrid_table").data("gridOptions", {
            url: '<?php echo app_url();?>grid/operator_log/index',
            colModel: [
                {
                    label: '后台用户名称',
                    name: 'operator_name',
                    index: 'operator_name',
                    search: true,
                    width: 50,
					searchoptions: {sopt: ['eq', 'ne']}
                },
                {
                    label: '控制器',
                    name: 'control',
                    index: 'control',
                    search: true,
                    width: 50,
                    searchoptions: {sopt: ['eq', 'ne']}
                },
                {
                    label: '动作',
                    name: 'action',
                    index: 'action',
                    search: true,
                    width: 50,
                    searchoptions: {sopt: ['eq', 'ne']}
                },
                {
                    label: '目标id',
                    name: 'target_id',
                    index: 'target_id',
                    search: true,
                    width: 50,
                    searchoptions: {sopt: ['eq', 'ne']}
                },

                {
                    label:'添加时间',
                    name: 'addtime',
                    index: 'addtime',
                    search: true,
                    align: "center",
                    width: 50,
                    searchoptions: {dataInit: dateRangePick, sopt: ['cn','eq']}
                }
               
            ],
            sortname: 'id',
            sortorder: 'desc',
            footerrow: true
        });
        Grid.initGrid(jQuery("#jqgrid_table"));

    });
</script>