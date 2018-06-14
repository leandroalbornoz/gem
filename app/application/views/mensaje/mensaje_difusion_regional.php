
<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_entidades">
	<thead>
		<tr style="background-color: #f4f4f4" >
			<th style="text-align: center;" colspan="8">Lista de Regionales filtradas</th>
		</tr>
		<tr>
			<th style="width: 90%;">Descripción</th>
			<th style="width: 10%;"></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>
	function cambiar_checkboxs(checked) {
		$('#form_mensaje input[type="checkbox"]').prop('checked', checked);
	}
	var table_entidades;
	$(document).ready(function() {
		$('#tbl_listar_entidades thead tr:nth-child(2n) th:last-child').append('<a class="btn btn-xs btn-primary" href="javascript:cambiar_checkboxs(true)" title="Marcar todos"><i class="fa fa-fw fa-check-square-o"></i></a> <a class="btn btn-xs btn-danger"  href="javascript:cambiar_checkboxs(false)" title="Desmarcar todos"><i class="fa fa-fw fa-square-o"></i></a>');
		table_entidades = $('#tbl_listar_entidades').DataTable({dom: 'tp', autoWidth: false, paging: false, pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], columnDefs: [{orderable: false, targets: [1]}]});
		table_entidades.clear();
		var nivel = $('#nivel').val();
		var dependencia = $('#dependencia').val();
		var linea = $('#linea').val();
		var supervision = $('#supervision').val();
		$('#tbl_listar_entidades tbody').html('');
		$.ajax({
			type: 'POST',
			url: 'mensaje/mensaje_difusion_listar_entidades?',
			data: {nivel: nivel, dependencia: dependencia, linea: linea, supervision: supervision, entidad_tipo: <?php echo $rol_seleccionado->entidad_tipo_id; ?>},
			dataType: 'json',
			success: function(result) {
				if (result.status === 'success') {
					for (var idx in result.regionales) {
						var regional = result.regionales[idx];
						table_entidades.row.add([
							regional.descripcion,
							'<input type="checkbox" align="center" name="entidad[]" value=' + regional.id + '>'
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