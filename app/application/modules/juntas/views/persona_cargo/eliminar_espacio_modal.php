<link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'selectizeForm')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['documento_bono']['label']; ?>
			<?php echo $fields['documento_bono']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['persona']['label']; ?>
			<?php echo $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['cargo']['label']; ?>
			<?php echo $fields['cargo']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['espacio']['label']; ?>
			<?php echo $fields['espacio']['form']; ?>
		</div>
		<?php if (!empty($fields_pmatricula)): ?>
			<div class="form-group col-md-12">
				<u><h4>Datos de Matrícula:</h4></u>
			</div>
			<div class="form-group col-md-12">
				<?php echo $fields_pmatricula['matricula_tipo']['label']; ?>
				<?php echo $fields_pmatricula['matricula_tipo']['form']; ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $fields_pmatricula['matricula_numero']['label']; ?>
				<?php echo $fields_pmatricula['matricula_numero']['form']; ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $fields_pmatricula['matricula_vence']['label']; ?>
				<?php echo $fields_pmatricula['matricula_vence']['form']; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar' || $txt_btn === 'Activar') ? form_hidden('id', $persona_idoneidad->id) : ''; ?>
</div>
<?php echo form_close(); ?>
