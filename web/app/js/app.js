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

    var key = getKey();

    if (key) {
        $('.show-diff').each(function() {
            var href = $(this).attr('href');
            href = href.replace('#', '#key=' + key + '&');

            $(this).attr('href', href);
        });
    }

    $('.show-diff').click(function() {
        $($(this).data('target')).toggle();
    });

    var diffLinkTest1 = (document.location.href).indexOf('#diff-') !== -1;
    var diffLinkTest2 = (document.location.href).indexOf('&diff-') !== -1;

    if (diffLinkTest1 || diffLinkTest2) {
        var anchor = '#' + (document.location.href).toString().split('#')[1];

        $('.show-diff[href="' + anchor + '"]').click();
    }
}

var myEvents = function() {
    $('.btn-delete').click(function() {
        if (confirm(trans('form.confirm'))) {
            $('#delete_id').val($(this).data('id'));
            $('#form-deletion form').submit();
        }
    });

    $(document).on('change keyup keydown', '#form-api-key', function() {
        $(this).val($(this).data('key'));
    });
}

var mainEditorEvents = function() {
    $('#main-form').submit(function(e) {
        if ($('.cipher-input:checked').val() === 'yes' || typeof cipherGistClone !== 'undefined') {
            var key = getKey();

            if (key) {
                var passphrase = key;
            } else {
                var passphrase = randomString(256, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
            }

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
        return parts[1].split('&')[0];
    }

    return null;
}

var viewerEvents = function() {
    var $render = $('#viewer code[data-cipher]');

    $(document).ready(function() {
        var key = getKey();

        var $cipherEditor = $('.cipher-editor');
        var $embedInput = $('#embed-input');

        var to = ' ';

        if (key) {
            $('.cipher-link').each(function() {
                var href = $(this).attr('href');
                href = href + '#key=' + key;

                $(this).attr('href', href);
            });

            if (0 !== $render.length || $cipherEditor.length !== 0) {
                if ($render.length !== 0) {
                    var decrypted = CryptoJS.AES.decrypt($render.html(), key, {
                        format: JsonFormatter
                    });

                    $render.text(decrypted.toString(CryptoJS.enc.Utf8));
                    $render.attr('class', $render.data('class'));
                    Prism.highlightAll();

                    to = ' data-key="#key=' + key + '" ';
                } else {
                    var decrypted = CryptoJS.AES.decrypt($cipherEditor.val(), key, {
                        format: JsonFormatter
                    });

                    $cipherEditor.val(decrypted.toString(CryptoJS.enc.Utf8));
                }
            }
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
