<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Preinscrición de Alumnos - <label class="label label-default"><?php echo $preinscripcion->ciclo_lectivo; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
			<li><a href="preinscripciones/escuela/ver/<?= "$preinscripcion->id/$instancia"; ?>">Preinscripción alumnos</a></li>
			<li><a href="preinscripciones/escuela/instancia_<?php echo "$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/$redireccion";?>"><?php echo "{$instancia}° Instancia"; ?></a></li>
			<li class="active">Agregar alumno</li>
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
						<h3 class="box-title">Esc. <?php echo $escuela->nombre_largo; ?> - Preinscripción a 1° grado <?php echo $preinscripcion->ciclo_lectivo; ?></h3>
					</div>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="preinscripciones/escuela/ver/<?php echo "$preinscripcion->id/$instancia"; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<span class="btn btn-app btn-app-zetta active btn-app-zetta-active">
							<i class="fa fa-plus"></i> Agregar alumno
						</span>
						<h4>Verifique que los datos del alumno a preinscribir sean correctos.</h4>
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
						<hr style="margin: 10px 0;">
						<h4><strong><u>Datos personales del alumno</u></strong></h4>
						<div class="row">
							<?php echo isset($p_persona_id) ? form_hidden('p_persona_id', $p_persona_id) : ''; ?>
							<?php echo isset($alumno) ? form_hidden('alumno_id', $alumno->id) : ''; ?>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['cuil']['label']; ?>
								<?php echo $fields_alumno['cuil']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['documento_tipo']['label']; ?>
								<?php echo $fields_alumno['documento_tipo']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['documento']['label']; ?>
								<span class="label label-danger" id="documento_existente"></span>
								<?php echo $fields_alumno['documento']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['apellido']['label']; ?>
								<?php echo $fields_alumno['apellido']['form']; ?>
							</div>
							<div class="form-group col-sm-4">
								<?php echo $fields_alumno['nombre']['label']; ?>
								<?php echo $fields_alumno['nombre']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields_alumno['nacionalidad']['label']; ?>
								<?php echo $fields_alumno['nacionalidad']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['fecha_nacimiento']['label']; ?>
								<?php echo $fields_alumno['fecha_nacimiento']['form']; ?>
							</div>
							<div class="form-group col-md-7">
								<?php echo $fields_alumno['email_contacto']['label']; ?>
								<?php echo $fields_alumno['email_contacto']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['grupo_sanguineo']['label']; ?>
								<?php echo $fields_alumno['grupo_sanguineo']['form']; ?>
							</div>
							<div class="form-group col-sm-10">
								<?php echo $fields_alumno['obra_social']['label']; ?>
								<?php echo $fields_alumno['obra_social']['form']; ?>
							</div>
							<div class="form-group col-sm-12">
								<?php echo $fields_alumno['observaciones']['label']; ?>
								<?php echo $fields_alumno['observaciones']['form']; ?>
							</div>
						</div>
						<br>
						<h4><strong><u>Domicilio</u></strong></h4>
						<div class="row">
							<div class="form-group col-sm-5">
								<?php echo $fields_alumno['calle']['label']; ?>
								<?php echo $fields_alumno['calle']['form']; ?>
							</div>
							<div class="form-group col-sm-2">
								<?php echo $fields_alumno['calle_numero']['label']; ?>
								<?php echo $fields_alumno['calle_numero']['form']; ?>
							</div>
							<div class="form-group col-sm-5">
								<?php echo $fields_alumno['localidad']['label']; ?>
								<?php echo $fields_alumno['localidad']['form']; ?>
							</div>
							<div class="form-group col-sm-5">
								<?php echo $fields_alumno['barrio']['label']; ?>
								<?php echo $fields_alumno['barrio']['form']; ?>
							</div>
							<div class="form-group col-sm-1">
								<?php echo $fields_alumno['manzana']['label']; ?>
								<?php echo $fields_alumno['manzana']['form']; ?>
							</div>
							<div class="form-group col-sm-1">
								<?php echo $fields_alumno['casa']['label']; ?>
								<?php echo $fields_alumno['casa']['form']; ?>
							</div>
							<div class="form-group col-sm-1">
								<?php echo $fields_alumno['departamento']['label']; ?>
								<?php echo $fields_alumno['departamento']['form']; ?>
							</div>
							<div class="form-group col-sm-1">
								<?php echo $fields_alumno['piso']['label']; ?>
								<?php echo $fields_alumno['piso']['form']; ?>
							</div>
						</div>
						<br>
						<button class="btn btn-primary pull-right" type="submit" value="2" name="editar">Guardar y Agregar familiares</button>
						<?php echo form_close(); ?>
						<br>
						<hr>
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid" id="tbl_trayectoria">
							<thead>
								<tr style="background-color: #f4f4f4" >
									<th style="text-align: center;" colspan="9">Trayectoria alumno</th>
								</tr>
								<tr>
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
									<?php foreach ($trayectoria as $t): ?>
										<tr>
											<td><?php echo $t->ciclo_lectivo; ?></td>
											<td><?php echo "$t->numero_escuela $t->nombre_escuela"; ?></td>
											<td><?php echo "$t->curso $t->division"; ?></td>
											<td><?php echo $t->legajo; ?></td>
											<td><?php echo $t->fecha_desde; ?></td>
											<td><?php echo $t->causa_entrada; ?></td>
											<td><?php echo $t->fecha_hasta; ?></td>
											<td><?php echo $t->causa_salida; ?></td>
											<td><?php echo $t->estado; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr><td colspan="9">-- No tiene --</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
						<div style="display:none;" id="div_persona_buscar_listar"></div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="preinscripciones/escuela/instancia_<?php echo "$instancia/$preinscripcion->ciclo_lectivo/$escuela->id/$redireccion";?>" title="Cancelar">Cancelar</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>