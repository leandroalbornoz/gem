<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Asistencia y Novedades <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="areas/area/ver/<?php echo $area->id; ?>"><?php echo "$area->codigo - $area->descripcion"; ?></a></li>
			<li class="active">Asistencia y novedades</li>
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
						<a class="btn btn-app btn-app-zetta" href="areas/personal/listar/<?php echo "$area->id/$mes_id"; ?>">
							<i class="fa fa-users"></i> Personal
						</a>
						<a class="btn btn-app btn-app-zetta" href="areas/personal_novedad/listar/<?php echo "$area->id/$mes_id"; ?>">
							<i class="fa fa-calendar"></i> Novedades
						</a>
						<a class="btn btn-app btn-app-zetta" href="areas/personal_novedad/listar/<?php echo "$area->id/$mes_id/1"; ?>">
							<i class="fa fa-calendar"></i> Novedades a confirmar
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="areas/asisnov/index/<?php echo "$area->id/$mes_id"; ?>">
							<i class="fa fa-print"></i> Asis. nov
						</a>
						<hr style="margin: 10px 0;">
						<?php if (!empty($planillas)) : ?>
							<div class="row">
								<div class="col-xs-6">
									<?php if (empty($planillas_abiertas)): ?>
										<a class="btn bg-green btn-app btn-app-zetta" href="areas/asisnov/imprimir/<?php echo "$area->id/$mes_id"; ?>" target="_blank">
											<i class="fa fa-print"></i> Presentación
										</a>
										<?php if ($edicion): ?>
											<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/asisnov/modal_rectificativa/<?php echo "$area->id/$mes_id"; ?>">
												<i class="fa fa-plus"></i> Rectificativa
											</a>
										<?php endif; ?>
									<?php else: ?>
										<a class="btn bg-yellow btn-app btn-app-zetta" href="areas/asisnov/imprimir/<?php echo "$area->id/$mes_id"; ?>" target="_blank">
											<i class="fa fa-print"></i> Revisión
										</a>
										<?php if ($edicion): ?>
											<a class="btn bg-red btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/asisnov/modal_cerrar/<?php echo $planillas_abiertas[0]->id; ?>">
												<i class="fa fa-check"></i> Cerrar
											</a>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="well well-sm">
										<table class="table">
											<thead>
												<tr>
													<th>Rectificativa</th>
													<th>Creación</th>
													<th>Cierre</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($planillas)): ?>
													<?php foreach ($planillas as $planilla): ?>
														<tr>
															<td><?php echo $planilla->rectificativa; ?></td>
															<td><?php echo (new DateTime($planilla->fecha_creacion))->format('d/m/Y H:i'); ?></td>
															<td><?php echo empty($planilla->fecha_cierre) ? '' : (new DateTime($planilla->fecha_cierre))->format('d/m/Y H:i'); ?></td>
															<td><a href="areas/asisnov/listar/<?php echo $planilla->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-search"></i> Ver</a></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						<?php elseif ($edicion): ?>
							<a class="btn bg-blue btn-app btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="areas/asisnov/modal_rectificativa/<?php echo "$area->id/$mes_id"; ?>">
								<i class="fa fa-plus"></i> Planilla
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("areas/asisnov/cambiar_mes/$area->id/$mes_id"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo $fecha; ?>"></div>
						<input type="hidden" name="mes" id="mes" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar'), 'Seleccionar'); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>