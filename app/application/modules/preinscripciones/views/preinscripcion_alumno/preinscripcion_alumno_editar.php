<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumno
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
			<li><a href="preinscripciones/escuela/instancia_<?php echo "$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/escuela"; ?>">Preinscripción</a></li>
			<li class="active">Editar alumno</li>
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
					<div class="box-header with-border">
						<h3 class="box-title">
							<?php if (!empty($alumno)): ?>
								<?php echo "$alumno->apellido, $alumno->nombre"; ?>
							<?php endif; ?>
						</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_preinscripcion')); ?>
					<div class="box-body">
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['cuil']['label']; ?>
								<?php echo $fields['cuil']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['documento_tipo']['label']; ?>
								<?php echo $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['documento']['label']; ?>
								<span class="label label-danger" id="documento_existente"></span>
								<?php echo $fields['documento']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['apellido']['label']; ?>
								<?php echo $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-8">
								<?php echo $fields['email_contacto']['label']; ?>
								<?php echo $fields['email_contacto']['form']; ?>
							</div>
						</div>
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#tab_domicilio" aria-controls="tab_persona" role="tab" data-toggle="tab">Datos Domicilio</a></li>
							<li role="presentation"><a id="a_tab_familiares" href="#tab_familiares" aria-controls="tab_familiares" role="tab" data-toggle="tab">Datos Familiares</a></li>
							<li role="presentation"><a id="a_tab_personales" href="#tab_personales" aria-controls="tab_personales" role="tab" data-toggle="tab">Datos Personales</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="tab_domicilio">
								<div class="row">
									<div class="form-group col-sm-5">
										<?php echo $fields['calle']['label']; ?>
										<?php echo $fields['calle']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['calle_numero']['label']; ?>
										<?php echo $fields['calle_numero']['form']; ?>
									</div>
									<div class="form-group col-sm-5">
										<?php echo $fields['localidad']['label']; ?>
										<?php echo $fields['localidad']['form']; ?>
									</div>
									<div class="form-group col-sm-5">
										<?php echo $fields['barrio']['label']; ?>
										<?php echo $fields['barrio']['form']; ?>
									</div>
									<div class="form-group col-sm-1">
										<?php echo $fields['manzana']['label']; ?>
										<?php echo $fields['manzana']['form']; ?>
									</div>
									<div class="form-group col-sm-1">
										<?php echo $fields['casa']['label']; ?>
										<?php echo $fields['casa']['form']; ?>
									</div>
									<div class="form-group col-sm-1">
										<?php echo $fields['departamento']['label']; ?>
										<?php echo $fields['departamento']['form']; ?>
									</div>
									<div class="form-group col-sm-1">
										<?php echo $fields['piso']['label']; ?>
										<?php echo $fields['piso']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['nacionalidad']['label']; ?>
										<?php echo $fields['nacionalidad']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['telefono_fijo']['label']; ?>
										<?php echo $fields['telefono_fijo']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['telefono_movil']['label']; ?>
										<?php echo $fields['telefono_movil']['form']; ?>
									</div>
									<div class="form-group col-sm-3">
										<?php echo $fields['prestadora']['label']; ?>
										<?php echo $fields['prestadora']['form']; ?>
									</div>
									<div class="form-group col-md-4">
										<?php echo $fields['email']['label']; ?>
										<?php echo $fields['email']['form']; ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_familiares">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="11">
												Familiares
												<?php if ($txt_btn === 'Editar'): ?>
													<a class="pull-left btn btn-xs btn-success" id="persona_agregar_familiar" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/preinscripcion_alumno/modal_alumno_familia_buscar/<?php echo $preinscripcion_alumno_id; ?>"><i class="fa fa-plus"></i></a>
												<?php endif; ?>
											</th>
										</tr>
										<tr>
											<th>Parentesco</th>
											<th>Documento</th>
											<th>Apellido</th>
											<th>Nombre</th>
											<th>Nivel de estudios</th>
											<th>Ocupacion</th>
											<th>Celular</th>
											<th>Email</th>
											<th>Convive</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php $madre = FALSE; ?>
										<?php $padre = FALSE; ?>
										<?php if (!empty($parientes)): ?>
											<?php foreach ($parientes as $pariente): ?>
												<?php $madre |= $pariente->parentesco === 'Madre'; ?>
												<?php $padre |= $pariente->parentesco === 'Padre'; ?>
												<tr>
													<td><?= $pariente->parentesco; ?></td>
													<td><?= $pariente->documento; ?></td>
													<td><?= $pariente->apellido; ?></td>
													<td><?= $pariente->nombre; ?></td>
													<td><?= $pariente->nivel_estudio; ?></td>
													<td><?= $pariente->ocupacion; ?></td>
													<td><?= $pariente->telefono_movil; ?></td>
													<td><?= $pariente->email; ?></td>
													<td><?php
														if ($pariente->convive == 1) {
															echo 'SI';
														} else {
															echo 'No';
														}
														?></td>
													<td width="60">
														<?php if ($txt_btn === 'Editar'): ?>
															<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/preinscripcion_alumno/modal_editar_familiar/<?php echo $preinscripcion_alumno_id; ?>/<?= $pariente->id ?>"><i class="fa fa-edit"></i></a>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/preinscripcion_alumno/modal_eliminar_familiar/<?php echo $preinscripcion_alumno_id; ?>/<?= $pariente->id ?>"><i class="fa fa-remove"></i></a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td style="text-align: center;" colspan="11">
													-- No tiene --
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
								<div class="row">
									<?php if (!$padre && (!empty($alumno->documento_padre) || !empty($alumno->nombre_padre))): ?>
										<div class="col-sm-6">
											<label>Padre (SIGA)</label>
											<input class="form-control" readonly value="<?= "$alumno->documento_padre $alumno->nombre_padre"; ?>">
										</div>
									<?php endif; ?>
									<?php if (!$madre && (!empty($alumno->documento_madre) || !empty($alumno->nombre_madre))): ?>
										<div class="col-sm-6">
											<label>Madre (SIGA)</label>
											<input class="form-control" readonly value="<?= "$alumno->documento_madre $alumno->nombre_madre"; ?>">
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_personales">
								<div class="row">
									<div class="form-group col-md-3">
										<?php echo $fields['estado_civil']['label']; ?>
										<?php echo $fields['estado_civil']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['ocupacion']['label']; ?>
										<?php echo $fields['ocupacion']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['nivel_estudio']['label']; ?>
										<?php echo $fields['nivel_estudio']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['grupo_sanguineo']['label']; ?>
										<?php echo $fields['grupo_sanguineo']['form']; ?>
									</div>
									<div class="form-group col-md-12">
										<?php echo $fields['obra_social']['label']; ?>
										<?php echo $fields['obra_social']['form']; ?>
									</div>
									<div class="form-group col-md-5">
										<?php echo $fields['lugar_traslado_emergencia']['label']; ?>
										<?php echo $fields['lugar_traslado_emergencia']['form']; ?>
									</div>
									<div class="form-group col-md-7">
										<?php echo $fields['observaciones']['label']; ?>
										<?php echo $fields['observaciones']['form']; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="preinscripciones/escuela/instancia_<?php echo "$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/escuela"; ?>" title="Cancelar">Cancelar</a>
						<input type="submit" value="Guardar" class="btn btn-primary pull-right" title="Guardar" id="btn_guardar">
						<?php echo form_hidden('id', $alumno->id); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
				<div style="display:none;" id="div_buscar_familiar"></div>
			</div>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Trayectoria</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
							<thead>
								<tr>
									<th>C. lectivo</th>
									<th>Escuela</th>
									<th>Division</th>
									<th>Legajo</th>
									<th>Desde</th>
									<th>Causa de entrada</th>
									<th>Hasta</th>
									<th>Causa de salida</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($trayectoria)): ?>
									<?php foreach ($trayectoria as $division): ?>
										<tr>
											<td><?= $division->ciclo_lectivo; ?></td>
											<td><?= "$division->numero_escuela - $division->nombre_escuela"; ?></td>
											<td><?= "$division->curso $division->division"; ?></td>
											<td><?= $division->legajo; ?></td>
											<td><?= empty($division->fecha_desde) ? '' : (new DateTime($division->fecha_desde))->format('d/m/y'); ?></td>
											<td><?= $division->causa_entrada; ?></td>
											<td><?= empty($division->fecha_hasta) ? '' : (new DateTime($division->fecha_hasta))->format('d/m/y'); ?></td>
											<td><?= $division->causa_salida; ?></td>
											<td><?= $division->estado; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td style="text-align: center;" colspan="9">
											-- Sin trayectoria --
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#documento,#documento_tipo').change(verificar_doc_repetido);
	});
	function verificar_doc_repetido(e) {
		var documento_tipo = $('#documento_tipo')[0].selectize.getValue();
		var documento = $('#documento').val();
		if (documento_tipo === '8') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_indocumentado?',
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= $alumno->persona_id; ?>'},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#documento_existente').text(null);
						$('#btn_guardar').attr('disabled', false);
						$('#documento').closest('.form-group').removeClass("has-error");
						$('#documento').val(result);
					}
				}
			});
		} else if (documento_tipo !== '' && documento !== '') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_persona?',
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= $alumno->persona_id; ?>'},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#documento_existente').text('Doc. en Uso');
						$('#btn_guardar').attr('disabled', true);
						$('#documento').closest('.form-group').addClass("has-error");
					} else {
						$('#documento_existente').text(null);
						$('#btn_guardar').attr('disabled', false);
						$('#documento').closest('.form-group').removeClass("has-error");
					}
				}
			});
		}
	}
</script>
<script>
	$(document).ready(function() {
		$('#cuil').inputmask("99-99999999-9");
		var url = document.location.toString();
		if (url.match('#')) {
			$('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
		}
		$('.nav-tabs a').on('shown.bs.tab', function(e) {
			if (history.replaceState) {
				history.replaceState(null, null, url.split('#')[0] + e.target.hash);
			} else {
				window.location.hash = e.target.hash;
			}
		});
<?php if (isset($pariente_id)): ?>
	<?php if ($pariente_id === '-1'): ?>
				$('#div_buscar_familiar').append('<a id="a_buscar_familiar" href="preinscripciones/preinscripcion_alumno/modal_agregar_familiar_nuevo/<?php echo "$preinscripcion_alumno_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
				setTimeout(function() {
					$('#a_buscar_familiar').click();
				}, 500);
	<?php else: ?>
				$('#div_buscar_familiar').append('<a id="a_buscar_familiar" href="preinscripciones/preinscripcion_alumno/modal_agregar_familiar_existente/<?php echo "$preinscripcion_alumno_id/$pariente_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
				setTimeout(function() {
					$('#a_buscar_familiar').click();
				}, 500);
	<?php endif; ?>
<?php endif; ?>
	});
</script>

<?php if (isset($abrir_modal) && $abrir_modal): ?>
	<script>
		$(document).ready(function() {
			setTimeout(function() {
				$('#persona_agregar_familiar').click();
			}, 500);
		});
	</script>
<?php endif; ?>