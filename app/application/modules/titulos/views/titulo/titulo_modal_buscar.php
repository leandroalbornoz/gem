<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_titulo_buscar_listar')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row"  onkeypress="validar(event)">

		<div class="form-group col-sm-3">
			<?php echo $fields['d_nombre']['label']; ?>
			<?php echo $fields['d_nombre']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['d_establecimiento']['label']; ?>
			<?php echo $fields['d_establecimiento']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['d_tipo']['label']; ?>
			<?php echo $fields['d_tipo']['form']; ?>
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
		<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_titulos">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align: center;" colspan="4">Títulos</th>
				</tr>
				<tr>
					<th>Nombre</th>
					<th>País Origen</th>
					<th>Establecimiento</th>
					<th>Carrera</th>
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
	var table_titulos_busqueda;
	$(document).ready(function() {
		agregar_eventos($('#form_titulo_buscar_listar'));
		table_titulos_busqueda = $('#tbl_listar_titulos').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 5, lengthMenu: [5], columnDefs: [{orderable: false, targets: [3]}]});
		$('#tbl_listar_titulos').css('width', '100%');

		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#d_tipo').attr('readonly', false);
			$('#d_establecimiento').attr('readonly', false);
			$('#d_nombre').attr('readonly', false);
			table_titulos_busqueda.clear();
			$('#tbl_listar_titulos tbody').html('');
		});
		$('#btn-search').click(function() {
			$('#d_tipo,#d_establecimiento,#d_nombre').closest('.form-group').removeClass("has-error");
			$('#btn-search').attr('disabled', true);
			$('#dato_invalido').text('');
			table_titulos_busqueda.clear();
			var tipo = $('#d_tipo').val();
			var establecimiento = $('#d_establecimiento').val();
			var nombre = $('#d_nombre').val();
			if (nombre.length >= 3 || tipo.length >= 3 || establecimiento.length >= 2) {
				$('#tbl_listar_titulos tbody').html('');
				if (nombre !== '' || tipo !== '' || establecimiento !== '') {
					$.ajax({
						type: 'GET',
						url: 'ajax/get_listar_titulos?',
						data: {tipo: tipo, establecimiento: establecimiento, nombre: nombre},
						dataType: 'json',
						success: function(result) {
							$('#d_tipo,#d_establecimiento,#d_nombre').attr('readonly', true);
							$('#btn-clear').attr('disabled', false);
							if (result.status === 'success') {
								if (result.titulos_listar.length > 0) {
									for (var idx in result.titulos_listar) {
										var titulos_listar = result.titulos_listar[idx];
										table_titulos_busqueda.row.add([
											titulos_listar.nombre,
											titulos_listar.pais_origen,
											titulos_listar.establecimiento_descripcion,
											titulos_listar.tipo_descripcion,
											'<button type="submit" class="btn btn-xs btn-success pull-right" title="Seleccionar" name="titulo_id" value="' + titulos_listar.id + '"><i class="fa fa-plus"></i> Agregar</button>']);
									}
								}
								table_titulos_busqueda.row.add([
									'X',
									'',
									'Otro título que no aparece en la lista',
									'',
									'<button type="submit" class="btn btn-xs btn-primary pull-right" title="Seleccionar" name="titulo_id" value="-1""><i class="fa fa-plus"></i> Agregar</button>']);
								table_titulos_busqueda.draw();
								$('#btn-submit').attr('disabled', false);
							} else {
								table_titulos_busqueda.row.add([
									'X',
									'',
									'Título no encontrado',
									'',
									'<button type="submit" class="btn btn-xs btn-primary pull-right" title="Seleccionar" name="titulo_id" value="-1""><i class="fa fa-plus"></i> Agregar</button>']);
								table_titulos_busqueda.draw();
							}
						}
					});
				}
			} else {
				$('#btn-search').attr('disabled', false);
				if (tipo.length < 3 || establecimiento.length < 2 || nombre.length < 3) {
					if (nombre.length < 3) {
						$('#d_nombre').closest('.form-group').addClass("has-error");
					}
					if ((tipo.length < 3)) {
						$('#d_tipo').closest('.form-group').addClass("has-error");
					}
					if (establecimiento.length < 2) {
						$('#d_establecimiento').closest('.form-group').addClass("has-error");
					}

					$('#dato_invalido').text('Ingrese como mínimo 3 caracteres en apellido y nombre');
					$('#boton_guardar').attr('disabled', true);
					$('#boton_guardar2').attr('disabled', true);
				}
			}
		});
	});
</script>