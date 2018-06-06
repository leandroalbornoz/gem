<div class="content-wrapper">
	<section class="content-header">
		<h1>ANEXO 1 - <?php echo "$escuela->nombre_largo"; ?></h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
			<?php if (!empty($escuela)): ?>
				<li><?php echo "Esc. $escuela->nombre_largo"; ?></li>
			<?php endif; ?>
			<li class="active">Anexo I</li>
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
						<a class=" btn bg-default btn-app pull-right" href="rrhh/exportar_pdf/<?php echo $escuela->id; ?>" title="Exportar Pdf Inscriptos" target="_blank"><i class="fa fa-file-pdf-o" style="color:red"></i> Exportar a pdf</a>
						<div class="row text-center">
							<h3><u>DECLARACIÓN JURADA DE PLANTA DE CELADORES</u></h3>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4">
								<b>Escuela N°: </b> <?php echo "$escuela->numero/$escuela->anexo"; ?><br>
								<b>Anexos: </b> <?php echo $escuela->anexos; ?><br>
								<b>Domicilio: </b> <?php echo "$escuela->calle $escuela->calle_numero, $escuela->barrio $escuela->manzana $escuela->casa"; ?><br>
								<b>Departamento: </b> <?php echo $escuela->departamento; ?><br>
								<b>Distrito: </b> <?php echo $escuela->localidad; ?><br>
								<b>Turno: </b> <?php echo $escuela->turno; ?><br>
							</div>
							<div class="col-md-4">
								<b>N° Secciones: </b> <?php echo $escuela->divisiones + $escuela->divisiones_anexo; ?><br>
								<?php if (count($anexos) > 1): ?>
									<?php foreach ($anexos as $anexo): ?>
										<?php if ($anexo->anexo === '0'): ?>
											&nbsp;&nbsp;&nbsp;Sede: <?php echo $anexo->divisiones; ?><br>
										<?php else: ?>
											&nbsp;&nbsp;&nbsp;Anexo <?php echo $anexo->anexo; ?>: <?php echo $anexo->divisiones; ?><br>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								<b>Matrícula total: </b> <?php echo $escuela->matricula + $escuela->matricula_anexo; ?><br>
								<?php if (count($anexos) > 1): ?>
									<?php foreach ($anexos as $anexo): ?>
										<?php if ($anexo->anexo === '0'): ?>
											&nbsp;&nbsp;&nbsp;Sede: <?php echo $anexo->alumnos; ?><br>
										<?php else: ?>
											&nbsp;&nbsp;&nbsp;Anexo <?php echo $anexo->anexo; ?>: <?php echo $anexo->alumnos; ?><br>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
							<div class="col-md-4">
								<b>Zona: </b> <?php echo $escuela->zona; ?><br>
								<b>Teléfono y/o Cel: </b> <?php echo $escuela->telefono; ?><br>
								<b>E-mail: </b> <?php echo $escuela->email; ?><br>
							</div>
						</div><br>
						<?php if (!empty($celadores)): ?>
							<div class="row">
								<div class="col-xs-12">
									<div class="box">
										<div class="box-header text-center">
											<h3 class="box-title">Nómina de celadores y funciones</h3>
										</div>
										<div class="box-body table-responsive no-padding">
											<table class="table table-hover table-bordered table-condensed">
												<thead>
													<tr>
														<?php if (count($anexos) > 1): ?>
															<th>Anexo</th>
														<?php endif; ?>
														<th>Persona</th>
														<th>Cuil</th>
														<th>F.nac</th>
														<th>S.R</th>
														<th>Horarios</th>
														<th>Tareas</th>
														<th>Concepto</th>
														<th>Estudios</th>
														<th>Fecha de alta</th>
														<th>Reemplaza a</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($celadores as $celador): ?>
														<tr>
															<?php if (count($anexos) > 1): ?>
																<td><?php echo $celador->anexo === '0' ? 'Sede' : "Anexo $celador->anexo"; ?></td>
															<?php endif; ?>
															<td><?php echo "$celador->persona"; ?></td>
															<td style="white-space: nowrap"><?php echo $celador->cuil; ?></td>
															<td style="white-space: nowrap"><?php echo (new DateTime($celador->fecha_nacimiento))->format('d/m/Y'); ?></td>
															<td style="white-space: nowrap"><?php echo $celador->situacion_revista; ?></td>
															<td><a type="button" href="rrhh/modal_ver_horario/<?php echo "$celador->cargo_id/$escuela->id" ?>" class="btn btn-block btn-success btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Ver</a></td>
															<td><?php echo $celador->tarea; ?></td>
															<td><?php echo $celador->celador_concepto; ?></td>
															<td><?php echo $celador->nivel_estudio; ?></td>
															<td style="white-space: nowrap"><?php echo (new DateTime($celador->fecha_alta))->format('d/m/Y') ?></td>
															<td><?php echo $celador->reemplazado; ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-6">
								<div class="box">
									<div class="box-body table-responsive no-padding">
										<table class="table table-hover">
											<tbody>
												<?php if (!empty($caracteristicas)): ?>
													<?php foreach ($caracteristicas as $caracteristica): ?>
														<tr>
															<td><?php echo $caracteristica->descripcion; ?></td>
															<td><?php echo $caracteristica->valor; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr>
														<td>-- Ninguna característica encontrada --</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<a class="btn btn-default" href="rrhh/listar" title="Volver">Volver</a>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
	</section>
</div>