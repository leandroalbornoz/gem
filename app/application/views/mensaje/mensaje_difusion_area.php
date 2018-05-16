<div class="form-group pull-right">
	<label style="width:100%">&nbsp;</label>
	<a class="btn btn-sm btn-primary" href="javascript:seleccionar_todo()" title="Marcar todos">Marcar todos</a>
	<a class="btn btn-sm btn-danger"  href="javascript:deseleccionar_todo()" title="Desmarcar todos">Desmarcar todos</a>
</div>
<table class="text-sm table table-hover table-bordered table-condensed" role="grid" id="tbl_listar_entidades">
	<thead>
		<tr style="background-color: #f4f4f4" >
			<th style="text-align: center;" colspan="3">Lista de Areas filtradas</th>
		</tr>
		<tr>
			<th style="width: 30%;">Código</th>
			<th style="width: 60%;">Descripción</th>
			<th style="width: 10%;"></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>
	<tbody>
	</tbody>
</table>
<script>
	var table_entidades;
	function seleccionar_todo() {
		$('#form_mensaje').find('input[type="checkbox"]').prop('checked', true);
		$('#form_mensaje').find('input[type="checkbox"]').change();
	}
	function deseleccionar_todo() {
		$('#form_mensaje').find('input[type="checkbox"]').prop('checked', false);
		$('#form_mensaje').find('input[type="checkbox"]').change();
	}
	$(document).ready(function() {
		table_entidades = $('#tbl_listar_entidades').DataTable({dom: 'tp', autoWidth: false, paging: false, pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], initComplete: entidad_table});
		table_entidades.clear();
		$('#tbl_listar_entidades tbody').html('');
		$.ajax({
			type: 'POST',
			url: 'mensaje/mensaje_difusion_listar_entidades?',
			data: {entidad_tipo: <?php echo $rol_seleccionado->entidad_tipo_id; ?>},
			dataType: 'json',
			success: function(result) {
				if (result.status === 'success') {
					for (var idx in result.areas) {
						var area = result.areas[idx];
						table_entidades.row.add([
							area.codigo,
							area.descripcion,
							'<input type="checkbox" align="center" name="entidad[]" value=' + area.id + '>'
						]);
					}
					table_entidades.draw();
				} else {
					table_entidades.row.add([
						'-',
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
	function entidad_table() {
		agregar_filtros('tbl_listar_entidades', table_entidades, 2);
		$('#tbl_listar_entidades').find('input[placeholder="Código"]').attr("placeholder", "Descripción");
		$('#tbl_listar_entidades').find('input[placeholder="Código"]').change();
		$('#tbl_listar_entidades').find('input[placeholder="Lista de Areas filtradas"]').attr("placeholder", "Código");
		$('#tbl_listar_entidades').find('input[placeholder="Lista de Areas filtradas"]').change();
	}
</script>