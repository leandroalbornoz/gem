<style>
	#formulario_novedad .table{
		margin-bottom: 0;
	}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_hasta']['label']; ?>
			<?php echo $fields['fecha_hasta']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['dias']['label']; ?>
			<?php echo $fields['dias']['form']; ?>
		</div>
		<?php if ($servicio->regimen_tipo_id === '2'): ?>
			<div class="form-group col-md-3">
				<?php echo $fields['obligaciones']['label']; ?>
				<?php echo $fields['obligaciones']['form']; ?>
			</div>
		<?php endif; ?>
		<?php if ($servicio->regimen_tipo_id === '2'): ?>
			<div class="col-sm-6">
				<label>Horas Cátedra Semanales <span style="font-size: 1.1em;">(<?php echo $servicio->carga_horaria; ?>)</span></label>
				<?php if (!empty($horarios)): ?>
					<table class="table table-condensed text-center text-sm">
						<thead>
							<tr>
								<?php foreach ($horarios as $horario): ?>
									<th><?php echo mb_substr($horario->dia, 0, 2); ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($horarios as $horario): ?>
									<td class="text-center" id="dia_<?php echo $horario->dia_id; ?>"><?php echo $horario->cantidad; ?></td>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($novedades)): ?>
			<div class="col-md-12">
				<label>Novedades Servicio</label>
				<table class="table table-condensed text-center text-sm">
					<thead>
						<tr>
							<th>Artículo</th>
							<th>Desde</th>
							<th>Hasta</th>
							<th>Días</th>
							<?php if ($servicio->regimen_tipo_id === '2'): ?>
								<th>Obligaciones</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($novedades as $novedad): ?>
							<tr>
								<td><?php echo "$novedad->articulo-$novedad->inciso"; ?></td>
								<td><?php echo (new DateTime($novedad->fecha_desde))->format('d/m/Y'); ?></td>
								<td><?php echo (new DateTime($novedad->fecha_hasta))->format('d/m/Y'); ?></td>
								<td><?php echo $novedad->dias; ?></td>
								<?php if ($servicio->regimen_tipo_id === '2'): ?>
									<td><?php echo $novedad->obligaciones; ?></td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>