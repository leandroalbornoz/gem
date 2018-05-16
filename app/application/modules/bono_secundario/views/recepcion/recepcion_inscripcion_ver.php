<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Bono Secundario
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo "escritorio"; ?>"><?php echo ucfirst($controlador); ?></a></li>
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
					<div class="box-body">
						<div class="row">
							<div class="col-md-12"><h4>Datos Personales: </h4></div>
							<div class="form-group col-md-2">
								<?php echo $fields['documento_tipo']['label']; ?>
								<?php echo $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['documento']['label']; ?>
								<?php echo $fields['documento']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['cuil']['label']; ?>
								<?php echo $fields['cuil']['form']; ?>
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
							<div class="form-group col-md-3">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-3">
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
							<div class="form-group col-md-3">
								<?php echo $fields['localidad']['label']; ?>
								<?php echo $fields['localidad']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['codigo_postal']['label']; ?>
								<?php echo $fields['codigo_postal']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['telefono_fijo']['label']; ?>
								<?php echo $fields['telefono_fijo']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['telefono_movil']['label']; ?>
								<?php echo $fields['telefono_movil']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['email']['label']; ?>
								<?php echo $fields['email']['form']; ?>
							</div>
						</div>
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
											<th style="width:60px;">Aceptado</th>
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
														<span><?= ($titulo->estado == 1) ? "<i class='fa fa-check fa-lg' style='color:green'></i>" : "<i class='fa fa-remove fa-lg' style='color:red'></i>" ?> </span>
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
											<th style="width:60px;">Aceptado</th>
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
														<span><?= ($postitulo->estado == 1) ? "<i class='fa fa-check fa-lg' style='color:green'></i>" : "<i class='fa fa-remove fa-lg' style='color:red'></i>" ?></span> 
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
											<th style="width:60px;">Aceptado</th>
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
														<span><?= ($posgrado->estado == 1) ? "<i class='fa fa-check fa-lg' style='color:green'></i>" : "<i class='fa fa-remove fa-lg'style='color:red'></i>" ?></span> 
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
											<th style="width:7%;">Aceptado</th>
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
														<span><?= ($antiguedad->estado == 1) ? "<i class='fa fa-check fa-lg' style='color:green'></i>" : "<i class='fa fa-remove fa-lg' style='color:red'></i>" ?></span>
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
											<th style="width:60px;">Aceptado</th>
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
														<span><?= ($antecedente->estado == 1) ? "<i class='fa fa-check fa-lg' style='color:green'></i>" : "<i class='fa fa-remove fa-lg' style='color:red'></i>" ?></span>
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
						<br><?php if (isset($inscripcion->observaciones_recepcion) && !empty($inscripcion->observaciones_recepcion)): ?>
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h3 class="panel-title" align="center">Observaciones</h3>
								</div>
								<div class="panel-body">
									<?php
									if (isset($inscripcion->observaciones_recepcion) && ($inscripcion->observaciones_recepcion != '')):
										echo $inscripcion->observaciones_recepcion;
									else:
										echo "<p align=center>-- Sin observaciones --</p> ";
									endif;
									?>									
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>