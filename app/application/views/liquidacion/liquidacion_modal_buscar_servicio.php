<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<?php if (!empty($liquidaciones)): ?>
		<table class="table table-condensed table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>JU</th>
					<th>REPA</th>
					<th>ESCUELA</th>
					<th>REGIMEN</th>
					<th>CLASE</th>
					<th>LIQUIDACION</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($liquidaciones as $liquidacion): ?>
					<tr>
						<td><?php echo $liquidacion->JU; ?></td>
						<td><?php echo $liquidacion->REPART; ?></td>
						<td>
							<i class="fa <?php echo $liquidacion->escuela_id === $servicio->escuela_id ? 'fa-check text-green' : 'fa-remove text-red'; ?>"></i>
							<?php echo $liquidacion->ESCUELA; ?>
						</td>
						<td>
							<i class="fa <?php echo $liquidacion->regimen_id === $servicio->regimen_id ? 'fa-check text-green' : 'fa-remove text-red'; ?>"></i>
							<?php echo "$liquidacion->REGSAL $liquidacion->REGIMEN"; ?>
						</td>
						<td>
							<i class="fa <?php echo $liquidacion->regimen_id === $servicio->regimen_id && ($servicio->regimen_tipo_id === '1' || str_pad($servicio->carga_horaria, 4, '0', STR_PAD_LEFT) === $liquidacion->CLASE) ? 'fa-check text-green' : 'fa-remove text-red'; ?>"></i>
							<?php echo $liquidacion->CLASE; ?>
						</td>
						<td>
							<?php echo $liquidacion->LIQUIDACION; ?>
						</td>
						<td>
							<?php if ($liquidacion->escuela_id === $servicio->escuela_id && $liquidacion->regimen_id === $servicio->regimen_id && ($servicio->regimen_tipo_id === '1' || str_pad($servicio->carga_horaria, 4, '0', STR_PAD_LEFT) === $liquidacion->CLASE)): ?>
								<button class="btn btn-success btn-xs" type="submit" value="<?php echo $liquidacion->id; ?>"><i class="fa fa-check"></i></button>
							<?php else: ?>
								<button class="btn btn-danger btn-xs" type="button" disabled value="<?php echo $liquidacion->id; ?>"><i class="fa fa-remove"></i></button>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		-- No hay liquidaciones pendientes para <?php echo "$servicio->apellido, $servicio->nombre"; ?> --
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_hidden('id', $servicio->id); ?>
</div>
<?php echo form_close(); ?>