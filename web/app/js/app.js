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
        $($(this).data('target')).toggle();
    });

    if ((document.location.href).indexOf('#diff-') !== -1) {
        var anchor = '#' + (document.location.href).toString().split('#')[1];
        $('.show-diff[href="' + anchor + '"]').click();
        document.location.href = anchor;
    }
}

var myEvents = function() {
    $('.btn-delete').click(function() {
        $('#delete_id').val($(this).data('id'));
        $('#form-deletion form').submit();
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

var getKey = function() {
    var url = document.location.href;
    var parts = url.split('#key=');

    if (parts.length === 2) {
        return parts[1];
    }

    return null;
}

var viewerEvents = function() {
    var $render = $('.syntaxhighlighter');

    $(document).ready(function() {
        var key = getKey();
        var $embedInput = $('#embed-input');
        var to = ' ';

        if (0 !== $render.length && key) {
            var decrypted = CryptoJS.AES.decrypt($render.html(), key, {
                format: JsonFormatter
            });
            $render.text(decrypted.toString(CryptoJS.enc.Utf8));
            SyntaxHighlighter.all();

            to = ' data-key="#key=' + key + '" ';

            $('.lang').each(function() {
                $(this).attr('href', $(this).attr('href') + '#key=' + key);
            });
        }

        if ($embedInput.length) {
            $embedInput.val($embedInput.val().replace('%key%', to));
        }
    });
}

var bootstrap = function() {
    editorEvents();
    viewerEvents();
    myEvents();
    mainEditorEvents();
}

bootstrap();
