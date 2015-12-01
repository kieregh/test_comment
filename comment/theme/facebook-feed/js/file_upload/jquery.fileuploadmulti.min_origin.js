/*!
 * jQuery Upload File Plugin
 * version: 3.1.2
 * @requires jQuery v1.5 or later & form plugin
 * Copyright (c) 2013 Ravishanker Kusuma
 * http://hayageek.com/jquery-multiple-file-upload/
 */
 
 !function(a) {
    if (void 0 == a.fn.ajaxForm) a.getScript("http://malsup.github.io/jquery.form.js");
    var b = {};
    b.fileapi = void 0 !== a("<input type='file'/>").get(0).files;
    b.formdata = void 0 !== window.FormData;
    a.fn.uploadFile = function(c) {
        var d = a.extend({
            url: "",
            method: "POST",
            enctype: "multipart/form-data",
            formData: null,
            returnType: null,
            allowedTypes: "*",
            fileName: "file",
            formData: {},
            dynamicFormData: function() {
                return {};
            },
            maxFileSize: -1,
            maxFileCount: -1,
            multiple: true,
            dragDrop: true,
            autoSubmit: true,
            showCancel: true,
            showAbort: true,
            showDone: true,
            showDelete: false,
            showError: true,
            showStatusAfterSuccess: true,
            showStatusAfterError: true,
            showFileCounter: true,
            fileCounterStyle: "). ",
            showProgress: false,
            onSelect: function(a) {
                return true;
            },
            onSubmit: function(a, b) {},
            onSuccess: function(a, b, c) {},
            onError: function(a, b, c) {},
            deleteCallback: false,
            afterUploadAll: false,
            uploadButtonClass: "upload",
            dragDropStr: "<span><b>Drag &amp; Drop Files</b></span>",
            abortStr: "Abort",
            cancelStr: "Cancel",
            deletelStr: "Delete",
            doneStr: "Done",
            multiDragErrorStr: "Multiple File Drag &amp; Drop is not allowed.",
            extErrorStr: "is not allowed. Allowed extensions: ",
            sizeErrorStr: "is not allowed. Allowed Max size: ",
            uploadErrorStr: "Upload is not allowed",
            maxFileCountErrorStr: " is not allowed. Maximum allowed files are:"
        }, c);
        this.fileCounter = 1;
        this.selectedFiles = 0;
        this.fCounter = 0;
        this.sCounter = 0;
        this.tCounter = 0;
        var e = "upload-" + new Date().getTime();
        this.formGroup = e;
        this.hide();
        this.errorLog = a("<div></div>");
        this.after(this.errorLog);
        this.responses = [];
        if (!b.formdata) d.dragDrop = false;
        if (!b.formdata) d.multiple = false;
        var f = this;
        var g = a("<div>" + a(this).html() + "</div>");
        a(g).addClass(d.uploadButtonClass);
        !function s() {
            if (a.fn.ajaxForm) {
                if (d.dragDrop) {
                    var b = a('<div class="ajax-upload-dragdrop" style="vertical-align:top;"></div>');
                    a(f).before(b);
                    a(b).append(g);
                    a(b).append(a(d.dragDropStr));
                    j(f, d, b);
                } else a(f).before(g);
                p(f, e, d, g);
            } else window.setTimeout(s, 10);
        }();
        this.startUpload = function() {
            a("." + this.formGroup).each(function(b, c) {
                if (a(this).is("form")) a(this).submit();
            });
        };
        this.stopUpload = function() {
            a(".upload-red").each(function(b, c) {
                if (a(this).hasClass(f.formGroup)) a(this).click();
            });
        };
        this.getResponses = function() {
            return this.responses;
        };
        var h = false;
        function i() {
            if (d.afterUploadAll && !h) {
                h = true;
                !function a() {
                    if (0 != f.sCounter && f.sCounter + f.fCounter == f.tCounter) {
                        d.afterUploadAll(f);
                        h = false;
                    } else window.setTimeout(a, 100);
                }();
            }
        }
        function j(b, c, d) {
            d.on("dragenter", function(b) {
                b.stopPropagation();
                b.preventDefault();
                a(this).css("border", "2px solid #000000");
            });
            d.on("dragover", function(a) {
                a.stopPropagation();
                a.preventDefault();
            });
            d.on("drop", function(d) {
                a(this).css("border", "2px solid #000000");
                d.preventDefault();
                b.errorLog.html("");
                var e = d.originalEvent.dataTransfer.files;
                if (!c.multiple && e.length > 1) {
                    if (c.showError) a("<div style='color:red;'>" + c.multiDragErrorStr + "</div>").appendTo(b.errorLog);
                    return;
                }
                if (false == c.onSelect(e)) return;
                m(c, b, e);
            });
            a(document).on("dragenter", function(a) {
                a.stopPropagation();
                a.preventDefault();
            });
            a(document).on("dragover", function(a) {
                a.stopPropagation();
                a.preventDefault();
                d.css("border", "2px solid #000000");
            });
            a(document).on("drop", function(a) {
                a.stopPropagation();
                a.preventDefault();
                d.css("border", "2px solid #000000");
            });
        }
        function k(a) {
            var b = "";
            var c = a / 1024;
            if (parseInt(c) > 1024) {
                var d = c / 1024;
                b = d.toFixed(2) + " MB";
            } else b = c.toFixed(2) + " KB";
            return b;
        }
        function l(b) {
            var c = [];
            if ("string" == jQuery.type(b)) c = b.split("&"); else c = a.param(b).split("&");
            var d = c.length;
            var e = [];
            var f, g;
            for (f = 0; f < d; f++) {
                c[f] = c[f].replace(/\+/g, " ");
                g = c[f].split("=");
                e.push([ decodeURIComponent(g[0]), decodeURIComponent(g[1]) ]);
            }
            return e;
        }
        function m(b, c, d) {
            for (var e = 0; e < d.length; e++) {
                if (!n(c, b, d[e].name)) {
                    if (b.showError) a("<div style='color:red;'><b>" + d[e].name + "</b> " + b.extErrorStr + b.allowedTypes + "</div>").appendTo(c.errorLog);
                    continue;
                }
                if (b.maxFileSize != -1 && d[e].size > b.maxFileSize) {
                    if (b.showError) a("<div style='color:red;'><b>" + d[e].name + "</b> " + b.sizeErrorStr + k(b.maxFileSize) + "</div>").appendTo(c.errorLog);
                    continue;
                }
                if (b.maxFileCount != -1 && c.selectedFiles >= b.maxFileCount) {
                    if (b.showError) a("<div style='color:red;'><b>" + d[e].name + "</b> " + b.maxFileCountErrorStr + b.maxFileCount + "</div>").appendTo(c.errorLog);
                    continue;
                }
                c.selectedFiles++;
                var f = b;
                var g = new FormData();
                var h = b.fileName.replace("[]", "");
                g.append(h, d[e]);
                var i = b.formData;
                if (i) {
                    var j = l(i);
                    for (var m = 0; m < j.length; m++) if (j[m]) g.append(j[m][0], j[m][1]);
                }
                f.fileData = g;
                var o = new q(c, b);
                var p = "";
                if (b.showFileCounter) p = c.fileCounter + b.fileCounterStyle + d[e].name; else p = d[e].name;
                o.filename.html(p);
                var s = a("<form style='display:block; position:absolute;left: 150px;' class='" + c.formGroup + "' method='" + b.method + "' action='" + b.url + "' enctype='" + b.enctype + "'></form>");
                s.appendTo("body");
                var t = [];
                t.push(d[e].name);
                r(s, f, o, t, c);
                c.fileCounter++;
            }
        }
        function n(a, b, c) {
            var d = b.allowedTypes.toLowerCase().split(",");
            var e = c.split(".").pop().toLowerCase();
            if ("*" != b.allowedTypes && jQuery.inArray(e, d) < 0) return false;
            return true;
        }
        function o(b, c) {
            if (b.showFileCounter) {
                var d = a(".upload-filename").length;
                c.fileCounter = d + 1;
                a(".upload-filename").each(function(c, e) {
                    var f = a(this).html().split(b.fileCounterStyle);
                    var g = parseInt(f[0]) - 1;
                    var h = d + b.fileCounterStyle + f[1];
                    a(this).html(h);
                    d--;
                });
            }
        }
        function p(c, d, e, f) {
            var g = "ajax-upload-id-" + new Date().getTime();
            var h = a("<form method='" + e.method + "' action='" + e.url + "' enctype='" + e.enctype + "'></form>");
            var i = "<input type='file' id='" + g + "' name='" + e.fileName + "'/>";
            if (e.multiple) {
                if (e.fileName.indexOf("[]") != e.fileName.length - 2) e.fileName += "[]";
                i = "<input type='file' id='" + g + "' name='" + e.fileName + "' multiple/>";
            }
            var j = a(i).appendTo(h);
            j.change(function() {
                c.errorLog.html("");
                var g = e.allowedTypes.toLowerCase().split(",");
                var i = [];
                if (this.files) {
                    for (t = 0; t < this.files.length; t++) i.push(this.files[t].name);
                    if (false == e.onSelect(this.files)) return;
                } else {
                    var j = a(this).val();
                    var k = [];
                    i.push(j);
                    if (!n(c, e, j)) {
                        if (e.showError) a("<div style='color:red;'><b>" + j + "</b> " + e.extErrorStr + e.allowedTypes + "</div>").appendTo(c.errorLog);
                        return;
                    }
                    k.push({
                        name: j,
                        size: "NA"
                    });
                    if (false == e.onSelect(k)) return;
                }
                o(e, c);
                f.unbind("click");
                h.hide();
                p(c, d, e, f);
                h.addClass(d);
                if (b.fileapi && b.formdata) {
                    h.removeClass(d);
                    var l = this.files;
                    m(e, c, l);
                } else {
                    var s = "";
                    for (var t = 0; t < i.length; t++) {
                        if (e.showFileCounter) s += c.fileCounter + e.fileCounterStyle + i[t] + "<br>"; else s += i[t] + "<br>";
                        c.fileCounter++;
                    }
                    if (e.maxFileCount != -1 && c.selectedFiles + i.length > e.maxFileCount) {
                        if (e.showError) a("<div style='color:red;'><b>" + s + "</b> " + e.maxFileCountErrorStr + e.maxFileCount + "</div>").appendTo(c.errorLog);
                        return;
                    }
                    c.selectedFiles += i.length;
                    var u = new q(c, e);
                    u.filename.html(s);
                    r(h, e, u, i, c);
                }
            });
            h.css({
                margin: 0,
                padding: 0
            });
            var k = a(f).width() + 10;
            if (10 == k) k = 120;
            var l = f.height() + 10;
            if (10 == l) l = 35;
            f.css({
                position: "relative",
                overflow: "hidden",
                cursor: "default"
            });
            j.css({
                position: "absolute",
                cursor: "pointer",
                top: "0px",
                width: k,
                height: l,
                left: "0px",
                "z-index": "100",
                opacity: "0.0",
                filter: "alpha(opacity=0)",
                "-ms-filter": "alpha(opacity=0)",
                "-khtml-opacity": "0.0",
                "-moz-opacity": "0.0"
            });
            h.appendTo(f);
        }
        function q(b, c) {
            this.statusbar = a("<div class='upload-statusbar'></div>");
            this.filename = a("<div class='upload-filename'></div>").appendTo(this.statusbar);
            this.progressDiv = a("<div class='upload-progress'>").appendTo(this.statusbar).hide();
            this.progressbar = a("<div class='upload-bar " + b.formGroup + "'></div>").appendTo(this.progressDiv);
            this.abort = a("<div class='upload-red " + b.formGroup + "'>" + c.abortStr + "</div>").appendTo(this.statusbar).hide();
            this.cancel = a("<div class='upload-red'>" + c.cancelStr + "</div>").appendTo(this.statusbar).hide();
            this.done = a("<div class='upload-green'>" + c.doneStr + "</div>").appendTo(this.statusbar).hide();
            this.del = a("<div class='upload-red'>" + c.deletelStr + "</div>").appendTo(this.statusbar).hide();
            b.errorLog.after(this.statusbar);
            return this;
        }
        function r(a, c, d, e, f) {
            var g = null;
            var h = {
                cache: false,
                contentType: false,
                processData: false,
                forceSync: false,
                data: c.formData,
                formData: c.fileData,
                dataType: c.returnType,
                beforeSubmit: function(b, g, h) {
                    if (false != c.onSubmit.call(this, e)) {
                        var j = c.dynamicFormData();
                        if (j) {
                            var k = l(j);
                            if (k) for (var m = 0; m < k.length; m++) if (k[m]) if (void 0 != c.fileData) h.formData.append(k[m][0], k[m][1]); else h.data[k[m][0]] = k[m][1];
                        }
                        f.tCounter += e.length;
                        i();
                        return true;
                    }
                    d.statusbar.append("<div style='color:red;'>" + c.uploadErrorStr + "</div>");
                    d.cancel.show();
                    a.remove();
                    d.cancel.click(function() {
                        d.statusbar.remove();
                    });
                    return false;
                },
                beforeSend: function(a, g) {
                    d.progressDiv.show();
                    d.cancel.hide();
                    d.done.hide();
                    if (c.showAbort) {
                        d.abort.show();
                        d.abort.click(function() {
                            a.abort();
                            f.selectedFiles -= e.length;
                        });
                    }
                    if (!b.formdata) d.progressbar.width("5%"); else d.progressbar.width("1%");
                },
                uploadProgress: function(a, b, e, f) {
                    if (f > 98) f = 98;
                    var g = f + "%";
                    if (f > 1) d.progressbar.width(g);
                    if (c.showProgress) {
                        d.progressbar.html(g);
                        d.progressbar.css("text-align", "center");
                    }
                },
                success: function(b, g, h) {
                    f.responses.push(b);
                    d.progressbar.width("100%");
                    if (c.showProgress) {
                        d.progressbar.html("100%");
                        d.progressbar.css("text-align", "center");
                    }
                    d.abort.hide();
                    c.onSuccess.call(this, e, b, h);
                    if (c.showStatusAfterSuccess) {
                        if (c.showDone) {
                            d.done.show();
                            d.done.click(function() {
                                d.statusbar.hide("slow");
                                d.statusbar.remove();
                            });
                        } else d.done.hide();
                        if (c.showDelete) {
                            d.del.show();
                            d.del.click(function() {
                                d.statusbar.hide().remove();
                                if (c.deleteCallback) c.deleteCallback.call(this, b, d);
                                f.selectedFiles -= e.length;
                                o(c, f);
                            });
                        } else d.del.hide();
                    } else {
                        d.statusbar.hide("slow");
                        d.statusbar.remove();
                    }
                    a.remove();
                    f.sCounter += e.length;
                },
                error: function(b, g, h) {
                    d.abort.hide();
                    if ("abort" == b.statusText) {
                        d.statusbar.hide("slow").remove();
                        o(c, f);
                    } else {
                        c.onError.call(this, e, g, h);
                        if (c.showStatusAfterError) {
                            d.progressDiv.hide();
                            d.statusbar.append("<span style='color:red;'>ERROR: " + h + "</span>");
                        } else {
                            d.statusbar.hide();
                            d.statusbar.remove();
                        }
                        f.selectedFiles -= e.length;
                    }
                    a.remove();
                    f.fCounter += e.length;
                }
            };
            if (c.autoSubmit) a.ajaxSubmit(h); else {
                if (c.showCancel) {
                    d.cancel.show();
                    d.cancel.click(function() {
                        a.remove();
                        d.statusbar.remove();
                        f.selectedFiles -= e.length;
                        o(c, f);
                    });
                }
                a.ajaxForm(h);
            }
        }
        return this;
    };
}(jQuery);
