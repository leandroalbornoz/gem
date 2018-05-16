<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Personas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Personas</a></li>
			<li class="active"><?php echo ucfirst($metodo); ?></li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="persona/agregar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="persona/ver/<?php echo (!empty($persona->id)) ? $persona->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="persona/editar/<?php echo (!empty($persona->id)) ? $persona->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="persona/eliminar/<?php echo (!empty($persona->id)) ? $persona->id : ''; ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<?php if ($this->rol->codigo === ROL_ADMIN || $this->rol->codigo === ROL_USI): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="persona/eliminar/<?php echo (!empty($persona->id)) ? $persona->id : ''; ?>">
								<i class="fa fa-money"></i> Liquidación
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-sm-4">
								<?php echo $fields['cuil']['label']; ?>
								<span class="label label-danger" id="cuil_existente"></span>
								<?php echo $fields['cuil']['form']; ?>
							</div>
							<div class="form-group col-sm-4">
								<?php echo $fields['documento_tipo']['label']; ?>
								<?php echo $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-sm-4">
								<?php echo $fields['documento']['label']; ?>
								<span class="label label-danger" id="documento_existente"></span>
								<?php echo $fields['documento']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-4">
								<?php echo $fields['apellido']['label']; ?>
								<?php echo $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-4">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-2 col-sm-4">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-md-2 col-sm-6">
								<?php echo $fields['nacionalidad']['label']; ?>
								<?php echo $fields['nacionalidad']['form']; ?>
							</div>
							<div class="form-group col-md-2 col-sm-4">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-4 col-sm-4">
								<?php echo $fields['calle']['label']; ?>
								<?php echo $fields['calle']['form']; ?>
							</div>
							<div class="form-group col-md-2 col-sm-2">
								<?php echo $fields['calle_numero']['label']; ?>
								<?php echo $fields['calle_numero']['form']; ?>
							</div>
							<div class="form-group col-md-4 col-sm-4">
								<?php echo $fields['localidad']['label']; ?>
								<?php echo $fields['localidad']['form']; ?>
							</div>
							<div class="form-group col-md-2 col-sm-2">
								<?php echo $fields['codigo_postal']['label']; ?>
								<?php echo $fields['codigo_postal']['form']; ?>
							</div>
							<div class="form-group col-md-5 col-sm-4">
								<?php echo $fields['barrio']['label']; ?>
								<?php echo $fields['barrio']['form']; ?>
							</div>
							<div class="form-group col-md-1 col-sm-2">
								<?php echo $fields['manzana']['label']; ?>
								<?php echo $fields['manzana']['form']; ?>
							</div>
							<div class="form-group col-md-1 col-sm-2">
								<?php echo $fields['casa']['label']; ?>
								<?php echo $fields['casa']['form']; ?>
							</div>
							<div class="form-group col-md-1 col-sm-2">
								<?php echo $fields['departamento']['label']; ?>
								<?php echo $fields['departamento']['form']; ?>
							</div>
							<div class="form-group col-md-1 col-sm-2">
								<?php echo $fields['piso']['label']; ?>
								<?php echo $fields['piso']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-2 col-sm-2">
								<?php echo $fields['telefono_fijo']['label']; ?>
								<?php echo $fields['telefono_fijo']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['telefono_movil']['label']; ?>
								<?php echo $fields['telefono_movil']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['prestadora']['label']; ?>
								<?php echo $fields['prestadora']['form']; ?>
							</div>
							<div class="form-group col-md-4 col-sm-4">
								<?php echo $fields['email']['label']; ?>
								<?php echo $fields['email']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['estado_civil']['label']; ?>
								<?php echo $fields['estado_civil']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['nivel_estudio']['label']; ?>
								<?php echo $fields['nivel_estudio']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['ocupacion']['label']; ?>
								<?php echo $fields['ocupacion']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['grupo_sanguineo']['label']; ?>
								<?php echo $fields['grupo_sanguineo']['form']; ?>
							</div>
							<div class="form-group col-md-12 col-sm-12">
								<?php echo $fields['obra_social']['label']; ?>
								<?php echo $fields['obra_social']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['depto_nacimiento']['label']; ?>
								<?php echo $fields['depto_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-3">
								<?php echo $fields['fecha_defuncion']['label']; ?>
								<?php echo $fields['fecha_defuncion']['form']; ?>
							</div>
							<div class="form-group col-md-6 col-sm-6">
								<?php echo $fields['lugar_traslado_emergencia']['label']; ?>
								<?php echo $fields['lugar_traslado_emergencia']['form']; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="persona/listar" title="Cancelar">Cancelar</a>
						<?php echo zetta_form_submit($txt_btn); ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $persona->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#cuil').inputmask("99-99999999-9");
		$('#documento,#documento_tipo').change(verificar_doc_repetido);
		$('#cuil').change(verificar_cuil_repetido);
	});
	function verificar_doc_repetido(e) {
		var documento_tipo = $('#documento_tipo')[0].selectize.getValue();
		var documento = $('#documento').val();
		if (documento_tipo === '8') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_indocumentado?',
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#documento_existente').text(null);
						$('#boton_guardar').attr('disabled', false);
						$('#documento').closest('.form-group').removeClass("has-error");
						$('#documento').val(result);
					}
				}
			});
		} else if (documento_tipo !== '' && documento !== '') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_persona?',
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#documento_existente').text('Doc. en Uso');
						$('#boton_guardar').attr('disabled', true);
						$('#documento').closest('.form-group').addClass("has-error");
					} else {
						$('#documento_existente').text(null);
						$('#boton_guardar').attr('disabled', false);
						$('#documento').closest('.form-group').removeClass("has-error");
					}
				}
			});
		}
	}

	function verificar_cuil_repetido(e) {
		var cuil = $('#cuil').val();
		if (cuil !== '') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_cuil?',
				data: {cuil: cuil, id: '<?= empty($persona) ? '' : $persona->id; ?>'},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#cuil_existente').text('Ya existe persona con este cuil');
						$('#cuil').closest('.form-group').addClass("has-error");
					} else {
						$('#cuil_existente').text(null);
						$('#cuil').closest('.form-group').removeClass("has-error");
					}
				}
			});
		}
	}
</script>