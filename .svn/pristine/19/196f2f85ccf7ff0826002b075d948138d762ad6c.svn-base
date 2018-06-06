<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Programa Terminalidad Educativa (TEM)</h3>
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
							<th>Escuela</th>
							<th style="width: 60px;">Horas permitidas</th>
							<th style="width: 60px;">Horas asignadas</th>
							<th style="width: 34px;"></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($escuelas_tem)) : ?>
							<?php foreach ($escuelas_tem as $escuela) : ?>
								<tr>
									<td><?php echo $escuela->numero . ' ' . $escuela->nombre; ?></td>
									<td class="text-center"><?php echo $escuela->horas_permitidas; ?></td>
									<td class="text-center"><?php echo $escuela->horas_asignadas ?></td>
									<td>
										<a class="btn btn-xs" href="escuela/escritorio/<?php echo $escuela->escuela_id ?>"><i class="fa fa-search"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<a class="btn btn-success pull-right" href="#" data-remote="false" data-toggle="modal" data-target="#datepicker_modal">
			<i class="fa fa-file-excel-o" id="btn-planilla-asisnov"></i> Planilla de asistencia y novedades
		</a>
	</div>
</div>

<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("linea/exportar_planilla_general/".$linea->id, array('id' => 'form-export')); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Descargar planilla de asistencia y novedades</h4>
			</div>
			<div style="display:none;" id="div_servicio_baja"></div>

			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker"></div>
						<input type="hidden" name="mes" id="mes" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				<?php echo form_button(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar', 'id' => 'btn-submit', 'target' => '_blank'), 'Seleccionar'); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			startDate: '01/10/2017',
			//endDate: '01/03/2017',
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'));
		});

		$('#btn-submit').click(function(e) {
			e.preventDefault();
			$('#datepicker_modal').modal('toggle');
			$('#form-export').submit();
		});
	});
</script>