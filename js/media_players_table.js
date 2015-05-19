function mediaPlayersTable() {

	var self = this,
		$this = $(this);

	mediaId = $this.attr('media-id');

	console.log('media-media-table', this);

	var mediaplayersTable = new DinamicTable( {
		container: this,
		url: '/media/players/'+mediaId,
		source: 'players',
		refreshInterval: 10000,
		idColumn: 'playerId',
		multipleSelect: true,
		onRowDblClicked: function(value, row, column, rowData) {
			var win = window.open('/players/item/' + rowData['playerId'], '_blank');
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
				name: 'Nume',
				source: 'playerName',
				sortable: true,
				width: 'auto',
				cellRenderer: function(value) {
					return '<strong>' + value + '</value>'; 
				}
			},
			{
				name: 'Etichete',
				source: 'playerLabels',
				sortable: true,
				width: 'auto',
				cellRenderer: function(value, row, column, rowData) {
					return value.elipsis(10);
				}
			},			
			{
				name: 'Cod',
				source: 'code',
				sortable: true,
				width: 'auto'
			},

			{
				name: 'Judet',
				source: 'county',
				sortable: true,
				width: 'auto'
			},
			{
				name: 'Oras',
				source: 'city',
				sortable: true,
				width: 'auto'
			},
			{
				name: 'Status',
				source: 'lastMessage',
				sortable: true,
				width: 'auto',
				cellRenderer: function(value, row, column, rowData) {
					var messageDate = new Date(value),
						now = new Date(),
						maxDiff = 10 * 60000; // 10 minutes in ms

						if (isNaN(messageDate.getTime())) {
							return '<span class="glyphicon glyphicon-remove"></span>';
						}

					if ((now.getTime() - messageDate.getTime()) > maxDiff) {
						return '<span class="glyphicon glyphicon-remove"></span>'
					}

					return '<span class="glyphicon glyphicon-ok"></span>';
				}
			},
			{
				name: 'Spot curent',
				source: 'playedFile',
				sortable: true,
				width: 'auto',
				cellRenderer: function(value, row, column, rowData) {
					return value;
				}
			},
			{
				name: 'Ultimul mesaj',
				source: 'lastMessage',
				sortable: true,
				width: 'auto'
			},
			{
				name: 'Ultimul update',
				source: 'lastUpdate',
				sortable: true,
				width: 'auto'
			},
			{
				name: 'Spoturi astazi',
				source: 'playlistCountToday',
				sortable: true,
				width: 'auto'
			},
			{
				name: 'Lungime playlist',
				source: 'playlistLengthToday',
				sortable: true,
				width: 'auto',
				cellRenderer: function(value, row, column, rowData) {
					return value.StoHHMMSS();
				}
			},
			{
				name: 'Ultima eroare',
				source: 'lastError',
				sortable: true,
				width: 'auto',
				cellRenderer: function(value, row, column, rowData) {
					return value;
				}
			},
			{
				name: 'Ip extern',
				source: 'externalIp',
				sortable: true,
				width: 'auto'
			},
			{
				name: 'Ip intern',
				source: 'internalIp',
				sortable: true,
				width: 'auto'
			},
			{
				name: 'Observatii',
				source: 'comment',
				sortable: true,
				width: 'auto'
			},
		]
	});
}