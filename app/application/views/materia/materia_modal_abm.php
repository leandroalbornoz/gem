<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<?php foreach ($fields as $field): ?>
		<div class="form-group">
			<?php echo $field['label']; ?>
			<?php echo $field['form']; ?>
		</div>
	<?php endforeach; ?>
	<hr>
	<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
		<thead>
			<tr style="background-color: #c4c4c4" >
				<th style="text-align:center;" colspan="11">
					Espacios Curriculares
				</th>
			</tr>
			<tr>
				<th>Nivel</th>
				<th>Carrera</th>
				<th>Curso</th>
				<th>Carga horaria</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($datos_materia)): ?>
				<?php foreach ($datos_materia as $materia): ?>
					<tr>
						<td><?= $materia->nivel; ?></td>
						<td><?= $materia->carrera; ?></td>
						<td><?= $materia->curso; ?></td>
						<td><?= $materia->carga_horaria; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td style="text-align: center;" colspan="11">
						-- No tiene espacios curriculares asociados --
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $materia->id) : ''; ?>
</div>
<?php echo form_close(); ?>