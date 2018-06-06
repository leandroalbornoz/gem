<script>
	$(document).ready(function () {
		$('#cuil').inputmask('99-99999999-9');
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Buscar Personal
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<div class="row"  onkeypress="validar(event)">
							<div class="form-group col-sm-3">
								<div class="row">
									<label class="col-sm-12" style="line-height: 22px;"><input type="radio" name="opcion" value="2" checked=""> Búsqueda por CUIL.</label>
									<div class="form-group col-sm-12">
										<?php echo $fields['cuil']['label']; ?>
										<?php echo $fields['cuil']['form']; ?>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<div class="row">
									<label class="col-sm-12" style="line-height: 22px;"><input type="radio" name="opcion" value="1"> Búsqueda por Apellido y Nombre.<br></label>
									<div class="form-group col-sm-6">
										<?php echo $fields['apellido']['label']; ?>
										<span class="label label-danger" id="dato_invalido_asterisco"></span>
										<?php echo $fields['apellido']['form']; ?>
									</div>
									<div class="form-group col-sm-6">
										<?php echo $fields['nombre']['label']; ?>
										<?php echo $fields['nombre']['form']; ?>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-3">
								<div class="row">
									<label class="col-sm-12 hidden-xs" style="line-height: 23px;">&nbsp;</label>
								</div>
								<div class="row">
									<label class="col-sm-12">&nbsp;</label>
									<div class="col-sm-12">
										<button class="btn btn-primary" id="btn-search" type="button">
											<i class="fa fa-search"></i>
										</button>
										<button class="btn btn-default" id="btn-clear" type="button">
											<i class="fa fa-times"></i>
										</button>
									</div>
								</div>
							</div>
							<input type="hidden" name="documento_tipo_id" id="documento_tipo_id">
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_personas">
							<thead>
								<tr style="background-color: #f4f4f4" >
									<th style="text-align: center;" colspan="4">Persona</th>
								</tr>
								<tr>
									<th width="20%">CUIL</th>
									<th width="35%">Nombre</th>
									<th width="35%">Área/Escuela</th>
									<th width="10%">Cant. Cargos</th>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
</div>
</section>
</div>
<script>
	function validar(e) {
		var tecla = (document.all) ? e.keyCode : e.which;
		if (tecla === 13) {
			$('#btn-search').click();
		}
	}
	var table_personal_busqueda;
	$(document).ready(function() {
		agregar_eventos($('#form_persona_buscar_listar'));
		table_personal_busqueda = $('#tbl_listar_personas').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 5, lengthMenu: [5]});
		$('#apellido,#nombre').attr('disabled', true);
		$('#cuil').attr('disabled', false);
		$('#cuil').attr('required', true);
		$("input[name='opcion']").change(function() {
			if ($(this).val() === '1') {
				$('#apellido,#nombre').attr('disabled', false);
				$('#apellido,#nombre').attr('required', true);
				$('#cuil').attr('disabled', true);
				$('#cuil').val('');
				$('#btn-clear').click();
			} else if ($(this).val() === '2') {
				$('#cuil').attr('disabled', false);
				$('#cuil').attr('required', true);
				$('#apellido,#nombre').attr('disabled', true);
				$('#apellido,#nombre').val('');
				$('#apellido,#nombre').closest('.form-group').removeClass("has-error");
				$('#').closest('.form-group').removeClass("has-error");
				$('#btn-clear').click();
			}
		});
		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#apellido,#nombre').attr('readonly', false);
			$('#cuil').attr('readonly', false);
			table_personal_busqueda.clear();
			$('#tbl_listar_personas tbody').html('');
		});
		$('#btn-search').click(function() {
			$('#apellido,#nombre').closest('.form-group').removeClass("has-error");
			$('#btn-search').attr('disabled', true);
			$('#dato_invalido').text('');
			table_personal_busqueda.clear();
			var cuil = $('#cuil').val();
			var nombre = $('#nombre').val();
			var apellido = $('#apellido').val();
			if ((nombre.length >= 3 && apellido.length >= 3) || cuil !== '') {
				$('#tbl_listar_personas tbody').html('');
				if (nombre !== '' || apellido !== '' || cuil !== '') {
					$.ajax({
						type: 'GET',
						url: 'ajax/get_personal?',
						data: {nombre: nombre, apellido: apellido, cuil: cuil},
						dataType: 'json',
						success: function(result) {
							$('#apellido,#nombre').attr('readonly', true);
							$('#btn-clear').attr('disabled', false);
							$('#cuil').attr('readonly', true);
							if (result.status === 'success') {
								if (result.personas_listar.length > 0) {
									for (var idx in result.personas_listar) {
										var personas_listar = result.personas_listar[idx];
										table_personal_busqueda.row.add([
											personas_listar.cuil,
											personas_listar.persona,
											personas_listar.escuela,
											personas_listar.cant_cargos
										]);
									}
								}
								table_personal_busqueda.draw();
								$('#btn-submit').attr('disabled', false);
							} else {
								table_personal_busqueda.row.add([
									'X',
									'Persona no encontrada',
									'',
									'']);
								table_personal_busqueda.draw();
							}
						}
					});
				}
			} else {
				$('#btn-search').attr('disabled', false);
				if ((nombre.length < 3 || apellido.length < 3)) {
					if ((nombre.length < 3)) {
						$('#nombre').closest('.form-group').addClass("has-error");
					}
					if (apellido.length < 3) {
						$('#apellido').closest('.form-group').addClass("has-error");
					}
					$('#dato_invalido').text('Ingrese como mínimo 3 caracteres en apellido y nombre');
				}
			}
		});
	});
</script>