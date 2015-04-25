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
			valueHolder = $this.attr('value-holder'),
			onSuccess = $this.attr('on-success');

		var request = uploadFile({
			file: this.files[0],
			action: $this.attr('action')
		});

		request.success(function(data){
console.log('Upload result', data);
console.log('valueHolder', valueHolder);
			$(valueHolder).val(data.file['file_name']);

			// call onSuccess function
			if (onSuccess !== undefined) {
				window[onSuccess].call($this.get(0), data);
			}

		});
	})

});

// If media uploaded update file duration field
function mediaUploaded(data) {
	$('input[name=duration]').val(data['file']['duration'].toString().StoHHMMSS());
}