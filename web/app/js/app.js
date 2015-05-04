$('textarea').on('keyup change', function() {
	$(this).attr('rows', Math.max(10, $(this).val().split("\n").length));
});
