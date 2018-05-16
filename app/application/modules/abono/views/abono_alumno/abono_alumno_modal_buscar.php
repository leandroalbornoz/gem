<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_alumno_buscar_listar')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row"  onkeypress="validar(event)">

		<div class="form-group col-sm-3">
			<?php echo $fields['d_documento']['label']; ?>
			<?php echo $fields['d_documento']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['d_apellido']['label']; ?>
			<?php echo $fields['d_apellido']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['d_nombre']['label']; ?>
			<?php echo $fields['d_nombre']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<label>&nbsp;</label><br/>
			<button class="btn btn-primary" id="btn-search" type="button">
				<i class="fa fa-search"></i>
			</button>
			<button class="btn btn-default" id="btn-clear" type="button">
				<i class="fa fa-times"></i>
			</button>
		</div>
	</div>
	<div class="box-body ">
		<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_alumnos">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align: center;" colspan="4">Alumnos</th>
				</tr>
				<tr>
					<th>Documento</th>
					<th>Nombre</th>
					<th>División</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
	<button  class="btn btn-default pull-left" type="button" id="btn-submit" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
</div>
<input type="hidden" name="ames" value="<?php echo $ames; ?>" id="ames"/>
<input type="hidden" name="division_id" value="<?php echo $division_id; ?>" id="division_id"/>
<?php echo form_close(); ?>

<script>
	function validar(e) {
		var tecla = (document.all) ? e.keyCode : e.which;
		if (tecla === 13) {
			$('#btn-search').click();
		}
	}
	var table_alumnos_busqueda;
	$(document).ready(function() {
		agregar_eventos($('#form_alumno_buscar_listar'));
		table_alumnos_busqueda = $('#tbl_listar_alumnos').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 5, lengthMenu: [5], columnDefs: [{orderable: false, targets: [3]}]});
		$('#tbl_listar_alumnos').css('width', '100%');

		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#d_documento').attr('readonly', false);
			$('#d_nombre').attr('readonly', false);
			$('#d_apellido').attr('readonly', false);
			table_alumnos_busqueda.clear();
			$('#tbl_listar_alumnos tbody').html('');
		});
		$('#btn-search').click(function() {
			$('#d_documento,#d_nombre,#d_apellido').closest('.form-group').removeClass("has-error");
			$('#btn-search').attr('disabled', true);
			$('#dato_invalido').text('');
			table_alumnos_busqueda.clear();
			var documento = $('#d_documento').val();
			var nombre = $('#d_nombre').val();
			var apellido = $('#d_apellido').val();
			var escuela_id = <?php echo $escuela->id; ?>;
			var ames = <?php echo $ames; ?>;
			var division_id = <?php echo $division_id; ?>;
			if (documento.length >= 7 && documento.length < 9 || nombre.length >= 3 || apellido.length >= 3) {
				$('#tbl_listar_alumnos tbody').html('');
				if (escuela_id !== '' || documento !== '' || nombre !== '' || apellido !== '' || ames !== '' ) {
					$.ajax({
						type: 'GET',
						url: 'ajax/get_listar_abonos_alumnos?',
						data: {escuela_id: escuela_id, documento: documento, nombre: nombre, apellido: apellido, ames: ames, division_id: division_id},
						dataType: 'json',
						success: function(result) {
							$('#d_documento,#d_nombre,#d_apellido').attr('readonly', true);
							$('#btn-clear').attr('disabled', false);
							if (result.status === 'success') {
								if (result.alumnos_listar.length > 0) {
									for (var idx in result.alumnos_listar) {
										var alumnos_listar = result.alumnos_listar[idx];
										console.log(alumnos_listar.abono_encontrado);
										table_alumnos_busqueda.row.add([
											alumnos_listar.documento,
											alumnos_listar.nombre,
											alumnos_listar.curso + ' ' + alumnos_listar.division,
											alumnos_listar.abono_encontrado === 'false' ? '<button type="submit" class="btn btn-xs btn-success pull-right" title="Seleccionar" name="alumno_id" value="' + alumnos_listar.alumno_id + '"><i class="fa fa-plus"></i> Agregar</button>' : '<span class="badge bg-red pull-right">El Alumno ya tiene abono</span>']);
									}
								}
								table_alumnos_busqueda.draw();
								$('#btn-submit').attr('disabled', false);
							} else {
								table_alumnos_busqueda.row.add(['', 'No se encontro Alumno', '', '']);
								table_alumnos_busqueda.draw();
							}
						}
					});
				}
			} else {
				$('#btn-search').attr('disabled', false);
				if (documento.length < 7 || documento.length > 9 || nombre.length < 3 || apellido.length < 3) {
					if (documento.length < 7 || documento.length > 9) {
						$('#d_documento').closest('.form-group').addClass("has-error");
					}
					if ((nombre.length < 3)) {
						$('#d_nombre').closest('.form-group').addClass("has-error");
					}
					if (apellido.length < 3) {
						$('#d_apellido').closest('.form-group').addClass("has-error");
					}
					$('#dato_invalido').text('Cantidad de caracteres minimos inválida');
					$('#boton_guardar').attr('disabled', true);
					$('#boton_guardar2').attr('disabled', true);
				}
			}
		});
	});
</script>