<?= form_open(base_url('becas/validacion/operacion'), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?= $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-3">
			<?= $fields['fecha']['label']; ?>
			<?= $fields['fecha']['form']; ?>
		</div>
		<div class="form-group col-md-9">
			<?= $fields['escuela']['label']; ?>
			<?= $fields['escuela']['form']; ?>
		</div>
		<div class="form-group col-md-9">
			<?= $fields['persona']['label']; ?>
			<?= $fields['persona']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?= $fields['beca_estado']['label']; ?>
			<div class="input-group">
				<?= $fields['beca_estado']['form']; ?>
				<span class="input-group-btn">
					<button type="button" style="cursor: auto" class="btn <?= $beca_persona->clase; ?>"><i class="fa fa-fw <?= $beca_persona->icono; ?>"></i></button>
				</span>
			</div>
		</div>
	</div>
	<hr>
	<?php if (!empty($operaciones_posibles)): ?>
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Operaciones posibles</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($operaciones_posibles as $operacion): ?>
					<tr>
						<td>
							<button type="submit" class="btn btn-block <?= $operacion->clase; ?>" name="operacion" value="<?= $operacion->id; ?>"><i class="fa fa-fw <?= $operacion->icono; ?>"></i> <?= "$operacion->operacion ($operacion->beca_estado_o => $operacion->beca_estado_d)"; ?></button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	<?= form_hidden('id', $beca_persona->id); ?>
</div>
<?= form_close(); ?>