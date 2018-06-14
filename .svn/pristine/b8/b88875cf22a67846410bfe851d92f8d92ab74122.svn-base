<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Preinscripción Ciclo Lectivo <?php echo $ciclo_lectivo; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li>Preinscripción alumnos</li>
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
						<h3 class="box-title">Esc. <?php echo $escuela->nombre_largo; ?> - Preinscripción a 1° grado <?php echo $ciclo_lectivo; ?></h3>
					</div>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="preinscripciones/escuela/instancia_3/<?php echo "$preinscripcion->ciclo_lectivo/$escuela->id/$redireccion"; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class=" btn bg-default btn-app " href="preinscripciones/escuela/anexo1_imprimir_pdf/<?php echo $preinscripcion->id ?>" title="Exportar Pdf Inscriptos" target="_blank"><i class="fa fa-file-pdf-o"></i> Anexo I</a>
						<a class=" btn bg-default btn-app " href="preinscripciones/escuela/anexo3_imprimir_pdf/<?php echo $preinscripcion->id ?>" title="Exportar Pdf Inscriptos" target="_blank"><i class="fa fa-file-pdf-o"></i> Anexo III</a>
						<div class="dropdown pull-right">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-file"></i> Reportes
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu menu-right" aria-labelledby="dropdownMenu1">
								<li class="dropdown-header"><strong>Alumnos</strong></li>
								<li><a class="dropdown-item  bg-default" href="preinscripciones/escuela/anexo1_excel/<?php echo $preinscripcion->id ?>/3/<?php echo "$redireccion"?>" title="Exportar Excel"><i class="fa fa-file-excel-o"></i> Inscriptos</a></li>
								<li><a class="dropdown-item  bg-default" href="preinscripciones/escuela/anexo3_excel/<?php echo $preinscripcion->id ?>/3/<?php echo "$redireccion"?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i> Excedentes</a></li>
							</ul>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<table class="table table-hover table-bordered table-condensed no-footer" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<?php if (empty($alumnos_derivados)): ?>
												<th style="text-align: center;" colspan="9">Alumnos Preinscriptos 3° Instancia</th>
											<?php else : ?>
												<th style="text-align: center;" colspan="9">Alumnos Excedentes derivados y sin derivar</th>
											<?php endif; ?>
										</tr>
										<tr>
											<th>Orden</th>
											<th>Nombre</th>
											<th>Documento</th>
											<th>F.Nac.</th>
											<th>Sexo</th>
											<th>Dirección</th>
											<th>Padre/Madre/Tutor</th>
											<?php if (!empty($alumnos_derivados)): ?>
												<th>Escuela de destino</th>
											<?php else: ?>
												<th>Escuela de origen</th>
											<?php endif; ?>
											<?php if ($fecha <= $fecha_hasta): ?>
												<th></th>
											<?php endif; ?>	
										</tr>
									</thead>
									<tbody>
										<?php if (empty($alumnos_derivados)): ?>
											<?php $i = 1; ?>
											<?php if (!empty($alumnos[5])): ?>
												<?php foreach ($alumnos[5] as $orden => $alumno): ?>
													<?php if ($alumno->estado === 'Inscripto'): ?>
														<tr>
															<td><?= $i++; ?></td>
															<td><?= $alumno->persona; ?></td>
															<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
															<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
															<td><?= substr($alumno->sexo, 0, 1); ?></td>
															<td><?= $alumno->direccion; ?></td>
															<td><?= $alumno->familiares; ?></td>
															<td><?= $alumno->escuela_derivada?></td>
															<?php if ($fecha <= $fecha_hasta): ?>
															<td><a class="btn btn-xs btn-warning" href="preinscripciones/preinscripcion_alumno/editar/<?= $alumno->id; ?>"><i class="fa fa-edit"></i></a></td>
															<?php endif;?>
														</tr>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php else : ?>
												<tr>
													<td colspan="8" style="text-align: center;">-- Ningún alumno recibido de otro colegio --</td>
												</tr>
											<?php endif; ?>
										<?php else : ?>
											<?php $i = 1; ?>
											<?php foreach ($alumnos_derivados as $orden => $alumno): ?>
												<tr>
													<td><?= $i++; ?></td>
													<td><?= $alumno->persona; ?></td>
													<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
													<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
													<td><?= substr($alumno->sexo, 0, 1); ?></td>
													<td><?= $alumno->direccion; ?></td>
													<td><?= $alumno->familiares; ?></td>
													<td><?= $alumno->estado === 'Derivado' ? $alumno->escuela_derivada : 'Alumno aún no derivado'; ?></td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<?php if ($redireccion === 'supervision'): ?>
							<a class="btn btn-default" href="supervision/escritorio/<?php echo $escuela->supervision_id; ?>" title="Volver">Volver</a>
						<?php else: ?>
							<a class="btn btn-default" href="escuela/escritorio/<?php echo $escuela->id; ?>" title="Volver">Volver</a>
						<?php endif; ?>
					</div>		
				</div>
			</div>
		</div>
		
		<?php if ($fecha <= $fecha_hasta): ?>
		<?php if (empty($alumnos_derivados)): ?>
			<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
			<?php if ($vacantes <= 0): ?>
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Alumnos excedentes de otras escuelas</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse">
										<i class="fa fa-minus"></i>
									</button>
								</div>
							</div>
							<div class="box-body">
								<div class="row">
									<div class="text-green" align="center"><h4>El número de vacantes ya fue alcanzado</h4></div>
								</div>
							</div>
							<div class="box-footer">
								<div class="row pull-right">
								</div>
								<div class="row pull-left">
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php else: ?>
				<!--Tabla de alumnos postulantes-->
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Alumnos excedentes de otras escuelas</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse">
										<i class="fa fa-minus"></i>
									</button>
								</div>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-xs-6">
										<?php echo $fields['escuela']['label']; ?>
										<?php echo $fields['escuela']['form']; ?>
									</div>
									<div class="col-xs-6">
										<label>&nbsp;</label><br>
										<button class="btn btn-primary" id="btn-search" type="button">
											<i class="fa fa-search"> Seleccionar</i>
										</button>
										<button class="btn btn-default" id="btn-clear" type="button">
											<i class="fa fa-times"> Cancelar</i>
										</button>
									</div>
								</div><br>
								<div class="row">
									<div class="col-xs-12">
										<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_personas">
											<thead>
												<tr style="background-color: #f4f4f4" >
													<th style="text-align: center;" colspan="8">Alumnos</th>
												</tr>
												<tr>
													<th>Nombre</th>
													<th>Documento</th>
													<th>F.Nac.</th>
													<th>Sexo</th>
													<th>Dirección</th>
													<th>Padre/Madre/Tutor</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<div class="row pull-right">
									<div class="col-xs-12 ">
										<span id="cartel" class="btn hidden text-red"><strong>El límite de vacantes fue excedido</strong></span>
										<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Derivar', 'id' => 'derivar'), 'Derivar Alumnos'); ?>
									</div>
								</div>

								<?php echo ($txt_btn === 'Derivar') ? form_hidden('id', $preinscripcion->id) : ''; ?>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</section>
</div>

<?php if ($fecha <= $fecha_hasta): ?>
<?php if (empty($alumnos_derivados)): ?>
	<?php if ($vacantes > 0): ?>
		<script>
			var table_personas_busqueda;
			var derivar = document.getElementById("derivar");
			var vacantes =<?php echo $vacantes; ?>;
			$(document).ready(function() {
				$('#btn-clear').attr('disabled', true);
				$('#tbl_listar_personas').change('input[type="checkbox"]', function() {
					var a_inscribir = $('#tbl_listar_personas').find('input[type="checkbox"]:checked').size();
					if (a_inscribir > vacantes) {
						$('#derivar').attr('disabled', true);
						$('#cartel').removeClass('hidden');
					} else {
						$('#derivar').attr('disabled', false);
						$('#cartel').addClass('hidden');
					}
				});
				table_personas_busqueda = $('#tbl_listar_personas').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 5, lengthMenu: [5]});
				$('#tbl_listar_personas').css('width', '100%');
				$('#escuela').attr('disabled', false);
				derivar.removeAttribute("href");
				$('#derivar').attr('disabled', true);
				$('#escuela').attr('required', true);
				$('#btn-clear').click(function() {

					$('#btn-clear').attr('disabled', true)
					$('#escuela')[0].selectize.enable();
					$('#btn-search').attr('disabled', false);
					table_personas_busqueda.clear();
					$('#tbl_listar_personas tbody').html('');
				});
				$('#btn-search').click(function() {
					$('#btn-clear').attr('disabled', false)
					$('#escuela')[0].selectize.disable();
					$('#btn-search').attr('disabled', true);
					table_personas_busqueda.clear();
					var escuela_id = $('#escuela').val();
					if (escuela_id !== '') {

						$('#tbl_listar_personas tbody').html('');
						$('#d_persona_id').val(null);
						$.ajax({
							type: 'GET',
							url: 'ajax/preinscripciones/get_listar_postulantes?',
							data: {escuela_id: escuela_id},
							dataType: 'json',
							success: function(result) {
								$('#d_documento').attr('readonly', true);
								if (result.status === 'success') {
									if (result.personas_listar.length > 0) {
										for (var idx in result.personas_listar) {
											var personas_listar = result.personas_listar[idx];
											table_personas_busqueda.row.add([
												personas_listar.persona,
												personas_listar.documento_tipo + ' ' + personas_listar.documento,
												(personas_listar.fecha_nacimiento === null ? '' : moment(personas_listar.fecha_nacimiento).format('DD/MM/YYYY')),
												personas_listar.sexo.substring(0,1),
												personas_listar.direccion,
												personas_listar.familiares,
												'<input type="checkbox" class="single-checkbox" name="derivados[]" value="' + personas_listar.id + '">'
											]);
										}
									}
									table_personas_busqueda.draw();
									$('#btn-submit').attr('disabled', false);
								}
								$('#derivar').attr('disabled', false);
								derivar.setAttribute("href", "");
							}
						});
					}
				});
			});
		</script>
	<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
