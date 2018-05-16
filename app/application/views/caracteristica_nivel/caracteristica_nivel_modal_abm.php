<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-6 form-group">
			<?php echo $fields['caracteristica_tipo']['label']; ?>
			<?php echo $fields['caracteristica_tipo']['form']; ?>
		</div>
		<div class="col-sm-6 form-group">
			<?php echo $fields['lista_valores']['label']; ?>
			<?php echo $fields['lista_valores']['form']; ?>
		</div>
		<div class="col-sm-12 form-group">
			<?php echo $fields['descripcion']['label']; ?>
			<?php echo $fields['descripcion']['form']; ?>
		</div>
	</div>
	<table style="table-layout: fixed; margin-top: 3%;" class="table table-bordered table-condensed table-striped">
		<tr>
			<th style="text-align: center;">Valores</th>
		</tr>
		<tr>
			<td>
				<?php if (!empty($valores)): ?>
					<ul class="list-group">
						<?php foreach ($valores as $valor): ?>
							<li class="list-group-item">
								<span class=""> <?= $valor->valor ?></span>
							</li>
						<?php endforeach; ?> 
					</ul>
				<?php else: ?>
					-- Sin valores --
				<?php endif; ?>
			</td>
		</tr>
	</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $txt_btn !== 'Ver' ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php if ($txt_btn === 'Eliminar'): ?>
		<?php echo form_hidden('id', $caracteristica_nivel->id); ?>
		<?php echo form_submit(array('class' => 'btn btn-danger pull-right', 'title' => 'Quitar'), 'Quitar'); ?>
	<?php elseif ($txt_btn === 'Agregar'): ?>
		<?php echo form_hidden('id', $caracteristica->id); ?>
		<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Agregar'), 'Agregar'); ?>
	<?php endif; ?>
</div>
<?php echo form_close(); ?>