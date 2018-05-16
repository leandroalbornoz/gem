
<script>
	$(document).ready(function() {
		var xhr;
		var select_rol, $select_rol;
		var select_entidad, $select_entidad;

		$select_rol = $('#rol').selectize({
			onChange: actualizar_entidad
		});

		$select_entidad = $('#entidad').selectize({
			valueField: 'id',
			labelField: 'nombre',
			searchField: ['nombre']
		});

		select_rol = $select_rol[0].selectize;
		select_entidad = $select_entidad[0].selectize;
		if (select_rol.getValue() !== '') {
			actualizar_entidad(select_rol.getValue());
		}

		function actualizar_entidad(value) {
			select_entidad.enable();
			var valor = select_entidad.getValue();
			select_entidad.disable();
			select_entidad.clearOptions();
			if (value !== '') {
				select_entidad.load(function(callback) {
					xhr && xhr.abort();
					xhr = $.ajax({
						url: 'ajax/get_entidades/' + value,
						dataType: 'json',
						success: function(results) {
							select_entidad.enable();
							callback(results);
							if (results.length === 1) {
								select_entidad.setValue(results[0].id);
								select_entidad.disable();
							} else {
								select_entidad.setValue(valor);
							}
						},
						error: function() {
							callback();
						}
					})
				});
			}
		}
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_seleccionar_rol')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="panel panel-default col-sm-12">
		<p><b>De: </b><?php echo $conversacion[0]->de; ?></p>
		<p><b>Para: </b><?php echo $conversacion[0]->para; ?></p>
		<p><b>Asunto: </b><?php echo $conversacion[0]->asunto; ?></p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="direct-chat direct-chat-warning" style="background: #f8f9fb; border-radius: 6px;">
				<div class="direct-chat-messages" id="chat">
					<?php foreach ($conversacion as $conversacion): ?>
						<?php if ($conversacion->de_usuario === $remitente): ?>
							<div class="direct-chat-msg">
								<div class="direct-chat-info clearfix">
									<span class="direct-chat-name pull-left"><?php echo $conversacion->de; ?></span>
									<span class="direct-chat-timestamp pull-right"><?php echo empty($conversacion->fecha) ? '' : (new DateTime($conversacion->fecha))->format('d/m/Y H:i'); ?></span>
								</div>
								<img class="direct-chat-img" src="img/generales/usuario.png" alt="message user image">
								<div class="direct-chat-text">
									<?php echo nl2br($conversacion->mensaje); ?>
								</div>
							</div>
						<?php else: ?>
							<div class="direct-chat-msg right">
								<div class="direct-chat-info clearfix">
									<span class="direct-chat-name pull-right"><?php echo $conversacion->de; ?></span>
									<span class="direct-chat-timestamp pull-left"><?php echo empty($conversacion->fecha) ? '' : (new DateTime($conversacion->fecha))->format('d/m/Y H:i'); ?></span>
								</div>
								<img class="direct-chat-img" src="img/generales/usuario.png" alt="message user image">
								<div class="direct-chat-text" style="background: #3c8dbc;border-color:#3c8dbc;">
									<?php echo nl2br($conversacion->mensaje); ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-header">
</div>
<div class="modal-body">
	<h4>Seleccione el rol y la entidad a la que desea derivar el mensaje</h4>
	<div class="row">
		<div class="form-group col-sm-6">
			<?php echo $fields['rol']['label']; ?>
			<?php echo $fields['rol']['form']; ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $fields['entidad']['label']; ?>
			<?php echo $fields['entidad']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo form_hidden('usuario_id', $usuario->id); ?>
</div>
<?php echo form_close(); ?>
<script>
	agregar_eventos($('#form_seleccionar_rol'));
</script>