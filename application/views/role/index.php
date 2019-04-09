<div class="clearfix"></div>
<table id="jqgrid_table"></table>
<div id="pager"></div>
<div class="clearfix"></div>
<!-- Modal -->

<script type="text/javascript">
    jQuery(function ($) {
        $("#jqgrid_table").data("gridOptions", {
            url: '<?php echo app_url();?>grid/role/index',
            colModel: [
		        {
                    name: '操作',
                    align: "center",
                    width: 80,
                    sortable: false,
                    stype: false,
                    formatter: function (cellValue, options, rowObject, action) {
                        var inner_str = '<a class="btn purple btn-xs" href="<?php echo app_url();?>role/edit?id=' + rowObject.id + '"><i class="fa fa-edit"></i> 编辑</a>&nbsp;';
						inner_str += '<a target="_blank" class="btn green btn-xs"  href="<?php echo app_url();?>role/users?id=' + rowObject.id + '"><i class="fa fa-user"></i> 查看角色用户</a>&nbsp;';
                        return inner_str;
                    },
                    search: false
                },
                {
                    label: '名称',
                    name: 'name',
                    index: 'name',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '描述',
                    name: 'remark',
                    index: 'remark',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: ['eq', 'ne']}
                }
            ],
            operations: function (itemArray) {
                var $select = $('<li data-position="multi" data-toolbar="show" open-type="direct"><a href="<?php echo app_url();?>role/add"><i class="fa fa-plus"></i> 添加</a></li>');
				itemArray.push($select);
            },
			delurl: '<?php echo app_url();?>grid/role/batch_delete',
            sortname: 'id',
            sortorder: 'desc',
            footerrow: true
        });
        Grid.initGrid(jQuery("#jqgrid_table"));

    });
</script>