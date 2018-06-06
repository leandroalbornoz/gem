<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_evaluacion')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<?php if ($txt_btn === 'Eliminar'): ?>
	<?php $data_submit = array('class' => 'btn btn-danger pull-right', 'title' => $txt_btn); ?>
<?php else: ?>
	<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
<?php endif; ?>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['ciclo_lectivo']['label']; ?>
			<?php echo $fields['ciclo_lectivo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['periodo']['label']; ?>
			<?php echo $fields['periodo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['evaluacion_tipo']['label']; ?>
			<?php echo $fields['evaluacion_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha']['label']; ?>
			<?php echo $fields['fecha']['form']; ?>
		</div>
		<input type="hidden" name="cursada_id" value="<?php echo $cursada->id; ?>">
		<?php if (isset($evaluacion)): ?>
			<input type="hidden" name="evaluacion_id" value="<?php echo $evaluacion->id; ?>">
		<?php endif; ?>
	</div><br>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><b>Conceptos</b></h3>
				</div>
				<div class="box-body">
					<div class="box-group" id="accordion">
						<?php $i = 0; ?>
						<?php foreach ($evaluacion_espacio_curricular_conceptos as $concepto => $detalle): ?>
							<?php $i++; ?>
							<div class="panel box box-primary">
								<div class="box-header with-border">
									<h4 class="box-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="false" class="collapsed">
											<?php echo $concepto; ?>
										</a>
									</h4>
								</div>
								<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" aria-expanded="true" style="">
									<div class="box-body">
										<ul>
											<?php foreach ($detalle as $detalle_concepto): ?>
												<?php echo "<li>$detalle_concepto</li>"; ?>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_evaluacion'));
	});
</script>