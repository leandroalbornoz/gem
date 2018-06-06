<style>
	.nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
	}
	.nav-tabs-custom>.nav-tabs>li {
    border: 3px solid transparent;
		border-top-color: #3c8dbcb8;
    border-right-color: #3c8dbcb8;
    border-left-color: #3c8dbcb8;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li>a>.fa{
		color: #3c8dbcb8 !important;
	}
	.nav-tabs-custom>.nav-tabs>li.active {
    border-top-color: #3c8dbc;
    border-right-color: #3c8dbc;
    border-left-color: #3c8dbc;
    border-bottom-color: #fff;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li>a {
    color: #444444b5;
	}
	.nav-tabs-custom>.nav-tabs>li.active>a>.fa {
		color: #3c8dbc !important;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Tribunal de Cuentas
			<?php echo empty($escuela) ? '' : " - Esc. $escuela->nombre_largo"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo "tribunal/escritorio/listar/"; ?>">Escuelas</a></li>
			<li><a href="<?php echo "tribunal/escritorio/escuela/$escuela->id"; ?>">Tribunales</a></li>
			<li class="active">Gestión de Referentes</li>
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
			<?php if (!empty($cuentas_bancarias)): ?>
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Número de Cuenta Bancaria:</h3>
						</div>
						<div class="box-body" style="padding: 0px;">
							<div class="nav-tabs-custom" style="margin-bottom: 0px;">
								<ul class="nav nav-tabs">
									<?php foreach ($cuentas_bancarias as $key => $cuenta): ?>
									<li class="<?php echo ($key === 0) ? "active" : ""; ?>"><a href="#<?php echo $cuenta->id; ?>" data-toggle="tab"><strong><u><?php echo (!empty($cuenta->descripcion_cuenta)) ? $cuenta->descripcion_cuenta : "Cuenta"; ?></u>:</strong> <?php echo $cuenta->numero_cuenta; ?></a></li>
									<?php endforeach; ?>
								</ul>
								<div class="tab-content">
									<?php foreach ($cuentas_bancarias as $key => $cuenta): ?>
										<div class="<?php echo ($key === 0) ? "active" : ""; ?> tab-pane" id="<?php echo $cuenta->id; ?>">
												<a class="btn bg-blue btn-app btn-app-zetta" href="tribunal/referente/modal_buscar/<?php echo $escuela->id ?>/<?php echo $cuenta->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
													<i class="fa fa-plus" id="<?php echo $cuenta->id; ?>"></i> Agregar
												</a>
												<a class="btn bg-yellow btn-app btn-app-zetta" href="tribunal/escritorio/modal_editar_cuenta/<?php echo $escuela->id ?>/<?php echo $cuenta->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
													<i class="fa fa-edit" id="<?php echo $cuenta->id; ?>"></i> Editar Cuenta
												</a>
												<a class="btn bg-green btn-app btn-app-zetta pull-right" href="tribunal/escritorio/imprimir_reporte/<?php echo $escuela->id ?>/<?php echo $cuenta->id; ?>" target="_blank">
													<i class="fa fa-file-pdf-o" id="<?php echo $cuenta->id; ?>"></i> Reporte
												</a>
												<a class="btn bg-green btn-app btn-app-zetta pull-right" href="tribunal/escritorio/modal_reporte_dinamico/<?php echo $escuela->id ?>/<?php echo $cuenta->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
													<i class="fa fa-file-pdf-o" id="<?php echo $cuenta->id; ?>"></i> Reporte Dinamico
												</a>
												<hr style="margin: 10px 0;">
											<h3><u>Referentes</u>:</h3>
											<?php echo $js_table[$cuenta->id]; ?>
											<?php echo $html_table[$cuenta->id]; ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>	
						<?php if ($rol->codigo === ROL_ADMIN || $rol->codigo === ROL_USI || $rol->codigo === ROL_MODULO): ?>
							<div class="box-footer">
								<a class="btn btn-default" href="tribunal/escritorio/listar/" title="Volver">Volver</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>
<script>
<?php foreach ($cuentas_bancarias as $key => $cuenta): ?>
		var referente<?php echo $cuenta->id; ?>_table;
		function complete_referente<?php echo $cuenta->id; ?>_table() {
			agregar_filtros('referente<?php echo $cuenta->id; ?>_table', referente<?php echo $cuenta->id; ?>_table, 8);
		}
<?php endforeach; ?>
</script>