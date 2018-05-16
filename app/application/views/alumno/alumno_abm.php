<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumno
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"><?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division_id ?>"><?php echo "$division"; ?></a></li>
			<li><a href="division/alumnos/<?php echo "$division_id/$ciclo_lectivo"; ?>">Alumnos</a></li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="alumno/ver/<?php echo $alumno_division_id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<?php if ($edicion): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="alumno/editar/<?php echo $alumno_division_id; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Editar
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar'] === 'disabled' ? 'disabled' : ''; ?>" href="alumno/caracteristicas/<?php echo $alumno_division_id; ?>">
								<i class="fa fa-edit" id="btn-editar"></i> Características
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar'] === 'disabled' ? 'disabled' : ''; ?>" href="alumno/ficha_psicopedagogica_ver/<?php echo $alumno_division_id; ?>">
							<i class="fa fa-folder-o" id="btn-editar"></i> F. Psicopedagógica
						</a>
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
							<div class="form-group col-md-3">
								<?php echo $fields['apellido']['label']; ?>
								<?php echo $fields['apellido']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields['nacionalidad']['label']; ?>
								<?php echo $fields['nacionalidad']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-5">
								<?php echo $fields['email_contacto']['label']; ?>
								<?php echo $fields['email_contacto']['form']; ?>
							</div>
						</div>	
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#tab_domicilio" aria-controls="tab_persona" role="tab" data-toggle="tab">Datos Domicilio</a></li>
							<li role="presentation"><a id="a_tab_familiares" href="#tab_familiares" aria-controls="tab_familiares" role="tab" data-toggle="tab">Datos Familiares</a></li>
							<li role="presentation"><a id="a_tab_personales" href="#tab_personales" aria-controls="tab_personales" role="tab" data-toggle="tab">Datos Personales</a></li>
							<li role="presentation"><a href="#derivaciones" aria-controls="derivaciones" role="tab" data-toggle="tab">Derivación Hospitalaria/Domiciliaria</a></li>
							<li role="presentation"><a href="#apoyo" aria-controls="apoyo" role="tab" data-toggle="tab">Apoyo Modalidad Especial</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="tab_domicilio">
								<div class="row">
									<div class="form-group col-sm-4">
										<?php echo $fields['calle']['label']; ?>
										<?php echo $fields['calle']['form']; ?>
									</div>
									<div class="form-group col-sm-2">
										<?php echo $fields['calle_numero']['label']; ?>
										<?php echo $fields['calle_numero']['form']; ?>
									</div>
									<div class="form-group col-sm-4">
										<?php echo $fields['localidad']['label']; ?>
										<?php echo $fields['localidad']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields['codigo_postal']['label']; ?>
										<?php echo $fields['codigo_postal']['form']; ?>
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
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_familiares">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="11">
												Familiares
												<?php if ($txt_btn === 'Editar'): ?>
													<a class="pull-left btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno/modal_alumno_familia_buscar/<?php echo $alumno_division_id; ?>"><i class="fa fa-plus"></i></a>
												<?php endif; ?>
											</th>
										</tr>
										<tr>
											<th>Parentesco</th>
											<th>Documento</th>
											<th>Apellido</th>
											<th>Nombre</th>
											<th>Sexo</th>
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
													<td><?= $pariente->sexo; ?></td>
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
															<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno/modal_editar_familiar/<?php echo $alumno_division_id; ?>/<?= $pariente->id ?>"><i class="fa fa-edit"></i></a>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno/modal_eliminar_familiar/<?php echo $alumno_division_id; ?>/<?= $pariente->id ?>"><i class="fa fa-remove"></i></a>
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
							<div role="tabpanel" class="tab-pane" id="derivaciones">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="7">
												Derivación Hospitalaria/Domiciliaria
												<?php if ($txt_btn === 'Editar'): ?>
													<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_derivacion/modal_agregar/<?php echo $alumno_division_id; ?>"><i class="fa fa-plus"></i></a>
												<?php endif; ?>
											</th>
										</tr>
										<tr>
											<th>Ingreso</th>
											<th>Egreso</th>
											<th>Escuela Origen</th>
											<th>Escuela Hosp./Dom.</th>
											<th>Diagnostico</th>
											<th>Fecha de Grabación</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($derivacion)): ?>
											<tr>
												<?php foreach ($derivacion as $campo): ?>
												<tr>
													<td><?= empty($campo->ingreso) ? '' : (new DateTime($campo->ingreso))->format('d/m/Y'); ?></td>
													<td><?= empty($campo->egreso) ? ' - No definida -' : (new DateTime($campo->egreso))->format('d/m/Y'); ?></td>
													<td><?= $campo->escuela2; ?></td>
													<td><?= $campo->escuela; ?></td>
													<td><?= $campo->diagnostico; ?></td>
													<td><?= empty($campo->fecha_grabacion) ? '' : (new DateTime($campo->fecha_grabacion))->format('d/m/Y'); ?></td>
													<td width="60">
														<?php if ($txt_btn === 'Editar'): ?>
															<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_derivacion/modal_editar/<?php echo $alumno_division_id; ?>/<?= $campo->id ?>"><i class="fa fa-edit"></i></a>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_derivacion/modal_eliminar/<?php echo $alumno_division_id; ?>/<?= $campo->id ?>"><i class="fa fa-remove"></i></a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td style="text-align: center;" colspan="6">
													-- No tiene --
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
							<div role="tabpanel" class="tab-pane" id="apoyo">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="8">
												Apoyo modalidad especial
												<?php if ($txt_btn === 'Editar'): ?>
													<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_apoyo_especial/modal_agregar/<?php echo $alumno_division_id; ?>"><i class="fa fa-plus"></i></a>
												<?php endif; ?>
											</th>
										</tr>
										<tr>
											<th>Escuela Origen</th>
											<th>Escuela que brinda apoyo</th>
											<th>Docente que hace el acompañamiento</th>
											<th>Trayectoria Compartida</th>
											<th>CUD</th>
											<th>Cohorte inicial</th>
											<th>Fecha de grabado</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($apoyo_especial)): ?>
											<tr>
												<?php foreach ($apoyo_especial as $campo): ?>
												<tr>
													<td><?= $campo->escuela_origen; ?></td>
													<td><?= $campo->escuela; ?></td>
													<td><?= (!empty($campo->docente_apoyo) ? $campo->docente_apoyo : '-') ?></td>
													<td><?= $campo->trayectoria_compartida; ?></td>
													<td><?= $campo->cud; ?></td>
													<td><?= $campo->cohorte_inicial; ?></td>
													<td><?= empty($campo->fecha_grabacion) ? '' : (new DateTime($campo->fecha_grabacion))->format('d/m/Y'); ?></td>
													<td width="60">
														<?php if ($txt_btn === 'Editar'): ?>
															<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_apoyo_especial/modal_editar/<?php echo $alumno_division_id; ?>/<?= $campo->id ?>"><i class="fa fa-edit"></i></a>
															<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_apoyo_especial/modal_eliminar/<?php echo $alumno_division_id; ?>/<?= $campo->id ?>"><i class="fa fa-remove"></i></a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td style="text-align: center;" colspan="9">
													-- No tiene --
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="<?php echo empty($txt_btn) ? "division/alumnos/$division_id/$ciclo_lectivo" : "alumno/ver/$alumno_division_id"; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php if ($txt_btn === 'Editar'): ?>
							<input type="submit" value="Guardar" class="btn btn-primary pull-right" title="Guardar" id="btn_guardar">
						<?php endif; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $alumno->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
				<div style="display:none;" id="div_buscar_familiar"></div>
			</div>
			<?php if ($txt_btn !== 'Editar'): ?>
				<div class="col-xs-12">
					<div class="box box-primary collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title">Características</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="box-body ">
							<?php if (!empty($fields_tipos)): ?>
								<ul class="nav nav-tabs" role="tablist">
									<?php $tab_class = 'active'; ?>
									<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
										<?php if (count($fields_caracteristicas) > 0): ?>
											<li role="presentation" class="<?= $tab_class; ?>"><a href="#<?= strtolower(str_replace('/', '_', str_replace(' ', '_', $tipo))); ?>" aria-controls="<?= strtolower(str_replace('/', '_', str_replace(' ', '_', $tipo))); ?>" role="tab" data-toggle="tab"><?= $tipo; ?></a></li>
											<?php $tab_class = ''; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
								<div class="tab-content">
									<?php $tab_class = 'active'; ?>
									<?php foreach ($fields_tipos as $tipo => $fields_caracteristicas): ?>
										<?php if (count($fields_caracteristicas) > 0): ?>
											<div role="tabpanel" class="tab-pane <?= $tab_class; ?>" id="<?= strtolower(str_replace('/', '_', str_replace(' ', '_', $tipo))); ?>">
												<?php $tab_class = ''; ?>
												<div class="row">
													<?php foreach ($fields_caracteristicas as $field): ?>
														<div class="form-group col-sm-3">
															<?php echo $field['label']; ?>
															<?php echo $field['form']; ?>
														</div>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if (ENVIRONMENT !== 'production'): ?>
					<div class="col-xs-12">
						<div class="box box-primary collapsed-box">
							<div class="box-header with-border">
								<h3 class="box-title">Abono</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
								</div>
							</div>
							<div class="box-body ">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<a class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="abono/abono_alumno/modal_agregar_abono_alumno/<?= $alumno->id; ?>/<?= $escuela->id . "/" .$division_id . "/" . date('Ym') . "?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>" title="Agregar"><i class="fa fa-plus"></i></a>
									<thead>
										<tr>
											<th>N° Abono</th>
											<th>Tipo Abono</th>
											<th>Monto</th>
											<th>Motivo Alta</th>
											<th>Período</th>
											<th style="min-width:70px;"></th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($abonos)): ?>
											<?php foreach ($abonos as $abono): ?>
												<tr>
													<td><?= $abono->numero_abono; ?></td>
													<td><?= $abono->abono_tipo_descripcion; ?></td>
													<td><?= "$abono->monto"; ?></td>
													<td><?= "$abono->motivo_alta"; ?></td>
													<td><?= "$abono->ames"; ?></td>
													<td class="text-center">
														<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="abono/abono_alumno/modal_editar/<?= $abono->id; ?>/<?= $escuela->id . "?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>" title="Editar"><i class="fa fa-edit"></i></a>
														<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="abono/abono_alumno/modal_eliminar/<?= $abono->id; ?>/<?= $escuela->id . "?redirect_url=" . urlencode(str_replace(base_url(), '', current_url())); ?>" title="Eliminar"><i class="fa fa-remove"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td style="text-align: center;" colspan="10">
													-- Sin Abonos --
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php endif; ?>	
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
										<th>C.L.</th>
										<th>Condición</th>
										<th>Escuela</th>
										<th>División</th>
										<th>Legajo</th>
										<th colspan="2">Ingreso</th>
										<th colspan="2">Egreso</th>
										<th>Estado</th>
										<th>Inasistencias</th>
										<th style="min-width:70px;"></th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($trayectoria)): ?>
										<?php foreach ($trayectoria as $division): ?>
											<tr>
												<td><?= $division->ciclo_lectivo; ?></td>
												<td><?= $division->condicion; ?><?= (empty($division->fecha_hasta)) ? "<a class='btn btn-xs btn-success pull-right' data-remote='false' data-toggle='modal' data-target='#remote_modal' href='alumno_division/modal_certificado_alumno_regular/$division->id' title='Imprimir certificado' target='_blank'><i class='fa fa-print'></i></a>" : ""; ?></td>
												<td><?= "$division->nombre_escuela"; ?></td>
												<td><?= "$division->curso $division->division" . ($division->grado_multiple === 'Si' ? " ($division->curso_grado_multiple)" : ''); ?></td>
												<td><?= $division->legajo; ?></td>
												<td><?= empty($division->fecha_desde) ? '' : (new DateTime($division->fecha_desde))->format('d/m/y'); ?></td>
												<td class="text-sm"><?= $division->causa_entrada; ?></td>
												<td><?= empty($division->fecha_hasta) ? '' : (new DateTime($division->fecha_hasta))->format('d/m/y'); ?></td>
												<td class="text-sm"><?= $division->causa_salida; ?></td>
												<td><?= $division->estado; ?></td>
												<td class="text-center">
													<?= number_format($division->falta, 1, ',', ''); ?>&nbsp;
													<a class="btn btn-xs btn-primary pull-left" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_inasistencia_alumno/<?= $division->id; ?>" title="Editar"><i class="fa fa-clock-o"></i></a>
												</td>
												<td class="text-center">
													<?php if (in_array($this->rol->codigo, array(ROL_ADMIN, ROL_USI))): ?>
														<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_editar_alumno_division/<?= $division->id; ?>" title="Editar"><i class="fa fa-edit"></i></a>
														<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_eliminar_alumno_division/<?= $division->id; ?>" title="Eliminar"><i class="fa fa-remove"></i></a>
													<?php else: ?>
														<div class="btn-group" role="group">
															<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> Administrar <span class="caret"></span></button>
															<ul class="dropdown-menu dropdown-menu-right">
																<li><a class="dropdown-item btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_editar_alumno_division/<?= $division->id; ?>" title="Editar"><i class="fa fa-pencil"></i>Editar fecha ingreso</a></li>
																<li><a class="dropdown-item btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_editar_alumno_condicion/<?= $division->id; ?>" title="Editar"><i class="fa fa-pencil"></i>Editar condición</a></li>
																<?php if (!empty($division->fecha_hasta)) : ?>
																	<li><a class="dropdown-item btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_eliminar_baja/<?= $division->id; ?>" title="Editar"><i class="fa fa-remove"></i>Eliminar egreso</a></li>
																<?php endif; ?>
															</ul>
														</div>
													<?php endif; ?>
													<?php if ($division->grado_multiple === 'Si'): ?>
														<a class="btn btn-xs btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="alumno_division/modal_grado_multiple/<?= $division->id; ?>" title="Grado múltiple"><i class="fa fa-edit"></i></a>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td style="text-align: center;" colspan="10">
												-- Sin trayectoria --
											</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php endif; ?>
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
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($alumno) ? '' : $alumno->persona_id; ?>'},
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
				data: {documento_tipo: documento_tipo, documento: documento, id: '<?= empty($alumno) ? '' : $alumno->persona_id; ?>'},
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
				$('#div_buscar_familiar').append('<a id="a_buscar_familiar" href="alumno/modal_agregar_familiar_nuevo/<?php echo "$alumno_division_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
				setTimeout(function() {
					$('#a_buscar_familiar').click();
				}, 500);
	<?php else: ?>
				$('#div_buscar_familiar').append('<a id="a_buscar_familiar" href="alumno/modal_agregar_familiar_existente/<?php echo "$alumno_division_id/$pariente_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
				setTimeout(function() {
					$('#a_buscar_familiar').click();
				}, 500);
	<?php endif; ?>
<?php endif; ?>
	});
</script>
