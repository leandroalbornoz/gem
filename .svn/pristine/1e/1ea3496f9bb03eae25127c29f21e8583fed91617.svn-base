<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_persona_buscar_listar')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row"  onkeypress="validar(event)">
		<div class="form-group col-sm-6">
			<input type="radio" name="opcion" value="2"> Búsqueda por Apellido y Nombre.<br>
		</div>
		<div class="form-group col-sm-6">
			<input type="radio" name="opcion" value="1" checked=""> Búsqueda por Documento.<br>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['d_apellido']['label']; ?>
			<span class="label label-danger" id="dato_invalido_asterisco"></span>
			<?php echo $fields['d_apellido']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['d_nombre']['label']; ?>
			<?php echo $fields['d_nombre']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['d_documento']['label']; ?>
			<?php echo $fields['d_documento']['form']; ?>
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
		<input type="hidden" name="documento_tipo_id" id="documento_tipo_id">
	</div>
	<div class="box-body ">
		<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_personas">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align: center;" colspan="4">Personas</th>
				</tr>
				<tr>
					<th width="20px">Documento</th>
					<th>Nombre</th>
					<th width="75px">F. Nac.</th>
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
<?php echo form_close(); ?>

<script>
	function validar(e) {
		var tecla = (document.all) ? e.keyCode : e.which;
		if (tecla === 13) {
			$('#btn-search').click();
		}
	}
	var table_personas_busqueda;
	$(document).ready(function() {
		agregar_eventos($('#form_persona_buscar_listar'));
		table_personas_busqueda = $('#tbl_listar_personas').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 5, lengthMenu: [5], columnDefs: [{orderable: false, targets: [3]}]});
		$('#tbl_listar_personas').css('width', '100%');
		$('#d_apellido,#d_nombre').attr('disabled', true);
		$('#d_documento').attr('disabled', true);
		$('#d_documento').attr('disabled', false);
		$('#d_documento').attr('required', true);
		$("input[name='opcion']").change(function() {
			if ($(this).val() === '1') {
				$('#d_documento').attr('disabled', false);
				$('#d_documento').attr('required', true);
				$('#d_apellido,#d_nombre').attr('disabled', true);
				$('#d_apellido,#d_nombre').val('');
				$('#d_apellido,#d_nombre').closest('.form-group').removeClass("has-error");
				$('#btn-clear').click();
			} else if ($(this).val() === '2') {
				$('#d_apellido,#d_nombre').attr('disabled', false);
				$('#d_apellido,#d_nombre').attr('required', true);
				$('#d_documento').attr('disabled', true);
				$('#d_documento').val('');
				$('#btn-clear').click();
			}
		});
		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#d_apellido,#d_nombre').attr('readonly', false);
			$('#d_documento').attr('readonly', false);
			table_personas_busqueda.clear();
			$('#tbl_listar_personas tbody').html('');
		});
		$('#btn-search').click(function() {
			$('#d_apellido,#d_nombre').closest('.form-group').removeClass("has-error");
			$('#btn-search').attr('disabled', true);
			$('#dato_invalido').text('');
			table_personas_busqueda.clear();
			var documento = $('#d_documento').val();
			var nombre = $('#d_nombre').val();
			var apellido = $('#d_apellido').val();
			if ((nombre.length >= 3 && apellido.length >= 3) || documento !== '') {
				$('#tbl_listar_personas tbody').html('');
				if (nombre !== '' || apellido !== '' || documento !== '') {
					$.ajax({
						type: 'GET',
						url: 'ajax/get_listar_personas?',
						data: {nombre: nombre, apellido: apellido, documento: documento},
						dataType: 'json',
						success: function(result) {
							$('#d_apellido,#d_nombre').attr('readonly', true);
							$('#btn-clear').attr('disabled', false);
							$('#d_documento').attr('readonly', true);
							if (result.status === 'success') {
								if (result.personas_listar.length > 0) {
									for (var idx in result.personas_listar) {
										var personas_listar = result.personas_listar[idx];
										table_personas_busqueda.row.add([
											personas_listar.descripcion_corta + ' ' + personas_listar.documento,
											personas_listar.apellido + ', ' + personas_listar.nombre,
											personas_listar.fecha_nacimiento,
											'<button type="submit" class="btn btn-xs btn-success pull-right" title="Seleccionar" name="persona_id" value="' + personas_listar.id + '"><i class="fa fa-plus"></i> Agregar</button>']);

									}
								}
								table_personas_busqueda.row.add([
									'X',
									'Otro pariente que no aparece en lista',
									'',
									'<button type="submit" class="btn btn-xs btn-primary pull-right" title="Seleccionar" name="persona_id" value="-1""><i class="fa fa-plus"></i> Agregar</button>']);
								table_personas_busqueda.draw();
								$('#btn-submit').attr('disabled', false);
							} else {
								table_personas_busqueda.row.add([
									'X',
									'Pariente no encontrado',
									'',
									'<button type="submit" class="btn btn-xs btn-primary pull-right" title="Seleccionar" name="persona_id" value="-1""><i class="fa fa-plus"></i> Agregar</button>']);
								table_personas_busqueda.draw();
							}
						}
					});
				}
			} else {
				$('#btn-search').attr('disabled', false);
				if ((nombre.length < 3 || apellido.length < 3)) {
					if ((nombre.length < 3)) {
						$('#d_nombre').closest('.form-group').addClass("has-error");
					}
					if (apellido.length < 3) {
						$('#d_apellido').closest('.form-group').addClass("has-error");
					}
					$('#dato_invalido').text('Ingrese como mínimo 3 caracteres en apellido y nombre');
					$('#boton_guardar').attr('disabled', true);
					$('#boton_guardar2').attr('disabled', true);
				}
			}
		});
	});
</script>