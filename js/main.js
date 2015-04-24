// On document load
$(function(){
	$(document).on('submit', '[data-ajax=true]', function(ev) {
		ev.preventDefault();
		submitForm(this);
	});

	$('#main-media-table').each(mainMediaTable);
});