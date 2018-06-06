<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<?php if (empty($txt_btn)): ?>
		<div class="box">
			<div class="box-body no-padding">
				<table class="table table-bordered table-condensed">
					<?php if (!empty($horarios_dia)): ?>
						<?php foreach ($horarios_dia as $dia => $horario): ?>		
							<tbody>
								<tr>
									<th class="active text-center" colspan="4">
										<?php echo $dia; ?>
										<div class="box-tools pull-right">
											<span data-toggle="tooltip" title="" class="badge bg-green"><?php echo count($horario); ?></span>
										</div>
									</th>
								</tr>
								<tr>
									<th class="text-center">Desde</th>
									<th class="text-center">Hasta</th>
								</tr>
								<?php foreach ($horario as $hora): ?>
									<tr>
										<td class="text-center"><?php echo substr($hora->hora_desde, 0, -3); ?></td>
										<td class="text-center"><?php echo substr($hora->hora_hasta, 0, -3); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						<?php endforeach; ?>
					<?php else: ?>
						<thead>
							<tr>
								<th class="text-center active" colspan="4">Horarios del cargo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" colspan="4"> -- Sin horarios --</td>
							</tr>
						</tbody>
					<?php endif; ?>
				</table>
			</div>
		</div>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>