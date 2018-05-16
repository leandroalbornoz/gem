<style>
	.table-horarios .selectize-control.single .selectize-input:after {
    right: 5px;
	}
	.table-horarios .selectize{
		font-size: 10px;
		line-height: 1.4;
		padding: 2px;
	}
	.table-horarios .selectize-dropdown, .table-horarios .selectize-input, .table-horarios .selectize-input input {
		font-size: 10px;
		line-height: 1.4;
		padding: 2px;
	}
	.table-horarios .selectize-input>input {
		display: inline-block;
		/*position:relative;*/
		/*display: none !;*/
	}
	.table-horarios .selectize-dropdown-content .option{
		border: 1px #888 solid;
		border-radius: 5%;
	}
	.table-horarios .selectize-input.disabled{
		display:none;
	}
	.par1 .selectize-input.full > input {
		position:absolute;
	}
	.table-horarios>tbody>tr>td>select.selectized:disabled+div{
		display: none;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Ver horario de <?php echo "$division->curso $division->division"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<li class="active">Ver horario</li>
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
						<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo $division->id; ?>">
							<i class="fa fa-search"></i> Ver división
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="division/ver_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Ver horario
						</a>
						<?php if ($edicion && empty($division->fecha_baja)): ?>
						<a class="btn btn-app btn-app-zetta" href="division/editar_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Editar horas
						</a>
						<a class="btn btn-app btn-app-zetta" href="division/asignar_horario/<?php echo $division->id; ?>">
							<i class="fa fa-clock-o"></i> Asignar materias
						</a>
						<?php endif; ?>
<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
									<li><a class="dropdown-item btn-default" href="division/cargos/<?php echo $division->id; ?>"><i class="fa fa-fw fa-users" id="btn-cargos"></i> Cargos</a></li>
								<?php endif; ?>
								<li><a class="dropdown-item btn-default" href="division/alumnos/<?php echo (!empty($division->id)) ? $division->id : ''; ?>"><i class="fa  fa-fw fa-users" id="btn-alumnos"></i> Alumnos</a></li>
							</ul>
						</div>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['curso']['label']; ?>
								<?php echo $fields['curso']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['division']['label']; ?>
								<?php echo $fields['division']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['turno']['label']; ?>
								<?php echo $fields['turno']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['carrera']['label']; ?>
								<?php echo $fields['carrera']['form']; ?>
							</div>
						</div>
						<input type="hidden" name="escuela_id" value="<?= $escuela->id ?>"/>
						<div class="row">
							<div class="col-sm-12">
								<table class="table-horarios table table-condensed" style="text-align: center; table-layout: fixed;">
									<tr>
										<th style="text-align: center; width: 40px;">Hora</th>
										<?php foreach ($dias as $dia): ?>
										<th style="text-align: center;"><?php echo mb_substr($dia->nombre, 0, 2); ?></th>
										<?php endforeach; ?>
									</tr>
									<?php for ($hora_catedra = 1;
									$hora_catedra <= $turno->max_hora_catedra;
									$hora_catedra++): ?>
									<tr>
										<td style="vertical-align: middle;"><?php echo $hora_catedra; ?></td>
											<?php foreach ($dias as $dia): ?>
										<td style="vertical-align: top;">
											<?php
											if (isset($turno->horarios[$hora_catedra][$dia->id])) {
											echo substr($turno->horarios[$hora_catedra][$dia->id]->hora_desde, 0, 5) . ' - ' . substr($turno->horarios[$hora_catedra][$dia->id]->hora_hasta, 0, 5) . ($turno->horarios[$hora_catedra][$dia->id]->obligaciones === '1.0' ? '' : '<br/>(' . $turno->horarios[$hora_catedra][$dia->id]->obligaciones . ' oblig.)') . '<br/>';
											echo '<span class="text-sm">';
											echo $turno->horarios[$hora_catedra][$dia->id]->cargos[0];
											echo isset($turno->horarios[$hora_catedra][$dia->id]->cargos[1]) ? '<br> ' . $turno->horarios[$hora_catedra][$dia->id]->cargos[1] : '';
											echo '</span>';
											}
											?>
										</td>
									<?php endforeach; ?>
									</tr>
<?php endfor; ?>
								</table>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="division/listar/<?= $escuela->id ?>" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>