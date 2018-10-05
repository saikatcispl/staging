(function ($) {
    $(document).ready(function () {
        var cookieValue = $.cookie("sb_dev_login");
        if (typeof cookieValue != 'undefined' && cookieValue != '') {
            var userRememberInfo = cookieValue.split(',');
            $('input[name="username"]').val(userRememberInfo[0]);
            $('input[name="password"]').val(userRememberInfo[1]);
            $('input[name="remember"]').prop('checked', true);
        }
        if ($('form[name=form_login]').length && $.fn.validate) {
            $('form[name=form_login]').validate({
                rules: {
                    username: {
                        required: true,
                    },
                    password: {
                        required: true,
                    }
                },
                messages: {
                    username: "",
                    password: "",
                },
                highlight: function (element) {
                    $(element)
                            .closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element)
                            .closest('.form-group').removeClass('has-error');
                },
                submitHandler: function (form) {
                    $(".alert-danger").html('').hide();
                    try {
                        $.ajax({
                            type: 'POST',
                            url: $(form).attr('action'),
                            data: $(form).serialize(),
                            beforeSend: function () {
                                if ($('#remember').is(':checked')) {
                                    var user_username = $('input[name="username"]').val();
                                    var user_password = $('input[name="password"]').val();
                                    var userInfo = [user_username, user_password];
                                    $.cookie("sb_dev_login", userInfo, {expires: 30});
                                }
                                else {
                                    $.removeCookie("sb_dev_login");
                                }

                                if ($('div.message-notify').length) {
                                    $('div.message-notify').html('');
                                }
                            },
                            success: function (data) {
                                var response = prep_xhr_resp(data);
                                console.log(response);
                                if (response != null && typeof response == 'object') {
                                    if (response.success) {
                                        $(form).trigger('reset');
                                        var redir = app_consts.SITEURL + 'dashboard/';
                                        if (window.location.hash.length == 0) {
                                            if ($('input[name=redir]').length && $.trim($('input[name=redir]').val()) != '') {
                                                redir = $.trim($('input[name=redir]').val());
                                            } else if (response.redir && $.trim(response.redir) != '') {
                                                redir = $.trim(response.redir);
                                            }
                                        } else {
                                            redir = redir + window.location.hash;
                                        }
                                        window.location = redir;

                                        return false;
                                    } else {
                                        console.log(response.message);
                                        $(".alert-danger").html(response.message).show();
                                    }
                                } else {
                                    $(".alert-danger").html(app_langs.AUTH.FAILURE).show();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                var message = handleXHRMessage(jqXHR);
                                $(".alert-danger").html(message).show();
                            }
                        });
                    } catch (err) {
//                        if (isDebug) {
//                            console.log(err);
//                        }
                    }
                }
            });
        }

        $('input[name="username"]').keyup(function () {
            $(".alert-danger").html('').hide();
            $(".alert-warning").remove();
            $(".alert-success").remove();
        });
        $('input[name="password"]').keyup(function () {
            $(".alert-danger").html('').hide();
            $(".alert-warning").remove();
            $(".alert-success").remove();
        });
    });
})(jQuery);
