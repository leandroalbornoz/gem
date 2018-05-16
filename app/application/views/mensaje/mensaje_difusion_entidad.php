<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Mensaje de difusión a <?php echo $rol_seleccionado->nombre; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="mensaje/bandeja">Mensajes</a></li>
			<li class="active">Mensaje de difusión a <?php echo $rol_seleccionado->nombre; ?></li>
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
					<div class="box-body">
						<?php echo form_open(uri_string(), array('name' => 'form_mensaje', 'id' => 'form_mensaje')); ?>
						<?php echo $html_destinatarios; ?>
						<div class="modal fade" id="modal_enviar"  role="dialog" aria-labelledby="Modal" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="form-group col-md-12">
												<?php echo $fields_mensaje['de']['label']; ?>
												<?php echo $fields_mensaje['de']['form']; ?>
											</div>
											<div class="form-group col-md-12">
												<label>Para</label>
												<input type="text" class="form-control" value="0 destinatarios seleccionadas" readonly id="para_escuelas">
											</div>
											<div class="form-group col-md-12">
												<?php echo $fields_mensaje['asunto']['label']; ?>
												<?php echo $fields_mensaje['asunto']['form']; ?>
											</div>
											<div class="form-group col-md-12">
												<?php echo $fields_mensaje['mensaje']['label']; ?>
												<?php echo $fields_mensaje['mensaje']['form']; ?>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
										<button class="btn btn-primary pull-right" type="submit" id="enviar" title="Enviar mensaje">Enviar mensaje</button>
									</div>
								</div>
							</div>
						</div>
						<?php echo form_close(); ?>
						<div class="box-footer">
							<a class="btn btn-default" href="mensaje/bandeja" title="Cancelar">Cancelar</a>
							<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal_enviar" id="seleccionar" title="Enviar mensaje">Seleccionar</button>
							<div class="row pull-right hidden" style="padding-right: 5%;"  id="cartel">
								<div class="bg-red text-bold" style="border-radius: 2px;"><h5>Debe seleccionar al menos una opción</h5></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>