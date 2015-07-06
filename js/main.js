// On document load
$(function(){
	// Enable js/jquery items
		$('#main-media-table').each(function() {
			mediaTable = mainMediaTable($(this));
		});
		$('#main-players-table').each(mainPlayersTable);
		$('#playlist-table').each(playlistTable);
		$('#media-players-table').each(mediaPlayersTable);

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
	});

	// Select players to add to selected media
	$(document).on('click', '[data-action=add-media-to-players]', function(event) {
		event.preventDefault();
		var selectedMedia = mediaTable.getSelectedRows();
		var selectedMediaIds = _.pluck(selectedMedia, 'mediaId');

		selectPlayers(selectedMediaIds);
	});

	$(document).on('change', '#media-list-date', function(event) {
		var val = $('#media-list-date').val()
		mediaTable.updateSettings({
			onDataFetch: function() {
				return {
					date: val,
					timestamp: Date.now()
				}
			}
		});
		mediaTable.update();
	})

	$(document).on('change', '#playlist-date', function() {
		var val = $('#playlist-date').val()
		playlistTable.updateSettings({
			onDataFetch: function() {
				return {
					date: val
				}
			}
		});
		playlistTable.update();
	})

	$(document).on('keyup', '#players-table-filter', function() {
		var val = $('#players-table-filter').val();
		playersTable.setFilter(val);
	});

	$(document).on('click', '[data-action=save-media-order]', function(event) {
		event.preventDefault();
		showConfirmBox('Salveaza ordinea playlistului', 'Esti sigur ca vrei sa salvezi ordinea playlistului?', savePlaylistOrder);
	});

	$(document).on('click', '[data-action=delete-media]', function(event) {
		event.preventDefault();

		showConfirmBox('Sterge campania', 'Esti sigur ca vrei sa stergi campaniile selectate?', deleteSelectedMedia);
	})

	$(document).on('click', '[data-action=remove-players-from-media]', function(event) {
		event.preventDefault();

		removeSelectedPlayersFromMedia();
	});

	$(document).on('click', '[data-action=remove-media-from-playlist]', function(event) {
		removeSelectedMediaFromPlaylist();
	});
});

function savePlaylistOrder() {
	var media = mediaTable.getAllRows();
	var mediaIds = _.pluck(media, 'mediaId');
	var mediaOrders = _.pluck(media, 'order');

	mediaOrders = _.sortBy(mediaOrders);

	showOverlay();
	var request = $.ajax({
		url: '/media/setOrder',
		type: 'POST',
		data: {
			mediaIds: mediaIds,
			order: mediaOrders
		}
	});

	request.always(function(data1, data2) {
		hideOverlay();
		console.log('/media/setOrder response', data1, data2);
	});

	request.done(function() {
		mediaTable.update();
		showMessageBox('Succes!', 'Ordinea playlist-ului a fost salvata!');
	});
}

function deleteSelectedMedia() {
	var media = mediaTable.getSelectedRows();

	if (_.isEmpty(media)) {
		return;
	}

	var mediaIds = _.pluck(media, 'mediaId');

	var deleteMedia = function() {
		if (!mediaIds.length) {
			return;
		}
		var mediaIdToDelete = mediaIds.splice(0,1);
		showOverlay();
		var request = $.ajax({
			url: '/media/delete/' + mediaIdToDelete,
			type: 'GET'
		});

		request.always(function(data1, data2) {
			hideOverlay();
			console.log('/media/delete response', data1, data2);
		});

		request.done(function() {
			if (mediaIds.length) {
				deleteMedia();
			}
			else {
				mediaTable.update();
				showMessageBox('Succes!', 'Campaniile au fost sterse!');
			}
		});
	}

	deleteMedia();
}

function removeSelectedPlayersFromMedia() {
	var selectedPlayers = mediaplayersTable.getSelectedRows();

	if (_.isEmpty(selectedPlayers)) {
		console.error('removeSelectedPlayersFromMedia: No players selected');
		return;
	}

	var selectedPlayerIds = _.pluck(selectedPlayers, 'playerId');
	var currentMediaId = getCurrentMediaId();

	if (!currentMediaId) {
		console.error('removeSelectedPlayersFromMedia: could not find currentMediaId');
		return;
	}

	showConfirmBox('Scoate statii', 'Esti sigur ca vrei sa renunti la statiile selectate?', function() {
		showOverlay();
		var request = $.ajax({
			url: '/players/removeMedia/' + currentMediaId,
			method: 'post',
			data: {
				playerIds:  selectedPlayerIds
			}
		});

		request.always(function(data1, data2) {
			hideOverlay();
			console.log(data1, data2);
		});

		request.done(function(data) {
			mediaplayersTable.update();
		});
	});
}


function removeSelectedMediaFromPlaylist() {
	var selectedMedia = playlistTable.getSelectedRows();

	if (_.isEmpty(selectedMedia)) {
		console.error('removeSelectedMediaFrmPlaylist: No media selected');
		return;
	}

	var selectedMediaIds = _.pluck(selectedMedia, 'mediaId');
	var currentPlayerId = getCurrentPlayerId();

	if (!currentPlayerId) {
		console.error('removeSelectedMediaFrmPlaylist: could not find currentPlayerId');
		return;
	}

	showConfirmBox('Scoate campanii', 'Esti sigur ca vrei sa renunti la campaniile selectate?', function() {
		showOverlay();
		var request = $.ajax({
			url: '/media/removeFromPlayer/' + currentPlayerId,
			method: 'post',
			data: {
				mediaIds:  selectedMediaIds
			}
		});

		request.always(function(data1, data2) {
			hideOverlay();
			console.log(data1, data2);
		});

		request.done(function(data) {
			playlistTable.update();
		});
	});
}

function getCurrentMediaId() {
	var $idInput = $("input[name=id][data-type=media]").first();

	if ($idInput.length) {
		return $idInput.val();
	}
}

function getCurrentPlayerId() {
	var $idInput = $("input[name=id][data-type=player]").first();

	if ($idInput.length) {
		return $idInput.val();
	}
}

// If media uploaded update file duration field
function mediaUploaded(data) {
	$('input[name=duration]').val(data['file']['duration']);
	$('input.duration-display').val(data['file']['duration'].toString().StoHHMMSS());
	$('#play_media').attr('href', '/media/download/' + data['file']['file_name']);
}
