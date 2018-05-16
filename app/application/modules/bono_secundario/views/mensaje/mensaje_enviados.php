<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Mensajes enviados
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
						<a class="btn btn-app btn-app-zetta" href="bono_secundario/mensaje/bandeja">
							<i class="fa fa-envelope-square"></i> Mensajes no leídos
						</a>
						<a class="btn btn-app btn-app-zetta" href="bono_secundario/mensaje/leidos">
							<i class="fa fa-envelope-square"></i> Mensajes leídos
						</a>
						<a class="btn btn-app btn-app-zetta btn-app-zetta-active active" href="bono_secundario/mensaje/enviados">
							<i class="fa fa-envelope-square"></i> Enviados
						</a>
						<div class="box-body text-sm pull-right">
							<a class="btn bg-blue" href="bono_secundario/mensaje/modal_enviar" data-remote="false" data-toggle="modal" data-target="#remote_modal">
								<i class="fa fa-envelope-o"></i> Enviar mensaje
							</a>
						</div>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var mensaje_table;
	function complete_mensaje_table() {
		agregar_filtros('mensaje_table', mensaje_table, 9);
	}
</script>