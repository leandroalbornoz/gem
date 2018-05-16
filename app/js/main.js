Inputmask.extendDefaults({ androidHack: "rtfm" });
$(document).ready(function() {
	agregar_eventos($(document));
});

function agregar_filtros(id, table, columns) {
	$('#' + id + ' tfoot th').each(function(i) {
		var title = $('#' + id + ' thead th').eq(i).text();
		if (title !== '') {
			$(this).html('<input class="form-control input-xs" style="width: 100%;" type="text" placeholder="' + title + '" value="' + table.column(i).search() + '"/>');
		}
	});
	$('#' + id + ' tfoot th').eq(columns).html('<button class="btn btn-xs btn-default" onclick="limpiar_filtro(\'' + id + '\');" title="Limpiar filtros"><i class="fa fa-eraser"></i> Filtros</button>');
	table.columns().every(function() {
		var column = this;
		$('input', table.table().footer().children[0].children[this[0][0]]).on('change keypress', function(e) {
			if (e.type === 'change' || e.which === 13) {
				if (column.search() !== this.value) {
					column.search(this.value).draw();
				}
			}
		});
	});
	var r = $('#' + id + ' tfoot tr');
	r.find('th').each(function() {
		$(this).css('padding', 8);
	});
	$('#' + id + ' thead').append(r);
	$('#search_0').css('text-align', 'center');
}
function limpiar_filtro(id) {
	localStorage.removeItem('DataTables_' + id + '_' + window.location.pathname);
	location.reload();
}
Selectize.define('clear_selection', function(options) {
	var self = this;

	//Overriding because, ideally you wouldn't use header & clear_selection simultaneously
	self.plugins.settings.dropdown_header = {
		title: '<span style="cursor:pointer;">Quitar selección</span>'
	};
	this.require('dropdown_header');

	self.setup = (function() {
		var original = self.setup;

		return function() {
			original.apply(this, arguments);
			this.$dropdown.on('mousedown', '.selectize-dropdown-header', function(e) {
				self.setValue('');
				self.close();
				self.blur();

				return false;
			});
		}
	})();
});
function agregar_eventos(container) {
	container.find('select.selectize').selectize({plugins: {
			'clear_selection': {}
		}});
	container.find('.input-selectize').selectize({
		delimiter: ',',
		persist: false,
		create: function(input) {
			return {
				value: input,
				text: input
			}
		},
		render: {
			option_create: function(data, escape) {
				return '<div class="create">Agregar <strong>' + escape(data.input) + '</strong>&hellip;</div>';
			}
		}
	});
	setTimeout(function() {
		container.preventDoubleSubmission();
	}, 1000);
	container.find('.precioFormat,numberFormat').each(function(index, element) {
		$(element).inputmask('decimal', {radixPoint: ',', digits: 2, autoUnmask: true, placeholder: '', removeMaskOnSubmit: true, onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}});
		$(element).on('change', function(e) {
			var value = $(element).inputmask('unmaskedvalue');
			if (value !== '') {
				var decimals = value.split('.')[1];
				if (typeof decimals === 'undefined')
					$(element).val(value + ',00');
				else {
					$(element).val(value + ('00').substr(decimals.length));
				}
			}
		});
	});
	container.find('.dateFormat').each(function() {
		$(this).inputmask("d[/m[/y]]");
		$(this).on('blur', function(e) {
			if ($(this).inputmask('unmaskedvalue') == null) {
				$(this).val('');
			} else {
				if ($(this).inputmask('unmaskedvalue').length === 2) {
					$(this).val($(this).inputmask('unmaskedvalue') + $.datepicker.formatDate('mmyy', new Date()));
				} else if ($(this).inputmask('unmaskedvalue').length === 4)
					$(this).val($(this).inputmask('unmaskedvalue') + $.datepicker.formatDate('yy', new Date()));
				try {
					$.datepicker.parseDate('ddmmyy', $(this).inputmask('unmaskedvalue'));
					$(this)[0].setCustomValidity('');
				} catch (error) {
					$(this).select();
					$(this)[0].setCustomValidity('Fecha inválida');
				}
			}
		});
	});
}
function table_detalles(api, rowIdx, columns) {
	var html = $.map(columns, function(col, i) {
		return col.hidden ?
						'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
						'<td>' + col.title + ':' + '</td> ' +
						'<td>' + col.data + '</td>' +
						'</tr>' :
						'';
	}).join('');
	html = $('<table class="table table-condensed table-bordered table-hover"/>').append(html).prop('outerHTML');
//	var html = $.map(columns, function(col, i) {
//		return col.hidden ?
//						'<div class="col-xs-6 col-md-4" data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
//						'<b>' + col.title + ':' + '</b> ' + col.data + '</div>' :
//						'';
//	}).join('');
	return html;
}
function currencyNumber(val) {
	if (val === null)
		return '';
	val = val.toString().replace('.', ',');
	while (/(\d+)(\d{3})/.test(val.toString())) {
		val = val.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
	}
	val = '$ ' + val;
	return val;
}
function validaCuil(cuil) {
	if (typeof (cuil) == 'undefined')
		return true;
	cuil = cuil.toString().replace(/[-_]/g, "");
	if (cuil == '')
		return true; //No estamos validando si el campo esta vacio, eso queda para el "required"
	if (cuil.length != 11)
		return false;
	else {
		var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
		var total = 0;
		for (var i = 0; i < mult.length; i++) {
			total += parseInt(cuil[i]) * mult[i];
		}
		var mod = total % 11;
		var digito = mod == 0 ? 0 : mod == 1 ? 9 : 11 - mod;
	}
	return digito == parseInt(cuil[10]);
}
jQuery.fn.preventDoubleSubmission = function() {
	$(this).bind('submit', function(e) {
		if (!e.isDefaultPrevented()) {
			var $form = $(this);
			if ($form.data('submitted') === true) {
				e.preventDefault();
				return false;
			} else {
				$form.data('submitted', true);
			}
		}
	});
	return this;
};