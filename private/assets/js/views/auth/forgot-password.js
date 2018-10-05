(function ($) {
    $(document).ready(function () {
        $('input[name="email"]').keyup(function () {
            $(".alert-danger").html('').hide();
        });
        if ($('form[name=forgot_pwd_form]').length && $.fn.validate) {
            $('form[name=forgot_pwd_form]').validate({
                rules: {
                    email: {
                        required: true,
                    }
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
                            cache: false,
                            beforeSend: function () {
                                $(".alert-danger").html('').hide();
                            },
                            success: function (data) {
                                var response = prep_xhr_resp(data);
                                if (response != null && typeof response == 'object') {
                                    if (response.success) {
                                        $(form).trigger('reset');
                                        var redir = app_consts.SITEURL + 'login/';
                                        if (response.redir && $.trim(response.redir) != '') {
                                            redir = $.trim(response.redir);
                                        }
                                        window.setTimeout(function () {
                                            window.location = redir;
                                        }, 2000);
                                        return false;
                                    } else {
                                        $(".alert-danger").html(response.message).show();
                                    }
                                } else {
                                    $(".alert-danger").html(app_langs.RESET_REQUEST.INVALID).show();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                var message = handleXHRMessage(jqXHR);
                                $(".alert-danger").html(message).show();
                            }
                        });
                    } catch (err) {
                        if (isDebug) {
                            console.log(err);
                        }
                    }
                }
            });
        }
    });
})(jQuery);
