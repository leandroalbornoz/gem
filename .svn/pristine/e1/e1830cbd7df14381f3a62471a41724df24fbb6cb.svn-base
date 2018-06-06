<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Busqueda de Escuelas
		</h1>
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
		<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
		<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_buscar')); ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Seleccione una escuela de la lista</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="form-group col-md-12">
								<div class="form-group col-md-5">
									<?php echo $fields['escuela']['label']; ?>
									<?php echo $fields['escuela']['form']; ?>
								</div>
								<div class="form-group col-md-1">
									<label style="width:100%">&nbsp;</label>
									<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
								</div>
								<div class="form-group col-md-6">
									<a class=" btn pull-right bg-green btn-success btn-app" href="aprender/escritorio/imprimir_excel" title="Exportar excel"><i class="fa fa-file-excel-o"></i>Imprimir Reporte</a>
								</div>
							</div>
						</div>
					</div>
					<div class="box-body ">
						<hr>
						<?php echo form_close(); ?>
						<?php if (!empty($escuela_id)): ?>
							<h4>
								Esc. <?php echo "$escuela->nombre_largo (CUE: $escuela->cue)"; ?>
							</h4>
							<?php foreach ($operativos as $operativo): ?>
								<div class="row">
									<div class="col-xs-12">
										<div class="box box-primary">
											<div class="box-header with-border">
												<h3 class="box-title">Aplicadores <?php echo $operativo->operativo_tipo; ?></h3>
											</div>
											<div class="box-body">
												<a class="btn bg-blue btn-app btn-app-zetta" href="aprender/escritorio/modal_buscar_aplicador/<?php echo $operativo->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
													<i class="fa fa-plus"></i> Agregar aplicador
												</a>
												<a class=" btn bg-default btn-app bg-green" href="aprender/escritorio/imprimir_pdf/<?php echo $operativo->id; ?>" title="Exportar PDF" target="_blank"><i class="fa fa-file-pdf-o"></i>Imprimir</a>
												<div class="row">
													<div class="col-sm-12">
														<label>Divisiones de <?php echo substr($operativo->operativo_tipo, 0, strpos($operativo->operativo_tipo, 'Escuela') - 1); ?>:</label> <?php echo "$operativo->divisiones ($operativo->divisiones_d)"; ?>
													</div>
													<div class="col-sm-12">
														<label>Aplicadores a asignar:</label> <?php echo $operativo->divisiones; ?>
													</div>
												</div>
												<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
													<thead>
														<tr style="background-color: #e4e4e4;">
															<th style="text-align: center; border-color: gray !important;" colspan="9">
																<div style="width:25px; margin: 2px ;" class="pull-left bg-green-active"><?php echo isset($aplicadores[$operativo->operativo_tipo_id])? count($aplicadores[$operativo->operativo_tipo_id]): ''; ?></div>
																<div style="width:80px" class="pull-left">
																	<span>&nbsp;</span>
																</div>
																<div style="width:100%; padding-right:130px;">Cantidad de Aplicadores</div>
															</th>
														</tr>
														<tr>
															<th>Cuil</th>
															<th>Apellido y Nombre</th>
															<th>Teléfono fijo/móvil</th>
															<th>Email</th>
															<th style="width: 80px;"></th>
														</tr>
													</thead>
													<tbody>
														<?php if (!empty($aplicadores[$operativo->operativo_tipo_id])): ?>
															<?php foreach ($aplicadores[$operativo->operativo_tipo_id] as $orden => $aplicador): ?>
																<tr>
																	<td><?php echo "$aplicador->cuil"; ?></td>
																	<td><?php echo "$aplicador->apellido, $aplicador->nombre"; ?></td>
																	<td><?php echo "$aplicador->telefono_fijo/$aplicador->telefono_movil"; ?></td>
																	<td><?php echo "$aplicador->email"; ?></td>
																	<td>
																		<a class="btn btn-xs btn-danger" href="aprender/escritorio/modal_eliminar_aplicador/<?php echo "$operativo->id/$aplicador->operativo_persona_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i> Eliminar aplicador</a>
																	</td>
																</tr>
															<?php endforeach; ?>
														<?php else : ?>
															<tr>
																<td colspan="5" style="text-align: center;">-- No hay aplicadores asignados --</td>
															</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
	</section>
</div>