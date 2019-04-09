String.prototype.stripHTML = function () {
    var reTag = /<(?:.|\s)*?>/g;
    return this.replace(reTag, "");
}
var Global = function () {
    var a;
    return {
        init: function () {
            $('.JS_spinner').spinner();
            jQuery("body").on("click", 'a[data-toggle="modal-ajaxify"],a[target="modal-ajaxify"]', function (g) {
                g.preventDefault();
                $(this).popupDialog()
            });
            //jQuery("body").on("click", 'a[data-toggle="onepage-ajaxify"],a[target="onepage-ajaxify"]', function (g) {
            //    var $dom_id = $(this).attr('data-target-id');
            //    var c = $(this).attr("href");
            //    if (c == undefined) {
            //        c = $(this).attr("data-url")
            //    }
            //    g.preventDefault();
            //    Util.assertNotBlank(c);
            //    $("#" + $dom_id).ajaxGetUrl(c, false);
            //});
            $.fn.daterangepicker.defaults = {
                opens: (Metronic.isRTL() ? "left" : "right"),
                startDate: moment(startDate),
                endDate: moment(endDate),
                dateLimit: {days: 365},
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    "今天": [moment(), moment()],
                    "昨天": [moment().subtract("days", 1), moment().subtract("days", 1)],
                    "最近一周": [moment().subtract("days", 6), moment()],
                    "最近一月": [moment().subtract("days", 29), moment()],
                    "最近一季度": [moment().subtract("days", 89), moment()],
                    "本月": [moment().startOf("month"), moment().endOf("month")],
                    "上月": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")]
                },
                buttonClasses: ["btn"],
                applyClass: "green",
                cancelClass: "default",
                format: "YYYY-MM-DD",
                separator: " to ",
                locale: {
                    applyLabel: "确定",
                    fromLabel: "从",
                    toLabel: "到",
                    customRangeLabel: "自由选取",
                    daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
                    monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
                    firstDay: 1
                }
            };
            (function (g) {
                (jQuery.browser = jQuery.browser || {}).mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(g) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(g.substr(0, 4))
            })(navigator.userAgent || navigator.vendor || window.opera);
            if ($.fn.editable) {
                $.fn.editable.defaults.inputclass = "form-control"
            }
            $(window).resize(function () {
                if ($.fn.jqGrid) {
                    Grid.refreshWidth()
                }
            });
            toastr.options = {
                tapToDismiss: false,
                closeButton: true,
                positionClass: "toast-bottom-right",
                extendedTimeOut: 600000
            };
            var e = $(".page-sidebar li > a");
            $.each(e, function () {
                var g = $(this);
                g.attr("data-py", $.trim(makePy(g.text())))
            });
            //-------------------菜单搜索------------------------------------
            $('.sidebar-search input[name="search"]').autocomplete({
                autoFocus: true,
                source: function (h, g) {
                    console.log(g);
                    var return_source = g(e.map(function () {
                        var l = h.term.toLowerCase();
                        var i = $(this);
                        var k = i.text();
                        var j = i.attr("data-py").toLowerCase();
                        if (j.indexOf(l) > -1 || k.indexOf(l) > -1) {
                            return {label: $.trim(k), link: i, href: i.attr("href")};
                        }
                    }));
                    return return_source;
                },
                minLength: 1,
                select: function (h, i) {
                    var g = i.item;
                    window.location.href = i.item.href;
                    $(this).parent().find(".submit").data("link", g.link);
                    g.link.click();
                    return true
                }
            }).focus(function () {
                $(this).select()
            }).val("").focus();
            //-------------------菜单搜索EOF------------------------------------

            //全选
            $('body').on('click', '.JS_checkall', function () {
                $(this).parent().parent().find('.JS_checkbox').each(function () {
                    $(this).prop('checked', true);
                });
                jQuery.uniform.update();
            });
            //取消全选
            $('body').on('click', '.JS_uncheckall', function () {
                $(this).parent().parent().find('.JS_checkbox').each(function () {
                    $(this).prop('checked', false);
                });
                jQuery.uniform.update();
            });
            if ($('.search_select,.JS_select2').length > 0) {
                $('.search_select,.JS_select2').select2();
            }
            //确认删除
            $('body').on('click', '.JS_delete', function () {
                return confirm('确认删除？');
            });
            //选择产品线
            $('body').on('click', '.JS_select_product_line', function () {
                var i = 0;
                $('.JS_product_lines').each(function () {
                    if ($(this).prop('checked')) {
                        i++;
                    }
                });
                if (i == 0) {
                    $.gritter.add({
                        title: '提示信息',
                        text: '请先选择产品线.'
                    });
                    return false;
                }
                $("#jqgrid_table").trigger("reloadGrid");
                $('#myModal').modal('hide')
            });
            $('.date-picker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                language: 'zh-CN',
                isRTL: Metronic.isRTL(),
                pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
            });
            $('.date-picker2').datepicker({ // moved from weekly/view.php by Horse, 2015-9-6
                format: "yyyy-mm-dd",
                autoclose: true,
                language: 'zh-CN',
                isRTL: Metronic.isRTL(),
                calendarWeeks:true,
                pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
            }).on('changeDate', function (ev) {
                var year=ev.date.getFullYear();
                var month=(ev.date.getMonth()+1);
                var day=ev.date.getDate();
                var date_val=year+"-"+month+"-"+day;
                $('#tdateold').val(date_val);
                $('#date_form').submit();
            });
            $('.date-picker3').datepicker({// added by horse, 2015-7-31
                format: "yyyy-mm-dd",
                autoclose: true,
                language: 'zh-CN',
                isRTL: Metronic.isRTL(),
                pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
            }).on('changeDate', function (ev) {
                var date_val = moment(ev.date).format('YYYY-MM-DD');
                $('#tdate3old').val(date_val);
                $('#date3_form').submit();
            });
            $('.months-picker').datepicker({
                format: "yyyy-mm",
                autoclose: true,
                viewMode: "months",
                minViewMode: "months",
                language: 'zh-CN',
                isRTL: Metronic.isRTL(),
                pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
            });
            $('.JS_months-picker').datepicker({
                format: "yyyy-mm",
                autoclose: true,
                viewMode: "months",
                endDate: '+2d',
                minViewMode: "months",
                language: 'zh-CN',
                isRTL: Metronic.isRTL(),
                pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
            }).on('changeDate', function (ev) {
                var date_val = moment(ev.date).format('YYYY-MM');
                $('#tmonthold').val(date_val);
                $('#month_form').submit();
            });
            $(".datetime-picker").datetimepicker({
                autoclose: true,
                isRTL: Metronic.isRTL(),
                format: "yyyy-mm-dd hh:ii",
                pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
            });
            //表单验证
            var form1 = $('.JS_form_valid');
            if (form1.length > 0) {
                form1.each(function () {
                    var frm = $(this);
                    var options = $.extend(true, frm.data("formOptions") || {});
                    frm.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block help-block-error', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input

                        invalidHandler: function (event, validator) { //display error alert on form submit
                            Metronic.scrollTo(frm, -200);
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
                            var options = $.extend(true, frm.data("formOptions") || {});
                            if (options.submitHandler) {
                                if(!options.submitHandler.call(this, form)){
                                    return false
                                }
                            }
                            form.submit();
                        }
                    });
                });
            }
            if ($('#reportrange').length > 0) {
                var currentLang = 'zh-cn';
                moment.lang(currentLang);
                $('#reportrange').daterangepicker($.fn.daterangepicker.defaults,
                    function (start, end) {
                        $('#date_range').val(start.format('YYYY-MM-DD') + '~' + end.format('YYYY-MM-DD'));
                        $('#reportrange span').html(start.format('L') + ' ~ ' + end.format('L'));
                        $('#search_form').submit();
                    }
                );

                //Set the initial state of the picker label
                $('#reportrange span').html(startDate + ' ~' + endDate);
            }
            $('#tmonth').change(function () {
                //alert($('#tmonth').val());
                $('#tmonthold').val('<?php echo BizApp::instance()->global_month;?>');
                if ($('#tmonth').val() != $('#tmonthold').val()) {
                    $('#tmonthold').val($('#tmonth').val());
                    $('#month_form').submit();
                }
            });

        }, findUserProfileParams: function (b) {
            return a[b]
        }, setUserProfileParams: function (b, c) {
            a[b] = c;
        }, autoCloseContainer: function (i, g) {
            var d = $(i);
            if (d.attr("data-prevent-close") == undefined || d.attr("data-prevent-close") == "false") {
                var e = d.closest(".tabbable-secondary");
                if (e.length == 0) {
                    var j = d.closest(".modal");
                    if (j.size() > 0) {
                        j.modal("hide")
                    } else {
                        var l = d.closest(".tab-closable");
                        if (l.length > 0) {
                            l.parent(".tab-content").parent().find(" > .nav li.active .close").click()
                        } else {
                            var k = d.closest(".panel-content");
                            var b = k.attr("data-url");
                            if (b.indexOf("bpm-task!show") > -1) {
                                $("#layout-nav .btn-close-active").click()
                            } else {
                                k.ajaxGetUrl(b)
                            }
                        }
                    }
                } else {
                    if (e.find(" > ul.nav > li").not(".tools").size() < 2) {
                        var l = d.closest(".tab-closable");
                        if (l.length > 0) {
                            l.parent(".tab-content").parent().find(" > .nav li.active .close").click()
                        } else {
                            $("#layout-nav .btn-close-active").click()
                        }
                    } else {
                        var c = d.closest("form").find("input[name='id']").val();
                        if (c && c != "") {
                            e.find(" > ul.nav > li.tools > .reload").click()
                        } else {
                            var h = d.closest(".tabbable-primary");
                            var f = h.find(" > ul.nav > li.active > a");
                            var b = Util.AddOrReplaceUrlParameter(f.attr("data-url"), "id", g.userdata.id);
                            f.attr("data-url", b);
                            h.find(" > ul.nav > li.tools > .reload").click()
                        }
                    }
                }
            }
        }, doSomeStuff: function () {
            myFunc();
        }, notify: function (b, c, d) {
            if (b == "error") {
                toastr.options.timeOut = 5000;
                toastr.options.positionClass = "toast-bottom-center"
            } else {
                toastr.options.positionClass = "toast-bottom-right"
            }
            if (d == undefined) {
                d = ""
            }
            toastr[b](c, d)
        }, addOrActivePanel: function (e, c) {
            var f = "欢迎访问";
            if (e.size() > 0) {
                c = e.attr("href");
                if (c == undefined) {
                    c = e.attr("data-url")
                }
                f = e.text()
            }
            var h = $("#layout-nav");
            var g = h.next(".tab-content");
            var l = g.find("> div[data-url='" + c + "']");
            if (l.length == 0) {
                l = $('<div data-url="' + c + '" class="panel-content"></div>').appendTo(g);
                l.ajaxGetUrl(c)
            } else {
                l.show()
            }
            g.find("> div").not(l).hide();
            var d = h.find(" > .btn-group > ul.dropdown-menu");
            var i = d.find("> li > a[href='" + c + "']");
            if (i.length == 0) {
                i = $('<a href="' + c + '">' + f + '<span class="badge badge-default">X</span></a>').appendTo(d).wrap("<li/>");
                i.find(".badge").click(function (p) {
                    p.preventDefault();
                    var o = false;
                    l.find("form[method='post']:not(.form-track-disabled)[form-data-modified='true']").each(function () {
                        var r = $(this);
                        if (!confirm("当前表单有修改数据未保存，确认离开当前表单吗？")) {
                            o = true;
                            return false
                        }
                    });
                    if (!o) {
                        i.parent("li").remove();
                        l.remove();
                        var n = 1;
                        d.find("> li").each(function () {
                            var r = $(this).attr("count");
                            if (r) {
                                if (Number(r) > n) {
                                    n = Number(r)
                                }
                            }
                        });
                        var q = d.find("> li[count='" + n + "'] > a");
                        if (q.length > 0) {
                            q.click()
                        } else {
                            $("#layout-nav >  li > .btn-dashboard").click()
                        }
                    }
                });
                i.click(function (n) {
                    n.preventDefault();
                    e.click()
                });
                var b = $(".page-sidebar-menu").find("a[href='" + c + "']");
                var m = '<li><a href="' + c + '" title="刷新当前页面">' + f + "</a></li>";
                if (b.length > 0) {
                    var k = b.parent("li").parent(".sub-menu");
                    while (k.length > 0) {
                        var f = k.prev("a").children("span.title").text();
                        m = '<li class="hidden-inline-xs"><a href="#" title="TODO">' + f + '</a> <i class="fa fa-angle-right"></i></li>' + m;
                        k = k.parent("li").parent(".sub-menu")
                    }
                }
                m = '<li><a href="#dashboard" class="btn-dashboard"><i class="fa fa-home"></i></a></li> ' + m;
                i.data("path", m)
            }
            var j = 1;
            d.find("> li").each(function () {
                $(this).removeClass("active");
                var n = $(this).attr("count");
                if (n) {
                    if (Number(n) > j) {
                        j = Number(n)
                    }
                }
            });
            i.parent("li").addClass("active");
            i.parent("li").attr("count", j + 1);
            h.find("> li:not(.btn-group)").remove();
            h.append(i.data("path"));
            h.find("> li:not(.btn-group) > a[href='" + c + "']").click(function (n) {
                n.preventDefault();
                l.ajaxGetUrl(c)
            });
        }, addOrActiveTab: function (c, f) {
            var b = c.parent("div");
            var e = "tab_" + Util.hashCode(f.url);
            var g = $("#" + e);
            if ($("#" + e).length == 0) {
                var h = $('<li><a id="' + e + '" data-toggle="tab" href="' + f.url + '">' + f.title + ' <button class="close" type="button" style="margin-left:8px"></button></a></li>');
                c.append(h);
                $("#" + e).click();
                var d = c.parent().find(h.find("a").attr("href"));
                d.addClass("tab-closable");
                h.find(".close").click(function () {
                    var j = false;
                    d.find("form[method='post']:not(.form-track-disabled)[form-data-modified='true']").each(function () {
                        var l = $(this);
                        if (!confirm("当前表单有修改数据未保存，确认离开当前表单吗？")) {
                            j = true;
                            return false
                        }
                    });
                    if (!j) {
                        d.remove();
                        h.remove();
                        var i = 0;
                        var k = c.find("li:not(.tools) > a");
                        k.each(function () {
                            var l = $(this).attr("click-idx");
                            if (l && Number(l) > i) {
                                i = Number(l)
                            }
                        });
                        if (i == 0) {
                            k.first().click()
                        } else {
                            k.filter("[click-idx='" + i + "']").click()
                        }
                    }
                })
            } else {
                $("#" + e).tab("show")
            }
        }, fixCloneElements: function (b) {
            b.find(".date-picker").each(function () {
                $(this).attr("id", "").removeData().off();
                $(this).find("button").removeData().off();
                $(this).find("input").removeData().off();
                $(this).datepicker({language: "zh-CN", autoclose: true, format: $(this).attr("data-date-format")})
            })
        }
    }
}();