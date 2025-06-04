function _confirm(message, callback) {
    swal.fire({
        html: message,
        showCancelButton: true,
        confirmButtonText: "Confirm!",
    }).then((result) => {
        if (result.value) {
            callback();
        }
    });
}

function submitForm(type) {
    $(".ajax-form").attr("data-continue", type);
    $(".ajax-form").submit();
}

function loadPage(url) {
    $("#page-progress .progress-bar").css("opacity", 1);
    $("#page-progress .progress-bar").css("width", "85%");

    $.ajax({
        type: "get",
        url: url,
        data: {
            ap: 1,
        },
        dataType: "html",
        success: (html) => {
            setTimeout(() => {
                $("[data-section]").each(function () {
                    var section = $(this).attr("data-section");
                    $(this).html(
                        $(html)
                            .find('[data-section="' + section + '"]')
                            .html()
                    );
                });

                $(".page-script").html(
                    "<script>" +
                        $(html).find("script#page-script").html() +
                        "</script>"
                );
                $("#page-progress .progress-bar").css("width", "100%");

                setTimeout(() => {
                    init();

                    $("#page-progress .progress-bar").css("opacity", "0");
                    setTimeout(() => {
                        $("#page-progress .progress-bar").css("width", "0");
                    }, 200);
                }, 500);

                history.pushState(null, $(html).find("title").text(), url);
            }, 300);
        },
        error: (html) => {
            location.href = url;
        },
    });
}

function init() {
    if (typeof window["controller"] === "function") {
        _ctrl = new window["controller"]();
        _ctrl.init();
    }

    $(".summernote").summernote();

    if ($("#json-editor").length > 0) {
        var editor = ace.edit("json-editor");
        editor.getSession().setMode("ace/mode/json");

        editor.getSession().on("change", function () {
            var id = $("#json-editor").attr("data-for");
            var json = editor.getValue();

            try {
                if (JSON.parse(json)) {
                    $("#" + id).val(json);
                }
            } catch (e) {
                $("#" + id)
                    .parent(".form-group:first")
                    .find(".invalid-feedback")
                    .text("Invalid JSON Format");
            }
        });
    }

    $(".selectpicker").selectpicker();
}

function doSearch() {
    if (_ctrl.dt) {
        var q = {};
        $("input,select", $("#filter-wrapper")).each(function () {
            if ($(this).val().length > 0) {
                q[$(this).attr("name")] = $(this).val();
            }
        });
        _ctrl.dt.setDataSourceParam("filters", q);
        _ctrl.dt.reload();
    }
}

$(document).ready(function () {
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: true,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    };

    init();

    $(window).bind("popstate", () => {
        loadPage(location.href);
    });

    $("body").delegate("a", "click", function (e) {
        let href = $(this).attr("href");
        let bypass = $(this).attr("data-bypass");

        if (
            ["javascript:void(0)", "javascript:;", "#", undefined].indexOf(
                href
            ) > -1 ||
            href.indexOf("javascript") == 0 ||
            href.length == 0 ||
            bypass !== undefined
        ) {
        } else {
            e.preventDefault();
            loadPage($(this).attr("href"));
        }
    });

    $("body").delegate(".deletable", "click", function () {
        let _this = $(this);
        _confirm(
            'Do you want to perform <strong style="color:red;">DELETE</strong> action for the record?',
            () => {
                $.ajax({
                    url: _this.attr("data-href"),
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: $("#csrf-token").attr("content"),
                    },
                    statusCode: {
                        204: (xhr) => {
                            toastr.success("Record Successfully Deleted.");
                            _ctrl.dt.load();
                        },
                    },
                    error: (err) => {
                        console.error(err);
                        var json = err.responseJSON;
                        toastr.error(json.message);
                    },
                });
            }
        );
    });

    $("body").delegate(".ajax-form", "submit", function () {
        let $wrapper = $(this).parents(".form-wrapper:first");
        let value = $(this).attr("data-continue"),
            messages,
            key,
            m,
            data = {},
            arr;

        if ($wrapper.is(".processing")) {
            return false;
        }
        
        if (typeof appendBuildForm == "function") { 
            appendBuildForm(); 
        }

        $wrapper.addClass("processing");
        $wrapper
            .find(".btn-submit")
            .addClass(
                "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
            );
        $(".is-invalid", $(this)).removeClass("is-invalid");
        $(".invalid-feedback", $(this)).html("");

        let completed = () => {
            $wrapper.removeClass("processing");
            $wrapper
                .find(".btn-submit")
                .removeClass(
                    "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
                );
        };

        $.ajax({
            url: $(this).attr("action"),
            type: "post",
            dataType: "json",
            data: $(this).serialize(),
            success: (json) => {
                completed();
                if (json.status == 200 || json.status == 201) {
                    toastr.success(json.message);

                    switch (value) {
                        case "2": //save and reload page
                            loadPage(location.href);
                            break;
                        case "1": //save and edit
                            let url = $(this).attr("data-edit-url");
                            loadPage(url.replace("%ID%", json.data.id));
                            break;
                        case "0": //go back
                        default:
                            history.back();
                            break;
                    }
                } else {
                    toastr.error(json.message);
                }
            },
            error: (err) => {
                completed();
                console.error(err);
                var json = err.responseJSON;

                toastr.error(json.message);
                for (key in json.errors) {
                    messages = json.errors[key];
                    $("#input-" + key).addClass("is-invalid");
                    for (let i = 0; i < messages.length; i++) {
                        m = messages[i];
                        $(`[data-group="${key}"]`)
                            .find(".invalid-feedback")
                            .append(`<div>${m}</div>`);
                    }
                }
            },
        });

        return false;
    });
});
