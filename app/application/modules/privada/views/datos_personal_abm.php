<style>
	td.child>ul{
		width:100%;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Datos de personal
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="datos_personal/listar/<?php echo $escuela->id; ?>">Datos personal</a></li>
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
						<?php if (!isset($es_funcion) || $es_funcion === '0'): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="datos_personal/ver/<?php echo $servicio->id; ?>">
								<i class="fa fa-search"></i> Ver
							</a>
							<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="datos_personal/editar/<?php echo $servicio->id; ?>">
								<i class="fa fa-edit"></i> Editar
							</a>

							<a class="btn btn-app btn-app-zetta" href="datos_personal/antiguedad/<?php echo $servicio->id; ?>">
								<i class="fa fa-archive"></i> Antigüedad
							</a>
						<?php endif; ?>
						<div class="row">
							<div class="form-group col-md-4 col-sm-4">
								<?php echo $fields['cuil']['label']; ?>
								<span class="label label-danger" id="cuil_existente"></span>
								<?php echo $fields['cuil']['form']; ?>
							</div>
							<div class="form-group col-md-4 col-sm-4">
								<?php echo $fields['documento_tipo']['label']; ?>
								<?php echo $fields['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-md-4 col-sm-4">
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
							<div class="form-group col-md-3 col-sm-4">
								<?php echo $fields['sexo']['label']; ?>
								<?php echo $fields['sexo']['form']; ?>
							</div>
							<div class="form-group col-md-3 col-sm-4">
								<?php echo $fields['fecha_nacimiento']['label']; ?>
								<?php echo $fields['fecha_nacimiento']['form']; ?>
							</div>
						</div>
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#tab_domicilio" aria-controls="tab_persona" role="tab" data-toggle="tab">Datos Domicilio</a></li>
							<li role="presentation"><a id="a_tab_personales" href="#tab_personales" aria-controls="tab_personales" role="tab" data-toggle="tab">Datos Personales</a></li>
							<li role="presentation"><a id="a_tab_antiguedad" href="#tab_antiguedad" aria-controls="tab_antiguedad" role="tab" data-toggle="tab">Antigüedad</a></li>
							<li role="presentation"><a id="a_tab_bonificaciones" href="#tab_bonificaciones" aria-controls="tab_bonificaciones" role="tab" data-toggle="tab">Bonificaciones</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="tab_domicilio">
								<div class="row">
									<div class="form-group col-md-4 col-sm-4">
										<?php echo $fields['calle']['label']; ?>
										<?php echo $fields['calle']['form']; ?>
									</div>
									<div class="form-group col-md-2 col-sm-4">
										<?php echo $fields['calle_numero']['label']; ?>
										<?php echo $fields['calle_numero']['form']; ?>
									</div>
									<div class="form-group col-md-4 col-sm-4">
										<?php echo $fields['localidad']['label']; ?>
										<?php echo $fields['localidad']['form']; ?>
									</div>
									<div class="form-group col-md-2 col-sm-4">
										<?php echo $fields['codigo_postal']['label']; ?>
										<?php echo $fields['codigo_postal']['form']; ?>
									</div>
									<div class="form-group col-md-5 col-sm-4">
										<?php echo $fields['barrio']['label']; ?>
										<?php echo $fields['barrio']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['manzana']['label']; ?>
										<?php echo $fields['manzana']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['casa']['label']; ?>
										<?php echo $fields['casa']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['departamento']['label']; ?>
										<?php echo $fields['departamento']['form']; ?>
									</div>
									<div class="form-group col-md-1 col-sm-4">
										<?php echo $fields['piso']['label']; ?>
										<?php echo $fields['piso']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['nacionalidad']['label']; ?>
										<?php echo $fields['nacionalidad']['form']; ?>
									</div>
									<div class="form-group col-md-2 col-sm-4">
										<?php echo $fields['telefono_fijo']['label']; ?>
										<?php echo $fields['telefono_fijo']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['telefono_movil']['label']; ?>
										<?php echo $fields['telefono_movil']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['prestadora']['label']; ?>
										<?php echo $fields['prestadora']['form']; ?>
									</div>
									<div class="form-group col-md-4 col-sm-4">
										<?php echo $fields['email']['label']; ?>
										<?php echo $fields['email']['form']; ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_personales">
								<div class="row">
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['estado_civil']['label']; ?>
										<?php echo $fields['estado_civil']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['nivel_estudio']['label']; ?>
										<?php echo $fields['nivel_estudio']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['ocupacion']['label']; ?>
										<?php echo $fields['ocupacion']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['grupo_sanguineo']['label']; ?>
										<?php echo $fields['grupo_sanguineo']['form']; ?>
									</div>
									<div class="form-group col-md-12 col-sm-12">
										<?php echo $fields['obra_social']['label']; ?>
										<?php echo $fields['obra_social']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['depto_nacimiento']['label']; ?>
										<?php echo $fields['depto_nacimiento']['form']; ?>
									</div>
									<div class="form-group col-md-3 col-sm-4">
										<?php echo $fields['fecha_defuncion']['label']; ?>
										<?php echo $fields['fecha_defuncion']['form']; ?>
									</div>
									<div class="form-group col-md-6 col-sm-4">
										<?php echo $fields['lugar_traslado_emergencia']['label']; ?>
										<?php echo $fields['lugar_traslado_emergencia']['form']; ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_antiguedad">
								<table class="table table-hover table-bordered table-condensed table-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="antiguedad_table">
									<thead>
										<tr>
											<th>Escuela</th>
											<th style="width:80px;">Desde</th>
											<th style="width:80px;">Hasta</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($antiguedades)): ?>
											<tr>
												<td class="text-center">-- Sin antiguedad informada --</td>
												<td class="text-center"></td>
												<td class="text-center"></td>
											</tr>
										<?php else: ?>
											<?php foreach ($antiguedades as $antiguedad): ?>
												<tr>
													<td><?php echo "$antiguedad->numero $antiguedad->nombre"; ?></td>
													<td><?php echo (new DateTime($antiguedad->fecha_desde))->format('d/m/Y'); ?></td>
													<td><?php echo (new DateTime($antiguedad->fecha_hasta))->format('d/m/Y'); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_bonificaciones">
								
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" id="alumno_table" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="5">
												<a class="pull-left btn btn-xs btn-success" href ="privada/datos_personal/modal_agregar_bonificaciones/<?php echo $persona->id; ?>/<?php echo $servicio->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-plus"></i></a>


												</a>
												Bonificaciones
											</th>
										</tr>
										<tr>
											<th>Escolaridad</th>
											<th>Matrimonio</th>
											<th>Persona a Cargo</th>
											<th>Discapacidad</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($bonificaciones as $bonificacion): ?>
											<tr>
												<td><?= $bonificacion->bonificacion_escolaridad; ?></td>
												<td><?= $bonificacion->bonificacion_matrimonio; ?></td>
												<td><?= $bonificacion->bonificacion_cargo; ?></td>
												<td><?= $bonificacion->bonificacion_discapacidad; ?></td>
												<td>	<a class="pull-left btn btn-xs btn-danger" href ="privada/datos_personal/modal_eliminar_bonificaciones/<?php echo $bonificacion->id; ?> " data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
														<a class="pull-left btn btn-xs btn-warning" href ="privada/datos_personal/modal_editar_bonificaciones/<?php echo $bonificacion->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-edit"></i></a>
													
													</td>
													
											</tr>
										<?php endforeach; ?>
									</tbody>
									
								</table>
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" id="alumno_table" role="grid">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="5">
												<a class="pull-left btn btn-xs btn-success" href ="privada/datos_personal/modal_agregar_prenatal/<?php echo $persona->id; ?>/<?php echo $servicio->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-plus"></i></a>


												</a>
												Prenatal
											</th>
										</tr>
										<tr>
											<th>Fecha Certificada</th>
											<th>Fecha Posible Nacimiento</th>
											<th>Fecha Defunción</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($bonificaciones as $bonificacion): ?>
											<tr>
												<td><?= $bonificacion->fecha_certificada; ?></td>
												<td><?= $bonificacion->fecha_posiblenac; ?></td>
												<td><?= $bonificacion->fecha_defuncion; ?></td>
												
												<td>	<a class="pull-left btn btn-xs btn-danger" href ="privada/datos_personal/modal_eliminar_bonificaciones/<?php echo $bonificacion->id; ?> " data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-remove"></i></a>
														<a class="pull-left btn btn-xs btn-warning" href ="privada/datos_personal/modal_editar_bonificaciones/<?php echo $bonificacion->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-edit"></i></a>
													
													</td>
													
											</tr>
										<?php endforeach; ?>
									</tbody>
									
								</table>
							</div>
						</div>

						<div style="margin-top: 3%;">
							<?php if ($txt_btn === 'Ver' && !empty($servicios)): ?>
								<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="servicio_table" style="width:100% !important">
									<thead>
										<tr style="background-color: #f4f4f4" >
											<th style="text-align: center;" colspan="10">Servicios</th>
										</tr>
										<tr>
											<th></th>
											<th>Liquidación</th>
											<th>S.R.</th>
											<th>Escuela/Area</th>
											<th>División</th>
											<th>Régimen/Materia</th>
											<th>Hs. Cát.</th>
											<th>Fecha alta</th>
											<th>Fecha baja</th>
											<th></th>
											<th class="none">Observaciones</th>
											<th class="none">F.Detalle</th>
											<th class="none">F.Destino</th>
											<th class="none">F.Norma</th>
											<th class="none">F.Tarea</th>
											<th class="none">F.Hs.</th>
											<th class="none">F.Desde</th>
											<th class="none">Motivo Baja</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($servicios as $servicio): ?>
											<tr>
												<td style="vertical-align: baseline;">
													<a class="boton_azul" id="<?= $servicio->id; ?>" onClick="cambiarDisplay('<?= $servicio->liquidacion; ?>', '<?= $servicio->id; ?>');"></a>
												</td>
												<td><?= $servicio->liquidacion; ?></td>
												<td><?= $servicio->situacion_revista; ?></td>
												<td><?php echo empty($servicio->area) ? $servicio->escuela : $servicio->area; ?></td>
												<td class="dt-body-center"><?= $servicio->division; ?></td>
												<td><?= "$servicio->regimen_codigo-$servicio->regimen<br/>$servicio->materia"; ?></td>
												<td class="dt-body-center"><?= $servicio->carga_horaria; ?></td>
												<td><?= empty($servicio->fecha_alta) ? '' : (new DateTime($servicio->fecha_alta))->format('d/m/Y'); ?></td>
												<td><?= empty($servicio->fecha_baja) ? '' : (new DateTime($servicio->fecha_baja))->format('d/m/Y'); ?></td>
												<td>
													<a class="pull-right btn btn-xs btn-default" href="servicio/ver/<?= $servicio->id; ?>"><i class="fa fa-search"></i></a>
												</td>
												<td><?= $servicio->observaciones; ?></td>
												<td><?= $servicio->f_detalle; ?></td>
												<td><?= $servicio->f_destino; ?></td>
												<td><?= $servicio->f_norma; ?></td>
												<td><?= $servicio->f_tarea; ?></td>
												<td><?= $servicio->f_carga_horaria; ?></td>
												<td><?= empty($servicio->f_fecha_desde) ? '' : (new DateTime($servicio->f_fecha_desde))->format('d/m/Y'); ?></td>
												<td><?= $servicio->motivo_baja; ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php endif; ?>
						</div>
						<div style="margin-top: 3%;">
							<?php if ($txt_btn === 'Ver'): ?>
								<?php if (!empty($alumno[0]->alumno_id)): ?>
									<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
										<thead>
											<tr style="background-color: #f4f4f4" >
												<th style="text-align: center;" colspan="5">Trayectoria alumno</th>
											</tr>
											<tr>
												<th>Ciclo lectivo</th>
												<th>Escuela</th>
												<th>Division</th>
												<th>Fecha ingreso</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($alumno as $campo): ?>
												<tr>
													<td><?= $campo->ciclo_lectivo ?></td>
													<td><?= $campo->escuela ?></td>
													<td><?= $campo->division2 ?></td>
													<td><?= empty($campo->fecha_desde) ? '' : (new DateTime($campo->fecha_desde))->format('d/m/Y'); ?></td>
													<td>
														<a class="pull-right btn btn-xs btn-default" href="alumno/ver/<?= $campo->division_id ?>"><i class="fa fa-search"></i></a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="datos_personal/listar/<?php echo $escuela->id; ?>" title="<?php echo ($txt_btn === 'Ver') ? 'Volver' : 'Cancelar'; ?>"><?php echo ($txt_btn === 'Ver') ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo ($txt_btn === 'Ver') ? '' : zetta_form_submit($txt_btn); ?>
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
		$('#servicio_table,#antiguedad_table').DataTable({
			dom: 't',
			autoWidth: false,
			paging: false,
			ordering: false
		});
		$('#cuil').inputmask("99-99999999-9");
		$('#cuil').change(verificar_cuil_repetido);
	});

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