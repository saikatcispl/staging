(function ($) {
    $(document).ready(function () {
//       $("#oldPassword").focusout(function(){
//        var oldPassword = $(this).val();
//        $.ajax({
//                            type: 'POST',
//                            url: $("#check-password").val(),
//                            data: {oldPassword:oldPassword},
//                            beforeSend: function () {
//                                if ($('div.message-notify').length) {
//                                    $('div.message-notify').html('');
//                                }
//                            },
//                            success: function (data) {
//                                console.log(data);
//                            },
//                            error: function (jqXHR, textStatus, errorThrown) {
//                                console.log(textStatus+"---"+errorThrown);
//                            }
//                        });
//       });     
        if ($('form[name=form_change_password]').length && $.fn.validate) {
            $('form[name=form_change_password]').validate({
                rules: {
                    oldPassword: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    confirmPassword: {
                        required: true,
                        minlength: 8,
                        equalTo: "#newPassword"
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
                                    $(".msg-show").html('<div class="alert alert-success text-center">' + returnedData.message + '</div>');

                                }
                                if (returnedData.response == 'FAIL') {
                                    $(".msg-show").html('<div class="alert alert-danger text-center">' + returnedData.message + '</div>');
                                }
                                window.setTimeout(function () {
                                    $(".alert").remove();
                                }, 3000);
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


