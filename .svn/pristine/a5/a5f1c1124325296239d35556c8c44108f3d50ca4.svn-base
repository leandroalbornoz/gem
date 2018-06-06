<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_cursada')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['division']['label']; ?>
			<?php echo $fields['division']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['espacio_curricular']['label']; ?>
			<?php echo $fields['espacio_curricular']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['carga_horaria']['label']; ?>
			<?php echo $fields['carga_horaria']['form']; ?>
		</div>
		<?php if ($escuela->nivel_id === '7'): ?>
			<div class="form-group col-md-12">
				<?php echo $fields['cuatrimestre']['label']; ?>
				<?php echo $fields['cuatrimestre']['form']; ?>
			</div>
		<?php endif; ?>
		<div class="form-group col-md-6">
			<?php echo $fields['alumnos']['label']; ?>
			<?php echo $fields['alumnos']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['grupo']['label']; ?>
			<?php echo $fields['grupo']['form']; ?>
		</div>
		<?php if (!empty($cursada)): ?>
			<input type="hidden" name="espacio_curricular_id" value="<?php echo $cursada->espacio_curricular_id; ?>">
		<?php endif; ?>
	</div>
	<?php if (empty($txt_btn)): ?>
		<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align:center;" colspan="5">
						Cargos
					</th>
				</tr>
				<tr>
					<th>Condición</th>
					<th>Curso</th>
					<th>División</th>
					<th>Persona</th>
					<th>Hs cubiertas</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($cargos_cursada)): ?>
					<?php foreach ($cargos_cursada as $cargo_cursada): ?>
						<tr>
							<td><?= $cargo_cursada->condicion_cargo; ?></td>
							<td><?= $cargo_cursada->curso; ?></td>
							<td><?= $cargo_cursada->division; ?></td>
							<td><?= $cargo_cursada->persona; ?></td>
							<td><?= $cargo_cursada->carga_horaria; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td style="text-align: center;" colspan="5">
							-- No tiene --
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Editar') {
		echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Editar', 'id' => 'boton_guardar'), 'Guardar');
	} else if ($txt_btn === 'Eliminar') {
		echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Eliminar'), 'Eliminar');
	} else if ($txt_btn === 'Agregar') {
		echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar'), 'Agregar');
	}
	?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $cursada->id) : ''; ?>
	<?php echo form_hidden('division_id', $division->id); ?>
</div>
<?php echo ($txt_btn === 'Editar') ? form_hidden('url_redireccion', $url_redireccion) : ''; ?>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_cursada'));
	});
</script>