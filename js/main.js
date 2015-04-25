// On document load
$(function(){
	// Enable js/jquery items
		$('#main-media-table').each(mainMediaTable);

	// Add events
		$(document).on('submit', 'form[data-ajax=true]', function(ev) {
			ev.preventDefault();
			submitForm(this);
		});

	// Upload file
	$(document).on('change', 'input[type=file]', function() {
		var $this = $(this),
			valueHolder = $this.attr('value-holder');

		var request = uploadFile({
			file: this.files[0],
			action: $this.attr('action')
		});

		request.success(function(data){
console.log('Upload result', data);
console.log('valueHolder', valueHolder);
			$(valueHolder).val(data.file['file_name']);
		});
	})

});