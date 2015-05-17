// On document load
$(function(){
	// Enable js/jquery items
		$('#main-media-table').each(mainMediaTable);
		$('#main-players-table').each(mainPlayersTable);

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
			$(valueHolder).val(data.file['file_name']);

			// call onSuccess function
			if (onSuccess !== undefined) {
				window[onSuccess].call($this.get(0), data);
			}

		});
	})

	// Select players to add to selected media
	$(document).on('click', '[data-action=add-media-to-players]', function(event) {
		event.preventDefault();
		var selectedMedia = mediaTable.getSelectedRows();
		var selectedMediaIds = _.pluck(selectedMedia, 'mediaId');

		selectPlayers(selectedMediaIds);
	});

});

// If media uploaded update file duration field
function mediaUploaded(data) {
	$('input[name=duration]').val(data['file']['duration']);
	$('input.duration-display').val(data['file']['duration'].toString().StoHHMMSS());
	$('#play_media').attr('href', '/media/download/' + data['file']['file_name']);
}