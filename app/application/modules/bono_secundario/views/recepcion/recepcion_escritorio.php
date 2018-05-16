<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Personas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="bono/<?php echo $controlador . "/cambiar_rol" ?>"><?php echo ucfirst($controlador); ?></a></li>
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
					<div class="box-header with-border">
						<h3 class="box-title">Auditoria de Bono Secundario</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<?php if (isset($this->session->userdata('usuario')->rol_escritorio)): ?>
							<a class="btn btn-app btn-app-zetta  <?php echo $class['pendientes']; ?> >" href="bono/recepcion/pendientes/<?= $escuela->id ?>">
								<i class="fa fa-envelope"></i> Pendientes de recepción<span class="badge"><?= $cantidad_bonos['precepcion'] ?></span>
							</a>
							<a class="btn btn-app btn-app-zetta  <?php echo $class['recibidos']; ?> >" href="bono/recepcion/recibidos/<?= $escuela->id ?>">
								<i class="fa fa-check"></i> Recibidos<span class="badge"><?= $cantidad_bonos['recibidos'] ?></span>
							</a>
							<a class="btn btn-app btn-app-zetta  <?php echo $class['cambiar_rol']; ?> >" href="bono/recepcion/cambiar_rol">
								<i class="fa fa-refresh"></i> Cambiar escuela
							</a>
						<?php endif ?>
						<?php if (!isset($this->session->userdata('usuario')->rol_escritorio)): ?>
							<h4>Seleccione escuela para la recepción de inscripciones:</h4>
							<div class="row">
								<div class="form-group col-md-6">
									<?php echo $fields['escuela']['label']; ?>
									<?php echo $fields['escuela']['form']; ?>
								</div>
							</div>
						<?php endif ?>
					</div>
					<div class="box-footer">
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
