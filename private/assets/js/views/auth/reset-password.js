(function ($) {
    $(document).ready(function () {
//        if ($('input[name=password]').length && $.fn.pwstrength) {
//            $('input[name=password]').pwstrength();
//
//            $('input[name=password]').keyup(function (e) {
//                if ($(this).val().length != 0) {
//                    $('#pwindicator').show();
//                } else {
//                    $('#pwindicator').hide();
//                }
//
//                $('input[name=confirm_password]').trigger('keyup');
//            });
//        }
//
//        $('input[name=confirm_password]').keyup(function (e) {
//            if ($(this).val().length != 0 && $('input[name=password]').val().length != 0) {
//                if ($.trim($(this).val()) != $.trim($('input[name=password]').val())) {
//                    $('.url-validity-marker.url-green').hide();
//                    $('.url-validity-marker.url-red').show();
//                } else {
//                    $('.url-validity-marker.url-green').show();
//                    $('.url-validity-marker.url-red').hide();
//                }
//            } else {
//                $('.url-validity-marker.url-green').hide();
//                $('.url-validity-marker.url-red').hide();
//            }
//        });

        if ($('form[name=form_reset_password]').length && $.fn.validate) {
            $('form[name=form_reset_password]').validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 8
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        equalTo: '#fc_password'
                    },
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
                                    $(".alert-danger").html(app_langs.AUTH.FAILURE).show();
                                }
                            },
                            error: function () {
                                $(".alert-danger").html(app_langs.AUTH.FAILURE).show();
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
