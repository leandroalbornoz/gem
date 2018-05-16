<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Bandeja de mensajes
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Mensajes</a></li>
			<li class="active">Bandeja</li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body text-sm">
						<a class="btn btn-app btn-app-zetta btn-app-zetta-active active" href="mensaje/bandeja">
							<i class="fa fa-envelope-square"></i> Respondedor
						</a>
						<a class="btn btn-app btn-app-zetta" href="mensaje/bandeja">
							<i class="fa fa-envelope-square"></i> Mensajes no leídos
						</a>
						<a class="btn btn-app btn-app-zetta" href="mensaje/mensajes_difusion">
							<i class="fa fa-bullhorn"></i> Msj Difusión
						</a>
						<a class="btn btn-app btn-app-zetta" href="mensaje/leidos">
							<i class="fa fa-envelope-square"></i> Mensajes leídos
						</a>
						<a class="btn btn-app btn-app-zetta" href="mensaje/enviados">
							<i class="fa fa-envelope-square"></i> Enviados
						</a>
						<a class="btn bg-blue" href="mensaje/modal_enviar" data-remote="false" data-toggle="modal" data-target="#remote_modal">
							<i class="fa fa-envelope-o"></i> Enviar mensaje
						</a>
						<?php if (in_array($this->rol->codigo, $this->roles_mensaje_masivo)): ?>
							<a class="btn bg-blue" href="mensaje/modal_seleccionar_rol" data-remote="false" data-toggle="modal" data-target="#remote_modal">
								<i class="fa fa-bullhorn"></i> Enviar anuncio
							</a>
						<?php endif; ?>
						<hr style="margin: 10px 0;">
						<?php $i = 0; ?>
						<?php foreach ($mensajes as $mensaje): ?>
							<?php if (($i % 3 ) == 0): ?>
								<?php echo '<div class="row">'; ?>
							<?php endif; ?>
							<?php $i++; ?>
							<div class="col-sm-4">
								<div style="outline: 1px solid #999;">
									<?php echo form_open(base_url("mensaje/modal_responder/$mensaje->id")); ?>
									<div><b>Fecha: </b><?php echo (new DateTime($mensaje->fecha))->format('d/m/y H:i'); ?>
										<div class="btn-group pull-right" role="group">
											<a class="btn btn-xs btn-default" href="mensaje/modal_ver/<?php echo $mensaje->id; ?>/1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i></a>
											<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li><a class="dropdown-item" href="mensaje/modal_responder/<?php echo $mensaje->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-reply"></i> Responder</a></li>
											</ul>
										</div>
									</div>
									<p><b>De: </b>
										<?php echo "$mensaje->de_usuario<br>"; ?>
										<?php if ($mensaje->de_tabla === 'escuela'): ?>
											<?php echo '<a target="_blank" href="escuela/escritorio/' . $mensaje->de_entidad_id . '" title="Ver escuela">' . $mensaje->de_rol . '</a>'; ?>
										<?php else: ?>
											<?php echo $mensaje->de_rol; ?>
										<?php endif; ?>
									</p>
									<p><b>Para: </b><?php echo $mensaje->para_rol . (empty($mensaje->para_usuario) ? '' : " - $mensaje->para_usuario"); ?></p>
									<p><b>Asunto: </b><?php echo $mensaje->asunto; ?></p>
									<p><?php echo nl2br($mensaje->mensaje); ?></p>
									<div class="form-group text-left">
										<input type="hidden" name="asunto" value="<?php echo substr("RE:$mensaje->asunto", 0, 100); ?>" maxlength="100" id="asunto" class="form-control" required="">
										<textarea name="mensaje" cols="40" rows="3" id="mensaje" class="form-control" required></textarea>
									</div>
									<div class="input-group">
										<span class="input-group-addon">
											<input type="checkbox" checked name="leido">
										</span>
										<button class="btn btn-block btn-primary" type="submit">Responder</button>
									</div>
									<?php echo form_close(); ?>
									<?php echo form_open(base_url("mensaje/modal_ver/$mensaje->id")); ?>
									<button class="btn btn-block btn-warning" type="submit" name="accion" value="leer">Marcar como leido</button>
									<?php echo form_close(); ?>
								</div>
							</div>
							<?php if (($i % 3 ) == 0): ?>
								<?php echo '</div>'; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>