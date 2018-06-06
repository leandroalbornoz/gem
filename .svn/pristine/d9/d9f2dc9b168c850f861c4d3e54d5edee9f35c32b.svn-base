<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_agregar_cuenta')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">
		<?php if (empty($referente_vigencia->fecha_hasta)): ?> 
			<?php echo $title; ?>
		<?php else: ?>
			<span style="color: red"> - Período cerrado - </span>
		<?php endif; ?>
	</h4>
</div>
<div class="modal-body">
	<?php if (empty($referente_vigencia->fecha_hasta)): ?> 
		<p style="color: red;"><strong>RECUERDE: "Una vez cerrado el período, el Referente pasará al estado NO VIGENTE y no se podrá realizar ninguna 
				modificación sobre lo cargado".</strong></p>
	<?php endif; ?>		
	<table class="table table-condensed table-bordered table-striped table-hover">
		<tr>
			<th colspan="9" style="text-align:center;">Datos del referente</th>
		</tr>
		<tr>
			<th>Apellido y nombre</th>
			<th>DNI</th>
			<th>CUIL</th>
		</tr>
		<tr>
			<td><?php echo "$persona->apellido, $persona->nombre"; ?></td>
			<td><?php echo "$persona->documento_tipo $persona->documento"; ?></td>
			<td><?php echo "$persona->cuil"; ?></td>
		</tr>
	</table>
	<hr style="margin: 10px 0;">
	<h4><u><strong>Último cheque</strong></u><span style="text-align:right; color:red; font-size: 18px; float: right;"><?php echo (empty($ultimo_cheque)) ? "(Falta completar)" : ""; ?></span></h4>
	<div class="row">
		<div class="form-group col-md-3">
			<?php echo $fields_vc['numero']['label']; ?>
			<?php echo $fields_vc['numero']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_vc['importe']['label']; ?>
			<?php echo $fields_vc['importe']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_vc['fecha']['label']; ?>
			<?php echo $fields_vc['fecha']['form']; ?>
		</div>
	</div>
	<h4><u><strong>Saldo de cuenta bancaria</strong></u><span style="text-align:right; color:red; font-size: 18px; float: right;"><?php echo (empty($saldo)) ? "(Falta completar)" : ""; ?></span></h4>
	<div class="row">
		<div class="form-group col-md-3">
			<?php echo $fields_vs['saldo']['label']; ?>
			<?php echo $fields_vs['saldo']['form']; ?>
		</div>
		<div class="form-group col-md-3">
			<?php echo $fields_vs['fecha']['label']; ?>
			<?php echo $fields_vs['fecha']['form']; ?>
		</div>
	</div>
	<h4><u><strong>Fondos pendientes</strong></u></h4>
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
				<thead>
					<tr>
						<th>Fecha de transferencia</th>
						<th>Concepto</th>
						<th>Importe</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($fondos_pendientes)): ?>
						<?php foreach ($fondos_pendientes as $fondo): ?>
							<tr>
								<td><?= empty($fondo->fecha_transferencia) ? '' : (new DateTime($fondo->fecha_transferencia))->format('d/m/Y'); ?></td>
								<td><?= $fondo->concepto; ?></td>
								<td><?= $fondo->importe; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td style="text-align: center;" colspan="4">
								-- Sin fondos pendientes --
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php if (empty($referente_vigencia->fecha_hasta)): ?> 
		<input type="submit" value="Cerrar" class="btn btn-danger pull-right" title="Cerrar"  <?php echo (empty($saldo) || empty($ultimo_cheque)) ? "disabled" : ""; ?>>
		<input type="hidden" name="id" value="<?php echo $referente->id; ?>" id="id"/>
		<input type="hidden" name="referente_vigencia_id" value="<?php echo $referente_vigencia->id; ?>" id="referente_vigencia_id"/>
		<input type="hidden" name="fecha_hasta" value="<?php echo date('d/m/Y'); ?>" id="fecha_hasta"/>
	<?php endif; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $referente_vigencia->id) : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		agregar_eventos($('#form_agregar_cuenta'))
	});
</script>