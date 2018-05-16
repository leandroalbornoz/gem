<div class="content-wrapper">
	<section class="content-header">
		<h1>Reportes Estáticos</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i> Inicio</li>
			<li><a href="consultas/reportes_estaticos/escritorio">Reportes Estaticos</a></li>
			<li class="active">Reporte de oferta educativa</li>
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
		<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_reporte')); ?>
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Reporte de oferta educativa</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-3">
						<div class="form-group">
							<?php echo $fields['departamento']['label']; ?>
							<?php echo $fields['departamento']['form']; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<a class="btn btn-primary" href="consultas/reportes_estaticos/escritorio">
					Cancelar
				</a>
				<?php $data_submit = array('class' => 'btn btn-success pull-right', 'title' => $txt_btn); ?>
				<?php echo form_hidden('enviado', 1); ?>
				<?php echo form_submit($data_submit, $txt_btn); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_reporte'));
	});
</script>