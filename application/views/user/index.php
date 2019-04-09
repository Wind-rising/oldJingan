<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>活跃情况
        </div>
    </div>
    <div class="portlet-body form">
         <div class="form-body">
                <div class="form-group last">
                    <label class="col-md-2 control-label">今日新增注册人数：<?php echo $counttoday;?></label>
                    <label class="col-md-2 control-label">昨日新增注册人数：<?php echo $countyes;?></label>
                    <label class="col-md-2 control-label">今日已登录人数：<?php echo $count;?></label>
                    <label class="col-md-2 control-label">本周已登录人数：<?php echo $weekcount;?></label>
                    <label class="col-md-2 control-label">本月已登录人数：<?php echo $monthcount;?></label>
                </div>
            </div>
    </div>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-reorder"></i>统计
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="form-group last">
                <div id="main" style="width: 100%;height:400px;"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));

    // 指定图表的配置项和数据
    var option = {
        title: {
            text: '本月注册用户分析图'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {

            data: ['新注册客户']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19'
                , '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30','31']
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            name: '新注册客户',
            type: 'line',
            stack: '总量',
            data: <?php echo $add_num_js;?>
        }]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>

<div class="clearfix"></div>
<table id="jqgrid_table"></table>
<div id="pager"></div>
<div class="clearfix"></div>
<!-- Modal -->

<script type="text/javascript">
    jQuery(function ($) {
        $('.date-picker').datepicker({
            language: 'zh-CN',
            autoclose: true,
            todayHighlight: true
        })
        
        $("#jqgrid_table").data("gridOptions", {
            url: '<?php echo app_url();?>grid/user/index',
            colModel: [
//		        {
//                    name: '操作',
//                    align: "center",
//                    width: 80,
//                    sortable: false,
//                    stype: false,
//                    formatter: function (cellValue, options, rowObject, action) {
//                        if(rowObject.issaler==1) {
//                            var inner_str = '<button style="color:#fff; margin-right:10px;" class="btn green btn-xs" onclick="checkUser(' + rowObject.user_id + ', \'' + 0 + '\')">取消审核</button>';
//                        }else{
//                            var inner_str = '<button style="color:#fff; margin-right:10px;" class="btn green btn-xs" onclick="checkUser(' + rowObject.user_id + ', \'' + 1 + '\')">审核</button>';
//                        }
//                        return inner_str;
//                    },
//                    search: false
//                },
                {
                    label: '手机号',
                    name: 'mobile',
                    index: 'mobile',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '昵称',
                    name: 'nick_name',
                    index: 'nick_name',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '姓名',
                    name: 'full_name',
                    index: 'full_name',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '性别',
                    name: 'sex',
                    index: 'sex',
                    search: true,
                    width: 100,
                    stype: 'select',
                    searchoptions: {value: ":ALL;0:女;1:男", sopt: ['eq']}

                },
                {
                    label: '经纪人',
                    name: 'issaler',
                    index: 'issaler',
                    search: true,
                    width: 100,
                    stype: 'select',
                    searchoptions: {value: ":ALL;0:否;1:是", sopt: ['eq']}

                },
                {
                    label: '车主认证',
                    name: 'isauthentication',
                    index: 'isauthentication',
                    search: true,
                    width: 100,
                    formatter: function (cellValue, options, rowObject, action) {
                        if(rowObject.isauthentication==1) {
                            var inner_str = '<button style="color:#fff; margin-right:10px;" class="btn green btn-xs" >已认证</button>';
                        }else{
                            var inner_str = '<button style="color:#fff; margin-right:10px;" class="btn red btn-xs" >未认证</button>';
                        }
                        return inner_str;
                    }
                },
                {
                    label: '注册时间',
                    name: 'create_time',
                    index: 'create_time',
                    search: true,
                    align: "center",
                    width: 100,
                    searchoptions: {dataInit: dateRangePick, sopt: ['cn','eq']}
                },
                {
                    label: '登陆时间',
                    name: 'login_time',
                    index: 'login_time',
                    search: true,
                    align: "center",
                    width: 100,
                    searchoptions: {dataInit: dateRangePick, sopt: ['cn','eq']}
                },
                {
                    label: '省',
                    name: 'province',
                    index: 'province',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '市',
                    name: 'city',
                    index: 'city',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '当月登录次数',
                    name: 'loginnum',
                    index: 'loginnum',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '积分',
                    name: 'point',
                    index: 'point',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                },
                {
                    label: '佣金',
                    name: 'amount',
                    index: 'amount',
                    search: true,
                    width: 100,
                    searchoptions: {sopt: [ 'eq','bw','cn']}
                }
            ],
            operations: function (itemArray) {
//                var $select = $('<li data-position="multi" data-toolbar="show" open-type="direct"><a href="<?php //echo app_url();?>//operator/add"><i class="fa fa-plus"></i> 添加</a></li>');
//				itemArray.push($select);
            },
			delurl: '<?php echo app_url();?>grid/user/batch_delete',
            sortname: 'user_id',
            sortorder: 'desc',
            footerrow: true
        });
        Grid.initGrid(jQuery("#jqgrid_table"));

    });

    function checkUser(user_id,tag){
        var check_code = confirm("请审核是否成为经纪人");
        if(check_code == false){
            return false;
        }
      //  alert(check_code);
        var post_data = {};
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