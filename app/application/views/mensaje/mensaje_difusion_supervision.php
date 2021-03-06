<?php if (in_array($this->rol->codigo, ARRAY(ROL_ADMIN, ROL_USI))): ?>
	<div class="row">
		<div class="form-group col-sm-3">
			<?php echo $fields['dependencia']['label']; ?>
			<?php echo $fields['dependencia']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['nivel']['label']; ?>
			<?php echo $fields['nivel']['form']; ?>
		</div>
	</div>
	<div class="form-group pull-left">
		<label>&nbsp;</label><br/>
		<button class="btn btn-primary" id="btn-search" type="button">
			<i class="fa fa-search"></i> Buscar Supervisiones
		</button>
	</div>
<?php endif; ?>

<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_entidades">
	<thead>
		<tr style="background-color: #f4f4f4" >
			<th style="text-align: center;" colspan="6">Lista de supervisiones filtradas</th>
		</tr>
		<tr>
			<th style="width: 10%;">Dependencia</th>
			<th style="width: 20%;">Nivel</th>
			<th style="width: 25%;">Nombre</th>
			<th style="width: 20%;">Responsable</th>
			<th style="width: 15%;">Email</th>
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
		table_entidades = $('#tbl_listar_entidades').DataTable({dom: 'tp', autoWidth: false, paging: false, pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], columnDefs: [{orderable: false, targets: [3]}]});
<?php if (in_array($this->rol->codigo, ARRAY(ROL_ADMIN, ROL_USI))): ?>
			$('#btn-search').click(function() {
<?php endif; ?>
			table_entidades.clear();
			var nivel = $('#nivel').val();
			var dependencia = $('#dependencia').val();
			$('#tbl_listar_entidades tbody').html('');
			$.ajax({
				type: 'POST',
				url: 'mensaje/mensaje_difusion_listar_entidades?',
				data: {nivel: nivel, dependencia: dependencia, entidad_tipo: <?php echo $rol_seleccionado->entidad_tipo_id; ?>},
				dataType: 'json',
				success: function(result) {
					if (result.status === 'success') {
						for (var idx in result.supervisiones) {
							var supervision = result.supervisiones[idx];
							table_entidades.row.add([
								supervision.dependencia,
								supervision.nivel,
								supervision.supervision,
								supervision.responsable,
								supervision.email,
								'<input type="checkbox" align="center" name="entidad[]" value=' + supervision.id + '>'
							]);
						}
						table_entidades.draw();
					} else {
						table_entidades.row.add([
							'-',
							'-',
							'-',
							'-',
							'-',
							'-']);
						table_entidades.draw();
					}
				}
			});
<?php if (in_array($this->rol->codigo, ARRAY(ROL_ADMIN))): ?>
			});
<?php endif; ?>
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