<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Búsqueda de Aplicadores para <?php echo $escuela->nombre_largo; ?></h4>
</div>
<div class="modal-body">
	<p>
		<?php
		switch ($operativo->operativo_tipo_id) {
			case '1':
				echo 'Docentes con cargo en divisiones de 4° Grado - En caso de que ninguno esté disponible borrar 4° de Búsqueda para buscar de otros cursos.';
				break;
			case '2':
				echo 'Docentes con cargo en divisiones de 6° Grado - En caso de que ninguno esté disponible borrar 6° de Búsqueda para buscar de otros cursos.';
				break;
			case '3':
				echo 'Docentes con cargo en divisiones de 5° Año';
				break;
		}
		?>
	</p>
	<?php echo form_open(base_url("aprender/aprender_operativo/agregar_aplicador/$operativo->id")); ?>
	<table class="text-sm table table-hover table-bordered table-condensed" role="grid" id="tbl_listar_personas">
		<thead>
			<tr>
				<th>CUIL/Nombre</th>
				<th>Cursos</th>
				<th>Teléfono/E-mail</th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($docentes as $docente): ?>
				<tr>
					<td>
						<?php echo $docente->cuil; ?><br/>
						<?php echo "$docente->apellido, $docente->nombre"; ?>
					</td>
					<td>
						<?php echo $docente->cursos; ?>
					</td>
					<td>
						<?php if (empty($docente->telefono_fijo) || empty($docente->telefono_movil)): ?>
							<?php echo $docente->telefono_fijo . $docente->telefono_movil; ?>
						<?php else: ?>
							<?php echo "$docente->telefono_fijo/$docente->telefono_movil"; ?>
						<?php endif; ?><br/>
						<?php echo "$docente->email"; ?>
					</td>
					<td>
						<?php if (empty($docente->telefono_fijo) && empty($docente->telefono_movil)): ?>
							<div class="badge bg-red text-bold">Falta teléfono.</div>
						<?php elseif (empty($docente->email)): ?>
							<div class="badge bg-red text-bold">Falta e-mail.</div>
						<?php elseif (!empty($docente->aplicador_id)): ?>
							<div class="badge bg-blue text-bold">Asignado Esc.<?php echo $docente->escuela_asignada; ?></div>
						<?php else: ?>
							<button type="submit" name="aplicador" class="btn btn-xs btn-success" formaction="aprender/aprender_operativo/agregar_aplicador/<?php echo $operativo->id; ?>" value="<?php echo $docente->id; ?>"><i class="fa fa-plus"></i> Agregar aplicador</button>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo form_close(); ?>
</div>
<div class="modal-footer">
	<button  class="btn btn-default pull-left" type="button" id="btn-submit" data-dismiss="modal">Cancelar</button>
</div>
<script>
	function validar(e) {
		var tecla = (document.all) ? e.keyCode : e.which;
		if (tecla === 13) {
			$('#btn-search').click();
		}
	}
	var table_personas_busqueda;
	$(document).ready(function() {
		$('#d_cuil').inputmask("99-99999999-9");
		agregar_eventos($('#form_buscar_aplicador'));
		table_personas_busqueda = $('#tbl_listar_personas').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], initComplete: aprender_operativo_table});
		$('#tbl_listar_personas').css('width', '100%');
		$('#d_cuil').attr('required', true);
		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#d_cuil').attr('readonly', false);
		});
		$('#btn-search').click(function() {
			$('#btn-search').attr('disabled', true);
			var cuil = $('#d_cuil').val();
			if (cuil !== '') {
				table_personas_busqueda.search(cuil);
			} else {
				$('#btn-search').attr('disabled', false);
			}
		});
	});
	function aprender_operativo_table() {
		agregar_filtros('tbl_listar_personas', table_personas_busqueda, 5);
		var buscar ='<?php echo ($operativo->operativo_tipo_id === '1' ? '4' : ($operativo->operativo_tipo_id === '2' ? '6' : '5')); ?>°';
		$('#tbl_listar_personas').find('input[placeholder="Cursos"]').val(buscar);
		$('#tbl_listar_personas').find('input[placeholder="Cursos"]').change();
	}
</script>