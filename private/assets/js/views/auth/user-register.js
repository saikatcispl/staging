(function ($) {


    function convertToSlug(Text)
    {
        return Text
                .toLowerCase()
                .replace(/ /g, '')
                .replace(/[^\w-]+/g, '')
                ;
    }
    $(document).ready(function () {
        $("#business_name").keyup(function () {
            var slug_value = convertToSlug($(this).val()).substr(0, 15);
            $("#business_slug").val(slug_value);
        });

        if ($('form[name=form_register]').length && $.fn.validate) {
            $('form[name=form_register]').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
                    phone: {
                        required: true,
                        number: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    re_password: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    },
                    address: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    country: {
                        required: true,
                    },
                    zipcode: {
                        required: true,
                    },
                    business_name: {
                        required: true,
                    },
                    business_slug: {
                        required: true,
                    },
                    business_email: {
                        required: true,
                        email: true
                    },
                    business_phone: {
                        required: true,
                        number: true
                    },
                    business_address: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    email: "",
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
                                if ($('div.message-notify').length) {
                                    $('div.message-notify').html('');
                                }
                            },
                            success: function (data) {
                                console.log(data);
                                var returnedData = JSON.parse(data);
                                console.log(returnedData);

                                if (returnedData.response == 'SUCCESS') {
                                    $(".register-box-msg").after('<div class="alert alert-success text-center">' + returnedData.message + '</div>');
                                    window.setTimeout(function () {
                                        var redir = app_consts.SITEURL + 'login/';
                                        window.location = redir;
                                    }, 5000);
                                }
                                if (returnedData.response == 'FAIL') {
                                    $(".register-box-msg").after('<div class="alert alert-danger text-center">' + returnedData.message + '</div>');
                                    $('html, body').animate({scrollTop: 0}, 'slow');
                                }
                                window.setTimeout(function () {
                                    $(".alert").remove();
                                }, 5000);

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
    });
})(jQuery);
