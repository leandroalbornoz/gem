<style>
	.modal-body input{
		height:22px;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Registrar baja de servicio</h4>
</div>
<div class="modal-body">
	<div class="row text-sm">
		<?php if (isset($servicio)): ?>
			<h4 class="col-md-12"><?php echo "$servicio->apellido, $servicio->nombre (CUIL: $servicio->cuil)"; ?></h4>
			<div class="form-group col-md-5">
				<?php echo $fields['escuela']['label']; ?>
				<?php echo $fields['escuela']['form']; ?>
			</div>
			<div class="form-group col-md-5">
				<?php echo $fields['regimen']['label']; ?>
				<?php echo $fields['regimen']['form']; ?>
			</div>
			<div class="form-group col-md-2">
				<?php echo $fields['carga_horaria']['label']; ?>
				<?php echo $fields['carga_horaria']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['division']['label']; ?>
				<?php echo $fields['division']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['espacio_curricular']['label']; ?>
				<?php echo $fields['espacio_curricular']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['situacion_revista']['label']; ?>
				<?php echo $fields['situacion_revista']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['liquidacion']['label']; ?>
				<?php echo $fields['liquidacion']['form']; ?>
			</div>
			<div class="form-group col-md-3">
				<?php echo $fields['fecha_alta']['label']; ?>
				<?php echo $fields['fecha_alta']['form']; ?>
			</div>
			<div class="form-group col-md-3">
				<?php echo $fields['fecha_baja']['label']; ?>
				<?php echo $fields['fecha_baja']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['motivo_baja']['label']; ?>
				<?php echo $fields['motivo_baja']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['articulo_reemplazo']['label']; ?>
				<?php echo $fields['articulo_reemplazo']['form']; ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $fields['reemplazado']['label']; ?>
				<?php echo $fields['reemplazado']['form']; ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $fields['observaciones']['label']; ?>
				<?php echo $fields['observaciones']['form']; ?>
			</div>
			<div class="form-group col-md-3">
				<?php echo $fields_novedad['fecha']['label']; ?>
				<?php echo $fields_novedad['fecha']['form']; ?>
			</div>
			<div class="form-group col-md-9">
				<?php echo $fields_novedad['motivo_baja']['label']; ?>
				<?php echo $fields_novedad['motivo_baja']['form']; ?>
			</div>
		<?php endif; ?>
		<div class="col-md-12">
			<?php if (!empty($liquidaciones)): ?>
				<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="liquidacion_table" style="width:100% !important">
					<thead>
						<tr style="background-color: #f4f4f4" >
							<th style="text-align: center;" colspan="12">Liquidaciones</th>
						</tr>
						<tr>
							<th>Sueldo</th>
							<th>Mes</th>
							<th>N°Liquidación</th>
							<th>S.R.</th>
							<th>Repa<br/>Esc</th>
							<th>Régimen<br/>Clase</th>
							<th>Hs/Días</th>
							<th>Alta</th>
							<th>Baja</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($liquidaciones as $liquidacion): ?>
							<?php if (empty($liquidacion->servicio_id)): ?>
								<tr>
									<td><?= $liquidacion->SINSUELDO === '1' ? '<i class="fa fa-times text-red"></i>' : '<i class="fa fa-check text-green"></i>'; ?></td>
									<td><?= $liquidacion->vigente; ?></td>
									<td><?= $liquidacion->liquidacion_s; ?></td>
									<td><?= $liquidacion->codrevi; ?></td>
									<td><?= "$liquidacion->juri/$liquidacion->repa" . (empty($liquidacion->guiescid) ? '' : "<br/>Esc. $liquidacion->guiescid"); ?></td>
									<td><?= "$liquidacion->regimen<br/>" . str_pad($liquidacion->diasoblig, 4, '0', STR_PAD_LEFT); ?></td>
									<td><?= $liquidacion->diashorapag; ?></td>
									<td><?= substr($liquidacion->fechaini, 0, 2) . '-' . substr($liquidacion->fechaini, 2); ?></td>
									<td><?= substr($liquidacion->fechafin, 0, 2) . '-' . substr($liquidacion->fechafin, 2); ?></td>
									<td><?= '<a class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Asig</a>'; ?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				-- Sin liquidaciones --
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Registrar baja'), 'Registrar baja'); ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		//.datepicker("setDate", new Date())
		$('#fecha').datepicker({
			format: "dd/mm/yyyy",
			startView: "days",
			minViewMode: "days",
			language: 'es',
			autoclose: true,
			orientation: "bottom",
			todayHighlight: true
		});
	});
</script>