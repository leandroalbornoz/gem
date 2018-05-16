<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Cursadas de <?php echo $escuela->nombre_largo ?></h4>
</div>
<div class="modal-body">
	<?php echo form_open(uri_string()); ?>
	<table class="text-sm table table-hover table-bordered table-condensed" role="grid" id="tbl_listar_cursadas">
		<thead>
			<tr>
				<th style="width: 15%">División</th>
				<th style="width: 25%">Materia</th>
				<th style="width: 25%">Grupo</th>
				<th style="width: 15%">Hs disp</th>
				<th style="width: 20%"></th>
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
			<?php foreach ($cursadas as $cursada): ?>
				<tr>
					<td>
						<?php echo $cursada->division; ?>
					</td>
					<td>
						<?php echo $cursada->materia; ?>
					</td>
					<td>
						<?php echo $cursada->grupo; ?>
					</td>
					<td>
						<?php echo $cursada->carga_horaria - $cursada->carga_horaria_cargos; ?>
					</td>
					<td style="text-align: center;">
						<?php if ($cursada->carga_horaria_cargos < $cursada->carga_horaria): ?>
							<button type="submit" name="cursada_id" class="btn btn-xs btn-primary" value="<?php echo $cursada->id; ?>"><i class="fa fa-plus"></i> Seleccionar</button>
						<?php else: ?>
							<div class="bg-red text-bold" style="border-radius: 2px; text-align: center;"><h5>Sin horas disponibles</h5></div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" name="cargo_id" value="<?php echo $cargo->id; ?>">
	<?php echo form_close(); ?>
</div>
<div class="modal-footer">
	<button  class="btn btn-default pull-left" type="button" id="btn-submit" data-dismiss="modal">Cancelar</button>
</div>
<script>
	var table_cursadas_busqueda;
	$(document).ready(function() {
		table_cursadas_busqueda = $('#tbl_listar_cursadas').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], initComplete: cursada_table});
	});
	function cursada_table() {
		agregar_filtros('tbl_listar_cursadas', table_cursadas_busqueda, 6);
		var buscar = '<?php echo empty($cargo->division) ? '' : $cargo->division;  ?>';
		$('#tbl_listar_cursadas').find('input[placeholder="División"]').val(buscar);
		$('#tbl_listar_cursadas').find('input[placeholder="División"]').change();
	}
</script>
