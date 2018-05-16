<style>
	.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
    padding: 6px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Destinatarios del anuncio
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Mensajes</a></li>
			<li class="active"><?php echo ucfirst($metodo); ?></li>
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
						<div class="box-body text-sm pull-right">
							<a class="btn bg-blue" href="mensaje/modal_enviar" data-remote="false" data-toggle="modal" data-target="#remote_modal">
								<i class="fa fa-envelope-o"></i> Enviar mensaje
							</a>
							<?php if (in_array($this->rol->codigo, $this->roles_mensaje_masivo)): ?>
								<a class="btn bg-blue" href="mensaje/modal_seleccionar_rol" data-remote="false" data-toggle="modal" data-target="#remote_modal">
									<i class="fa fa-bullhorn"></i> Enviar Msj.difusion
								</a>
							</div>
						<?php endif; ?>	
						<hr style="margin: 10px 0;">
						<div class="row">
							<div class="form-group col-md-4">
								<label for="de">Remitente</label>
								<input type="text" name="remitente1" value="<?php echo $mensaje_difusion->de_usuario; ?>" readonly="1" id="remitente1" class="form-control">
							</div>
							<div class="form-group col-md-4">
								<label for="de">Fecha</label>
								<input type="text" name="fecha1" value="<?php echo $mensaje_difusion->fecha; ?>" readonly="1" id="fecha1" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="de">Asunto</label>
								<input type="text" name="asunto1" value="<?php echo $mensaje_difusion->asunto; ?>" readonly="1" id="asunto1" class="form-control">
							</div>
							<div class="form-group col-md-4">
								<label for="mensaje">Mensaje</label>
								<textarea name="mensaje1" cols="50" rows="2" id="mensaje1" class="form-control" readonly="1"><?php echo $mensaje_difusion->mensaje; ?></textarea>
							</div>
						</div>
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var difusion_destinatario_table;
	function complete_difusion_destinatario_table() {
		agregar_filtros('difusion_destinatario_table', difusion_destinatario_table, 4);
	}
</script>