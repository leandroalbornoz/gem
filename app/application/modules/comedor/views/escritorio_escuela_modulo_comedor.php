<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Comedor 
				<label class="label label-default"><?php echo $comedor_mes_nombre; ?></label> 
				<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
	
		</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr>
							<th>División</th>
							<th>Turno</th>
							<th style="width: 150px;">Alumnos con ración</th>
							<th style="width: 150px;">Ración Completa</th>
							<th style="width: 150px;">Media Ración</th>
							<th style="width: 34px;"></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($comedor_divisiones)): ?>
							<?php $total_alumnos = 0; ?>
							<?php $total_alumnos_r1 = 0; ?>
							<?php $total_alumnos_r2 = 0; ?>
							<?php foreach ($comedor_divisiones as $division): ?>
								<tr>
									<td><?php echo "$division->curso $division->division"; ?></td>
									<td><?php echo "$division->turno"; ?></td>
									<td class="text-center"><?php echo empty($division->alumnos) ? '' : $division->alumnos; ?></td>
									<td class="text-center"><?php echo empty($division->alumnos_r1) ? '' : $division->alumnos_r1; ?></td>
									<td class="text-center"><?php echo empty($division->alumnos_r2) ? '' : $division->alumnos_r2; ?></td>
									<td>
										<?php if ($administrar): ?>
											<a class="btn btn-xs" href="comedor/division/ver/<?php echo $comedor_presupuesto->id; ?>/<?php echo $division->id; ?>"><i class="fa fa-search"></i></a>
										<?php endif; ?>
									</td>
								</tr>
								<?php $total_alumnos += $division->alumnos; ?>
								<?php $total_alumnos_r1 += $division->alumnos_r1; ?>
								<?php $total_alumnos_r2 += $division->alumnos_r2; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="10" style="text-align: center;">--Sin datos cargados -- </td>
							</tr>
						<?php endif; ?>
					</tbody>
					<?php if (!empty($comedor_divisiones)): ?>
						<tr><th colspan="7"></th></tr>
						<tr>
							<th colspan="2">Totales generales para la escuela</th>
							<th style="text-align: center;"><?php echo $total_alumnos; ?></th>
							<th style="text-align: center;"><?php echo $total_alumnos_r1; ?></th>
							<th style="text-align: center;"><?php echo $total_alumnos_r2; ?></th>
							<th></th>
						</tr>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<?php if (!empty($comedor_mes)): ?>
			<a class="btn btn-primary" href="comedor/escuela/ver/<?php echo (empty($comedor_presupuesto->id)) ? "" : "$comedor_presupuesto->id"; ?>">
				<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
			</a>
		<?php endif; ?>
	</div>
	<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<?php echo form_open("comedor/escuela/cambiar_mes/$escuela->id/$comedor_presupuesto->mes"); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
				</div>
				<div style="display:none;" id="div_servicio_baja"></div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-md-12" style="text-align:center;">
							<div id="datepicker" data-date="<?php echo $mes; ?>"></div>
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
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var fechas = <?= json_encode($meses); ?>;
		var array_fecha = [];
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false,
			beforeShowMonth: function(i) {
				var formattedDate = new Date(i);
				var m = formattedDate.getMonth();
				m += 1;
				var y = formattedDate.getFullYear();
				ames = y + '' + m;
				if (ames.length === 5) {
					array_fecha = y + '' + '0' + m;
				} else {
					array_fecha = y + '' + m;
				}
				console.log();
				if ($.inArray(array_fecha, fechas) > -1) {
					return;
				} else {
					return {
						enabled: false
					};
				}
			}
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});

	});
</script>


