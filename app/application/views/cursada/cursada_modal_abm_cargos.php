<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Búsqueda de Cargos para: <?php echo $cursada->espacio_curricular; ?></h4>
</div>
<div class="modal-body">
	<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_cargos')); ?>
	<table class="text-sm table table-hover table-bordered table-condensed" role="grid" id="tbl_listar_cargos">
		<thead>
			<tr>
				<th>División / Condición</th>
				<th>Regimen / Materia</th>
				<th>Hs</th>
				<th>Persona Actual</th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($cargos as $cargo): ?>
				<tr>
					<td>
						<?php echo "$cargo->curso $cargo->division"; ?><br/>
						<?php echo "$cargo->condicion_cargo"; ?>
					</td>
					<td>
						<?php echo $cargo->regimen_materia; ?>
					</td>
					<td>
						<?php echo $cargo->carga_horaria; ?>
					</td>
					<td>
						<?php echo $cargo->persona; ?>
					</td>
					<td>
						<button type="submit" name="cargo_id" class="btn btn-xs btn-primary" value="<?php echo $cargo->id; ?>"><i class="fa fa-plus"></i> Seleccionar</button>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" name="cursada_id" value="<?php echo $cursada->id; ?>">
	<?php echo form_hidden('url_redireccion', $url_redireccion); ?>
	<?php if (!empty($cargo_cursada)): ?>
		<input type="hidden" name="cargo_cursada_id" value="<?php echo $cargo_cursada->id; ?>">
	<?php endif; ?>
	<?php echo form_close(); ?>
</div>
<div class="modal-footer">
	<button  class="btn btn-default pull-left" type="button" id="btn-submit" data-dismiss="modal">Cancelar</button>
</div>
<script>
	var table_cargos_busqueda;
	$(document).ready(function() {
		table_cargos_busqueda = $('#tbl_listar_cargos').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], initComplete: cursada_cargo_table});
	});
	function cursada_cargo_table() {
		agregar_filtros('tbl_listar_cargos', table_cargos_busqueda, 6);
		var buscar = '<?php echo ("$division->curso $division->division"); ?>';
		$('#tbl_listar_cargos').find('input[placeholder="División / Condición"]').val(buscar);
		$('#tbl_listar_cargos').find('input[placeholder="División / Condición"]').change();
	}
</script>
