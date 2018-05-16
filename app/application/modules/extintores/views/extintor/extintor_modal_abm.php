<script>
	$(document).ready(function() {
		agregar_eventos($('#form_abm_extintor'));
		$('#kilos').inputmask('decimal', {radixPoint: ',', digits: 1, autoUnmask: true, placeholder: '', removeMaskOnSubmit: true, onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}});
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_abm_extintor')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['primer_carga']['label']; ?>
			<?php echo $fields['primer_carga']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['vencimiento']['label']; ?>
			<?php echo $fields['vencimiento']['form']; ?>
		</div>
		<div class="form-group col-md-4" id="numero_reg">
			<?php echo $fields['numero_registro']['label']; ?>
			<?php echo $fields['numero_registro']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['kilos']['label']; ?>
			<?php echo $fields['kilos']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['tipo_extintor']['label']; ?>
			<?php echo $fields['tipo_extintor']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['empresa_instalacion']['label']; ?>
			<?php echo $fields['empresa_instalacion']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['marca']['label']; ?>
			<?php echo $fields['marca']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<span class="bg-red text-bold hidden pull-left" id="cartel" style="border-radius: 2px;">&nbsp;El número de serie del extintor ya se encuentra registrado por otra escuela&nbsp;</span>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'id' => 'boton', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $extintor->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#numero_registro').change(verificar_numero_repetido);
		function verificar_numero_repetido(e) {
			var numero_registro = $('#numero_registro').val();
			$.ajax({
				type: 'GET',
				url: 'ajax/get_num_extintor?',
				data: {numero_registro: numero_registro},
				dataType: 'json',
				success: function(result) {
					if (result !== '') {
						$('#cartel').removeClass('hidden');
						$('#numero_reg').addClass('has-error');
						$('#boton').attr('disabled', true);
					} else {
						$('#cartel').addClass('hidden');
						$('#numero_reg').removeClass('has-error');
						$('#boton').attr('disabled', false);
					}
				}
			});
		}
	});
</script>