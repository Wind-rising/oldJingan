
<div class="clearfix"></div>
<table id="jqgrid_table"></table>
<div id="pager"></div>
<div class="clearfix"></div>
<!-- Modal -->

<script type="text/javascript">
    jQuery(function ($) {
        $("#jqgrid_table").data("gridOptions", {
            url: '<?php echo app_url();?>grid/user/salerapply',
            colModel: [
		        {
                    name: '操作',
                    align: "center",
                    width: 80,
                    sortable: false,
                    stype: false,
                    formatter: function (cellValue, options, rowObject, action) {
                        if(rowObject.status==1) {
                            var inner_str = '<button style="color:#fff; margin-right:10px;" class="btn green btn-xs" onclick="checkUser(' + rowObject.user_id + ', \'' + 0 + '\',' + rowObject.apply_id + ')">取消审核</button>';
                        }else{
                            var inner_str = '<button style="color:#fff; margin-right:10px;" class="btn green btn-xs" onclick="checkUser(' + rowObject.user_id + ', \'' + 1 + '\',' + rowObject.apply_id + ')">审核</button>';
                        }
                        return inner_str;
                    },
                    search: false
                },
                {
                    label: '手机号',
                    name: 'mobile',
                    index: 'mobile',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '姓名',
                    name: 'user_name',
                    index: 'user_name',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '是否是经纪人',
                    name: 'status',
                    index: 'status',
                    search: true,
                    width: 100,
                    stype: 'select',
                    searchoptions: {value: ":ALL;0:申请;1:通过;2:审核不通过", sopt: ['eq']}

                },
                {
                    label: '省',
                    name: 'pname',
                    index: 'pname',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}

                },
                {
                    label: '市',
                    name: 'cname',
                    index: 'cname',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}

                },
                {
                    label: '申请时间',
                    name: 'create_time',
                    index: 'create_time',
                    search: true,
                    align: "center",
                    width: 50,
                    searchoptions: {dataInit: dateRangePick, sopt: ['cn','eq']}
                }
            ],
            operations: function (itemArray) {
//                var $select = $('<li data-position="multi" data-toolbar="show" open-type="direct"><a href="<?php //echo app_url();?>//operator/add"><i class="fa fa-plus"></i> 添加</a></li>');
//				itemArray.push($select);
            },
			delurl: '<?php echo app_url();?>grid/user/batch_delete',
            sortname: 'apply_id',
            sortorder: 'desc',
            footerrow: true
        });
        Grid.initGrid(jQuery("#jqgrid_table"));

    });

    function checkUser(user_id,tag,applyid){
        var check_code = confirm("请审核是否成为经纪人");
        if(check_code == false){
            tag = 2;
        }
      //  alert(check_code);
        var post_data = {};
        post_data.applyid = applyid;
        post_data.user_id = user_id;
        post_data.tag = tag;
        ajaxRequest('<?php echo app_url();?>user/checkUser', 'POST', post_data, 'json', 'afterCheckUser(backdata)');
    }

    function afterCheckUser(backdata){
        if(backdata.flag == false){
            alert(backdata.message);
        }else {
            if (backdata.tag == 1) {
                alert('恭喜！您已通过全民经纪人认证！');
            }else{
                alert('恭喜！您已收回全民经纪人认证！');
            }
            jQuery("#jqgrid_table").trigger('reloadGrid');
        }
    }
</script>