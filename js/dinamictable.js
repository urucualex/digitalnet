var DinamicTable = function(settings) {
	self = this;
	var lastSync = 0;
	var rows = [];
	var tableElement;
	var tableIsFocused = false;
	var lastSortedAscending = false;

	var cellRenderer = function(value, row, column, rowData) {
		return value;
	}

	var headRenderer = function(columnData) {
		return '<th style="width: ' + columnData['width'] + '">' + columnData['name'] + '</th>';
	}

	var defaultSettings = {
		container: null,
		url: null,
		source: null,
		refreshInterval: 0,
		paginable: false,
		pageItemCount: null, 
		multipleSelect: false,
		manualOrder: false,
		columns: [],
		idColumn: 'id',
		onRowClick: null,
		onRowClicked: null,
		onRowDblClick: null,
		onRowDblClicked: null,
		onKeyPress: null,
		onKeyPressed: null,
		onRender: null,
		onData: null
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
console.log('settings', settings);
	init();
	refreshData();

	//----------------------------------- Public functions ---------------------------------

		this.selectAllRows = function() {
			tableElement.find('tbody').find('tr').addClass('selected');
		}

		this.invertRowsSelection = function() {
			var selected = tableElement.find('tbody').find('tr.selected');
			self.selectAllRows();
			selected.removeClass('selected');
		}

		this.getSelectedRowsIndex = function() {
			var selectedRowElements = tableElement.find('tr.selected');
			var result = [];
			selectedRowElements.each(function(index, rowElement) {
				var rowIndex = $(rowElement).attr('data-row-index');
				result.push(parseInt(rowIndex, 10));
			});
			return result;
		}

		this.getRowsByIndex = function (indexes) {
			if (!_.isArray(indexes)) {
				if (isNaN(indexes)) {
					return [];
				}
				indexes = [indexes];
			}
			var result = [];
			indexes.forEach(function(index) {
				if (rows[index]) {
					result.push(rows[index]);
				}
			});

			return result;
		}

		this.getSelectedRows = function() {
			var selectedRowElements = tableElement.find('tr.selected');
			var result = [];
			selectedRowElements.each(function(index, rowElement) {
				var rowIndex = $(rowElement).attr('data-row-index');
				result.push(rows[rowIndex]);
			});
			return result;
		}

		this.moveRow = function(fromIndex, toIndex) {
			if ((fromIndex < 0) || (toIndex < 0) || (fromIndex >= rows.length) || (toIndex >= rows.length) || (fromIndex == toIndex) || (rows.length < 2)) {
				return false;
			}
			//move row data
			var row = rows[fromIndex];
			rows.splice(fromIndex, 1);
			rows.splice(toIndex, 0, row);
			// move rows
			var rowToMove = tableElement.find('[data-row-index=' + fromIndex + ']');

			var targetRow = tableElement.find('[data-row-index=' + toIndex + ']');

			if (fromIndex > toIndex) {
				rowToMove.insertBefore(targetRow);
			}
			else {
				rowToMove.insertAfter(targetRow);				
			}

			refreshIndexes();

			cancelSortedState();

			return true;
		}

		this.insertRow = function(data, index) {
			//if (index === undefined)
		};

		this.refresh = function() {
			render();
		}

		this.url = function(url) {
			if (url === undefined) {
				return settings.url;
			}

			settings.url = url;
			refreshData();
		}

		this.sortByColumn = function (column, ascending) {
			if (!isNaN(column)) {
				column = settings.columns[column];
			}

			if (!_.isObject(column)) {
				console.error("Column not found", column);
				return false;
			}

			if (column.source) {
				rows = _.sortBy(rows, column['source']);

				if (ascending === undefined) {
					lastSortedAscending = !lastSortedAscending;
				}

				if (!lastSortedAscending) {
					rows = rows.reverse();
				}

				render();
			}


		}

	//----------------------------------- Private functions --------------------------------

		function refreshData() {
			var request = $.ajax({
				url: settings.url,
				data: {timeStamp: lastSync},
				type: 'POST',
				dataType: 'json',
			});

			request.done(function(data) {
				if (_.isFunction(settings.onData)) {
					settings.onData(data);
				}
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

				rows = _.intersection(rows, data);			
			}
		}

		function render() {
			if (!tableElement || (tableElement.length < 1)) {
				console.error('Table does not exist. Not initialized or container does not exist');
				return false;
			}

			if(_.isFunction(settings.onRender)) {
				settings.onRender(rows);
			}

			var tableHeader = tableElement.find('thead').empty().append('<tr></tr>');
			settings.columns.forEach(function (column, index) {
				if (column.active) {
					var columnHead = $(column.headRenderer(column)).attr('data-column-index', index);
					tableHeader.append(columnHead);
				}
			});

			var tableBody = tableElement.find('tbody');
			tableBody.empty();
			rows.forEach(function(row, rowIndex) {
				var newRow = $('<tr data-row-index="' + rowIndex + '"></tr>');
				newRow.get(0)['rowData'] = row;
				settings.columns.forEach(function (column, colIndex) {
					if (column.active) {
						var $newCell = $('<td>' + column.cellRenderer(row[column.source], rowIndex, colIndex, row) + "</td>");
						// Set cell element properties
						cellElement = $newCell.get(0);
						cellElement['value'] = row[column.source];
						cellElement['rowIndex'] = rowIndex;
						cellElement['colIndex'] = colIndex;
						cellElement['column'] = column;
						cellElement['rowData'] = row;
						newRow.append($newCell);
					}
				});
				var tableRow = tableBody.append(newRow);
			});
		}

		function refreshIndexes() {
			var rowElements = tableElement.find('[data-row-index]');

			rowElements.each(function(index) {
				$(this).attr('data-row-index', index);
			});
		}

		function moveSelectedRowsDown() {
			var cont = true; 
			var selectedRowsIndex = self.getSelectedRowsIndex();
			selectedRowsIndex.reverse()
			

			_.forEach(selectedRowsIndex, function(rowIndex) {
				if (cont) {
					cont = self.moveRow(rowIndex, 1 + rowIndex);
				} 
				else {
					return false;
				}
			});
		}

		function moveSelectedRowsUp() {
			var cont = true;
			var selectedRowsIndex = self.getSelectedRowsIndex();

			_.forEach(selectedRowsIndex, function(rowIndex) {
				if (cont) {
					cont = self.moveRow(rowIndex, rowIndex - 1);
				} 
				else {
					return false;
				}
			});
		}

		function cancelSortedState() {
			lastSortedAscending = false;
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

			// handle click on row and row selection
			tableElement.find('tbody').on('click', 'tr', function(event) {
				if (_.isFunction(settings.onRowClick) && settings.onRowClick(event) === false) {
					return;
				}
				var $this = $(this);

				if (settings.multipleSelect) {
					if ($this.hasClass('selected')) {
						$this.removeClass('selected');
					}
					else {
						$this.addClass('selected');
					}					
				}
				else {
					var remove = $this.hasClass('selected');
					tableElement.find('.selected').removeClass('selected');
					if (!remove) {
						$this.addClass('selected');
					}
				}


				if (_.isFunction(settings.onRowClicked)) {
					settings.onRowClicked();
				}
			});

			// handle table rearange with up down keys
			$(document).keydown(function(event) {
				if (!tableIsFocused){ 
					return;
				}

				if (event.keyCode == 40) {
					if (settings.manualOrder) {
						moveSelectedRowsDown();
					}
				} 

				if (event.keyCode == 38) {
					if (settings.manualOrder) {
						moveSelectedRowsUp();
					}
				}

				if ((event.keyCode == 65) && (event.ctrlKey == true)) {
					if (settings.multipleSelect) {
						self.selectAllRows();
					}
					event.preventDefault();
				}

				if ((event.keyCode == 88) && (event.ctrlKey == true)) {
					if (settings.multipleSelect) {
						self.invertRowsSelection();
					}
					event.preventDefault();
				}

			})

			// handle table focused
			$(document).click(function(event) {
				if ($.contains(tableElement.get(0), event.target)) {
					tableIsFocused = true;			
				} 
				else {
					tableIsFocused = false;
				}
			})

			// handle column click
			tableElement.on('click', 'th', function(event) {
				var $this = $(this),
					columnIndex = parseInt($this.attr('data-column-index')),
					column = settings.columns[columnIndex];
				
				if (_.isFunction(settings.onColumnClick) && (settings.onColumnClick(column, this, event) === false)) {
					return;
				}

				self.sortByColumn(column);

				if (_.isFunction(settings.onColumnClick)) {
					settings.onColumnClicked(column, this, event);
				}
			});

			tableElement.on('dblclick', 'tbody td', function(event) {
				if (_.isFunction(settings.onRowDblClick)) {
					if (settings.onRowDblClick(this['value'], this['rowIndex'], this['colIndex'], this['rowData'], event) === false) {
						return;
					}
				}

				if (_.isFunction(settings.onRowDblClicked)) {
					settings.onRowDblClicked(this['value'], this['rowIndex'], this['colIndex'], this['rowData'], event);
				}
			});
		}
}