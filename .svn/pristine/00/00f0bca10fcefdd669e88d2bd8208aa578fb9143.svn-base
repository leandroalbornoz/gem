<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" style="padding-right: 15px;">
		Eliminar la Incidencia
		<span class="pull-right badge bg-blue" data-original-title=""><?php echo!empty($incidencia_detalle) ? count($incidencia_detalle) : '0'; ?></span>
	</h4>
</div>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-body">
	<?php if (!empty($incidencia_detalle_fecha)): ?>
		<div class="tab-pane active" id="timeline" style="height:300px; overflow-y: scroll;">
			<ul class="timeline timeline-inverse">
				<?php foreach ($incidencia_detalle_fecha as $fecha => $detalle_fecha): ?>
					<li class="time-label">
						<span style="background-color:#f4f4f4;border: 1px solid #ddd">
							<b><?php echo date_format(new DateTime($fecha), 'd/m/Y'); ?></b>
						</span>
					</li>
					<?php foreach ($detalle_fecha as $detalle): ?>
						<li>
							<i class="fa fa-envelope bg-blue"></i>
							<div class="timeline-item">
								<span class="time"><i class="fa fa-clock-o"></i> <?= empty($detalle->fecha) ? '' : date_format(new DateTime($detalle->fecha), 'H:i'); ?></span>

								<h3 class="timeline-header"><b style="color: #3c8dbc;"><?php echo $detalle->usuario ?></b></h3>

								<div class="timeline-body">
									<?php echo $detalle->detalle; ?>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				<?php endforeach; ?>
				<li>
					<i class="fa fa-clock-o bg-green"></i>
				</li>
			</ul>
		</div>
	<?php else: ?>
		<strong>Sin detalles cargados.</strong>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => $txt_btn), $txt_btn); ?>
	<?php echo form_hidden('incidencia_id', $incidencia->id); ?>
</div>
<?php echo form_close(); ?>