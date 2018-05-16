<?php echo form_open(base_url("tribunal/referente/agregar_referente/$escuela->id/$cuenta->id"), array('data-toggle' => 'validator', 'id' => 'form_buscar_referente')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="box-body">
		<div class="row">
			<div class="form-group col-sm-3">
				<?php echo $fields['d_cuil']['label']; ?>
				<?php echo $fields['d_cuil']['form']; ?>
			</div>
			<div class="form-group col-sm-3">
				<label>&nbsp;</label><br>
				<button class="btn btn-primary" id="btn-search" type="button">
					<i class="fa fa-search"></i>
				</button>
				<button class="btn btn-default" id="btn-clear" type="button">
					<i class="fa fa-times"></i>
				</button>
			</div>
			<div class="form-group col-sm-6">
				<?php echo $fields['fecha_desde']['label']; ?>
				<div class="input-group date" id="datepicker">
					<input type="text" class="form-control dateFormat" name="fecha_desde" required id="fecha_desde" value="" required/>
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</div>
				</div>			
			</div>
		</div>
		<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_personas">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align: center;" colspan="4">Personas</th>
				</tr>
				<tr>
					<th width="100px">CUIL</th>
					<th>Nombre</th>
					<th>Regimen</th>
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
		$('#d_cuil').inputmask("99-99999999-9");
		agregar_eventos($('#form_buscar_referente'));
		table_personas_busqueda = $('#tbl_listar_personas').DataTable({dom: 't', autoWidth: false, pagination: false, language: {url: 'plugins/datatables/spanish.json'}});
		$('#tbl_listar_personas').css('width', '100%');
		$('#d_cuil').attr('required', true);
		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#d_cuil').attr('readonly', false);
			table_personas_busqueda.clear();
			$('#tbl_listar_personas tbody').html('');
		});
		$('#btn-search').click(function() {
			$('#btn-search').attr('disabled', true);
			table_personas_busqueda.clear();
			var cuil = $('#d_cuil').val();
			if (cuil !== '') {
				$('#tbl_listar_personas tbody').html('');
				$('#d_persona_id').val(null);
				if (cuil !== '') {
					$.ajax({
						type: 'GET',
						url: 'tribunal/ajax_tribunal/get_listar_referentes?',
						data: {cuil: cuil, escuela_id: <?php echo $escuela->id; ?>, cuenta_id: <?php echo $cuenta->id; ?>},
						dataType: 'json',
						success: function(result) {
							$('#btn-clear').attr('disabled', false);
							$('#d_cuil').attr('readonly', true);
							if (result.status === 'success') {
								if (result.personas_listar.length > 0) {
									for (var idx in result.personas_listar) {
										var personas_listar = result.personas_listar[idx];
										table_personas_busqueda.row.add([
											personas_listar.cuil,
											personas_listar.apellido + ', ' + personas_listar.nombre,
											personas_listar.regimen,
											personas_listar.ya_cargado === '1' ?
															'<div class="bg-red text-bold" style="text-align: center;">Referente ya asignado.</div>' :
															(personas_listar.es_celador === '0' ?
																			'<div class="bg-red text-bold" style="text-align: center;">No es referente.</div>' :
																			'<div style="text-align: center;"><input type="hidden" name="referente_id" value="' + personas_listar.referente_id + '" id="referete_id"/><button type="submit" class="btn btn-xs btn-success" name="servicio_id" value="' + personas_listar.servicio_id + '"><i class="fa fa-plus"></i> Agregar referente</a></div>')]);
									}
								}
								table_personas_busqueda.draw();
								$('#btn-submit').attr('disabled', false);
							} else {
								table_personas_busqueda.row.add([
									'X',
									'Referente no encontrado',
									'',
									'']);
								table_personas_busqueda.draw();
							}
						}
					});
				}
			} else {
				$('#btn-search').attr('disabled', false);
			}
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#datepicker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '<?php echo date('d/m/Y'); ?>',
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>