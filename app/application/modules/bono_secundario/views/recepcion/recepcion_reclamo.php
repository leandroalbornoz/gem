<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Bono Secundario
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>"><?php echo ucfirst($controlador); ?></a></li>
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
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<div class="alert alert-warning alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-exclamation"></i> Atención!</h4>
							<?php echo "Solo se aceptarán reclamos de lo que el docente tildó. <br> En el caso de ser necesario, usted podrá agregar items a reclamar" ?>
						</div>
						<div class="row">
							<div class="col-md-12"><h4>Datos Personales de <?= "$persona->cuil ($persona->documento_tipo: $persona->documento)" ?>: </h4></div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12" style="text-align: center"><h4><u><b>BONO SECUNDARIO</b></u></h4></div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<?php echo $fields['apellido']['label']; ?>
									<?php echo $fields['apellido']['form']; ?>
								</div>
								<div class="form-group col-md-6">
									<?php echo $fields['nombre']['label']; ?>
									<?php echo $fields['nombre']['form']; ?>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<?php echo $fields['fecha_nacimiento']['label']; ?>
									<?php echo $fields['fecha_nacimiento']['form']; ?>
								</div>
								<div class="form-group col-md-6">
									<?php echo $fields['sexo']['label']; ?>
									<?php echo $fields['sexo']['form']; ?>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<?php echo $fields['calle']['label']; ?>
									<?php echo $fields['calle']['form']; ?>
								</div>
								<div class="form-group col-md-2">
									<?php echo $fields['calle_numero']['label']; ?>
									<?php echo $fields['calle_numero']['form']; ?>
								</div>
								<div class="form-group col-md-2">
									<?php echo $fields['piso']['label']; ?>
									<?php echo $fields['piso']['form']; ?>
								</div>
								<div class="form-group col-md-2">
									<?php echo $fields['departamento']['label']; ?>
									<?php echo $fields['departamento']['form']; ?>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<?php echo $fields['localidad']['label']; ?>
									<?php echo $fields['localidad']['form']; ?>
								</div>
								<div class="form-group col-md-6">
									<?php echo $fields['codigo_postal']['label']; ?>
									<?php echo $fields['codigo_postal']['form']; ?>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<?php echo $fields['telefono_fijo']['label']; ?>
									<?php echo $fields['telefono_fijo']['form']; ?>
								</div>
								<div class="form-group col-md-6">
									<?php echo $fields['telefono_movil']['label']; ?>
									<?php echo $fields['telefono_movil']['form']; ?>
								</div>
							</div>
							<a class='btn btn btn-success pull-right' href='bono_secundario/recepcion/modal_editar_dp/<?= $persona->id ?>' data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class='fa fa-pencil'></i> Editar datos personales de bono secundario</a>
						</div>
						<?php if (isset($persona_gem) && !empty($persona_gem)): ?>
							<div class="col-md-6" style="border-left: 3px solid black">
								<div class="row">
									<div class="col-md-12" style="text-align: center"><h4><u><b>GEM(Solo lectura)</b></u></h4></div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['apellido']['label']; ?>
										<?php echo $fields_p_gem['apellido']['form']; ?>
									</div>
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['nombre']['label']; ?>
										<?php echo $fields_p_gem['nombre']['form']; ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['fecha_nacimiento']['label']; ?>
										<?php echo $fields_p_gem['fecha_nacimiento']['form']; ?>
									</div>
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['sexo']['label']; ?>
										<?php echo $fields_p_gem['sexo']['form']; ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['calle']['label']; ?>
										<?php echo $fields_p_gem['calle']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_p_gem['calle_numero']['label']; ?>
										<?php echo $fields_p_gem['calle_numero']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_p_gem['piso']['label']; ?>
										<?php echo $fields_p_gem['piso']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields_p_gem['departamento']['label']; ?>
										<?php echo $fields_p_gem['departamento']['form']; ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['localidad']['label']; ?>
										<?php echo $fields_p_gem['localidad']['form']; ?>
									</div>
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['codigo_postal']['label']; ?>
										<?php echo $fields_p_gem['codigo_postal']['form']; ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['telefono_fijo']['label']; ?>
										<?php echo $fields_p_gem['telefono_fijo']['form']; ?>
									</div>
									<div class="form-group col-md-6">
										<?php echo $fields_p_gem['telefono_movil']['label']; ?>
										<?php echo $fields_p_gem['telefono_movil']['form']; ?>
									</div>
								</div>
							</div>
						<?php else:; ?>
							<div class="row">
								<div class="col-md-6" style="text-align: center"><h4><u><b>No posee datos en GEM</b></u></h4></div>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-12">
								<h3 style="text-align: center;">Títulos
								</h3>
								<div style="overflow-x:auto;">
									<table style="table-layout: fixed;" class="table table-bordered table-condensed table-striped">
										<tr>
											<th style="width:70px;">F.Emisión</th>
											<th style="width:300px;">Título | Entidad</th>
											<th style="width:65px;">Promedio</th>
											<th style="width:90px;">Modalidad</th>
											<th style="width:100px;">Tipo norma legal</th>
											<th style="width:100px;">Años de cursado</th>
											<th style="width:45px;">Horas reloj</th>
											<th style="width:100px;">Registro</th>
											<th style="width:70px;">Estado</th>
											<th style="width:70px;">Reclamo</th>
										</tr>
										<?php if (isset($titulos_persona) && !empty($titulos_persona)): ?>
											<?php foreach ($titulos_persona as $titulo): ?>
												<tr>
													<td>
														<span><?= !empty($titulo->fecha_emision) ? (new DateTime($titulo->fecha_emision))->format('d/m/Y') : '' ?></span>
													</td>
													<td>
														<span><?= !empty($titulo->titulo) ? $titulo->titulo : '' ?></span>
														<br><span><?= !empty($titulo->entidad_emisora) ? $titulo->entidad_emisora : '' ?></span>
													</td>
													<td style="text-align: center">
														<span><?= !empty($titulo->promedio) ? $titulo->promedio : '' ?></span>
													</td>
													<td>
														<span><?= !empty($titulo->modalidad) ? $titulo->modalidad : '' ?></span>
													</td>
													<td>
														<span><?= !empty($titulo->norma_legal_tipo) ? $titulo->norma_legal_tipo : '' ?></span>
														<span><?= !empty($titulo->norma_legal_numero) ? $titulo->norma_legal_numero : '' ?></span>
														/
														<span><?= !empty($titulo->norma_legal_año) ? $titulo->norma_legal_año : '' ?></span>
													</td>
													<td>
														<span><?= !empty($titulo->años_cursado) ? $titulo->años_cursado : '' ?></span>
													</td>
													<td>
														<span><?= !empty($titulo->cantidad_hs_reloj) ? $titulo->cantidad_hs_reloj : '' ?></span>
													</td>
													<td>
														<span><?= !empty($titulo->registro) ? $titulo->registro : '' ?></span>
													</td>
													<td>
														<span><?php
															if ($titulo->estado == '1'):
																echo "<i class='fa fa-check fa-lg' style='color:green'></i>";
															elseif ($titulo->estado == '0'):
																echo "<i class='fa fa-remove fa-lg' style='color:red'></i>";
															else:
																echo "-";
															endif;
															?></span>
													</td>
													<td>
														<span><?= ($titulo->estado == 2) ? "<input type='checkbox' name='titulo_estado[]' value='$titulo->id'  onclick='return false;' checked>" : "<input type='checkbox' name='titulo_estado[]' value='$titulo->id'" ?> </span> 
													</td>
												</tr>
											<?php endforeach; ?> 
										<?php else: ?>
											<tr>
												<td colspan="9" style="text-align: center;">
													-- Sin Títulos --
												</td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3 style="text-align: center;">Postítulos
								</h3>
								<div style="overflow-x:auto;">
									<table style="table-layout: fixed;" class="table table-bordered table-condensed table-striped">
										<tr>
											<th style="width:70px;">F.Emisión</th>
											<th style="width:300px;">Título | Entidad</th>
											<th style="width:90px;">Tipo</th>
											<th style="width:65px;">Promedio</th>
											<th style="width:90px;">Modalidad</th>
											<th style="width:100px;">Tipo norma legal</th>
											<th style="width:45px;">Horas reloj</th>
											<th style="width:100px;">Registro</th>
											<th style="width:70px;">Aceptar</th>
											<th style="width:70px;">Reclamo</th>
										</tr>
										<?php if (isset($postitulos_persona) && !empty($postitulos_persona)): ?>
											<?php foreach ($postitulos_persona as $postitulo): ?>
												<tr>
													<td>
														<span><?= !empty($postitulo->fecha_emision) ? (new DateTime($postitulo->fecha_emision))->format('d/m/Y') : '' ?></span>
													</td>
													<td>
														<span><?= !empty($postitulo->titulo) ? $postitulo->titulo : '' ?></span>
														<br><span><?= !empty($postitulo->entidad_emisora) ? $postitulo->entidad_emisora : '' ?></span>
													</td>
													<td>
														<span><?= !empty($postitulo->postitulo_tipo) ? $postitulo->postitulo_tipo : '' ?></span>
													</td>
													<td style="text-align: center">
														<span><?= !empty($postitulo->promedio) ? $postitulo->promedio : '' ?></span>
													</td>
													<td>
														<span><?= !empty($postitulo->modalidad) ? $postitulo->modalidad : '' ?></span>
													</td>
													<td>
														<span><?= !empty($postitulo->norma_legal_tipo) ? $postitulo->norma_legal_tipo : '' ?></span>
														<span><?= !empty($postitulo->norma_legal_numero) ? $postitulo->norma_legal_numero : '' ?></span>
														/
														<span><?= !empty($postitulo->norma_legal_año) ? $postitulo->norma_legal_año : '' ?></span>
													</td>
													<td style="text-align: center">
														<span><?= !empty($postitulo->cantidad_hs_reloj) ? $postitulo->cantidad_hs_reloj : '' ?></span>
													</td>
													<td>
														<span><?= !empty($postitulo->registro) ? $postitulo->registro : '' ?></span>
													</td>
													<td>
														<span><?php
															if ($postitulo->estado == '1'):
																echo "<i class='fa fa-check fa-lg' style='color:green'></i>";
															elseif ($postitulo->estado == '0'):
																echo "<i class='fa fa-remove fa-lg' style='color:red'></i>";
															else:
																echo "-";
															endif;
															?></span>
													</td>
													<td>
														<span><?= ($postitulo->estado == 2) ? "<input type='checkbox' name='titulo_estado[]' value='$postitulo->id' onclick='return false;' checked>" : "<input type='checkbox' name='titulo_estado[]' value='$postitulo->id'" ?> </span> 
													</td>
												</tr>
											<?php endforeach; ?> 
										<?php else: ?>
											<tr>
												<td colspan="9" style="text-align: center;">
													-- Sin Postítulos --
												</td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3 style="text-align: center;">Posgrado
								</h3>
								<div style="overflow-x:auto;">
									<table style="table-layout: fixed;" class="table table-bordered table-condensed table-striped">
										<tr>
											<th style="width:70px;">F.Emisión</th>
											<th style="width:300px;">Título | Entidad</th>
											<th style="width:60px;">Tipo</th>
											<th style="width:65px;">Promedio</th>
											<th style="width:90px;">Modalidad</th>
											<th style="width:100px;">Tipo norma legal</th>
											<th style="width:45px;">Horas reloj</th>
											<th style="width:100px;">Registro</th>
											<th style="width:70px;">Aceptar</th>
											<th style="width:70px;">Reclamo</th>
										</tr>
										<?php if (isset($posgrados_persona) && !empty($posgrados_persona)): ?>
											<?php foreach ($posgrados_persona as $posgrado): ?>
												<tr>
													<td>
														<span><?= !empty($posgrado->fecha_emision) ? (new DateTime($posgrado->fecha_emision))->format('d/m/Y') : '' ?></span>
													</td>
													<td>
														<span><?= !empty($posgrado->titulo) ? $posgrado->titulo : '' ?></span>
														<br><span><?= !empty($posgrado->entidad_emisora) ? $posgrado->entidad_emisora : '' ?></span>
													</td>
													<td>
														<span><?= !empty($posgrado->posgrado_tipo) ? $posgrado->posgrado_tipo : '' ?></span>
													</td>
													<td style="text-align: center">
														<span><?= !empty($posgrado->promedio) ? $posgrado->promedio : '' ?></span>
													</td>
													<td>
														<span><?= !empty($posgrado->modalidad) ? $posgrado->modalidad : '' ?></span>
													</td>
													<td>
														<span><?= !empty($posgrado->norma_legal_tipo) ? $posgrado->norma_legal_tipo : '' ?></span>
														<span><?= !empty($posgrado->norma_legal_numero) ? $posgrado->norma_legal_numero : '' ?></span>
														/
														<span><?= !empty($posgrado->norma_legal_año) ? $posgrado->norma_legal_año : '' ?></span>
													</td>
													<td style="text-align: center">
														<span><?= !empty($posgrado->cantidad_hs_reloj) ? $posgrado->cantidad_hs_reloj : '' ?></span>
													</td>
													<td>
														<span><?= !empty($posgrado->registro) ? $posgrado->registro : '' ?></span>
													</td>
													<td>
														<span><?php
															if ($posgrado->estado == '1'):
																echo "<i class='fa fa-check fa-lg' style='color:green'></i>";
															elseif ($posgrado->estado == '0'):
																echo "<i class='fa fa-remove fa-lg' style='color:red'></i>";
															else:
																echo "-";
															endif;
															?></span>
													</td>
													<td>
														<span><?= ($posgrado->estado == 2) ? "<input type='checkbox' name='titulo_estado[]' value='$posgrado->id' onclick='return false;' checked>" : "<input type='checkbox' name='titulo_estado[]' value='$posgrado->id'" ?> </span> 
													</td>
												</tr>
											<?php endforeach; ?> 
										<?php else: ?>
											<tr>
												<td colspan="9" style="text-align: center;">
													-- Sin Posgrados --
												</td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3 style="text-align: center;">
									Antigüedad
								</h3>
								<div style="overflow-x:auto;">
									<table style="table-layout: fixed;" class="table table-bordered table-condensed table-striped">
										<tr>
											<th style="width:40%;">Tipo</th>
											<th style="width:20%px;">Institución</th>
											<th>Fecha desde</th>
											<th>Fecha hasta</th>
											<th style="width:7%;">Aceptar</th>
											<th style="width:7%;">Reclamo</th>
										</tr>
										<?php if (isset($antiguedad_persona) && !empty($antiguedad_persona)): ?>
											<?php foreach ($antiguedad_persona as $antiguedad): ?>
												<tr>
													<td>
														<span> <?= $antiguedad->descripcion; ?></span>
													</td>
													<td>
														<span> <?= $antiguedad->institucion; ?></span>
													</td>
													<td>
														<span> <?= (new DateTime($antiguedad->fecha_desde))->format('d/m/Y'); ?></span>
													</td>
													<td>
														<span> <?= (new DateTime($antiguedad->fecha_hasta))->format('d/m/Y'); ?></span>
													</td>
													<td>
														<span><?php
															if ($antiguedad->estado == '1'):
																echo "<i class='fa fa-check fa-lg' style='color:green'></i>";
															elseif ($antiguedad->estado == '0'):
																echo "<i class='fa fa-remove fa-lg' style='color:red'></i>";
															else:
																echo "-";
															endif;
															?></span>
													</td>
													<td>
														<span><?= ($antiguedad->estado == 2) ? "<input type='checkbox' name='antiguedad_estado[]' value='$antiguedad->id' onclick='return false;' checked>" : "<input type='checkbox' name='antiguedad_estado[]' value='$antiguedad->id'" ?> </span> 
													</td>
												</tr>
											<?php endforeach; ?> 
										<?php else: ?>
											<tr>
												<td colspan="5" style="text-align: center;">
													-- Sin datos antigüedad --
												</td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3 style="text-align: center;">
									Antecedentes
								</h3>
								<div style="overflow-x:auto;">
									<table style="table-layout: fixed;" class="table table-bordered table-condensed table-striped">
										<tr>
											<th style="width:70px;">Fecha de Emisión</th>
											<th style="width:350px;">Antecedente - Institución</th>
											<th style="width:100px;">N° Resolución</th>
											<th style="width:90px;">Duración</th>
											<th style="width:90px;">Modalidad</th>
											<th style="width:70px;">Aprobado</th>
											<th style="width:60px;">Aceptar</th>
											<th style="width:60px;">Reclamo</th>
										</tr>
										<?php if (isset($antecedentes_persona) && !empty($antecedentes_persona)): ?>
											<?php foreach ($antecedentes_persona as $antecedente): ?>
												<tr>
													<td>
														<span> <?= (new DateTime($antecedente->fecha_emision))->format('d/m/Y'); ?></span>
													</td>
													<td>
														<span> <?= $antecedente->antecedente; ?></span><br>
														<span> <?= $antecedente->institucion; ?></span>
													</td>
													<td>
														<span> <?= $antecedente->numero_resolucion; ?></span>
													</td>
													<td>
														<span> <?= $antecedente->duracion . " " . $antecedente->tipo_duracion; ?></span>
													</td>
													<td>
														<span> <?= $antecedente->modalidad; ?></span>
													</td>
													<td>
														<span> <?= $antecedente->aprobado ?></span>
													</td>
													<td>
														<span><?php
															if ($antecedente->estado == '1'):
																echo "<i class='fa fa-check fa-lg' style='color:green'></i>";
															elseif ($antecedente->estado == '0'):
																echo "<i class='fa fa-remove fa-lg' style='color:red'></i>";
															else:
																echo "-";
															endif;
															?></span>
													</td>
													<td>
														<span><?= ($antecedente->estado == 2) ? "<input type='checkbox' name='antecedente_estado[]' value='$antecedente->id' onclick='return false;' checked>" : "<input type='checkbox' name='antecedente_estado[]' value='$antecedente->id'" ?> </span> 
													</td>
												</tr>
											<?php endforeach; ?> 
										<?php else: ?>
											<tr>
												<td colspan="7" style="text-align: center;">
													-- Sin Antecedentes --
												</td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
						<?php if (isset($persona_cargos) && !empty($persona_cargos)) { ?>
							<u><strong>Cargos a los que aspira:</strong></u>		
							<?php
							foreach ($persona_cargos as $persona_cargo) {
								echo "$persona_cargo->cargo .-";
							}
						}
						?>
						<?php if (isset($inscripcion) && !empty($inscripcion)) { ?> 
							<br><br><strong><u>Escuela en la que presentó la documención: </u></strong>
							<?=
							"$inscripcion->escuela";
						}
						?>
						<h3 style="text-align: center;">
							Observaciones: 
						</h3>
						<div class="form-group col-md-12">
							<?php echo $fields['observaciones_recepcion']['form']; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="bono/persona/ver" title="Cancelar">Cancelar</a>
						<?php echo (!empty($txt_btn)) && $txt_btn !== 'Editar títulos' ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo form_hidden('id', $inscripcion->id); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
