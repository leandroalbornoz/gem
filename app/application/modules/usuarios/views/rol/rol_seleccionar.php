<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Bienvenido
		</h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-home"></i> Inicio</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-header with-border">
						<h3 class="box-title">Selección de rol</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								Debe seleccionar un rol para poder utilizar el sistema.
								<div class="form-group">
									<?php echo $fields['rol']['label']; ?>
									<?php echo $fields['rol']['form']; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<?php echo form_hidden('redirect_url', $redirect_url); ?>
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
						<button type="submit" class="btn btn-primary pull-right" title="Seleccionar">Seleccionar</button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>