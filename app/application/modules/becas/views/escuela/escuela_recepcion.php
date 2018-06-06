<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Postulaciones Becas - Recepción
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/escritorio/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li class="active">Recepción Postulaciones</li>
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
			<?php if ($this->edicion): ?>
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Esc. <?= "$escuela->nombre_largo"; ?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<form class="form-horizontal" method="post" id="form_buscar">
							<div class="box-body ">
								<div class="col-sm-12">
									<p>Ingrese el CUIL del docente para registrar la postulación en GEM.</p>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="cuil">CUIL<div style="float:left;" class="help-block with-errors"></div></label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="cuil" value="<?= $this->form_validation->set_value('cuil'); ?>" placeholder="__-_________-_" id="cuil" required data-validacuil="1">
									</div>
									<div class="col-sm-2">
										<button class="btn btn-primary">Buscar</button>
									</div>
								</div>
								<?php if (!empty($persona)): ?>
									<hr>
									<div class="row">
										<div class="col-xs-6">
											<b>Nombre:</b> <?= trim("$persona->apellido, $persona->nombre"); ?><br/>
											<b>CUIL:</b> <?= $this->input->post('cuil'); ?><br/>
											<b>Servicios:</b><br/><?= $persona->servicios; ?>
										</div>
										<div class="col-xs-6">
											<div class="text-center">
												<?php if (empty($persona->escuela_postulada)): ?>
													<a class="btn btn-lg btn-success" data-toggle="modal" data-target="#aceptar_modal"><i class="fa fa-check"></i> REGISTRAR COMO POSTULANTE</a>
													<a class="btn btn-lg btn-danger" data-toggle="modal" href="becas/escuela/recepcion/<?= $escuela->id; ?>"><i class="fa fa-remove"></i> CANCELAR</a>
												<?php else: ?>
													<span class="alert alert-danger">Docente CUIL <?= $persona->cuil ?> ya ha sido registrado en Escuela <?= $persona->escuela_postulada; ?></span>
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php elseif (!empty($_POST)): ?>
									<h4 style="color:red;">No se encontraron servicios con el CUIL ingresado.</h4>
								<?php endif; ?>
							</div>
						</form>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!empty($persona)): ?>
				<div class="modal fade" id="aceptar_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<?= form_open("becas/escuela/recibir/$escuela->id"); ?>
							<div class="modal-header text-green">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Postulación</h4>
							</div>
							<div class="modal-body">
								<input type="hidden" name="cuil" value="<?= $this->input->post('cuil'); ?>">
								<input type="hidden" name="id" value="<?= $persona->id; ?>">
								<div style="float:left;" class="help-block with-errors"></div>
								<label style="font-weight: normal;"><input required type="checkbox" name="acepto" value="Si">Declara <b>Sí</b> cumplir las condiciones establecidas en la Resolución 1293-DGE-18 para el beneficio.</label>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
								<button type="submit" name="estado" value="Conforme" class="btn btn-success pull-right" title="Registrar Postulación">Registrar Postulación</button>
							</div>
							<?= form_close(); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?= $vw_etapas; ?>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 style="width:100%; padding-right: 30px;" class="box-title">Postulaciones recibidas</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body ">
						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th>Fecha</th>
									<th>CUIL</th>
									<th>Persona</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($personas)): ?>
									<?php foreach ($personas as $persona): ?>
										<tr>
											<td><?= (new DateTime($persona->fecha))->format('d/m/Y H:i'); ?></td>
											<td><?= $persona->cuil; ?></td>
											<td><?= "$persona->apellido, $persona->nombre"; ?></td>
											<td><?= $persona->beca_estado === 'Postulado' ? '<a class="btn btn-xs btn-success" title="Imprimir" target="_blank" href="becas/escuela/imprimir/' . $persona->id . '"><i class="fa fa-print"></i></a> ' . $persona->beca_estado : $persona->beca_estado; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#cuil').inputmask("99-99999999-9");
		$('#form_buscar').validator({
			custom: {
				validacuil: function(input) {
					return validaCuil(input.val()) ? '' : 'Cuil no válido';
				}},
			errors: {
				validacuil: 'Cuil no válido'
			}
		});
	});
</script>