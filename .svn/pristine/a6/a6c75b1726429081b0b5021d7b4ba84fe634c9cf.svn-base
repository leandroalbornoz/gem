<script>
	$(document).ready(function() {
		agregar_eventos($('#formulario_asignar_reemplazado'));
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'formulario_asignar_reemplazado')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<?php if (!empty($servicios)): ?>
		<table class="table table-condensed table-striped">
			<tr>
				<th>Sit.Rev.</th>
				<th>Liquidación</th>
				<th>Nombre</th>
				<th>F.Alta</th>
				<th>F.Baja</th>
				<th>Motivo</th>
				<th></th>
			</tr>
			<?php foreach ($servicios as $s): ?>
				<tr>
					<td><?php echo "$s->situacion_revista"; ?></td>
					<td><?php echo "$s->liquidacion"; ?></td>
					<td><?php echo "$s->apellido, $s->nombre"; ?></td>
					<td><?php echo empty($s->fecha_alta) ? '' : (new DateTime($s->fecha_alta))->format('d/m/y'); ?></td>
					<td><?php echo empty($s->fecha_baja) ? '' : (new DateTime($s->fecha_baja))->format('d/m/y'); ?></td>
					<td><?php echo $s->motivo_baja; ?></td>
					<td><input type="radio" value="<?php echo $s->id; ?>" name="servicio_reemplazado"></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else: ?>
		No hay servicios adicionales en el cargo para seleccionar a quién reemplaza.
	<?php endif; ?>
	<div class="row">
		<div class="form-group col-sm-12">
			<?php echo $fields['articulo']['label']; ?>
			<?php echo $fields['articulo']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary">Asignar</button>
	<?php echo form_hidden('id', $servicio->id); ?>
</div>
<?php echo form_close(); ?>