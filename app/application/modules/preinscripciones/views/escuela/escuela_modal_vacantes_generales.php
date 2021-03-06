<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Preinscripciones <?= $ciclo_lectivo; ?> - Vacantes por Escuela</h4>
</div>
<div class="modal-body">
	<table class="text-sm table table-hover table-bordered table-condensed" role="grid" id="tbl_listar_escuelas">
		<thead>
			<tr>
				<th>Operativo</th>
				<th>Supervisión</th>
				<th>Escuela</th>
				<th>Localidad</th>
				<th>Turno</th>
				<th>Vacantes</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th style="width:60px;"></th>
				<th style="width:60px;"></th>
				<th></th>
				<th style="width:60px;"></th>
				<th style="width:60px;"></th>
				<th style="width:60px;"></th>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($escuelas as $escuela): ?>
				<tr>
					<td><?php echo substr(str_replace('Preinscripciones ', '', $escuela->operativo), 0, strlen($escuela->operativo) - 22); ?></td>
					<td><?php echo str_replace(' - ', '<br>', $escuela->supervision); ?></td>
					<td><?php echo "$escuela->numero<br>$escuela->nombre"; ?></td>
					<td><?php echo "$escuela->departamento<br>$escuela->localidad"; ?></td>
					<td><?php echo $escuela->turno; ?></td>
					<td style="text-align: center;"><?php echo "$escuela->vacantes"; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<button  class="btn btn-default pull-left" type="button" id="btn-submit" data-dismiss="modal">Cerrar</button>
</div>
<script>
	var table_escuelas;
	$(document).ready(function() {
		table_escuelas = $('#tbl_listar_escuelas').DataTable({dom: 'tp', autoWidth: false, language: {url: 'plugins/datatables/spanish.json'}, initComplete: listar_escuelas_table});
		$('#tbl_listar_escuelas').css('width', '100%');
	});
	function listar_escuelas_table() {
		agregar_filtros('tbl_listar_escuelas', table_escuelas, 6);
	}
</script>