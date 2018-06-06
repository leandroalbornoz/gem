<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" style="padding-right: 15px;">
		Detalle de Incidencia
		<span class="pull-right badge bg-blue" data-original-title=""><?php echo count($incidencia_detalle); ?></span>
	</h4>
</div>
<div class="modal-body">
	<div class="tab-pane active" id="timeline" style="height:400px; overflow-y: scroll;">
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
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>