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
						<div class="box-body text-sm pull-right">
							<a class="btn bg-blue" href="mensaje/modal_enviar" data-remote="false" data-toggle="modal" data-target="#remote_modal">
								<i class="fa fa-envelope-o"></i> Enviar mensaje
							</a>
							<?php if (in_array($this->rol->codigo, $this->roles_mensaje_masivo)): ?>
								<a class="btn bg-blue" href="mensaje/modal_seleccionar_rol" data-remote="false" data-toggle="modal" data-target="#remote_modal">
									<i class="fa fa-bullhorn"></i> Enviar Msj.Difusión
								</a>
							<?php endif; ?>
						</div>
						<hr style="margin: 10px 0;">
						<table id="mensaje_table" class="table table-hover table-bordered table-condensed">
							<thead>
								<tr>
									<th style="width: 15px;"></th>
									<th>Remitente</th>
									<th style="max-width: 70px;">Fecha</th>
									<th>Asunto</th>
									<th class="none">Mensaje</th>
									<th>Destinatario</th>
									<th style="width: 70px;" class="all"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($mensajes as $mensaje): ?>
									<tr>
										<td></td>
										<td><?php echo "$mensaje->de_rol - $mensaje->de_usuario"; ?></td>
										<td><?php echo (new DateTime($mensaje->fecha))->format('d/m/y H:i'); ?></td>
										<td><?php echo $mensaje->asunto; ?></td>
										<td><?php echo $mensaje->mensaje; ?></td>
										<td><?php echo $mensaje->para_rol . (empty($mensaje->para_usuario) ? '' : " - $mensaje->para_usuario"); ?></td>
										<td class="dt-body-center">
											<div class="btn-group" role="group">
												<a class="btn btn-xs btn-default" href="mensaje/modal_ver/<?php echo $mensaje->id; ?>/1" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-search"></i> Ver</a>
												<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li><a class="dropdown-item" href="mensaje/modal_responder/<?php echo $mensaje->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-reply"></i> Responder</a></li>
													<?php if (in_array($this->rol->codigo, $this->roles_derivar)): ?>
														<li><a class="dropdown-item" href="mensaje/modal_derivar/<?php echo $mensaje->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"><i class="fa fa-shower" aria-hidden="true"><i class="fa fa-sign-language" aria-hidden="true"></i></i>Derivar</a></li>
													<?php endif; ?>
												</ul>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<div style="display:none;" id="div_responder_mensaje"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#mensaje_table').DataTable({
			dom: 't',
			autoWidth: false,
			paging: false,
			ordering: false,
			responsive: true,
			language: {"url": "plugins/datatables/spanish.json"}
		});
<?php if (isset($responder_mensaje)): ?>
			$('#div_responder_mensaje').append('<a id="a_responder_mensaje" href="mensaje/modal_responder/<?php echo $responder_mensaje; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
			setTimeout(function() {
				$('#a_responder_mensaje').click();
			}, 500);
<?php endif; ?>
	});
</script>