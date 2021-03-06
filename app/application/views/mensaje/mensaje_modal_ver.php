<style>
	.direct-chat-warning .right>.direct-chat-text:after, .direct-chat-warning .right>.direct-chat-text:before {
		border-left-color:#3c8dbc;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">
		Ver Mensaje
		<span class="badge bg-blue" data-original-title=""><?php echo count($conversacion); ?></span>
	</h4>
</div>
<div class="modal-body">
	<div class="panel panel-default col-sm-12">
		<p><b>De: <a></a></a></b><?php echo $conversacion[0]->de; ?></p>
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
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	<?php if ($respondible === '1'): ?>
		<?php echo form_hidden('id', $mensaje->id); ?>
		<button type="submit" class="btn btn-success pull-right" title="Responder" name="accion" value="responder">Responder</button>
		<button type="submit" style="margin-right:5px;" class="btn btn-warning pull-right" title="Marcar como leído/resuelto" name="accion" value="leer">Marcar como leído/resuelto</button>
	<?php endif; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		$('#chat').animate({scrollTop: $('#chat').height()}, 800);
	});
</script>