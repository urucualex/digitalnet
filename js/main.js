// On document load
$(function(){
	$(document).on('submit', '[data-ajax=true]', function(ev) {
		ev.preventDefault();
		submitForm(this);
	});


	$('#main-media-table').each( function(){

		console.log('main-media-table', this);
		var table = new DinamicTable( {
			container: this,
			url: '/media/items',
			source: 'medias',
			refreshInterval: 10000,
			idColumn: 'idMedia',
			columns: [
				{
					name: '#',
					width: '30px',
					cellRenderer: function(value, row, column, rowData) {
						return '<td>' + row + '</td>';
					}					
				},
				{
					name: 'Pozitia in playlist',
					source: 'order',
					sortable: true,
					width: 'auto'
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
						return '<td>' + value.toString().StoHHMMSS() + '</td>';
					}
				},
				{
					name: 'Minutul afisarii',
					source: 'playMinute',
					sortable: true,
					width: 'auto',
					cellRenderer: function(value) {
						return '<td>' + value.toString().StoHHMMSS() + '</td>';
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
					width: 'auto'
				},
				{
					name: 'Data de sfarsit',
					source: 'endDate',
					sortable: true,
					width: 'auto'
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
	});


});

/** 
	settings = {
		url: '//'
	}

*/

var DinamicTable = function(settings) {

	var lastSync = 0;
	var rows = [];
	var tableElement;

	var cellRenderer = function(row, column, value, rowData) {
		return '<td>' + value + '</td>';
	}

	var headRenderer = function(columnData) {
		return '<th style="width: ' + columnData['width'] + '">'+ columnData['name'] +' <span class="glyphicon glyphicon-triangle-up"></span></th>'
	}

	var defaultSettings = {
		container: null,
		url: null,
		source: null,
		refreshInterval: 0,
		paginable: false,
		pageItemCount: null, 
		columns: [],
		idColumn: 'id',
		onRowClick: null,
		onRowClicked: null,
		onRowDblClick: null,
		onRowDblClicked: null,
		onKeyPress: null,
		onKeyPressed: null
	}

	var defaultColumnSettings = {
		name: '',
		source: '',
		type: 'string',
		active: true,
		sortable: false,
		width: 'auto',
		class: '',
		cellRenderer: cellRenderer,
		headRenderer: headRenderer,
	};

	//---------------------------------- Init ---------------------------------------------

	settings = _.extend({}, defaultSettings, settings);
	if (_.isArray(settings.columns)) {
	
		settings.columns.forEach(function (column, index) {
			settings.columns[index] = _.extend( {}, defaultColumnSettings, column);
		});
	}
	
	init();
	refreshData();

	//----------------------------------- Private functions --------------------------------

	function refreshData() {
		var request = $.ajax({
			url: settings.url,
			data: {timeStamp: lastSync},
			type: 'POST',
			dataType: 'json',
		});

		request.done(function(data) {
			syncData(data);
			render();
		});
	}

	function syncData(data) {
		if (!_.isArray(data)) {
			console.error('Data for table is not array', data);
			return;
		}

		if (rows.length == 0) {
			rows = data;
		}
		else {
			// for each new data
			data.forEach(function(item, index) {
				// find row by id
				var rowData = {};
				rowData[settings.idColumn] = item[settings.idColumn];
				var rowIndex = _.findIndex(rows, rowData);

				// if found update data
				if (rowIndex > -1) {
					rows[rowIndex] = item;
				}
				else {
					// add new row
					rows.push(item);
				}
			});			
		}
	}

	function insertRow(data, index) {
		//if (index === undefined)
	};

	function render() {
		if (!tableElement || (tableElement.length < 1)) {
			console.error('Table does not exist. Not initialized or container does not exist');
			return false;
		}

		var tableHeader = tableElement.find('thead').empty().append('<tr></tr>');
		settings.columns.forEach(function (column, index) {
			if (column.active) {
				tableHeader.append(column.headRenderer(column));
			}
		});

		var tableBody = tableElement.find('tbody');
		tableBody.empty();
		rows.forEach(function(row, rowIndex) {
			var newRow = $('<tr></tr>')
			settings.columns.forEach(function (column, colIndex) {
				if (column.active) {
					newRow.append(column.cellRenderer(rowIndex, colIndex, row[column.source]));
				}
			});
			var tableRow = tableBody.append(newRow);
		});
	}

	function init() {
		settings.container = $(settings.container);
		if (settings.container.length < 1) {
			console.error('Table container could not be found!', settings.container);
			return;
		}

		if (settings.container.prop('tagName') == 'TABLE') {
			tableElement = settings.container;
		}
		else {
			tableElement = $(settings.container).append('<table><thead></thead><tbody></tbody></table>');
		}
	}

}


//----------------------------------------- Helper functions -------------------------------------------


	function showLoader() {

	}

	function hideLoader() {

	}

	function successMessage(message) {
		alert(message);
	}

	function errorMessage(message) {
		alert(message);
	}

	function submitForm(form) {

		var $form = $(form),
			formData = $form.serialize(),
			action = $form.attr('action'),
			method = ($form.attr('method') || 'post');

	console.log('form', form, formData);
		showLoader();
		var request = $.ajax({
			url: action,
			type: method,
			data: formData, 
			dataType: 'json',
			timeout: 10000
		});

		request.always(function() {
			hideLoader();
		});

		request.done(function(data) {
			if (data.status !== 'ok') {
				errorMessage(data.message, data);
			} 
			else {
				if (data.id != null) {
					$form.find("input[name=id]").val(data.id);
				}
				successMessage(data.message);
			}

			if (data.redirect != null) {
				window.location = data.redirect;
			}
		});

		request.fail(function(jqXHR, textStatus) {
			alert(textStatus);
		});

		return request;	
	}

	//transforms number of seconds into time (5780 -> 1:36:20)
	String.prototype.StoHHMMSS = function(includeHours, separator) {
		value = parseInt(this, 10);

		if (separator === undefined) {
			separator = ':';
		}
		var hours = Math.floor(value / 3600);
		value %= 3600;
		var minutes = Math.floor(value / 60);
		seconds = value % 60;

		if (hours < 10) {
			hours = '0' + hours;
		}

		if (minutes < 10) {
			minutes = '0' + minutes;
		}

		if (seconds < 10) {
			seconds = '0' + seconds;
		}

		return ((hours > 0) || includeHours ? hours + ':' : '') + minutes + ':' + seconds;
	}