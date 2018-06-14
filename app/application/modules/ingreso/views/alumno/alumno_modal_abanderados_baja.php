<?php echo form_open_multipart(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_sacar_abanderados')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<h4><u>Alumno abanderados de baja en la escuela.</u></h4>
		</div>
		<div class="form-group col-md-12">
			<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
				<thead>
					<tr style="background-color: #f4f4f4" >
						<th style="text-align: center;" colspan="4">
							Alumnos
						</th>
					</tr>
					<tr>
						<th>Nombre y apellido</th>
						<th>Curso</th>
						<th>Division</th>
						<th>Abanderado</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($abanderados_baja)): ?>
						<?php foreach ($abanderados_baja as $abanderado): ?>
							<tr>
								<td style="text-align: center;">
									<?php echo $abanderado->persona; ?>
								</td>
								<td style="text-align: center;">
									<?php echo $abanderado->curso; ?>
								</td>
								<td style="text-align: center;">
									<?php echo $abanderado->division; ?>
								</td>
								<td style="text-align: center;">
									<?php echo $abanderado->abanderado; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td style="text-align: center;" colspan="5">
								-- No tiene --
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="id" value="<?php echo $escuela->id; ?>" id="id"/>
	<input type="hidden" name="ciclo_lectivo" value="<?php echo $ciclo_lectivo; ?>" id="ciclo_lectivo"/>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php if($txt_btn === 'Retirar'): ?>
	<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Retirar'), 'Retirar'); ?>
	<?php else: ?>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php endif; ?>
	<?php // echo ($txt_btn === 'Eliminar') ? form_hidden('escuela_id', $escuela_id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>	
$(document).ready(function() {
	agregar_eventos($('#form_sacar_abanderados'));
});
</script>

