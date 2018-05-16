<style>
	.direct-chat-warning .right>.direct-chat-text:after, .direct-chat-warning .right>.direct-chat-text:before {
		border-left-color:#3c8dbc;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">
		Ver Mensaje de difusión
	</h4>
</div>
<div class="modal-body">
	<div class="panel panel-default col-sm-12">
		<p><b>De: </b><?php echo $mensaje_masivo->de; ?></p>
		<p><b>Para: </b><?php echo $mensaje_masivo->para; ?></p>
		<p><b>Asunto: </b><?php echo $mensaje_masivo->asunto; ?></p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="direct-chat direct-chat-warning" style="background: #f8f9fb; border-radius: 6px;">
				<div class="direct-chat-messages" id="chat">
					<div class="direct-chat-msg">
						<div class="direct-chat-info clearfix">
							<span class="direct-chat-name pull-left"><?php echo $mensaje_masivo->de; ?></span>
							<span class="direct-chat-timestamp pull-right"><?php echo empty($mensaje_masivo->fecha) ? '' : (new DateTime($mensaje_masivo->fecha))->format('d/m/Y H:i'); ?></span>
						</div>
						<img class="direct-chat-img" src="img/generales/usuario.png" alt="message user image">
						<div class="direct-chat-text">
							<?php echo nl2br($mensaje_masivo->mensaje); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	<?php if ($respondible === '1'): ?>
		<?php echo form_hidden('id', $mensaje_masivo->id); ?>
		<button type="submit" class="btn btn-success pull-right" title="Responder" name="accion" value="responder">Responder</button>
	<?php endif; ?>
	<?php if (empty($mensaje_masivo->leido_fecha)): ?>
		<button type="submit" style="margin-right:5px;" class="btn btn-warning pull-right" title="Marcar como leído/resuelto" name="accion" value="leer">Marcar como leído</button>
	<?php endif; ?>
</div>
<?php echo form_close(); ?>