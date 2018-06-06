<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'formulario_novedad')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['novedad_tipo']['label']; ?>
			<?php echo $fields['novedad_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="form-group col-md-3">
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
		<div class="form-group col-md-6">
			<?php echo $fields['estado']['label']; ?>
			<?php echo $fields['estado']['form']; ?>
		</div>
		<?php if ($servicio->regimen_tipo_id === '2' && !empty($horarios)): ?>
			<div class="col-md-6">
				<label>Horas Cátedra Semanales</label>
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
			</div>
		<?php endif; ?>
		<?php if (!empty($novedades)): ?>
			<div class="col-md-12">
				<label>Otras novedades</label>
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
	<span class="badge bg-red" id="alerta"></span>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn, 'id' => 'btn_submit_novedad'), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $servicio_novedad->id) : ''; ?>
</div>
<?php echo form_close(); ?>