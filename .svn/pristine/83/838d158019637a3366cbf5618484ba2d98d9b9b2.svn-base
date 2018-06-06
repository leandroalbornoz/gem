<div class="form-group pull-right">
	<label style="width:100%">&nbsp;</label>
	<a class="btn btn-sm btn-primary" href="javascript:seleccionar_todo()" title="Marcar todos">Marcar todos</a>
	<a class="btn btn-sm btn-danger"  href="javascript:deseleccionar_todo()" title="Desmarcar todos">Desmarcar todos</a>
</div>
<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_entidades">
	<thead>
		<tr style="background-color: #f4f4f4" >
			<th style="text-align: center;" colspan="2">Lista de Direcciones de linea filtradas</th>
		</tr>
		<tr>
			<th style="width: 90%;">Nombre</th>
			<th style="width: 10%;"></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>
	function seleccionar_todo() {
		$('#form_mensaje').find('input[type="checkbox"]').prop('checked', true);
		$('#form_mensaje').find('input[type="checkbox"]').change();
	}
	function deseleccionar_todo() {
		$('#form_mensaje').find('input[type="checkbox"]').prop('checked', false);
		$('#form_mensaje').find('input[type="checkbox"]').change();
	}
	var table_entidades;
	$(document).ready(function() {
		table_entidades = $('#tbl_listar_entidades').DataTable({dom: 'tp', autoWidth: false, paging: false, pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], columnDefs: [{orderable: false, targets: [1]}]});
		table_entidades.clear();
		$('#tbl_listar_entidades tbody').html('');
		$.ajax({
			type: 'POST',
			url: 'mensaje/mensaje_difusion_listar_entidades?',
			data: {entidad_tipo: <?php echo $rol_seleccionado->entidad_tipo_id; ?>},
			dataType: 'json',
			success: function(result) {
				if (result.status === 'success') {
					for (var idx in result.lineas) {
						var linea = result.lineas[idx];
						table_entidades.row.add([
							linea.nombre,
							'<input type="checkbox" align="center" name="entidad[]" value=' + linea.id + '>'
						]);
					}
					table_entidades.draw();
				} else {
					table_entidades.row.add([
						'-',
						'-']);
					table_entidades.draw();
				}
			}
		});
		$('#form_mensaje').change('input[type="checkbox"]', function() {
			$('#cartel').addClass('hidden');
			$('#seleccionar').prop('disabled', false);
			var cantidad = $('#form_mensaje').find('input[type="checkbox"]:checked').size();
			$('#para_escuelas').val(cantidad + ' destinatarios seleccionados');
		});
		$("#seleccionar").on("click", function() {
			var cantidad = $('#form_mensaje').find('input[type="checkbox"]:checked').size();
			if (cantidad === 0) {
				$('#seleccionar').prop('disabled', true);
				$('#cartel').removeClass('hidden');
			} else {
				$('#cartel').addClass('hidden');
				$('#seleccionar').prop('disabled', false);
			}
		});
	});
</script>