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
			<li class="active"><?php echo "Retirar"; ?></li>
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
					<form action="<?php echo current_url(); ?>" method="post" name="form_pase_alumnos" id="form_pase_alumnos">
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
									<li><a class="dropdown-item btn-default" href="division/ver_horario/<?php echo (!empty($division->id)) ? $division->id : ''; ?>"><i class="fa  fa-fw fa-clock-o" id="btn-horarios"></i> Horarios</a></li>
								</ul>
							</div>
							<?php if ($edicion): ?>
								<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> Movimientos alumnos <span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-right">
										<li><a class="dropdown-item btn-default" href="division/mover_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa  fa-fw fa-arrows"></i> Mover alumnos entre divisiones</a></li>
										<li><a class="dropdown-item btn-success" href="division/persona_buscar_listar_modal/<?php echo "$division->id/$ciclo_lectivo"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa  fa-fw fa-user-plus"></i> Agregar alumno nuevo	al establecimiento</a></li>
										<li><a class="dropdown-item btn-danger" href="division/sacar_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa  fa-fw fa-user-times"></i> Retirar alumnos sin pase</a></li>
										<li><a class="dropdown-item btn-danger" href="division/pase_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa  fa-fw fa-random"></i> Pase de alumnos a otra escuela</a></li>
										<li><a class="dropdown-item btn-warning" href="division/movimientos_alumnos/<?php echo "$division->id/$ciclo_lectivo"; ?>"><i class="fa  fa-fw fa-history"></i> Revertir movimientos</a></li>
									</ul>
								</div>
							<?php endif; ?>
							<?php if($alumnos_transicion): ?>
							<div class="btn-group pull-right" role="group">
								<a type="button" class="bottom btn btn-primary" href="division/transicion_cl_alumnos/<?php echo "$division->id/2017"; ?>"><i class="fa  fa-fw fa-address-book"></i> Transición Ciclo lectivo</a>
							</div>
							<?php endif; ?>
							<hr style="margin: 10px 0;">
							<h4>Derivar a los alumnos seleccionados a la escuela de destino: </h4>
							<div class="row">
								<div class="form-group col-md-2">
									<?php echo $fields['fecha_pase']['label']; ?>
									<?php echo $fields['fecha_pase']['form']; ?>
								</div>
								<div class="form-group col-md-4">
									<?php echo $fields['escuela_destino']['label']; ?>
									<?php echo $fields['escuela_destino']['form']; ?>
								</div>
								<?php echo form_hidden('ciclo_lectivo', $ciclo_lectivo); ?>
								<div class="col-md-5 col-md-offset-1">
									<label style="width:100%">&nbsp;</label>
									<a class="btn btn-sm btn-primary" href="javascript:seleccionar_todo()" title="Marcar todos">Marcar todos</a>
									<a class="btn btn-sm btn-danger"  href="javascript:deseleccionar_todo()" title="Desmarcar todos">Desmarcar todos</a>
								</div>
							</div>
							<?php echo $js_table; ?>
							<?php echo $html_table; ?>
						</div>
						<div class="box-footer">
							<a class="btn btn-default" href="division/<?php echo empty($txt_btn) ? "alumnos/$division->id" : ""; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
							<button class="btn btn-primary pull-right" type="submit" id="retirar" title="Derivar alumnos">Derivar alumnos</button>
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
		agregar_filtros('alumno_table', alumno_table, 8);
	}
</script>
<script>
	function seleccionar_todo() {
		for (i = 0; i < document.form_pase_alumnos.elements.length; i++)
			if (document.form_pase_alumnos.elements[i].type == "checkbox")
				document.form_pase_alumnos.elements[i].checked = 1
	}
	function deseleccionar_todo() {
		for (i = 0; i < document.form_pase_alumnos.elements.length; i++)
			if (document.form_pase_alumnos.elements[i].type == "checkbox")
				document.form_pase_alumnos.elements[i].checked = 0
	}
	function verificar_fechas(event) {
		$('[name="alumno_division[]"]').closest('tr').find('td:nth-child(7)').each(function() {
			$(this).closest('tr').css('color', '');
		});
		$('[name="alumno_division[]"]:checked').closest('tr').find('td:nth-child(7)').each(function() {
			var fecha_hasta = $('#fecha_pase').val();
			var date_hasta = fecha_hasta.split('/');
			var fecha_hasta = date_hasta[2] + '/' + date_hasta[1] + '/' + date_hasta[0];
			var fecha_desde = $(this).html();
			var date_desde = fecha_desde.split('/');
			var fecha_desde = date_desde[2] + '/' + date_desde[1] + '/' + date_desde[0];
			if (fecha_hasta < fecha_desde) {
				$('#cartel').removeClass('hidden');
				event.preventDefault();
				return false;
			}
		});
	}
	$(document).ready(function() {
		$('#form_pase_alumnos').submit(verificar_fechas);
	});
</script>
