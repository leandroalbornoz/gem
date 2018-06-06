$(document).ready(function() {
	$(window).keypress(function(event) {
		var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
		if (key === 13 && event.target.type !== 'button' && event.target.type !== 'submit'
				&& event.target.type !== 'textarea' && event.target.className !== 'btn') {
			event.preventDefault();
			return false;
		}
	});
	$('input,select').filter(':not([type="checkbox"]):not([type="radio"]):not([type="submit"])').keypress(fn_enter);
	$('input[type="checkbox"]').keypress(function(e) {
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if (key === 13) {
			var input_list = $(e.target).closest('form,#formulario').find('input,select,textarea,button').filter(':enabled:visible:not([readonly="readonly"])');
			$(e.target).prop('checked', !($(e.target).prop('checked')));
			var input = input_list.eq(input_list.index(e.target) + 1)[0];
			if (typeof input !== 'undefined') {
				if ((input.nodeName === 'INPUT' && input.type === 'text') || input.nodeName === '')
					input.select();
				else
					input.focus();
				changing_combo = false;
			}
			e.preventDefault();
		}
	});
	$('input[type="radio"]').keypress(function(e) {
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if (key === 13) {
			$(e.target).prop('checked', true);
			$(e.target).change();
			var input_list = $(e.target).closest('form,#formulario').find('input,select,textarea,button').filter(':enabled:visible:not([readonly="readonly"])');
			var idx = input_list.index(e.target);
			while (input_list[idx].name === e.target.name)
				idx++;

			var input = input_list.eq(idx)[0];
			if (typeof input !== 'undefined') {
				if ((input.nodeName === 'INPUT' && input.type === 'text') || input.nodeName === '')
					input.select();
				else
					input.focus();
			}
			e.preventDefault();
		}
	});
	$('input[type="checkbox"],input[type="radio"]').keydown(function(e) {
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if (key === 37 || key === 39) {
			var input_list = $(e.target).closest('form,#formulario').find('input[type="checkbox"],input[type="radio"]').filter(':enabled:visible:not([readonly="readonly"])[name="' + e.target.name + '"]');
			var offset = 1;
			if (key === 37)
				offset = -1;
			var idx = input_list.index(e.target) + offset;
			if (input_list.length <= idx || idx === -1)
				return;

			var input = input_list.eq(idx)[0];
			if (typeof input !== 'undefined') {
				if ((input.nodeName === 'INPUT' && input.type === 'text') || input.nodeName === '')
					input.select();
				else
					input.focus();
			}
			e.preventDefault();
			return false;
		} else if (key === 38 || key === 40) {
			var input_list = $(e.target).closest('form,#formulario').find('input,select,textarea,button').filter(':enabled:visible:not([readonly="readonly"]):not([name="' + e.target.name + '"]),[id="' + e.target.id + '"]');
			var offset = 1;
			if (key === 38)
				offset = -1;
			var idx = input_list.index(e.target) + offset;
			if (input_list.length <= idx || idx === -1)
				return;

			var input = input_list.eq(idx)[0];
			if (typeof input !== 'undefined') {
				if ((input.nodeName === 'INPUT' && input.type === 'text') || input.nodeName === '')
					input.select();
				else
					input.focus();
			}
			e.preventDefault();
			return false;
		}
	});
});

function fn_enter(event) {
	var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
	if (key === 13) {
		var input_list = $(event.target).closest('form').find('input,select,textarea,button').filter(':enabled:visible:not([readonly="readonly"])');
		var input = input_list.eq(input_list.index(event.target) + 1)[0];
		if (typeof input !== 'undefined') {
			if ((input.nodeName === 'INPUT' && input.type === 'text') || input.nodeName === '') {
				input.select();
			} else {
				input.focus();
			}
		}
		event.preventDefault();
	}
}