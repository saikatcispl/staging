var ajax_req_no = 0;
window.loaderVisibility = 1;
var isDebug = 1;
/* Common Functions */
// Global uncaught error handling
//window.onerror = function catchAllErr(errorMsg, url, lineNumber) {
//    if (isDebug) {
//        console.log('Error: ' + errorMsg);
//        console.log('URL: ' + url);
//        console.log('Line: ' + lineNumber);
//    }
//};

var xhr = null, inlineFieldSaver = null;
var btnLoader = undefined;
var loaderHtml = '';

(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-XSRF-Token': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        error: function (jqXHR, responseStatus, errorThrown) {
            handleXHRError(jqXHR);

            try {
                $.nanoGress.end();
            } catch (err) {
                if (isDebug) {
                    console.log(err);
                }
            }
        }
    });

    $(document).ajaxSend(function () {
        ajax_req_no = (ajax_req_no < 0) ? 0 : ajax_req_no;
        ajax_req_no++;
    });

    $(document).ajaxComplete(function () {
        ajax_req_no--;
        ajax_req_no = (ajax_req_no < 0) ? 0 : ajax_req_no;
    });

    $(document).ready(function () {
        doHashChangeAjax();
    });

    $(window).on('hashchange', function () {
        console.log('change hash');
        doHashChangeAjax();
    });
})(jQuery);

function prep_xhr_resp(data) {
    var response = {};

    if (typeof data !== 'object') {
        try {
            response = $.parseJSON(data);
        } catch (err) {
            if (isDebug) {
                console.log(err);
            }
        }
    } else {
        response = data;
    }
    return response;
}

function handleXHRMessage(jqXHR) {
    var message = app_langs.DEFAULT.XHR_ERROR;
    var xhrResponse = prep_xhr_resp(jqXHR.responseText);

    if (xhrResponse != null && xhrResponse.message != null && typeof xhrResponse.message != 'undefined' && $.trim(xhrResponse.message) != '') {
        message = xhrResponse.message;
    }

    return message;
}

function handleXHRError(jqXHR, responseStatus, errorThrown) {

    if (jqXHR.status == 0 && jqXHR.statusText == "abort") {
        if (isDebug) {
            console.log('-aborted-');
        }

        return false;
    }
    var message = handleXHRMessage(jqXHR);

    notify({
        text: message,
        type: 'danger'
    });
    if (jqXHR.status == 401) {
        window.location = app_consts.SITEURL + "login/?redir=" + encodeURIComponent(window.location.href);
    }
    if (isDebug) {
        console.log(jqXHR);
        console.log(responseStatus);
        console.log(errorThrown);
    }
}
function notify(params) {

    var text = typeof (params.text) != 'undefined' ? params.text : '';
    var type = typeof (params.type) != 'undefined' ? params.type : 'success';
    var view = typeof (params.view) != 'undefined' ? params.view : 'back-end';
    $('div.show-header-msg').html('');

    var html = '';
    if (view == 'front-end') {
        html = '<div class="alert alert-' + type + '">\n\
                      <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>' + text + '\n\
            </div>';
    } else {
        html = '<div class="main-header-error-message">\n\
                    <div class="alert alert-' + type + ' main-inner-error-message"><span class="message-' + type + '"">' + text + '</span></div>\n\
                </div>';
    }
    $('div.show-header-msg').html(html);
    setTimeout(function () {
        $('div.show-header-msg').html('');
    }, 7000);
}

function doHashChangeAjax() {
    console.log(app_consts.SITEURL);
    if (window.location.hash) {
        var ajaxUrl = window.location.hash.substr(1);
        ajaxUrl = ajaxUrl.replace(/\/$/, '') + '/';
        if (ajaxUrl.length) {
            if (window.xhr && window.xhr.readystate != 4) {
                window.xhr.abort();
            }
            window.xhr = $.ajax({
                url: app_consts.SITEURL + ajaxUrl,
                type: 'GET',
                beforeSend: function () {
                    $("#page-wrapper").html(loaderHtml);
                },
                success: function (data) {
                    $('#page-wrapper').html('').html(data).fadeIn('fast');
                },
                error: function (jqXHR, responseStatus, errorThrown) {
                    handleXHRError(jqXHR, responseStatus, errorThrown);
                }
            });
        }
    }
}

function validateURL(url) {
    var urlpattern = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;

    if (urlpattern.test(url)) {
        return true;
    } else {
        return false;
    }
}

function validateEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function checkNumber(e, decimal) {
    if (decimal) {
        if (e.which == 46) {
            return true;
        }
    }
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
    return true;
}

function checkAlphabeticalText(e) {
    if (((e.which > 47 && e.which < 58) || (e.which > 64 && e.which < 91) || (e.which > 96 && e.which < 123)) || e.which == 8) {
        return true;
    }
    return false;
}

function checkAlphaNumericText(e) {
    if (((e.which > 47 && e.which < 58) || (e.which > 64 && e.which < 91) || (e.which > 96 && e.which < 123)) || e.which == 8) {
        return true;
    }
    return false;
}

function fb_notify(params) {
    var type = params.type;
    var text = typeof (params.text) != 'undefined' ? params.text : '';
    var title = typeof (params.title) != 'undefined' ? params.title : '';
    var icon = typeof (params.icon) != 'undefined' ? params.icon : '';
    var width = typeof (params.width) != 'undefined' ? params.width : '300px';
    var defaultPr = typeof (params.defaultPr) != 'undefined' ? params.defaultPr : '';

    var msg_notify = new PNotify({
        title: title,
        text: text,
        type: type,
        icon: icon,
        styling: 'bootstrap3',
        width: width,
        buttons: {
            closer: false,
            sticker: false
        }
    });

    if (defaultPr != 'loader') {
        setTimeout(function () {
            msg_notify.remove();
        }, 6000);
    }

}

function checkStrength(password) {
    var strength = 0
    if (password.length > 7)
        strength += 1;

    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
        strength += 1;

    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))
        strength += 1;

    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))
        strength += 2;

    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/))
        strength += 2;

    if (strength < 2)
    {
        return 'progress-bar-danger';
    }
    else if (strength < 3)
    {
        return 'progress-bar-warning';
    }
    else if (strength < 4)
    {
        return 'progress-bar-info';
    }
    else
    {
        return 'progress-bar-success';
    }
}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode

    if ((charCode > 31 && charCode < 43) || (charCode > 43 && charCode < 47) || charCode > 57) {
        return false;
    }
    else {
        if (charCode == 43) {
            console.log(charCode);
            return true;
        }
        else {
            return true;
        }
    }
}

function isNumber(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode

    if (charCode > 31 && (charCode <= 46 || charCode > 57)) {
        return false;
    }

    return true;
}