<div class="clearfix"></div>
<table id="jqgrid_table"></table>
<div id="pager"></div>
<div class="clearfix"></div>
<!-- Modal -->

<script type="text/javascript">
    jQuery(function ($) {
        $("#jqgrid_table").data("gridOptions", {
            url: '<?php echo app_url();?>grid/member/index',
            colModel: [
                {
                    label: '会员名称',
                    name: 'user_name',
                    index: 'user_name',
                    search: true,
                    width: 100,
                    align:"center",
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '联系方式',
                    align: "center",
                    name: 'mobile',
                    index: 'mobile',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','ne']}
                },
                {
                    label: '类型',
                    align: "center",
                    name: 'type',
                    index: 'type',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','ne']}
                },
                {
                    label: '参加时间',
                    align: 'center',
                    name: 'create_time',
                    index: 'create_time',
                    search: true,
                    width: 140,
                    searchoptions: {dataInit: dateRangePick, sopt: ['cn','eq']}
                },
            ],
            delurl: '<?php echo app_url();?>member/batch_delete',
            sortname: 'user_id',
            sortorder: 'desc',
            footerrow: true
        });
        Grid.initGrid(jQuery("#jqgrid_table"));
    });
</script>