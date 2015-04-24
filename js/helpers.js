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

Number.prototype.twoDigits = function() {
	if (this < 10) {
		return '0' + this;
	}
	return this;
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

	return ((hours > 0) || includeHours ? hours.twoDigits() + ':' : '') + minutes.twoDigits() + ':' + seconds.twoDigits();
}

String.prototype.toDate = function(separator) {
	var date = new Date(this);

	if (separator === undefined) {
		separator = '-';
	}

	return date.getDate().twoDigits() + separator + date.getMonth().twoDigits() + separator + date.getFullYear();
}

Number.prototype.toDays = function() {
	return Math.ceil(this/(3600000*24));
}

Date.prototype.isValid = function() {
	if (isNaN(this.getTime())) {
		return false;
	}
	return true;
}