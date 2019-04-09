<SCRIPT type="text/javascript">

    var setting = {
        view: {
            dblClickExpand: true,
            showLine: true,
            selectedMulti: false
        },
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        }
    };
    var zNodes = <?php echo json_encode($items_arr);?>;
    var zTree;

    $(document).ready(function () {
        $.fn.zTree.init($("#main_menu"), setting, zNodes);
        zTree = $.fn.zTree.getZTreeObj("main_menu");
        zTree.expandAll(true);
    });

</SCRIPT>
<div class="form">
    <form class="form-horizontal form-validation JS-form-edit-role" method="post" action="" role="form" id="editrole" data-editrulesurl="false">
        <input type="hidden" name="menus" value="" id="menus"/>

        <div class="col-md-3">
            <div class="portlet box blue" id="sidebar">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>操作选择</div>
                </div>
                <div class="portlet-body">
                    <div class="scroller" style="height:500px">
                        <ul id="main_menu" class="ztree"></ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i> 角色基本信息
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">
                                <span class="required">*</span> 名称
                            </label>

                            <div class="col-md-9">
                                <input type="text" name="name" data-required="1"
                                       class="form-control required"
                                       value="<?php echo !empty($role[0]['name'])?$role[0]['name']:'';?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">
                                描述
                            </label>

                            <div class="col-md-9">
                                <textarea rows="5" class="form-control" name="remark" id="remark"><?php echo !empty($role[0]['remark'])?$role[0]['remark']:'';?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="button" class="btn green" onclick="tijiao();">保存</button>
                            <button type="button" class="btn default" data-dismiss="modal">取消</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
</div>
<script>

		function tijiao(){
		    var $checkedNodes = zTree.getCheckedNodes(true);
            var $uncheckedNodes = zTree.getCheckedNodes(false);

            if ($checkedNodes.length <= 0) {
                alert('角色的操作权限未选择');
                return false;
            }

                $('#menus').val(JSON.stringify($checkedNodes));
                $("#editrole").submit();
		}


</script>
