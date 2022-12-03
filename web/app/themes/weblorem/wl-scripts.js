(function ($) {

	/*** PHP localize vars ***/
	let asset_url = php_vars['assets_url']

	/*** Send forms messages ***/
	$('form').on('submit', function (e) {
		e.preventDefault();
		let form = $(this);
		let form_data = form.serialize();
		let form_error = form.find('.form-error');
		let submit = $("input[type=submit]", form);
		let submit_text = submit.val();

		$.ajax({
			url: ajax_script.url,
			type: 'POST',
			data: {
				'action': 'form_send_message',
				'value': form_data,
			},
			beforeSend: function () {
				let svg = '<svg x="0px" y="0px" viewBox="-75 0 200 100" enable-background="new 0 0 15 14" xml:space="preserve"><circle fill="#fff" stroke="none" cx="6" cy="50" r="6"><animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1"/></circle><circle fill="#fff" stroke="none" cx="26" cy="50" r="6"><animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.2"/></circle><circle fill="#fff" stroke="none" cx="46" cy="50" r="6"><animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.3"/></circle></svg>';
				submit.prop('disabled', true);
				form_error.empty();
				submit.attr('value', '. . . . .');
			},
			success: function (json) {
				if (json.success) {
					let url = json.data.url;
					// Send hidden form
					send_hidden_form(url, {method: 'post'});
				} else {
					form_error.html(json.data.message).show();
					$('input[type="submit"]').prop("disabled", false);
				}
				submit.val(submit_text);
			},
			error: function (errorThrown) {
				console.log(errorThrown);
				form_error.html(json.data.message).show();
				$('input[type="submit"]').prop("disabled", false);
			}
		});
	});

	/*** Add title to modal ***/
	$('.add-title').on('click', function () {
		let title = $(this).data('title');
		let form = $('form');
		if (typeof title !== typeof undefined && title !== false) {
			form.find('input[name=form-title]').remove();
			form.append($('<input/>').attr('name', 'form-title').attr('type', 'hidden').attr('value', title));
		}
	})

	/** Send hidden form function */
	function send_hidden_form(path, params, method)
	{
		method = method || 'post';

		let form = document.createElement('form');
		form.setAttribute('method', method);
		form.setAttribute('action', path);

		for (let key in params) {
			if (params.hasOwnProperty(key)) {
				let hiddenField = document.createElement('input');
				hiddenField.setAttribute('type', 'hidden');
				hiddenField.setAttribute('name', key);
				hiddenField.setAttribute('value', params[key]);

				form.appendChild(hiddenField);
			}
		}

		document.body.appendChild(form);
		form.submit();
	}

}( jQuery ));
