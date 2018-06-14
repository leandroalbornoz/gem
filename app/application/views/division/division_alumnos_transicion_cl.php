<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos de <?= "$division->curso $division->division"; ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id; ?>"><?= "Esc. $escuela->nombre_corto"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li><a href="division/alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><?php echo "Alumnos"; ?></a></li>
			<li class="active"><?php echo "Transición C.L."; ?></li>
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
					<form action="<?php echo current_url(); ?>" method="post" name="form_mover_alumnos" id="form_mover_alumnos">
						<div class="box-body">
							<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo $division->id; ?>">
								<i class="fa fa-search" id="btn-ver"></i> Ver división
							</a>
							<a class="btn btn-app btn-app-zetta" href="division/alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>">
								<i class="fa fa-users" id="btn-alumnos"></i> Alumnos
							</a>
							<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
								<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-right">
									<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
										<li><a class="dropdown-item btn-default" href="division/cargos/<?php echo $division->id; ?>"><i class="fa fa-fw fa-users" id="btn-cargos"></i> Cargos</a></li>
									<?php endif; ?>
									<li><a class="dropdown-item btn-default" href="division/ver_horario/<?php echo (!empty($division->id)) ? $division->id : ''; ?>"><i class="fa fa-fw fa-clock-o" id="btn-horarios"></i> Horarios</a></li>
								</ul>
							</div>
							<?php if ($edicion): ?>
								<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> Movimientos alumnos <span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-right">
										<li><a class="dropdown-item btn-default" href="division/mover_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa fa-fw fa-arrows"></i> Mover alumnos entre divisiones</a></li>
										<li><a class="dropdown-item btn-success" href="division/persona_buscar_listar_modal/<?php echo "$division->id/$ciclo_lectivo"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-fw fa-user-plus"></i> Agregar alumno nuevo	al establecimiento</a></li>
										<li><a class="dropdown-item btn-danger" href="division/sacar_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa fa-fw fa-user-times"></i> Retirar alumnos sin pase</a></li>
										<li><a class="dropdown-item btn-danger" href="division/pase_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa fa-fw fa-random"></i> Pase de alumnos a otra escuela</a></li>
										<li><a class="dropdown-item btn-warning" href="division/movimientos_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa  fa-fw fa-history"></i> Revertir movimientos</a></li>
									</ul>
								</div>
							<?php endif; ?>
							<?php if (($escuela->nivel_id == 5 || $escuela->nivel_id == 9)): ?>
								<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
									<a type="button" class="bottom btn btn-primary" href="division/transicion_cl_alumnos/<?php echo "$division->id/2018"; ?>"><i class="fa  fa-address-book"></i> Transición 2018</a>
								</div>
							<?php endif; ?>
							<?php if ($alumnos_transicion > 0): ?>
								<div class="btn-group pull-right" role="group">
									<a type="button" class="bottom btn btn-primary" href="division/transicion_cl_alumnos/<?php echo "$division->id/2017"; ?>"><i class="fa  fa-address-book"></i> Transición Ciclo lectivo</a>
								</div>
							<?php endif; ?>
							<hr style="margin: 10px 0;">
							<h4>Mover alumnos seleccionados al siguiente curso, división y ciclo lectivo:</h4>
							<h5><b><u>Tipos de movimientos</u>:</b></h5> 
							<ul>
								<li><h5><b><u>Egreso</u>:</b> Egreso del alumno por finalizacion de estudios dentro del establecimiento.</h5></li>
								<li><h5><b><u>Promoción</u>:</b> Promoción del alumno al siguiente curso dentro del establecimiento.</h5></li>
								<?php if ($escuela->nivel_id != '8' && ($ciclo_lectivo !== '2018' || !in_array($escuela->nivel_id, array('5', '9')))): ?>
									<li><h5><b><u>Repitencia</u>:</b> Repetición del alumno en el curso dentro del establecimiento.</h5></li>
									<?php if (in_array($escuela->nivel_id, array('3', '4', '8'))): ?>
										<li><h5><b><u>Egreso no efectivo</u>:</b> Egreso del alumno por finalización de estudios dentro del establecimiento, con materias adeudadas.</h5></li>
										<?php if (!in_array($escuela->nivel_id, array('5', '8', '9'))): ?>
											<li><h5><b><u>Promoción condicional</u>:</b> Promoción del alumno al siguiente curso dentro del establecimiento, con materias adeudadas.</h5></li>
										<?php endif; ?>
									<?php endif; ?>
									<?php if (!in_array($escuela->nivel_id, array('3', '4', '5', '8'))): ?>
										<li><h5><b><u>Compensación primaria</u>:</b> Alumno de nivel primario con periodo compensatorio.</h5></li>
									<?php endif; ?>
								<?php endif; ?>
								<?php if (!in_array($escuela->nivel_id, array('2', '3', '4', '8')) && $ciclo_lectivo !== '2018' ): ?>
									<li><h5><b><u>Continua</u>:</b> Alumno sigue en el curso hasta completar los trayectos correspondientes.</h5></li>
								<?php endif; ?>
							</ul>
							<hr style="margin: 10px 0;">
							<div class="row">
								<div class="form-group col-md-2">
									<?php echo $fields['causa_salida']['label']; ?>
									<?php echo $fields['causa_salida']['form']; ?>
								</div>
								<div class="form-group col-md-2">
									<?php echo $fields['fecha_hasta']['label']; ?>
									<div class="input-group date" id="datepicker">
										<?php echo $fields['fecha_hasta']['form']; ?>
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>	
								</div>
								<?php echo form_hidden('division', $division->id); ?>
								<div class="form-group col-md-3">
									<?php echo $fields['division']['label']; ?>
									<?php echo $fields['division']['form']; ?>
								</div>
								<div class="form-group col-md-2">
									<?php echo $fields['fecha_desde']['label']; ?>
									<div class="input-group date" id="datepicker2">
										<?php echo $fields['fecha_desde']['form']; ?>
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>
								<div class="form-group col-md-3">
									<label style="width:100%">Ciclo lectivo</label>
									<div class="input-group date">
										<input type="text" class="form-control" name="ciclo_lectivo_nuevo" required id="ciclo_lectivo_nuevo" value="<?php echo "2018"; ?>" readonly=""/>
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>
							</div>
							<?php echo $js_table; ?>
							<?php echo $html_table; ?>
						</div>
						<div class="box-footer">
							<a class="btn btn-default" href="division/<?php echo empty($txt_btn) ? "alumnos/$division->id" : ""; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
							<button class="btn btn-primary pull-right" type="submit" id="retirar" title="Mover alumnos">Mover alumnos</button>
							<div class="row pull-right hidden" style="padding-right: 5%;"  id="cartel">
								<div class="bg-red text-bold" style="border-radius: 2px;"><h4>La fecha de Salida no puede ser Menor que la fecha de Ingreso</h4></div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var alumno_table;
	function complete_alumno_table() {
		agregar_filtros('alumno_table', alumno_table, 7);
		$('#alumno_table thead tr:first-child th:last-child').append('<a class="btn btn-xs btn-primary" href="javascript:cambiar_checkboxs(true)" title="Marcar todos"><i class="fa fa-fw fa-check-square-o"></i></a> <a class="btn btn-xs btn-danger"  href="javascript:cambiar_checkboxs(false)" title="Desmarcar todos"><i class="fa fa-fw fa-square-o"></i></a>');
	}
</script>
<script>
function cambiar_checkboxs(checked) {
		$('#form_mover_alumnos input[type="checkbox"]').prop('checked', checked);
	}
	
	$('#causa_salida').change(verificar);
	function verificar(e) {
		var causa_salida = $('#causa_salida')[0].selectize.getValue();
		if (causa_salida === '31') {
			$('#fecha_desde').attr('readonly', true);
			$('#fecha_desde').val(null);
			$('#division')[0].selectize.setValue('');
			$('#division')[0].selectize.disable();
		} else {
			$('#fecha_desde').attr('readonly', false);
			$('#fecha_hasta').attr('readonly', false);
<?php if (($escuela->nivel_id == 5 || $escuela->nivel_id == 9) && $ciclo_lectivo === '2018'): ?>
				$('#fecha_desde').val($('#fecha_hasta').val());
<?php else: ?>
				$('#fecha_desde').val('05/03/2018');
<?php endif; ?>
			$('#division')[0].selectize.enable();
		}
		if (causa_salida === '34') {
			$('#fecha_hasta').attr('readonly', true);
			$('#fecha_desde').attr('readonly', true);
			$('#ciclo_lectivo_nuevo').val('2017');
			$('#division')[0].selectize.setValue(<?php echo $division->id; ?>);
			$('#division')[0].selectize.disable();

		} else {
			$('#fecha_hasta').attr('readonly', false);
			$('#ciclo_lectivo_nuevo').val('2018');
			$('#division')[0].selectize.setValue('');
		}
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#datepicker,#datepicker2').datepicker({
			format: 'dd/mm/yyyy',
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>
