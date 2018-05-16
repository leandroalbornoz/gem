	<div class="box box-danger">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="text-green fa fa-calendar"></i> Auditoría Altas/Bajas <?php echo $ames; ?></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<table class="table table-bordered table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th colspan="6" class="text-center bg-gray">Calendario Auditoría <?php echo (new DateTime($fecha_desde))->format('d/m/y') . ' al ' . (new DateTime($fecha_hasta))->format('d/m/y'); ?></th>
					</tr>
					<tr>
						<th>Planilla</th>
						<th>Movimiento</th>
						<?php foreach ($estados as $estado): ?>
							<th><?php echo $estado; ?></th>
						<?php endforeach; ?>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($planillas as $pl => $planilla): ?>
						<?php foreach ($novedades as $nov => $novedad): ?>
							<tr>
								<td><?php echo $planilla; ?></td>
								<td><?php echo $novedad; ?></td>
								<td class="text-center text-bold"><?php echo isset($liquidaciones[$planilla][$nov]['Cargado']) ? $liquidaciones[$planilla][$nov]['Cargado'] : ''; ?></td>
								<td class="text-center text-bold text-green"><?php echo (isset($liquidaciones[$planilla][$nov]['Auditado']) || isset($liquidaciones[$planilla][$nov]['Procesado'])) ? ((isset($liquidaciones[$planilla][$nov]['Auditado']) ? $liquidaciones[$planilla][$nov]['Auditado'] : 0) + (isset($liquidaciones[$planilla][$nov]['Procesado']) ? $liquidaciones[$planilla][$nov]['Procesado'] : 0) . '<i class="pull-left fa fa-check"></i>') : ''; ?></td>
								<td class="text-center text-bold text-red"><?php echo isset($liquidaciones[$planilla][$nov]['Rechazado']) ? $liquidaciones[$planilla][$nov]['Rechazado'] . '<i class="pull-left fa fa-remove"></i>' : ''; ?></td>
								<td><a class="btn btn-xs btn-primary" href="liquidaciones/<?= strtolower($novedad); ?>/aud_escuela/<?= $escuela->id; ?>/<?= $pl; ?>/<?= $ames; ?>"><i class="fa fa-search"></i>Ver</a></td>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>