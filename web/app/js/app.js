var randomString = function(length, chars) {
    var result = '';

    for (var i = length; i > 0; --i) {
        result += chars[Math.round(Math.random() * (chars.length - 1))];
    }

    return result;
}

var JsonFormatter = {
    stringify: function(cipherParams) {
        var jsonObj = {
            ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)
        };

        if (cipherParams.iv) {
            jsonObj.iv = cipherParams.iv.toString();
        }
        if (cipherParams.salt) {
            jsonObj.s = cipherParams.salt.toString();
        }

        return JSON.stringify(jsonObj);
    },

    parse: function(jsonStr) {
        var jsonObj = JSON.parse(jsonStr);
        var cipherParams = CryptoJS.lib.CipherParams.create({
            ciphertext: CryptoJS.enc.Base64.parse(jsonObj.ct)
        });

        if (jsonObj.iv) {
            cipherParams.iv = CryptoJS.enc.Hex.parse(jsonObj.iv)
        }
        if (jsonObj.s) {
            cipherParams.salt = CryptoJS.enc.Hex.parse(jsonObj.s)
        }

        return cipherParams;
    }
};

var editorEvents = function() {
    $('textarea').on('keyup change', function() {
        $(this).attr('rows', Math.min(30, Math.max(10, $(this).val().split("\n").length)));
    });

    $('#options input').change(function() {
        var $input = $(this);

        var $label = $($input.data('id'));

        $label.html($label.data('tpl').replace('%value%', $input.data('title')));
    });

    $('#options li, #options a').click(function() {
        $(this).find('label').trigger('click');
    });

    $('#options label').click(function(e) {
        e.stopPropagation();
    });

    $('#options input:checked').each(function() {
        $(this).trigger('change');
    });

	$('.show-diff').click(function() {
		$(this).next().toggle();
	});
}

var mainEditorEvents = function() {
    $('.cipher-input').change(function() {
        if ($('.cipher-input:checked').val() === 'yes') {
            $('#cipher-alert').removeClass('hide');
        } else {
            $('#cipher-alert').addClass('hide');
        }
    });

    $('#main-form').submit(function(e) {
        if ($('.cipher-input:checked').val() === 'yes') {
            var passphrase = randomString(256, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
            var content = $('#form_content').val();
            var encrypted = CryptoJS.AES.encrypt(content, passphrase, {
                format: JsonFormatter
            });

            $(this).attr('action', $(this).attr('action') + '#key=' + passphrase);
            $('#form_content').val(encrypted);
        }
    });
}

var bootstrap = function() {
    editorEvents();
    mainEditorEvents();
}

bootstrap();
