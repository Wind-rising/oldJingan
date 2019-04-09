/**
 * Custom module for you to write your own javascript functions
 */

var Biz = function () {
    // private functions & variables
    var CacheDatas = {};
    // public functions
    return {

        init: function () {

        },
        preFetechCacheData: function () {
            setTimeout(function () {
                Biz.initCacheCommodityDatas(true);
                Biz.initCacheCustomerProfileDatas(true);
            }, 2000);
        },
        getGridCommodityEditOptions: function (selectCommodityFunc) {
            return {
                placeholder: '输入编码、名称、首字母拼音过滤...',
                dataInit: function (elem) {
                    var $grid = $(this);
                    var $elem = $(elem);
                    $elem.wrap('<div class="input-icon right"/>');
                    $elem.before('<i class="fa fa-ellipsis-h fa-select-commodity"></i>');
                    $elem.before('<i class="fa fa-times fa-clear-commodity"></i>');
                    var name = $elem.attr("name");
                    var id = name.replace(".display", ".id");
                    var selectCommodity = selectCommodityFunc;
                    if (selectCommodity == undefined) {
                        selectCommodity = function (item) {
                            var $curRow = $elem.closest("tr.jqgrow");
                            var rowdata = $grid.jqGrid("getEditingRowdata");
                            // 强制覆盖已有值
                            rowdata[id] = item.id, rowdata[name] = item.display;
                            $grid.jqGrid("setEditingRowdata", rowdata);
                            var vendorsku = item.vendorsku;
                            if (vendorsku == '' || vendorsku == null) {
                                vendorsku = item.erpsku;
                            } else {
                                vendorsku = item.vendorsku;
                            }
                            $grid.jqGrid("setEditingRowdata", {
                                'erpsku': item.erpsku,
                                'vendor_sku': vendorsku,
                                'unit_price': item.price,
                                'unit': item.unit
                            }, true);
                            // 如果没有值才覆盖
                            $grid.jqGrid("setEditingRowdata", {
                                'quantity_requested': 1
                            }, false);
                            // 更新计算相关价格信息
                            if ($grid.data("gridOptions").updateRowAmount != undefined) {
                                $grid.data("gridOptions").updateRowAmount.call($grid, $curRow);
                            }
                        }
                    }
                    $elem.parent().find(".fa-clear-commodity").click(function () {
                        var rowdata = $grid.jqGrid("getEditingRowdata");
                        // 强制覆盖已有值
                        rowdata[id] = '', rowdata[name] = ''
                        $grid.jqGrid("setEditingRowdata", rowdata);
                    });
                    $elem.parent().find(".fa-select-commodity").click(function () {
                        $(this).popupDialog({
                            url: WEB_ROOT + '/grid_supplier_product/index',
                            title: '选取商品',
                            callback: function (item) {
                                //$elem.attr("title", item.display);
                                selectCommodity.call($elem, item);
                            }
                        })
                    });
                    $elem.autocomplete({
                        autoFocus: true,
                        source: function (request, response) {
                            var data = Biz.queryCacheCommodityDatas(request.term);
                            return response(data);
                        },
                        minLength: 2,
                        select: function (event, ui) {
                            var item = ui.item;
                            this.value = item.display;
                            selectCommodity(item);
                            event.stopPropagation();
                            event.preventDefault();
                            return false;
                        },
                        change: function (event, ui) {
                            if (ui.item == null || ui.item == undefined) {
                                $elem.val("");
                                $elem.focus();
                            }
                        }
                    }).focus(function () {
                        $elem.select();
                    }).dblclick(function () {
                        $elem.parent().find(".fa-select-commodity").click();
                    });
                }
            }
        },

        getGridPOEditOptions: function (selectPOFunc) {
            return {
                placeholder: '请点击选择...',
                dataInit: function (elem) {
                    var $grid = $(this);
                    var $elem = $(elem);
                    $elem.wrap('<div class="input-icon right"/>');
                    $elem.before('<i class="fa fa-ellipsis-h fa-select-commodity"></i>');
                    $elem.before('<i class="fa fa-times fa-clear-commodity"></i>');

                    var selectCommodity = selectPOFunc;
                    if (selectCommodity == undefined) {
                        selectCommodity = function (item) {
                            var $curRow = $elem.closest("tr.jqgrow");
                            var rowdata = $grid.jqGrid("getEditingRowdata");
                            //强制覆盖已有值
                            //rowdata[po_id] = item.id;
                            //$grid.jqGrid("setEditingRowdata", rowdata);
                            $grid.jqGrid("setEditingRowdata", {
                                'po_id': item.po_id,
                                'po_no': item.po_no,
                                'po_line_no': item.line_no,
                                'erpsku': item.erpsku,
                                'quantity': item.quantity_actual,
                                'unit': item.unit,
                                'unit_price': item.unit_price,
                                'status': 'draft'
                            }, true);


                            // 更新计算相关价格信息
                            if ($grid.data("gridOptions").updateRowAmount != undefined) {
                                $grid.data("gridOptions").updateRowAmount.call($grid, $curRow);
                            }

                        }
                    }
                    $elem.parent().find(".fa-clear-commodity").click(function () {
                        var rowdata = $grid.jqGrid("getEditingRowdata");
                        // 强制覆盖已有值
                        $grid.jqGrid("setEditingRowdata", d);
                    });
                    $elem.parent().find(".fa-select-commodity").click(function () {
                        $(this).popupDialog({
                            url: WEB_ROOT + '/grid_po_po/get_po_line_view',
                            title: '选取采购单',
                            callback: function (item) {
                                //$elem.attr("title", item.display);
                                selectCommodity.call($elem, item);
                            }
                        })
                    });


                }
            }
        },

        getStockDatas: function () {
            if (CacheDatas.Stocks == undefined) {
                var url = WEB_ROOT + "/biz/stock/storage-location!findByPage?rows=-1";
                $("body").ajaxJsonSync(url, {}, function (data) {
                    var options = {};
                    $.each(data.content, function (i, item) {
                        options[item.id] = item.display;
                    })
                    options[''] = '';
                    CacheDatas.Stocks = options;
                });
            }
            return CacheDatas.Stocks;
        },
        getBrandDatas: function () {
            if (CacheDatas.Brands == undefined) {
                var url = WEB_ROOT + "/biz/md/brand!findByPage?rows=-1";
                $("body").ajaxJsonSync(url, {}, function (data) {
                    var options = {
                        '': ''
                    };
                    $.each(data.content, function (i, item) {
                        options[item.id] = item.display;
                    })
                    CacheDatas.Brands = options;
                })
            }
            return CacheDatas.Brands;
        },
        initCacheCommodityDatas: function (aysnc) {
            var url = WEB_ROOT + "/grid_supplier_product/commodity";
            var vendor_id = $("#vendor_id").val();
            var vendor_site_id = $("#vendor_site_id").val();
            var line_id = $("#line_id").val();
            var arrKey = vendor_id + '_' + vendor_site_id + '_' + line_id;
            $.ajax({
                async: aysnc,
                type: "GET",
                url: url,
                data: {vendor_id:vendor_id,vendor_site_id:vendor_site_id,line_id:line_id},
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (i, item) {
                        item.label = item.erpsku;
                        item.value = item.erpsku;
                        item.filterSpell = makePy(item.label);
                        if (item.filterSpell == undefined) {
                            item.filterSpell = "";
                        } else {
                            item.filterSpell = item.filterSpell.join(",");
                        }
                    });
                    if(CacheDatas.Commodities == undefined){
                        CacheDatas.Commodities = new Array();
                    }
                    CacheDatas.Commodities[arrKey] = TAFFY(data);
                    CacheDatas.Commodities[arrKey].sort("erpsku");
                },
                error:function(msg){
                    Global.notify('error', msg);
                }
            });
        },
        queryCacheCommodityDatas: function (term) {
            var vendor_id = $("#vendor_id").val();
            var vendor_site_id = $("#vendor_site_id").val();
            var line_id = $("#line_id").val();
            var arrKey = vendor_id + '_' + vendor_site_id + '_' + line_id;
            if (CacheDatas.Commodities == undefined || CacheDatas.Commodities[arrKey] == undefined) {
                Biz.initCacheCommodityDatas(false);
            }
            var query = null;
            if ($.isNumeric(term)) {
                query = [{
                    erpsku: {
                        like: term
                    }
                }, {
                    commodityBarcode: {
                        like: term
                    }
                }];
            } else {
                query = [{
                    title: {
                        like: term
                    }
                }, {
                    filterSpell: {
                        likenocase: term
                    }
                }];
            }
            var result = CacheDatas.Commodities[arrKey](query).order("erpsku");
            return result.get();
        },

        initCacheCustomerProfileDatas: function (aysnc) {
            var url = WEB_ROOT + "/biz/customer/customer-profile!frequentUsedDatas.json";
            $.ajax({
                async: aysnc,
                type: "GET",
                url: url,
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (i, item) {
                        item.id = item.id;
                        item.label = item.display;
                        item.value = item.display;
                        item.filterSpell = makePy(item.label);
                        if (item.filterSpell == undefined) {
                            item.filterSpell = "";
                        } else {
                            item.filterSpell = item.filterSpell.join(",");
                        }
                    });
                    CacheDatas.CustomerProfiles = TAFFY(data);
                    CacheDatas.CustomerProfiles.sort("value");
                }
            });
        },

        queryCacheCustomerProfileDatas: function (term) {
            if (CacheDatas.CustomerProfiles == undefined) {
                Biz.initCacheCustomerProfileDatas(false);
            }
            var query = [{
                label: {
                    like: term
                }
            }, {
                filterSpell: {
                    likenocase: term
                }
            }];
            var result = CacheDatas.CustomerProfiles(query).order("value");
            return result.get();
        },
        //选择ERP的产品
        setupErpProductSelect: function ($form) {
            // 客户元素处理
            $form.find(".fa-select-erp-product").click(function () {
                $(this).popupDialog({
                    url: WEB_ROOT + '/product/dialog',
                    title: '选取商品',
                    callback: function (rowdata) {
                        $form.find("input[name='product_channel[erpsku]']").val(rowdata.erpsku);
                    }
                })
            });
        },
        setupCustomerProfileSelect: function ($form) {
            // 客户元素处理
            $form.find(".fa-select-customer-profile").click(function () {
                $(this).popupDialog({
                    url: WEB_ROOT + '/biz/finance/biz-trade-unit!forward?_to_=selection',
                    title: '选取客户',
                    callback: function (rowdata) {
                        $form.find("input[name='customerProfile.display']").val(rowdata.display);
                        $form.find("input[name='customerProfile.id']").val(rowdata.id);
                    }
                })
            });
            $form.find("input[name='customerProfile.display']").autocomplete({
                autoFocus: true,
                source: function (request, response) {
                    var data = Biz.queryCacheCustomerProfileDatas(request.term);
                    return response(data);
                },
                minLength: 2,
                select: function (event, ui) {
                    var item = ui.item;
                    this.value = item.display;
                    $form.find("input[name='customerProfile.display']").val(item.display);
                    $form.find("input[name='customerProfile.id']").val(item.id);
                    event.stopPropagation();
                    event.preventDefault();
                    return false;
                },
                change: function (event, ui) {
                    if (ui.item == null || ui.item == undefined) {
                        $(this).val("");
                        $(this).focus();
                    }
                }
            }).focus(function () {
                $(this).select();
            }).dblclick(function (event) {
                $form.find(".fa-select-customer-profile").click();
            });
        },

        setupBizTradeUnitSelect: function ($form, selectCallback) {
            // 往来单位选取
            $form.find(".fa-select-biz-trade-unit").each(function () {
                var $trigger = $(this);
                var $text = $trigger.parent().find('input[type="text"]');
                var $hidden = $trigger.parent().find('input[type="hidden"]');

                if (BooleanUtil.toBoolean($text.attr("readonly"))) {
                    return;
                }

                $trigger.click(function () {
                    $(this).popupDialog({
                        url: WEB_ROOT + '/biz/finance/biz-trade-unit!forward?_to_=selection',
                        title: '选取往来单位',
                        callback: function (rowdata) {
                            $text.val(rowdata.display);
                            $hidden.val(rowdata.id);
                            if (selectCallback) {
                                selectCallback.call($trigger, rowdata)
                            }
                        }
                    })
                });

                $text.dblclick(function (event) {
                    $trigger.click();
                });
            })
        }
    };

}();

//新的采购模块搜索
var Bizerpsku = function () {
    // private functions & variables
    var CacheDatas = {};
    // public functions
    return {

        init: function () {

        },
        preFetechCacheData: function () {
            setTimeout(function () {
                Bizerpsku.initCacheCommodityDatas(true);
                Bizerpsku.initCacheCustomerProfileDatas(true);
            }, 2000);
        },
        getGridCommodityEditOptions: function (selectCommodityFunc) {
            return {
                placeholder: '输入编码、名称、首字母拼音过滤...',
                dataInit: function (elem) {
                    var $grid = $(this);
                    var $elem = $(elem);
                    $elem.wrap('<div class="input-icon right"/>');
                    //$elem.before('<i class="fa fa-ellipsis-h fa-select-commodity"></i>');
                    //$elem.before('<i class="fa fa-times fa-clear-commodity"></i>');
                    var name = $elem.attr("name");
                    var id = name.replace(".display", ".id");
                    var selectCommodity = selectCommodityFunc;
                    if (selectCommodity == undefined) {
                        selectCommodity = function (item) {
                            var $curRow = $elem.closest("tr.jqgrow");
                            var rowdata = $grid.jqGrid("getEditingRowdata");
                            // 强制覆盖已有值
                            rowdata[id] = item.id, rowdata[name] = item.display;
                            $grid.jqGrid("setEditingRowdata", rowdata);
                            var vendorsku = item.vendorsku;
                            if (vendorsku == '' || vendorsku == null) {
                                vendorsku = item.erpsku;
                            } else {
                                vendorsku = item.vendorsku;
                            }
                            $grid.jqGrid("setEditingRowdata", {
                                'itemsku': item.erpsku,
                                'vendorsku': vendorsku,
                                'unit_price': item.price,
                                'unit': item.unit
                            }, true);
                            // 如果没有值才覆盖
                            $grid.jqGrid("setEditingRowdata", {
                                'quantity_requested': ''
                            }, false);
                            // 更新计算相关价格信息
                            if ($grid.data("gridOptions").updateRowAmount != undefined) {
                                $grid.data("gridOptions").updateRowAmount.call($grid, $curRow);
                            }
                        }
                    }
                    $elem.parent().find(".fa-clear-commodity").click(function () {
                        var rowdata = $grid.jqGrid("getEditingRowdata");
                        // 强制覆盖已有值
                        rowdata[id] = '', rowdata[name] = ''
                        $grid.jqGrid("setEditingRowdata", rowdata);
                    });
                    $elem.parent().find(".fa-select-commodity").click(function () {
                        $(this).popupDialog({
                            url: WEB_ROOT + '/grid_supplier_product/index',
                            title: '选取商品',
                            callback: function (item) {
                                //$elem.attr("title", item.display);
                                selectCommodity.call($elem, item);
                            }
                        })
                    });
                    $elem.autocomplete({
                        autoFocus: true,
                        source: function (request, response) {
                            var data = Bizerpsku.queryCacheCommodityDatas(request.term);
                            return response(data);
                        },
                        minLength: 2,
                        select: function (event, ui) {
                            var item = ui.item;
                            this.value = item.display;
                            selectCommodity(item);
                            event.stopPropagation();
                            event.preventDefault();
                            return false;
                        },
                        change: function (event, ui) {
                            if (ui.item == null || ui.item == undefined) {
                                $elem.val("");
                                $elem.focus();
                            }
                        }
                    }).focus(function () {
                        $elem.select();
                    }).dblclick(function () {
                        $elem.parent().find(".fa-select-commodity").click();
                    });
                }
            }
        },

        getGridPOEditOptions: function (selectPOFunc) {
            return {
                placeholder: '请点击选择...',
                dataInit: function (elem) {
                    var $grid = $(this);
                    var $elem = $(elem);
                    $elem.wrap('<div class="input-icon right"/>');
                    //$elem.before('<i class="fa fa-ellipsis-h fa-select-commodity"></i>');
                    //$elem.before('<i class="fa fa-times fa-clear-commodity"></i>');

                    var selectCommodity = selectPOFunc;
                    if (selectCommodity == undefined) {
                        selectCommodity = function (item) {
                            var $curRow = $elem.closest("tr.jqgrow");
                            var rowdata = $grid.jqGrid("getEditingRowdata");
                            //强制覆盖已有值
                            //rowdata[po_id] = item.id;
                            //$grid.jqGrid("setEditingRowdata", rowdata);
                            $grid.jqGrid("setEditingRowdata", {
                                'po_id': item.po_id,
                                'po_no': item.po_no,
                                'po_line_no': item.line_no,
                                'itemsku': item.erpsku,
                                'quantity': item.quantity_actual,
                                'unit': item.unit,
                                'unit_price': item.unit_price,
                                'status': 'draft'
                            }, true);


                            // 更新计算相关价格信息
                            if ($grid.data("gridOptions").updateRowAmount != undefined) {
                                $grid.data("gridOptions").updateRowAmount.call($grid, $curRow);
                            }

                        }
                    }
                    $elem.parent().find(".fa-clear-commodity").click(function () {
                        var rowdata = $grid.jqGrid("getEditingRowdata");
                        // 强制覆盖已有值
                        $grid.jqGrid("setEditingRowdata", d);
                    });
                    $elem.parent().find(".fa-select-commodity").click(function () {
                        $(this).popupDialog({
                            url: WEB_ROOT + '/grid_po_po/get_po_line_view',
                            title: '选取采购单',
                            callback: function (item) {
                                //$elem.attr("title", item.display);
                                selectCommodity.call($elem, item);
                            }
                        })
                    });


                }
            }
        },
        getStockDatas: function () {
            if (CacheDatas.Stocks == undefined) {
                var url = WEB_ROOT + "/biz/stock/storage-location!findByPage?rows=-1";
                $("body").ajaxJsonSync(url, {}, function (data) {
                    var options = {};
                    $.each(data.content, function (i, item) {
                        options[item.id] = item.display;
                    });
                    options[''] = '';
                    CacheDatas.Stocks = options;
                });
            }
            return CacheDatas.Stocks;
        },
        getBrandDatas: function () {
            if (CacheDatas.Brands == undefined) {
                var url = WEB_ROOT + "/biz/md/brand!findByPage?rows=-1";
                $("body").ajaxJsonSync(url, {}, function (data) {
                    var options = {
                        '': ''
                    };
                    $.each(data.content, function (i, item) {
                        options[item.id] = item.display;
                    })
                    CacheDatas.Brands = options;
                })
            }
            return CacheDatas.Brands;
        },
        initCacheCommodityDatas: function (aysnc) {
            var url = WEB_ROOT + "/admin/depot/apiservice/searcherpskuByvendorid";
            var vendor_id = $("#vendor_id").val();
            var vendor_site_id = $("#vendor_site_id").val();
            var line_id = $("#line_id").val();
            if(line_id==''){
                Global.notify('error', '请先选择项目');
            }
            var arrKey = vendor_id + '_' + vendor_site_id + '_' + line_id;
            $.ajax({
                async: aysnc,
                type: "GET",
                url: url,
                data: {vendor_id:vendor_id,line_id:line_id},
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (i, item) {
                        item.label = item.erpsku;
                        item.value = item.erpsku;
                        item.filterSpell = makePy(item.label);
                        if (item.filterSpell == undefined) {
                            item.filterSpell = "";
                        } else {
                            item.filterSpell = item.filterSpell.join(",");
                        }
                    });
                    if(CacheDatas.Commodities == undefined){
                        CacheDatas.Commodities = new Array();
                    }
                    CacheDatas.Commodities[arrKey] = TAFFY(data);
                    CacheDatas.Commodities[arrKey].sort("erpsku");
                },
                error:function(msg){
                    Global.notify('error', msg);
                }
            });
        },
        queryCacheCommodityDatas: function (term) {
            var vendor_id = $("#vendor_id").val();
            var vendor_site_id = $("#vendor_site_id").val();
            var line_id = $("#line_id").val();
            var arrKey = vendor_id + '_' + vendor_site_id + '_' + line_id;
            if (CacheDatas.Commodities == undefined || CacheDatas.Commodities[arrKey] == undefined) {
                Bizerpsku.initCacheCommodityDatas(false);
            }
            var query = null;
            if ($.isNumeric(term)) {
                query = [{
                    itemsku: {
                        like: term
                    }
                }, {
                    commodityBarcode: {
                        like: term
                    }
                }];
            } else {
                query = [{
                    title: {
                        like: term
                    }
                }, {
                    filterSpell: {
                        likenocase: term
                    }
                }];
            }
            var result = CacheDatas.Commodities[arrKey](query).order("erpsku");
            return result.get();
        },

        initCacheCustomerProfileDatas: function (aysnc) {
            var url = WEB_ROOT + "/biz/customer/customer-profile!frequentUsedDatas.json";
            $.ajax({
                async: aysnc,
                type: "GET",
                url: url,
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (i, item) {
                        item.id = item.id;
                        item.label = item.display;
                        item.value = item.display;
                        item.filterSpell = makePy(item.label);
                        if (item.filterSpell == undefined) {
                            item.filterSpell = "";
                        } else {
                            item.filterSpell = item.filterSpell.join(",");
                        }
                    });
                    CacheDatas.CustomerProfiles = TAFFY(data);
                    CacheDatas.CustomerProfiles.sort("value");
                }
            });
        },

        queryCacheCustomerProfileDatas: function (term) {
            if (CacheDatas.CustomerProfiles == undefined) {
                Bizerpsku.initCacheCustomerProfileDatas(false);
            }
            var query = [{
                label: {
                    like: term
                }
            }, {
                filterSpell: {
                    likenocase: term
                }
            }];
            var result = CacheDatas.CustomerProfiles(query).order("value");
            return result.get();
        },
        //选择ERP的产品
        setupErpProductSelect: function ($form) {
            // 客户元素处理
            $form.find(".fa-select-erp-product").click(function () {
                $(this).popupDialog({
                    url: WEB_ROOT + '/product/dialog',
                    title: '选取商品',
                    callback: function (rowdata) {
                        $form.find("input[name='product_channel[erpsku]']").val(rowdata.erpsku);
                    }
                })
            });
        },
        setupCustomerProfileSelect: function ($form) {
            // 客户元素处理
            $form.find(".fa-select-customer-profile").click(function () {
                $(this).popupDialog({
                    url: WEB_ROOT + '/biz/finance/biz-trade-unit!forward?_to_=selection',
                    title: '选取客户',
                    callback: function (rowdata) {
                        $form.find("input[name='customerProfile.display']").val(rowdata.display);
                        $form.find("input[name='customerProfile.id']").val(rowdata.id);
                    }
                })
            });
            $form.find("input[name='customerProfile.display']").autocomplete({
                autoFocus: true,
                source: function (request, response) {
                    var data = Biz.queryCacheCustomerProfileDatas(request.term);
                    return response(data);
                },
                minLength: 2,
                select: function (event, ui) {
                    var item = ui.item;
                    this.value = item.display;
                    $form.find("input[name='customerProfile.display']").val(item.display);
                    $form.find("input[name='customerProfile.id']").val(item.id);
                    event.stopPropagation();
                    event.preventDefault();
                    return false;
                },
                change: function (event, ui) {
                    if (ui.item == null || ui.item == undefined) {
                        $(this).val("");
                        $(this).focus();
                    }
                }
            }).focus(function () {
                $(this).select();
            }).dblclick(function (event) {
                $form.find(".fa-select-customer-profile").click();
            });
        },

        setupBizTradeUnitSelect: function ($form, selectCallback) {
            // 往来单位选取
            $form.find(".fa-select-biz-trade-unit").each(function () {
                var $trigger = $(this);
                var $text = $trigger.parent().find('input[type="text"]');
                var $hidden = $trigger.parent().find('input[type="hidden"]');

                if (BooleanUtil.toBoolean($text.attr("readonly"))) {
                    return;
                }

                $trigger.click(function () {
                    $(this).popupDialog({
                        url: WEB_ROOT + '/biz/finance/biz-trade-unit!forward?_to_=selection',
                        title: '选取往来单位',
                        callback: function (rowdata) {
                            $text.val(rowdata.display);
                            $hidden.val(rowdata.id);
                            if (selectCallback) {
                                selectCallback.call($trigger, rowdata)
                            }
                        }
                    })
                });

                $text.dblclick(function (event) {
                    $trigger.click();
                });
            })
        }
    };
}();

//新的采购模块搜索
var BizerpskuByPono = function () {
    // private functions & variables
    var CacheDatas = {};
    // public functions
    return {

        init: function () {

        },
        preFetechCacheData: function () {
            setTimeout(function () {
                BizerpskuByPono.initCacheCommodityDatas(true);
                BizerpskuByPono.initCacheCustomerProfileDatas(true);
            }, 2000);
        },
        getGridCommodityEditOptions: function (selectCommodityFunc) {
            return {
                placeholder: '输入编码、名称、首字母拼音过滤...',
                dataInit: function (elem) {
                    var $grid = $(this);
                    var $elem = $(elem);
                    $elem.wrap('<div class="input-icon right"/>');
                    //$elem.before('<i class="fa fa-ellipsis-h fa-select-commodity"></i>');
                    //$elem.before('<i class="fa fa-times fa-clear-commodity"></i>');
                    var name = $elem.attr("name");
                    var id = name.replace(".display", ".id");
                    var selectCommodity = selectCommodityFunc;
                    if (selectCommodity == undefined) {
                        selectCommodity = function (item) {
                            var $curRow = $elem.closest("tr.jqgrow");
                            var rowdata = $grid.jqGrid("getEditingRowdata");
                            // 强制覆盖已有值
                            rowdata[id] = item.id;
                            rowdata[name] = item.display;
                            //alert(item.id);
                            $grid.jqGrid("setEditingRowdata", rowdata);
                            $grid.jqGrid("setEditingRowdata", {
                                'itemsku': item.itemsku,
                                'weight':item.weight,
                                'cubic':item.cubic
                            },true);
                            //alert($elem.attr("id"));
                            var dataid = $elem.attr("id").replace('_itemsku','');
                            //alert(dataid);

                            // 如果没有值才覆盖
                            $grid.jqGrid('setCell',dataid,'quantity_queued',item.quantity_queued);
                            $grid.jqGrid('setCell',dataid,'unit',item.unit);
                            $grid.jqGrid('setCell',dataid,'unit_price',item.unit_price);
                            $grid.jqGrid("setEditingRowdata", {
                                'quantity_requested': ''
                            }, false);
                            // 更新计算相关价格信息
                            if ($grid.data("gridOptions").updateRowAmount != undefined) {
                                $grid.data("gridOptions").updateRowAmount.call($grid, $curRow);
                            }
                        }
                    }
                    $elem.parent().find(".fa-clear-commodity").click(function () {
                        var rowdata = $grid.jqGrid("getEditingRowdata");
                        // 强制覆盖已有值
                        rowdata[id] = '', rowdata[name] = ''
                        $grid.jqGrid("setEditingRowdata", rowdata);
                    });
                    $elem.parent().find(".fa-select-commodity").click(function () {
                        $(this).popupDialog({
                            url: WEB_ROOT + '/grid_supplier_product/index',
                            title: '选取商品',
                            callback: function (item) {
                                //$elem.attr("title", item.display);
                                selectCommodity.call($elem, item);
                            }
                        })
                    });
                    $elem.autocomplete({
                        autoFocus: true,
                        source: function (request, response) {
                            var data = BizerpskuByPono.queryCacheCommodityDatas(request.term);
                            return response(data);
                        },
                        minLength: 2,
                        select: function (event, ui) {
                            var item = ui.item;
                            this.value = item.display;
                            selectCommodity(item);
                            event.stopPropagation();
                            event.preventDefault();
                            return false;
                        },
                        change: function (event, ui) {
                            if (ui.item == null || ui.item == undefined) {
                                $elem.val("");
                                $elem.focus();
                            }
                        }
                    }).focus(function () {
                        $elem.select();
                    }).dblclick(function () {
                        $elem.parent().find(".fa-select-commodity").click();
                    });
                }
            }
        },

        getGridPOEditOptions: function (selectPOFunc) {
            return {
                placeholder: '请点击选择...',
                dataInit: function (elem) {
                    var $grid = $(this);
                    var $elem = $(elem);
                    $elem.wrap('<div class="input-icon right"/>');
                    //$elem.before('<i class="fa fa-ellipsis-h fa-select-commodity"></i>');
                    //$elem.before('<i class="fa fa-times fa-clear-commodity"></i>');

                    var selectCommodity = selectPOFunc;
                    if (selectCommodity == undefined) {
                        selectCommodity = function (item) {
                            var $curRow = $elem.closest("tr.jqgrow");
                            var rowdata = $grid.jqGrid("getEditingRowdata");
                            //强制覆盖已有值
                            //rowdata[po_id] = item.id;
                            //$grid.jqGrid("setEditingRowdata", rowdata);
                            $grid.jqGrid("setEditingRowdata", {
                                'po_id': item.po_id,
                                'po_no': item.po_no,
                                'po_line_no': item.line_no,
                                'itemsku': item.item,
                                'quantity': item.quantity_actual,
                                'unit': item.unit,
                                'unit_price': item.unit_price,
                                'status': 'draft'
                            }, true);

                            // 更新计算相关价格信息
                            if ($grid.data("gridOptions").updateRowAmount != undefined) {
                                $grid.data("gridOptions").updateRowAmount.call($grid, $curRow);
                            }

                        }
                    }
                    $elem.parent().find(".fa-clear-commodity").click(function () {
                        var rowdata = $grid.jqGrid("getEditingRowdata");
                        // 强制覆盖已有值
                        $grid.jqGrid("setEditingRowdata", d);
                    });
                    $elem.parent().find(".fa-select-commodity").click(function () {
                        $(this).popupDialog({
                            url: WEB_ROOT + '/grid_po_po/get_po_line_view',
                            title: '选取采购单',
                            callback: function (item) {
                                //$elem.attr("title", item.display);
                                selectCommodity.call($elem, item);
                            }
                        })
                    });


                }
            }
        },
        getStockDatas: function () {
            if (CacheDatas.Stocks == undefined) {
                var url = WEB_ROOT + "/biz/stock/storage-location!findByPage?rows=-1";
                $("body").ajaxJsonSync(url, {}, function (data) {
                    var options = {};
                    $.each(data.content, function (i, item) {
                        options[item.id] = item.display;
                    });
                    options[''] = '';
                    CacheDatas.Stocks = options;
                });
            }
            return CacheDatas.Stocks;
        },
        getBrandDatas: function () {
            if (CacheDatas.Brands == undefined) {
                var url = WEB_ROOT + "/biz/md/brand!findByPage?rows=-1";
                $("body").ajaxJsonSync(url, {}, function (data) {
                    var options = {
                        '': ''
                    };
                    $.each(data.content, function (i, item) {
                        options[item.id] = item.display;
                    })
                    CacheDatas.Brands = options;
                })
            }
            return CacheDatas.Brands;
        },
        initCacheCommodityDatas: function (aysnc) {
            var url = WEB_ROOT + "/admin/depot/apiservice/searcherpskuBypono";
            var po_no = $("#po_no").val();
            if(po_no==''){
                Global.notify('error', '请先选择采购单');
            }
            var arrKey = po_no;
            $.ajax({
                async: aysnc,
                type: "GET",
                url: url,
                data: {po_no:po_no},
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (i, item) {
                        item.label = item.itemsku;
                        item.value = item.itemsku;
                        item.filterSpell = makePy(item.label);
                        if (item.filterSpell == undefined) {
                            item.filterSpell = "";
                        } else {
                            item.filterSpell = item.filterSpell.join(",");
                        }
                    });
                    if(CacheDatas.Commodities == undefined){
                        CacheDatas.Commodities = new Array();
                    }
                    CacheDatas.Commodities[arrKey] = TAFFY(data);
                    CacheDatas.Commodities[arrKey].sort("itemsku");
                },
                error:function(msg){
                    Global.notify('error', msg);
                }
            });
        },
        queryCacheCommodityDatas: function (term) {
            var po_no = $("#po_no").val();
            var arrKey = po_no;
            if (CacheDatas.Commodities == undefined || CacheDatas.Commodities[arrKey] == undefined) {
                BizerpskuByPono.initCacheCommodityDatas(false);
            }
            var query = null;
            if ($.isNumeric(term)) {
                query = [{
                    itemsku: {
                        like: term
                    }
                }, {
                    commodityBarcode: {
                        like: term
                    }
                }];
            } else {
                query = [{
                    title: {
                        like: term
                    }
                }, {
                    filterSpell: {
                        likenocase: term
                    }
                }];
            }
            var result = CacheDatas.Commodities[arrKey](query).order("itemsku");
            return result.get();
        },

        initCacheCustomerProfileDatas: function (aysnc) {
            var url = WEB_ROOT + "/biz/customer/customer-profile!frequentUsedDatas.json";
            $.ajax({
                async: aysnc,
                type: "GET",
                url: url,
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (i, item) {
                        item.id = item.id;
                        item.label = item.display;
                        item.value = item.display;
                        item.filterSpell = makePy(item.label);
                        if (item.filterSpell == undefined) {
                            item.filterSpell = "";
                        } else {
                            item.filterSpell = item.filterSpell.join(",");
                        }
                    });
                    CacheDatas.CustomerProfiles = TAFFY(data);
                    CacheDatas.CustomerProfiles.sort("value");
                }
            });
        },

        queryCacheCustomerProfileDatas: function (term) {
            if (CacheDatas.CustomerProfiles == undefined) {
                BizerpskuByPono.initCacheCustomerProfileDatas(false);
            }
            var query = [{
                label: {
                    like: term
                }
            }, {
                filterSpell: {
                    likenocase: term
                }
            }];
            var result = CacheDatas.CustomerProfiles(query).order("value");
            return result.get();
        },
        //选择ERP的产品
        setupErpProductSelect: function ($form) {
            // 客户元素处理
            $form.find(".fa-select-erp-product").click(function () {
                $(this).popupDialog({
                    url: WEB_ROOT + '/product/dialog',
                    title: '选取商品',
                    callback: function (rowdata) {
                        $form.find("input[name='product_channel[erpsku]']").val(rowdata.itemsku);
                    }
                })
            });
        },
        setupCustomerProfileSelect: function ($form) {
            // 客户元素处理
            $form.find(".fa-select-customer-profile").click(function () {
                $(this).popupDialog({
                    url: WEB_ROOT + '/biz/finance/biz-trade-unit!forward?_to_=selection',
                    title: '选取客户',
                    callback: function (rowdata) {
                        $form.find("input[name='customerProfile.display']").val(rowdata.display);
                        $form.find("input[name='customerProfile.id']").val(rowdata.id);
                    }
                })
            });
            $form.find("input[name='customerProfile.display']").autocomplete({
                autoFocus: true,
                source: function (request, response) {
                    var data = Biz.queryCacheCustomerProfileDatas(request.term);
                    return response(data);
                },
                minLength: 2,
                select: function (event, ui) {
                    var item = ui.item;
                    this.value = item.display;
                    $form.find("input[name='customerProfile.display']").val(item.display);
                    $form.find("input[name='customerProfile.id']").val(item.id);
                    event.stopPropagation();
                    event.preventDefault();
                    return false;
                },
                change: function (event, ui) {
                    if (ui.item == null || ui.item == undefined) {
                        $(this).val("");
                        $(this).focus();
                    }
                }
            }).focus(function () {
                $(this).select();
            }).dblclick(function (event) {
                $form.find(".fa-select-customer-profile").click();
            });
        },

        setupBizTradeUnitSelect: function ($form, selectCallback) {
            // 往来单位选取
            $form.find(".fa-select-biz-trade-unit").each(function () {
                var $trigger = $(this);
                var $text = $trigger.parent().find('input[type="text"]');
                var $hidden = $trigger.parent().find('input[type="hidden"]');

                if (BooleanUtil.toBoolean($text.attr("readonly"))) {
                    return;
                }

                $trigger.click(function () {
                    $(this).popupDialog({
                        url: WEB_ROOT + '/biz/finance/biz-trade-unit!forward?_to_=selection',
                        title: '选取往来单位',
                        callback: function (rowdata) {
                            $text.val(rowdata.display);
                            $hidden.val(rowdata.id);
                            if (selectCallback) {
                                selectCallback.call($trigger, rowdata)
                            }
                        }
                    })
                });

                $text.dblclick(function (event) {
                    $trigger.click();
                });
            })
        }
    };
}();
/*******************************************************************************
 * Usage
 ******************************************************************************/
// Custom.init();
// Custom.doSomeStuff();
