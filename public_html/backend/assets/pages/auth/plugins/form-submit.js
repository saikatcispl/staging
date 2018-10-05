(function ($) {

    var xhr;

    $.fn.formSubmit = function (options, callback) {
        return listenToForm(this, options, callback);
    };



    function validate(form, rules) {
        var isValid = true;
        form.find('input[type=text]').each(function (key, value) {

            var input = $(this);
            if (input.attr('name') in rules) {

                if (input.val().length && !getRegex(rules[input.attr('name')]).test(input.val())) {
                    input.closest('.form-group').addClass('has-error');
                    isValid = false;
                } else {
                    input.closest('.form-group').removeClass('has-error');
                }
            }

        });

        return isValid;
    }

    function getRegex(type) {
        switch (type) {
            case 'number_comma':
                return /^\d+(?:,\d+)*$/;
                break;
            case 'number_only' :
                return /^[0-9]+$/;
                break;
            case 'email':
                return /[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4}$/;
                break;
            case 'number_dash':
                return /^\d+(?:-\d+)*$/;
                break;

        }
    }

    function listenToForm(elem, options, callback) {

        if (typeof options !== 'object') {
            return false;
        }

        var $elem = $(elem);

        /**
         *	Attach submit handler
         */

        $elem.submit(function (e) {
            e.preventDefault();
            /**
             *	Before init is called if you need to do something before ajax request is sent
             * 	lets say, validating forms...
             */
            if (typeof options.beforeInit === 'function') {
                if (options.beforeInit($elem) === false) {
                    return false;
                }
            }

            if (typeof options.validate === 'object') {
                if (validate($elem, options.validate) === false) {
                    return false;
                }
            }

            if (xhr && xhr.readyState != 4) {
                xhr.abort();
            }

            xhr = $.ajax({
                type: typeof $elem.attr('method') !== 'undefined' && $elem.attr('method').length ? $elem.attr('method') : 'post',
                url: $elem.attr('action'),
                data: $elem.serialize(),
                cache: false,
                success: function (data) {

                    if (typeof options.template === 'undefined' || typeof options.appendTo === 'undefined') {
                        return false;
                    }

                    if (typeof callback === 'function') {
                        callback(data);
                    }
                }

            });
        });
        $elem.trigger('submit');
    }

})(jQuery);
