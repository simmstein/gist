$('textarea').on('keyup change', function() {
    $(this).attr('rows', Math.max(10, $(this).val().split("\n").length));
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

$('.cipher-input').change(function() {
    if ($('.cipher-input:checked').val() === 'yes') {
        $('#cipher-alert').removeClass('hide');
    } else {
        $('#cipher-alert').addClass('hide');
    }
});
