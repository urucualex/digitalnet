// On document load
$(function(){
	$(document).on('submit', '[data-ajax=true]', function(ev) {
		ev.preventDefault();
		submitForm(this);
	});


});

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