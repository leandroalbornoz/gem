<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Agregar Cargo
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="bono/persona/ver"><?php echo "Persona"; ?></a></li>
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
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<h3>
							<?= "$persona->cuil - $persona->apellido, $persona->nombre"; ?>
						</h3>
						<div class="row">
							<div class="form-group col-md-6">
								<?php echo $fields['cargos']['label']; ?>
								<?php echo $fields['cargos']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['cargo']['label']; ?>
								<?php echo $fields['cargo']['form']; ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label>
									<input type="checkbox" id="antecedenteCheck" name="antecedenteCheck" style="margin-top: 33px">
									Asignar antecedente al cargo
								</label>
							</div>
						</div>
						<div class="form-group col-md-12" id="div_antecedenteCheck" hidden>						
							<h3 style="text-align: center;">Antecedentes
								<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal_lg" href="juntas/persona_antecedente/modal_agregar_antecedente_avalado/<?php echo (!empty($persona->id)) ? $persona->id : ''; ?>"><i class="fa fa-plus"></i> Cursos aprobados por DGE</a>
								<br>
								<a class="pull-right btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="juntas/persona_antecedente/modal_agregar_antecedente/<?php echo (!empty($persona->id)) ? $persona->id : ''; ?>"><i class="fa fa-plus"></i> Otros antecedentes de carácter docente</a>
							</h3>
							<br>
							<div style="overflow-x:auto;">
								<table style="table-layout: fixed;" class="table table-bordered table-condensed table-striped">
									<tr>
										<th width="40px">Seleccionar</th>
										<th style="width:70px;">Fecha de Emisión</th>
										<th style="width:300px;">Antecedente</th>
										<th style="width:100px;">Institución</th>
										<th style="width:70px;">N° Resolución</th>
										<th  style="width:60px;">Duración</th>
										<th  style="width:60px;">Modalidad</th>
										<th  style="width:40px;">Aprobado</th>
									</tr>
									<?php if (isset($persona_antecedente) && !empty($persona_antecedente)): ?>
										<?php foreach ($persona_antecedente as $antecedente): ?>
											<tr>
												<td>
													<div class="form-group col-sm-12">
														<label><input type="radio" name="opcion" id="opcion" value="<?= $antecedente->id; ?>"><br></label>
													</div>
												</td>
												<td>
													<span class=""> <?= (new DateTime($antecedente->fecha_emision))->format('d/m/Y'); ?></span>
												</td>
												<td>
													<span class=""> <?= $antecedente->antecedente; ?></span>
												</td>
												<td>
													<span class=""> <?= $antecedente->institucion; ?></span>
												</td>
												<td>
													<span class=""> <?= $antecedente->numero_resolucion; ?></span>
												</td>
												<td>
													<span class=""> <?= $antecedente->duracion . " " . $antecedente->tipo_duracion; ?></span>
												</td>
												<td>
													<span class=""> <?= $antecedente->modalidad; ?></span>
												</td>
												<td>
													<span class=""> <?= $antecedente->aprobado ?></span>
												</td>
											</tr>
										<?php endforeach; ?> 
									<?php else: ?>
										<tr>
											<td colspan="8" style="text-align: center;">
												-- Sin Antecedentes --
											</td>

										</tr>
									<?php endif; ?>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<a class="btn btn-default" href="juntas/escritorio/ver/<?= $persona->id ?>" title="Cancelar">Cancelar</a>
					<button  type="submit" class="btn btn-primary pull-right" title="Guardar" name="submitform" id="submitform"> Guardar</button>
					<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $persona->id) : ''; ?>
				</div>
				<?php echo form_close(); ?>
			</div>
	</section>
</div>
<div class="modal fade" id="remote_modal_lg" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$("#remote_modal_lg").on("show.bs.modal", function(e) {
			var link = $(e.relatedTarget);
			$(this).find(".modal-content").load(link.attr("href"));
		});
		$('#remote_modal_lg').on("hidden.bs.modal", function(e) {
			$(this).find(".modal-content").empty();
		});
		var submitform = document.getElementById('submitform');
		$('#antecedenteCheck').on('change', function() {
			if (this.checked) {
				$("#div_antecedenteCheck").show();
				submitform.disabled = true;
			} else {
				$("#div_antecedenteCheck").hide();
				submitform.disabled = false;
			}
		});
		$('#opcion').on('change', function() {
			if (this.checked) {
				submitform.disabled = false;
			} else {
				submitform.disabled = true;
			}
		});
	});
</script>