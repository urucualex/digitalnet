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


function uploadFile(settings) {

	var baseSettings = {
		file: '',
		action: '',
		fieldName: 'file',
		progressFunction: null,
		successFunction: null,

	}

	settings = _.extend({}, baseSettings, settings);

	// Check if file is ok
    if (!(settings.file instanceof File)) {
        return false;
    }

    // prepare FormData
    var fileSize = settings.file.size;
    var formData = new FormData();
    formData.append(settings.fieldName, settings.file);
    
    // start request
    var request = $.ajax({
      type: 'post'
    , url: settings.action
    , data: formData
    , dataType: 'json'
    , success: function (data) {
        if (_.isFunction(settings.successFunction)) {
            settings.successFunction(data);
        }
    }
    , xhrFields: {
        // add listener to XMLHTTPRequest object directly for progress (jquery doesn't have this yet)
        onprogress: function (progress) {
            // calculate upload progress
            var percentage = Math.floor((progress.loaded / fileSize) * 100);

            if (_.isFunction(settings.progressFunction)) {
                settings.progressFunction(percentage);
            }
        }
    }
    , processData: false
    , contentType: false
    });

    return request;
}

Number.prototype.twoDigits = function() {
	if (isNaN(this)) {
		return this;
	}

	if ((this > -10) && (this < 0)) {
		return '-0' + Math.abs(this);
	}

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