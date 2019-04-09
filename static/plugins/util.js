var Util = function () {
    var b = {};
    var a = null;
    return {
        traverseTreeToKeyValue: function (d, c) {
            if (c == undefined) {
                c = {}
            }
            $.each(d, function (e, f) {
                c[f.id] = f.name;
                if (typeof(f.children) === "object") {
                    Util.traverseTreeToKeyValue(f.children, c)
                }
            });
            return c
        }, remoteSelectOptions: function (c, e) {
            if (e == false) {
                var d;
                $.ajax({
                    async: false, type: "GET", url: c, dataType: "json", success: function (f) {
                        if (f['code'] == 200 && f['status'] == 1) {
                            d = {"": " "};
                            d = $.extend(f['content'], d);
                        } else {
                            Global.notify("error", "查询条件读取失败，请重试.");
                        }

                    }, error: function (e) {
                        Global.notify("error", "查询条件加载失败，请重试.");
                    }
                });

                return d
            } else {
                if (e == undefined || $(e).size() == 0) {
                    e = $("body")
                }
                if (e.data("CacheUrlDatas") == undefined || e.data("CacheUrlDatas") == null) {
                    e.data("CacheUrlDatas", {})
                }
                var d = e.data("CacheUrlDatas")[c];
                if (d == undefined) {
                    $.ajax({
                        async: false, type: "GET", url: c, dataType: "json", success: function (f) {
                            if (f['code'] == 200 && f['status'] == 1) {
                                //TODO 空的在上边
                                d = {"": " "};
                                d = $.extend(d, f['content']);
                            } else {
                                Global.notify("error", "查询条件读取失败，请重试.");
                            }

                        }, error: function (e) {
                            Global.notify("error", "查询条件加载失败，请重试.");
                        }
                    });
                }
                return d
            }
        }, getCacheDatas: function (c, e) {
            if (e == undefined) {
                e = $("body")
            }
            if (e.data("CacheUrlDatas") == undefined) {
                e.data("CacheUrlDatas", {})
            }
            var d = e.data("CacheUrlDatas")[c];
            if (d == undefined) {
                $.ajax({
                    async: false, type: "GET", url: c, dataType: "json", success: function (f) {
                        d = f;
                        e.data("CacheUrlDatas")[c] = d
                    }
                })
            }
            return d
        }, getCacheSelectOptionDatas: function (c, e) {
            if (e == undefined) {
                e = $("body")
            }
            if (e.data("CacheSelectOptionDatas") == undefined) {
                e.data("CacheSelectOptionDatas", {})
            }
            var d = e.data("CacheSelectOptionDatas")[c];
            if (d == undefined) {
                $.ajax({
                    async: false, type: "GET", url: c, dataType: "json", success: function (g) {
                        var f = g;
                        if (g.content) {
                            f = g.content
                        }
                        d = {};
                        $.each(f, function (h, j) {
                            d[j.id] = j.display
                        });
                        e.data("CacheSelectOptionDatas")[c] = d
                    }
                })
            }
            return d
        }, getCacheEnumsByType: function (c, e) {
            if (e == undefined) {
                e = $("body")
            }
            if (e.data("CacheEnumDatas") == undefined) {
                $.ajax({
                    async: false,
                    type: "GET",
                    url: WEB_ROOT + "/pub/data!enums.json",
                    dataType: "json",
                    success: function (k) {
                        for (var j in k) {
                            var g = k[j];
                            var f = {"": ""};
                            for (var h in g) {
                                f[h] = g[h]
                            }
                            k[j] = f
                        }
                        e.data("CacheEnumDatas", k)
                    }
                })
            }
            var d = e.data("CacheEnumDatas")[c];
            if (d == undefined) {
                alert("错误的枚举数据类型：" + c);
                d = {}
            }
            return d
        }, getCacheDictDatasByType: function (d, g) {
            if (g == undefined) {
                g = $("body")
            }
            var h = g.data("CacheDictDatas");
            if (h == undefined) {
                $.ajax({
                    async: false,
                    type: "GET",
                    url: WEB_ROOT + "/pub/data!dictDatas.json",
                    dataType: "json",
                    success: function (j) {
                        h = j;
                        g.data("CacheDictDatas", h)
                    }
                })
            }
            var e = g.data("CacheDictDatas")[d];
            if (e == undefined) {
                var c = {};
                var f = true;
                $.each(h, function (j, k) {
                    if (k.parentPrimaryKey == d) {
                        f = false;
                        c[k.primaryKey] = k.primaryValue
                    }
                });
                e = c;
                g.data("CacheDictDatas")[d] = e;
                if (f) {
                    alert("错误的数据字典类型：" + d)
                }
            }
            return e
        }, assert: function (d, c) {
            if (!d) {
                alert(c)
            }
        }, assertNotBlank: function (d, c) {
            if (d == undefined || $.trim(d) == "") {
                Util.assert(false, c);
                return
            }
        }, debug: function (c) {
            if (window.console) {
                console.debug(c)
            } else {
                //alert(c)
            }
        }, hashCode: function (e) {
            var d = 0;
            if (e.length == 0) {
                return d
            }
            for (i = 0; i < e.length; i++) {
                var c = e.charCodeAt(i);
                d = ((d << 5) - d) + c;
                d = d & d
            }
            if (d < 0) {
                d = -d
            }
            return d
        }, AddOrReplaceUrlParameter: function (h, c, g) {
            var f = h.indexOf("?");
            if (f == -1) {
                h = h + "?" + c + "=" + g
            } else {
                var j = h.split("?");
                var k = j[1].split("&");
                var e = "";
                var d = false;
                for (i = 0; i < k.length; i++) {
                    e = k[i].split("=")[0];
                    if (e == c) {
                        k[i] = c + "=" + g;
                        d = true;
                        break
                    }
                }
                if (!d) {
                    h = h + "&" + c + "=" + g
                } else {
                    h = j[0] + "?";
                    for (i = 0; i < k.length; i++) {
                        if (i > 0) {
                            h = h + "&"
                        }
                        h = h + k[i]
                    }
                }
            }
            return h
        }, subStringBetween: function (f, h, d) {
            var g = new RegExp(h + ".*?" + d, "img");
            var e = new RegExp(h, "g");
            var c = new RegExp(d, "g");
            return f.match(g).join("=").replace(e, "").replace(c, "").split("=")
        }, split: function (c) {
            return c.split(",")
        }, isArrayContainElement: function (e, d) {
            var c = e.length;
            while (c--) {
                if (e[c] === d) {
                    return true
                }
            }
            return false
        }, getTextWithoutChildren: function (c) {
            return $(c)[0].childNodes[0].nodeValue.trim()
        }, findClosestFormInputByName: function (d, c) {
            return $(d).closest("form").find("[name='" + c + "']")
        }, setInputValIfBlank: function (c, d) {
            if ($.trim($(c).val()) == "") {
                $(c).val(d)
            }
        }, startWith: function (d, e) {
            var c = new RegExp("^" + e);
            return c.test(d)
        }, endWith: function (e, c) {
            var d = new RegExp(c + "$");
            return d.test(e)
        }, objectToString: function (c) {
            var d = "";
            $.each(c, function (f, e) {
                d += (f + ":" + e + ";\n")
            });
            return d
        }, parseFloatValDefaultZero: function (d) {
            if ($.trim($(d).val()) == "") {
                return 0
            } else {
                var c = parseFloat($.trim($(d).val()));
                if (isNaN(c)) {
                    return 0
                } else {
                    return c
                }
            }
        }, notSmallViewport: function () {
            var c = $(window).width();
            return c >= 768
        }, init: function () {
            $.fn.ajaxGetUrl = function (d, f, e) {
                Util.assertNotBlank(d, "URL参数不能为空");
                $("#btn-profile-param").hide();
                var c = $(this);
                c.addClass("ajax-get-container");
                c.attr("data-url", d);
                c.css("min-height", "100px");
                Metronic.blockUI({target: c});
                $.ajax({
                    type: "GET", cache: false, url: d, data: e, dataType: "html", success: function (h) {
                        try {
                            var response = jQuery.parseJSON(h);
                            if(response.code == 200){
                                h = response.content;
                            } else {
                                h = response.msg;
                            }
                        } catch(err){
                            //Global.notify("error", "表单处理异常，请联系管理员");
                        }
                        c.empty();
                        var g = $("<div class='ajax-page-inner'/>").appendTo(c);
                        g.hide();
                        g.html(h);
                        if (f) {
                            f.call(c, h)
                        }
                        Page.initAjaxBeforeShow(g);
                        g.show();
                        //Util.debug('g.show();');
                        g.trigger("afterAjaxPageShow");
                        //Util.debug('g.trigger("afterAjaxPageShow");');
                        FormValidation.initAjax(g);
                        //Util.debug('FormValidation.initAjax(g);');
                        Page.initAjaxAfterShow(g);
                        //Util.debug('Page.initAjaxAfterShow(g);');
                        Grid.initAjax(g);
                        //Util.debug('Grid.initAjax(g);');
                        Metronic.unblockUI(c)
                    },
                    error: function (j, g, h) {
                        //Util.debug(j);
                        c.html("<h4>页面内容加载失败</h4>" + j.responseText);
                        Metronic.unblockUI(c)
                    },
                    statusCode: {
                        403: function () {
                            Global.notify("error", "URL: " + d, "未授权访问")
                        },
                        404: function () {
                            Global.notify("error", "页面未找到：" + d + "，请联系管理员", "请求资源未找到")
                        }
                    }
                });
                return c
            };
            $.fn.ajaxJsonUrl = function (d, f, e) {
                Util.assertNotBlank(d);
                var c = $(this);
                Metronic.blockUI(c);
                $.ajax({
                    traditional: true,
                    type: "GET",
                    cache: false,
                    url: d,
                    dataType: "json",
                    data: e,
                    success: function (g) {
                        if (g.type == "error" || g.type == "warning" || g.type == "failure") {
                            Global.notify("error", g.message)
                        } else {
                            if (f) {
                                f.call(c, g)
                            }
                            json = g
                        }
                        Metronic.unblockUI(c)
                    },
                    error: function (j, g, h) {
                        Global.notify("error", "数据请求异常，请联系管理员", "系统错误");
                        Metronic.unblockUI(c)
                    },
                    statusCode: {
                        403: function () {
                            Global.notify("error", "URL: " + d, "未授权访问")
                        }, 404: function () {
                            Global.notify("error", "请尝试刷新页面试试，如果问题依然请联系管理员", "请求资源未找到")
                        }
                    }
                })
            };
            $.fn.ajaxJsonSync = function (d, f, g) {
                Util.assertNotBlank(d);
                var c = $(this);
                Metronic.blockUI(c);
                var e = null;
                $.ajax({
                    traditional: true,
                    type: "GET",
                    cache: false,
                    async: false,
                    url: d,
                    data: f,
                    contentType: "application/json",
                    dataType: "json",
                    success: function (h) {
                        if (h.type == "error" || h.type == "warning" || h.type == "failure") {
                            Global.notify("error", h.message)
                        } else {
                            if (g) {
                                g.call(c, h)
                            }
                            e = h
                        }
                        Metronic.unblockUI(c)
                    },
                    error: function (k, h, j) {
                        Global.notify("error", "数据请求异常，请联系管理员", "系统错误");
                        Metronic.unblockUI(c)
                    },
                    statusCode: {
                        403: function () {
                            Global.notify("error", "URL: " + d, "未授权访问")
                        }, 404: function () {
                            Global.notify("error", "请尝试刷新页面试试，如果问题依然请联系管理员", "请求资源未找到")
                        }
                    }
                });
                return e
            };
            $.fn.ajaxPostURL = function (d, g, f, c) {
                Util.assertNotBlank(d);
                if (f == undefined) {
                    f = "确认提交数据？"
                }
                if (f) {
                    if (!confirm(f)) {
                        return false
                    }
                }
                var c = $.extend({data: {}}, c);
                var e = $(this);
                Metronic.blockUI(e);
                $.post(encodeURI(d), c.data,function (h, l) {
                    Metronic.unblockUI();
                    if (h.status == 1 || h.code == "200") {
                        Global.notify('success', h.msg);
                        if (g) {
                            g.call(e, h)
                        }
                    } else {
                        Global.notify("error", h.msg)
                        if (c.failure) {
                            c.failure.call(e, h)
                        }
                    }
                }, "json").error(
                    function () {
                        Metronic.unblockUI();
                        Global.notify("error", "请尝试刷新页面试试，如果问题依然请联系管理员", "请求资源未找到");
                    }
                );
            };
            $.fn.ajaxPostForm = function (g, f, d, c) {
                if (f) {
                    if (!confirm(f)) {
                        return false
                    }
                }
                var c = $.extend({data: {}}, c);
                var e = $(this);
                Metronic.blockUI(e);
                e.ajaxSubmit({
                    dataType: "json", method: "post", success: function (h) {
                        Metronic.unblockUI(e);
                        if (h.type == "success") {
                            if (g) {
                                g.call(e, h)
                            }
                        } else {
                            if (h.type == "failure") {
                                bootbox.alert(h.message);
                                if (d) {
                                    d.call(e, h)
                                }
                            } else {
                                bootbox.alert("表单处理异常，请联系管理员");
                                if (d) {
                                    d.call(e, h)
                                }
                            }
                        }
                    }, error: function (l, k, h) {
                        Metronic.unblockUI(e);
                        var j = jQuery.parseJSON(l.responseText);
                        if (j.type == "error") {
                            bootbox.alert(j.message)
                        } else {
                            bootbox.alert("表单处理异常，请联系管理员")
                        }
                        if (d) {
                            d.call(e, j)
                        }
                    }
                })
            };
            $.fn.popupDialog = function (l) {
                var f = $(this);
                var c = f.attr("href");
                if (c == undefined) {
                    c = f.attr("data-url")
                }
                var g = f.attr("title");
                if (g == undefined) {
                    g = "对话框"
                }
                var k = f.attr("modal-size");
                if (k == undefined) {
                    k = "container"
                } else {
                    if (k == "auto") {
                        k = ""
                    }
                }
                var l = $.extend({url: c, postData: {}, title: g, size: k}, l);
                //Util.debug(l);
                Util.assertNotBlank(l.url);
                var modelID = $.trim(makePy(g)).replace(',','_');
                var j = "dialog_level_" + modelID;
                var d = $("#" + j);
                //Util.debug('popupDialog -> d');
                //Util.debug(d.html());
                //Util.debug(d.length);
                if (d.length == 0) {
                    var e = [];
                    e.push('<div id="' + j + '" class="modal ' + l.size + ' fade" tabindex="-1" role="dialog" aria-hidden="true">');
                    e.push('<div class="modal-header">');
                    e.push('<button type="button" class="close"  data-dismiss="modal" aria-hidden="true"></button>');
                    e.push('<button type="button" class="close btn-reload" style="margin-left:10px;margin-right:10px;margin-top:-3px!important;height:16px;width:13px;background-image: url(\'' + themeUrl + "img/portlet-reload-icon.png')!important;\"></button>");
                    e.push('<h4 class="modal-title">' + l.title + "</h4>");
                    e.push("</div>");
                    e.push('<div class="modal-body">');
                    e.push("</div>");
                    e.push('<div class="modal-footer hide">');
                    e.push('<button type="button" class="btn default" data-dismiss="modal">关闭窗口</button>');
                    e.push("</div>");
                    e.push("</div>");
                    var h = f.closest(".panel-content");
                    if (h == undefined) {
                        h = $(".page-container:first")
                    }
                    var d = $(e.join("")).appendTo($("body"));
                    d.find(" > .modal-body").ajaxGetUrl(l.url, false, l.postData);
                    d.modal({modalOverflow:true});
                    d.find(" > .modal-header > .btn-reload").click(function () {
                        d.find(" > .modal-body").ajaxGetUrl(l.url, false, l.postData)
                    });
                } else {
                    d.find(" > .modal-body").ajaxGetUrl(l.url, false, l.postData);
                    d.modal({modalOverflow:true});
                    d.modal("show");
                }
                if (l.callback) {
                    d.data("callback", l.callback)
                }
            }
        }
    }
}();
var BooleanUtil = function () {
    return {
        toBoolean: function (b) {
            if (b) {
                var a = $.type(b);
                if (a === "string" && (b == "true" || b == "1" || b == "y" || b == "yes" || b == "readonly" || b == "checked" || b == "enabled" || b == "enable" || b == "selected")) {
                    return true
                } else {
                    if (a === "number" && (b == 1)) {
                        return true
                    }
                }
            }
            return false
        }
    }
}();
var MathUtil = function () {
    return {
        mul: function (arg1, arg2) {
            if (arg1 == undefined) {
                arg1 = 0
            }
            if (arg2 == undefined) {
                arg2 = 0
            }
            var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
            try {
                m += s1.split(".")[1].length
            } catch (e) {
            }
            try {
                m += s2.split(".")[1].length
            } catch (e) {
            }
            return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m)
        }, div: function (arg1, arg2, fix) {
            if (fix == undefined) {
                fix = 2
            }
            var t1 = 0, t2 = 0, r1, r2;
            try {
                t1 = arg1.toString().split(".")[1].length
            } catch (e) {
            }
            try {
                t2 = arg2.toString().split(".")[1].length
            } catch (e) {
            }
            with (Math) {
                r1 = Number(arg1.toString().replace(".", ""));
                r2 = Number(arg2.toString().replace(".", ""));
                return MathUtil.mul((r1 / r2), pow(10, t2 - t1)).toFixed(fix)
            }
        }, add: function (arg1, arg2) {
            if (arg1 == undefined) {
                arg1 = 0
            }
            if (arg2 == undefined) {
                arg2 = 0
            }
            var r1, r2, m, c;
            try {
                r1 = arg1.toString().split(".")[1].length
            } catch (e) {
                r1 = 0
            }
            try {
                r2 = arg2.toString().split(".")[1].length
            } catch (e) {
                r2 = 0
            }
            c = Math.abs(r1 - r2);
            m = Math.pow(10, Math.max(r1, r2));
            if (c > 0) {
                var cm = Math.pow(10, c);
                if (r1 > r2) {
                    arg1 = Number(arg1.toString().replace(".", ""));
                    arg2 = Number(arg2.toString().replace(".", "")) * cm
                } else {
                    arg1 = Number(arg1.toString().replace(".", "")) * cm;
                    arg2 = Number(arg2.toString().replace(".", ""))
                }
            } else {
                arg1 = Number(arg1.toString().replace(".", ""));
                arg2 = Number(arg2.toString().replace(".", ""))
            }
            return MathUtil.div((arg1 + arg2), m)
        }, sub: function (arg1, arg2) {
            return MathUtil.add(arg1, -Number(arg2))
        }
    }
}();
function scanBarcodeCallback(b, a) {
    $("#" + b).trigger("barcode", [a])
};