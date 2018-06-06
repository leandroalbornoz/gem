<?php if (in_array($this->rol->codigo, ARRAY(ROL_LINEA, ROL_PRIVADA, ROL_SEOS))): ?>
	<div class="row">
		<div class="form-group col-sm-3">
			<?php echo $fields['supervision']['label']; ?>
			<?php echo $fields['supervision']['form']; ?>
		</div>
		<label>&nbsp;</label><br/>
		<button class="btn btn-primary" id="btn-search" type="button">
			<i class="fa fa-search"></i> Buscar Escuelas
		</button>
	</div>
<?php endif; ?>
<?php if (in_array($this->rol->codigo, ARRAY(ROL_ADMIN, ROL_USI, ROL_LIQUIDACION))): ?>
	<div class="row">
		<div class="form-group col-sm-3">
			<?php echo $fields['dependencia']['label']; ?>
			<?php echo $fields['dependencia']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['linea']['label']; ?>
			<?php echo $fields['linea']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['nivel']['label']; ?>
			<?php echo $fields['nivel']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['supervision']['label']; ?>
			<?php echo $fields['supervision']['form']; ?>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			var xhr_nivel;
			var xhr_supervision;
			var select_linea, $select_linea;
			var select_nivel, $select_nivel;
			var select_supervision, $select_supervision;
			$select_linea = $('select#linea').selectize({
				onChange: linea_actualizada
			});
			$select_nivel = $('select#nivel').selectize({
				valueField: 'id',
				labelField: 'descripcion',
				searchField: ['descripcion']
			});
			$select_supervision = $('select#supervision').selectize({
				valueField: 'id',
				labelField: 'nombre',
				searchField: ['nombre']
			});
			select_linea = $select_linea[0].selectize;
			select_nivel = $select_nivel[0].selectize;
			select_supervision = $select_supervision[0].selectize;
			if (select_linea.getValue() !== '') {
				linea_actualizada(select_linea.getValue());
			}
			function linea_actualizada(value) {
				actualizar_nivel(value);
				actualizar_supervision(value);
			}
			function actualizar_nivel(value) {
				select_nivel.enable();
				var valor = select_nivel.getValue();
				select_nivel.disable();
				select_nivel.clearOptions();
				if (value == '') {
					return;
				}
				select_nivel.load(function(callback) {
					xhr_nivel && xhr_nivel.abort();
					xhr_nivel = $.ajax({
						url: 'ajax/get_niveles/' + value,
						dataType: 'json',
						success: function(results) {
							select_nivel.enable();
							callback(results);
							if (results.length === 1) {
								select_nivel.setValue(results[0].id);
							} else {
								select_nivel.setValue(valor);
							}
						},
						error: function() {
							callback();
						}
					});
				});
			}
			function actualizar_supervision(value) {
				select_supervision.enable();
				var valor = select_supervision.getValue();
				select_supervision.disable();
				select_supervision.clearOptions();
				if (value == '') {
					return;
				}
				select_supervision.load(function(callback) {
					xhr_supervision && xhr_supervision.abort();
					xhr_supervision = $.ajax({
						url: 'ajax/get_supervisiones/' + value,
						dataType: 'json',
						success: function(results) {
							select_supervision.enable();
							callback(results);
							select_supervision.setValue(valor);
						},
						error: function() {
							callback();
						}
					})
				});
			}
		}
		);
	</script>
	<div class="form-group pull-left">
		<label>&nbsp;</label><br/>
		<button class="btn btn-primary" id="btn-search" type="button">
			<i class="fa fa-search"></i> Buscar Escuelas
		</button>
	</div>
<?php endif; ?>
<div class="form-group pull-right">
	<label style="width:100%">&nbsp;</label>
	<a class="btn btn-sm btn-primary" href="javascript:seleccionar_todo()" title="Marcar todos">Marcar todos</a>
	<a class="btn btn-sm btn-danger"  href="javascript:deseleccionar_todo()" title="Desmarcar todos">Desmarcar todos</a>
</div>
<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_entidades">
	<thead>
		<tr style="background-color: #f4f4f4" >
			<th style="text-align: center;" colspan="8">Lista de escuelas filtradas</th>
		</tr>
		<tr>
			<th style="width: 5%;">Número</th>
			<th style="width: 5%;">Anexo</th>
			<th style="width: 30%;">Nombre</th>
			<th style="width: 15%;">Dependencia</th>
			<th style="width: 10%;">Nivel</th>
			<th style="width: 15%;">Línea</th>
			<th style="width: 15%;">Supervisión</th>
			<th style="width: 5%;"></th>
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
		table_entidades = $('#tbl_listar_entidades').DataTable({dom: 'tp', autoWidth: false, paging: false, pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], columnDefs: [{orderable: false, targets: [1, 4, 5]}]});
<?php if (in_array($this->rol->codigo, ARRAY(ROL_ADMIN, ROL_USI, ROL_LIQUIDACION, ROL_LINEA, ROL_PRIVADA, ROL_SEOS))): ?>
			$('#btn-search').click(function() {
<?php endif; ?>
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
						for (var idx in result.escuelas) {
							var escuela = result.escuelas[idx];
							table_entidades.row.add([
								escuela.numero,
								escuela.anexo,
								escuela.nombre,
								escuela.dependencia,
								escuela.nivel,
								escuela.linea,
								escuela.supervision,
								'<input type="checkbox" align="center" name="entidad[]" value=' + escuela.id + '>'
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
							'-',
							'-',
							'-']);
						table_entidades.draw();
					}
				}
			});
<?php if (in_array($this->rol->codigo, ARRAY(ROL_ADMIN, ROL_USI, ROL_LIQUIDACION, ROL_LINEA, ROL_PRIVADA, ROL_SEOS))): ?>
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