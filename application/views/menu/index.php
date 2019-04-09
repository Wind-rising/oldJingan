<SCRIPT type="text/javascript">

    var setting = {
        view: {
            dblClickExpand: false,
            showLine: true,
            selectedMulti: false
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        edit: {
            enable: true,
            showRenameBtn: false,
            showRemoveBtn: showRemoveBtn,
            drag: {
                autoExpandTrigger: false,
                isCopy: false,
                maxShowNodeNum: 3
            }
        },
        callback: {
            beforeDrop: zTreeBeforeDrop,
            beforeRemove: zTreeBeforeRemove,
            onClick: zTreeOnClick,
            onDblClick: zTreeOnDblclick
        }
    };
    var zNodes = <?php echo $items_arr;?>;
    //console.log(zNodes);
    var zTree;
    $(document).ready(function () {
        $.fn.zTree.init($("#main_menu"), setting, zNodes);
        zTree = $.fn.zTree.getZTreeObj("main_menu");
        var node = zTree.getNodeByParam("id", <?php echo $menu_root_arr['0']['id'];?>, null);
        zTree.selectNode(node);
        zTree.expandAll(true);
        var menu_form = $('#menu_form');
        $('#menu_form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input

            invalidHandler: function (event, validator) { //display error alert on form submit
                Metronic.scrollTo(menu_form, -200);
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                Metronic.blockUI();
                var param_date = menu_form.serialize();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "<?php echo app_url().'menu/itemupdate/'.$menu_root_arr['0']['id'];?>",
                    data: param_date,
                    success: function (nodedata) {
                        Metronic.unblockUI();
                        var content = nodedata.content;
                        var selectedNode = zTree.getSelectedNodes();
                        var newNode = [
                            {name: content.name, id: content.id}
                        ];
                        if ((nodedata.status == 1) && (nodedata.code == 200)) {
                            zTree.addNodes(selectedNode[0], newNode);
                            clearForm();
                        } else if ((nodedata.status == 2) && (nodedata.code == 200)) {
                            selectedNode[0].name = content.name;
                            zTree.updateNode(selectedNode[0], true);
                            $("#current_id").val(<?php echo $menu_root_arr['0']['id'];?>);
                            clearForm();
                        } else {
                            Global.notify('error', nodedata.msg);
                        }
                    },
                    error: function () {
                        Metronic.unblockUI();
                        Global.notify('error', '保存失败');
                    }
                });
            }
        });
        function clearForm() {
            $('#menu_form')[0].reset();
            zTree.selectNode(node);
        }
    });
    /**
     * 判断哪些节点可以删除
     */
    function showRemoveBtn(treeId, treeNode) {
        return treeNode.id != '<?php echo $menu_root_arr['0']['id'];?>';
    }
    /**
     * 删除节点
     * @param treeId
     * @param treeNode
     */
    function zTreeBeforeRemove(treeId, treeNode) {
        if (confirm('确认删除该菜单项目?')) {
            Metronic.blockUI();
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "<?php echo app_url().'menu/delete/';?>",
                data: 'id=' + treeNode.id,
                cache: false,
                success: function (nodedate) {
                    Metronic.unblockUI();
                    if (nodedate.status == '0') {
                        alert(nodedate.msg);
                    } else {
                        if (treeNode.id == $('#target').val()) {
                            $('#target').val($('#root').val());
                        }
                        zTree.removeNode(treeNode);
                        Global.notify('success', '删除成功');
                    }
                },
                error: function () {
                    Metronic.unblockUI();
                    Global.notify('error', '删除失败');
                }
            });
            return false;
        } else {
            return false;
        }
    }
    /**
     * 移动节点
     * @param treeId
     * @param treeNode
     * @param targetNode
     * @param moveType
     */
    function zTreeBeforeDrop(treeId, treeNodes, targetNode, moveType) {
        if (targetNode != null) {
            Metronic.blockUI();
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "<?php echo app_url().'menu/move/';?>",
                data: 'id=' + treeNodes[0].id + '&type=' + moveType + '&target=' + targetNode.id,
                success: function (nodedate) {
                    Metronic.unblockUI();
                    if (nodedate.status == '0') {
                        Global.notify('error', nodedate.msg);
                    }
                },
                error: function () {
                    Metronic.unblockUI();
                    Global.notify('error', '移动失败');
                }
            });
        } else {
            return false;
        }
    }
    /**
     * 载入已有节点编辑
     * @param event
     * @param treeId
     * @param treeNode
     */
    function zTreeOnDblclick(event, treeId, treeNode) {
        Metronic.blockUI();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo app_url().'menu/get/';?>",
            data: 'id=' + treeNode.id,
            success: function (nodedate) {
                Metronic.unblockUI();
                if ((nodedate.status == 1) && (nodedate.code == 200)) {
                    var content = nodedate.content;
                    $('#name').val(content.name);
                    $('#icon_class').val(content.icon_class);
                    $('#controller').val(content.controller);
                    $('#action').val(content.action);
                    $("input[name=is_menu]").removeAttr("checked");
                    $("input[name=is_menu][value=" + content.is_menu + "]").prop("checked",true);
                    $('#current_id').val(content.id);
                } else {
                    Global.notify('error', nodedate.msg);
                }
            },
            error: function () {
                Metronic.unblockUI();
                Global.notify('error', '菜单节点数据载入失败');
            }
        });
    }
    /**
     * 选择节点
     * @param event
     * @param treeId
     * @param treeNode
     */
    function zTreeOnClick(event, treeId, treeNode) {
        var target = treeNode.id;
        $("#target").val(target);
        $("#current_id").val(0);
    }
    //-->
</SCRIPT>
<div class="row">
    <div class="col-md-3">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i> 权限菜单
                </div>
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
                    <i class="fa fa-gift"></i> 编辑
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal form-bordered" accept-charset="utf-8" id="menu_form" method="post">
                    <input type="hidden" name="target" id="target" value="<?php echo $menu_root_arr['0']['id'];?>"/>
                    <input type="hidden" name="root" id="root" value="<?php echo $menu_root_arr['0']['id'];?>"/>
                    <input type="hidden" name="current_id" id="current_id" value="0"/>

                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">
                                <span class="required">*</span> 标题
                            </label>

                            <div class="col-md-9">
                                <input type="text" name="name" id="name" data-required="1" class="form-control required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">
                                <span class="required">*</span> 图标样式
                            </label>

                            <div class="col-md-9">
                                <input type="text" name="icon_class" id="icon_class" data-required="1" class="form-control required"/>
                                <span class="help-block">
                                    从<a href="<?php echo static_url();?>MetroNic/ui_buttons.html" target="_blank">样式表中查找样式，如： fa-circle</a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">
                                <span class="required">*</span> 链接地址
                            </label>

                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control required" name="controller" id="controller" placeholder="Controller">
                                    <span class="input-group-addon"> / </span>
                                    <input type="text" class="form-control" name="action" id="action" placeholder="Action">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">
                                作为菜单目录
                            </label>

                            <div class="col-md-9">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_menu" value="Y" checked> 是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_menu" value="N"> 否
                                    </label>
                                </div>
                                <p class="text-muted">
                                    如设定为菜单目录，则本菜单会在导航中显示，如不作为菜单目录只作为权限验证的功能。
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">
                                验证
                            </label>

                            <div class="col-md-9">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_verify" value="Y" checked> 是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_verify" value="N"> 否
                                    </label>
                                </div>
                                <p class="text-muted">
                                    如设定为不需要验证，则所有人都可以访问
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> 保存</button>
                            <a href="" class="btn default">取消</a>
                        </div>
                    </div>
                    </form>
            </div>
        </div>
    </div>
</div>