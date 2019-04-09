$.extend($.jgrid.defaults, {
    width: '100%',
    datatype: "json",
    sortname: 'id',
    sortorder: 'desc',
    rowNum: 20,
    rowList: [20, 50, 100, 200, 500, 1000, 2000],
    pager: '#pager',
    height: "stretch",
    gridview: true,
    autowidth: true,
    rownumbers: true,
    viewrecords: true,
    forceFit: true,
    shrinkToFit: false,
    autoScroll: true,
    multiboxonly: true,
    multiselect: true,
    multiSort: false,
    altRows: true,
    altclass: "ui-jqgrid-evennumber",
    viewsortcols: [true, "vertical", true],
    subGridOptions: {reloadOnExpand: false},
    loadError: function (d, e, b, c) {
        Global.notify("error", "表格数据加载处理失败,请尝试刷新或联系管理员!")
    },
    loadComplete: function () {
        var O = $('#jqgrid_table');
        var x = $("#jqgrid_table_rn");
        var H = '<a href="javascript:;" title="显示快速查询"><span class="ui-icon ui-icon-carat-1-s"></span></a>';
        var Q = '<a href="javascript:;" title="隐藏快速查询"><span class="ui-icon ui-icon-carat-1-n"></span></a>';
        if (this.subGrid || this.filterToolbar == "hidden") {
            x.html(H);
            $('#jqgrid_table')[0].toggleToolbar()
        } else {
            x.html(Q)
        }
        x.on("click", ".ui-icon-carat-1-s", function () {
            x.html(Q);
            $('#jqgrid_table')[0].toggleToolbar()
        });
        x.on("click", ".ui-icon-carat-1-n", function () {
            x.html(H);
            $('#jqgrid_table')[0].toggleToolbar()
        });
        //高度自定义
        var w = $("#gbox_" + O.attr("id"));
        var ac = 0;
        var R = "div.ui-jqgrid-titlebar,div.ui-jqgrid-hdiv,div.ui-jqgrid-pager,div.ui-jqgrid-toppager,div.ui-jqgrid-sdiv";
        w.find(R).filter(":visible").each(function () {
            ac += $(this).outerHeight()
        });
        ac = ac + 20;
        var h = $(window).height() - O.closest(".ui-jqgrid").offset().top - ac;
        if (h < 300) {
            h = 300
        }
        O.setGridHeight(h, true)
        $("table.ui-jqgrid-btable:visible").each(function () {
            var c = $(this);
            var d = c.jqGrid("getGridParam", "width");
            var b = c.closest("div.ui-jqgrid").parent("div").width();
            if (d != b) {
                c.jqGrid("setGridWidth", b);
                var e = $(this).jqGrid("getGridParam", "groupHeader");
                if (e) {
                    c.jqGrid("destroyGroupHeader");
                    c.jqGrid("setGroupHeaders", e)
                }
            }
        });
        if(typeof(jqgrid_complete) == 'function'){
            jqgrid_complete();
        }
    }
});
$.extend($.jgrid.search, {
    multipleSearch: true,
    multipleGroup: true,
    width: 700,
    jqModal: true,
    searchOperators: true,
    stringResult: true,
    searchOnEnter: true,
    defaultSearch: "bw",
    operandTitle: "点击选择查询方式",
    odata: [{oper: "eq", text: "等于\u3000\u3000"}, {oper: "ne", text: "不等\u3000\u3000"}, {
        oper: "lt",
        text: "小于\u3000\u3000"
    }, {oper: "le", text: "小于等于"}, {oper: "gt", text: "大于\u3000\u3000"}, {
        oper: "ge",
        text: "大于等于"
    }, {oper: "bw", text: "开始于"}, {oper: "bn", text: "不开始于"}, {oper: "in", text: "属于\u3000\u3000"}, {
        oper: "ni",
        text: "不属于"
    }, {oper: "ew", text: "结束于"}, {oper: "en", text: "不结束于"}, {oper: "cn", text: "包含\u3000\u3000"}, {
        oper: "nc",
        text: "不包含"
    }, {oper: "nu", text: "不存在"}, {oper: "nn", text: "存在"}, {oper: "bt", text: "介于"}],
    operands: {
        eq: "=",
        ne: "!",
        lt: "<",
        le: "<=",
        gt: ">",
        ge: ">=",
        bw: "^",
        bn: "!^",
        "in": "=",
        ni: "!=",
        ew: "|",
        en: "!@",
        cn: "~",
        nc: "!~",
        nu: "#",
        nn: "!#",
        bt: "~~"
    }
});