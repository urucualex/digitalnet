function playlistTable(){
		console.log('playlist-table', this);

		var playerId = $(this).attr('player-id');

		playlistTable = new DinamicTable( {
			container: this,
			url: '/media/playlist/' + playerId,
			source: 'medias',
			refreshInterval: 10000,
			idColumn: 'mediaId',
			multipleSelect: true,
			onRender: function(rows) {
				var currentPlaylistTime = 0;
				_.forEach(rows, function(row, rowIndex) {
					rows[rowIndex]['playMinute'] = currentPlaylistTime;
					currentPlaylistTime += _.parseInt(row['duration'], 10) + 1;
				})
			},
			onDataFetch: function() {
				return {timestamp: Date.now()};
			},
			onDataFetched: function(rows) {
				_.forEach(rows, function(row, rowIndex) {
					var start = new Date(row['startDate']),
						end = new Date(row['endDate']);
						if (start.isValid() && end.isValid()) {
							rows[rowIndex]['daysCount'] = (end.getTime() - start.getTime()).toDays() + 1;
						}
						else {
							rows[rowIndex]['daysCount'] = '';
						}
				});
			},
			onRowDblClicked: function(value, row, column, rowData) {
				var win = window.open('/media/item/' + rowData['mediaId'], '_blank');
				win.focus();		
			},
			columns: [
				{
					name: '#',
					width: '30px',
					cellRenderer: function(value, row) {
						return (row + 1);
					}					
				},
				{
					name: 'Uploadat',
					source: 'uploaded',
					sortable: true,
					width: '50px',
					cellRenderer: function(value) {
						if (value) {
							return '<span class="glyphicon glyphicon-ok"></span>';
						};
						return '';
					}

				},				
				{
					name: 'Pozitia in playlist',
					source: 'order',
					sortable: true,
					width: '50px'
				},
				{
					name: 'Nume',
					source: 'mediaName',
					sortable: true,
					width: 'auto'
				},
				{
					name: 'Label',
					source: 'mediaLabels',
					sortable: true,
					width: 'auto'
				},
				{
					name: 'Fisier',
					source: 'file',
					sortable: true,
					width: 'auto'
				},
				{
					name: 'Durata',
					source: 'duration',
					sortable: true,
					width: 'auto',
					cellRenderer: function(value) {
						return value.toString().StoHHMMSS();
					}
				},
				{
					name: 'Minutul afisarii',
					source: 'playMinute',
					sortable: true,
					width: 'auto',
					cellRenderer: function(value) {
						return value.toString().StoHHMMSS();
					}
				},
				{
					name: 'Numar de statii',
					source: 'playersCount',
					sortable: true,
					width: 'auto'
				},
				{
					name: 'Client',
					source: 'client',
					sortable: true,
					width: 'auto'
				},
				{
					name: 'Tip campanie',
					source: 'type',
					sortable: true,
					width: 'auto'
				},
				{
					name: 'Data de inceput',
					source: 'startDate',
					sortable: true,
					width: 'auto',
					cellRenderer: function(value, row, column, rowData) {
						if ((value == '0000-00-00') || (rowData['useDateInterval'] == '0')) {
							return '';
						}
 						return value.toString().toDate();
					}
				},
				{
					name: 'Data de sfarsit',
					source: 'endDate',
					sortable: true,
					width: 'auto',
					cellRenderer: function(value, row, column, rowData) {
						if ((value == '0000-00-00') || (rowData['useDateInterval'] == '0')) {
							return '';
						}
						return value.toString().toDate();
					}
				},
				{
					name: 'Numar de zile',
					source: 'daysCount',
					sortable: true,
					width: 'auto'
				},
				{
					name: 'Observatii',
					source: 'comment',
					sortable: true,
					width: 'auto'
				}
			]
		});
	};