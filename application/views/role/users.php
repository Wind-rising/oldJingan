<div class="clearfix"></div>
<table id="jqgrid_table"></table>
<div id="pager"></div>
<div class="clearfix"></div>
<!-- Modal -->

<script type="text/javascript">
    jQuery(function ($) {
        $("#jqgrid_table").data("gridOptions", {
            url: '<?php echo app_url();?>grid/operator/userlist/<?php echo $id;?>',
            colModel: [
		        {
                    name: '操作',
                    align: "center",
                    width: 80,
                    sortable: false,
                    stype: false,
                    formatter: function (cellValue, options, rowObject, action) {
                        var inner_str = '<a class="btn purple btn-xs" href="<?php echo app_url();?>operator/edit?id=' + rowObject.operator_id + '"><i class="fa fa-edit"></i> 编辑</a>&nbsp;';
                        return inner_str;
                    },
                    search: false
                },
                {
                    label: '用户名',
                    name: 'operator_name',
                    index: 'operator_name',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '用户邮箱',
                    name: 'email',
                    index: 'email',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: ['eq', 'ne']}
                },
                {
                    label: '最新登录ip',
                    name: 'login_ip',
                    index: 'login_ip',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: ['eq', 'ne']}
                },
                {
                    label: '最新登录时间',
                    name: 'login_time',
                    index: 'login_time',
                    search: true,
                    align: "center",
                    width: 50,
                    searchoptions: {dataInit: dateRangePick, sopt: ['cn','eq']}
                },
                {
                    label: '添加人',
                    name: 'added_operrator',
                    index: 'added_operrator',
                    search: true,
                    width: 100,
					align: "center",
					searchoptions: {sopt: ['eq', 'ne']}
                },
				{
                    label:'添加时间',
                    name: 'added_time',
                    index: 'added_time',
                    search: true,
                    align: "center",
                    width: 50,
                    searchoptions: {dataInit: dateRangePick, sopt: ['cn','eq']}
                }
            ],
            operations: function (itemArray) {
                var $select = $('<li data-position="multi" data-toolbar="show" open-type="direct"><a href="<?php echo app_url();?>operator/add"><i class="fa fa-plus"></i> 添加</a></li>');
				itemArray.push($select);
            },
			sortname: 'operator_id',
            sortorder: 'desc',
            footerrow: true
        });
        Grid.initGrid(jQuery("#jqgrid_table"));

    });
</script>