<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Signos Web Service
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="">Signos Web Service</a></li>
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
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>	
					<div class="box-body">
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['PersonaCUIL']['label']; ?>
								<?php echo $fields['PersonaCUIL']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['PersonaApeYNom']['label']; ?>
								<?php echo $fields['PersonaApeYNom']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['LegajoExterno']['label']; ?>
								<?php echo $fields['LegajoExterno']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['LegajoId']['label']; ?>
								<?php echo $fields['LegajoId']['form']; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="titulos/titulo_persona/listar" title="Cancelar">Cancelar</a>
						<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Consultar'), 'Consultar'); ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<?php if (!empty($respuesta)): ?>
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-body">
							<table class="table table-condensed table-bordered table-striped">
								<thead>
									<tr>
										<th>CUIL</th>
										<th>Apellido Nombre</th>
										<th>Legajo Externo</th>
										<th>Legajo Id</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($respuesta as $rta): ?>
										<tr>
											<td><?php echo $rta->PersonaCUIL; ?></td>
											<td><?php echo $rta->PersonaApeYNom; ?></td>
											<td><?php echo $rta->LegajoExterno; ?></td>
											<td><?php echo $rta->LegajoId; ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>
