<script type="text/javascript">
	$(document).ready(function() {
		$('#titulo_establecimiento').on('change', function(e) {
			var value = $('#titulo_establecimiento').inputmask('unmaskedvalue');
			if (value === '1') {
				$('#establecimiento_val').removeClass('hidden');
			} else {
				$('#establecimiento_val').addClass('hidden');
			}
		});
		$('#titulo_tipo').on('change', function(e) {
			var value = $('#titulo_tipo').inputmask('unmaskedvalue');
			if (value === '1') {
				$('#tipo_val').removeClass('hidden');
			} else {
				$('#tipo_val').addClass('hidden');
			}
		});
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_titulo_persona_modal')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12">
			<?php echo $fields['nombre']['label']; ?>
			<?php echo $fields['nombre']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['pais_origen']['label']; ?>
			<?php echo $fields['pais_origen']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['provincia']['label']; ?>
			<?php echo $fields['provincia']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['titulo_establecimiento']['label']; ?>
			<?php echo $fields['titulo_establecimiento']['form']; ?>
		</div>
		<div class="form-group col-md-12 hidden" id="establecimiento_val">
			<?php echo $fields['titulo_establecimiento_val']['label']; ?>
			<?php echo $fields['titulo_establecimiento_val']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<b><?php echo "Carrera" ?></b>
			<?php echo $fields['titulo_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-12 hidden" id="tipo_val">
			<?php echo $fields['titulo_tipo_val']['label']; ?>
			<?php echo $fields['titulo_tipo_val']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $titulo->id) : ''; ?>
</div>
<?php echo form_close(); ?>
