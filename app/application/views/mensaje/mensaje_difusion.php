<style>
	.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
    padding: 6px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Mensajes de difusión
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
						<a class="btn btn-app btn-app-zetta btn-app-zetta-active active" href="mensaje/mensajes_difusion">
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
						<div class="row">
							<div class="col-md-12">
								<h5 style="text-align: center;"><b>Mensajes Recibidos</b></h5>
								<?php echo $js_table_difusion; ?>
								<?php echo $html_table_difusion; ?>
								<hr>
							</div>
							<?php if (in_array($this->rol->codigo, $this->roles_mensaje_masivo)): ?>
								<div class="col-md-12">
									<h5 style="text-align: center;"><b>Mensajes Enviados</b></h5>
									<?php echo $js_table_difusion_enviados; ?>
									<?php echo $html_table_difusion_enviados; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var mensaje_difusion_table;
	function complete_mensaje_difusion_table() {
		agregar_filtros('mensaje_difusion_table', mensaje_difusion_table, 8);
	}
	var mensaje_difusion_enviado_table;
	function complete_mensaje_difusion_enviado_table() {
		agregar_filtros('mensaje_difusion_enviado_table', mensaje_difusion_enviado_table, 6);
	}
</script>