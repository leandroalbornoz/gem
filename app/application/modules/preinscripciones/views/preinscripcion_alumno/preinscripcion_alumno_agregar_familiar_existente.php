<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_familiar_existente')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['parentesco_tipo']['label']; ?>
			<?php echo $fields['parentesco_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['convive']['label']; ?>
			<?php echo $fields['convive']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['documento_tipo']['label']; ?>
			<?php echo $fields_p['documento_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['documento']['label']; ?>
			<?php echo $fields_p['documento']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['apellido']['label']; ?>
			<?php echo $fields_p['apellido']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['nombre']['label']; ?>
			<?php echo $fields_p['nombre']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['nivel_estudio']['label']; ?>
			<?php echo $fields_p['nivel_estudio']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['ocupacion']['label']; ?>
			<?php echo $fields_p['ocupacion']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['telefono_movil']['label']; ?>
			<?php echo $fields_p['telefono_movil']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields_p['prestadora']['label']; ?>
			<?php echo $fields_p['prestadora']['form']; ?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields_p['email']['label']; ?>
			<?php echo $fields_p['email']['form']; ?>
		</div>
	</div>
		<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align:center;" colspan="11">
						Familiares
					</th>
				</tr>
				<tr>
					<th>Parentesco</th>
					<th>Nombre</th>
					<th>Documento</th>
					<th>Escuela</th>
					<th>Curso/División</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($hijos)): ?>
					<?php foreach ($hijos as $hijo): ?>
						<tr>
							<td><?= $hijo->parentesco.' de:'; ?></td>
							<td><?= $hijo->alumno; ?></td>
							<td><?= $hijo->documento; ?></td>
							<td><?= $hijo->escuela; ?></td>
							<td><?= $hijo->division; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td style="text-align: center;" colspan="11">
							-- No tiene --
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<input type="hidden" name="persona_id" value="<?php echo $persona->id; ?>" id="persona_id"/>
</div>
<?php echo form_close(); ?>