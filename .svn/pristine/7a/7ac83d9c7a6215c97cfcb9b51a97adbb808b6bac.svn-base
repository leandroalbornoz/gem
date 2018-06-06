<style>
	#historico_novedad.table-bordered {
    border-color: #777;
	}
	#historico_novedad.table-bordered>thead>tr>th, #historico_novedad.table-bordered>tbody>tr>th, #historico_novedad.table-bordered>tfoot>tr>th, #historico_novedad.table-bordered>thead>tr>td, #historico_novedad.table-bordered>tbody>tr>td, #historico_novedad.table-bordered>tfoot>tr>td {
		border-bottom-color: #777;
	}
	#historico_novedad.table>thead>tr>th, #historico_novedad.table>tbody>tr>th, #historico_novedad.table>tfoot>tr>th, #historico_novedad.table>thead>tr>td, #historico_novedad.table>tbody>tr>td, #historico_novedad.table>tfoot>tr>td {
    border-top-color:#777;
	}
	#historico_novedad.table>thead>tr>th, #historico_novedad.table>tbody>tr>th, #historico_novedad.table>tfoot>tr>th, #historico_novedad.table>thead>tr>td, #historico_novedad.table>tbody>tr>td, #historico_novedad.table>tfoot>tr>td {

	}
	#historico_novedad.table-bordered>thead>tr>th, #historico_novedad.table-bordered>tbody>tr>th, #historico_novedad.table-bordered>tfoot>tr>th, #historico_novedad.table-bordered>thead>tr>td, #historico_novedad.table-bordered>tbody>tr>td, #historico_novedad.table-bordered>tfoot>tr>td {
		border-color: #777;
	}
	#historico_novedad .text-xs{
		font-size: 10px;
	}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<table id="historico_novedad" class="table table-condensed table-bordered text-center text-sm">
				<thead>
					<tr>
						<th colspan="5">Registro</th>
						<th colspan="3">Acción</th>
					</tr>
					<tr>
						<th>Desde</th>
						<th>Hasta</th>
						<th>Días</th>
						<th>Oblig.</th>
						<th>Estado</th>
						<th>Usuario</th>
						<th>Fecha</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php $acciones = array('I' => 'Crea', 'U' => 'Modifica', 'D' => 'Borra'); ?>
					<?php foreach ($historicos as $h_id => $historico): ?>
						<?php $historico_sig = isset($historicos[$h_id + 1]) ? $historicos[$h_id + 1] : $historico; ?>
						<tr <?= empty($historico->audi_id) ? 'class="text-bold"' : (isset($historicos[$h_id + 1]) ? '' : ' class="text-success"'); ?>>
							<td class="<?= $historico->fecha_desde !== $historico_sig->fecha_desde ? 'text-bold text-red' : ''; ?>"><?php echo $historico->fecha_desde; ?></td>
							<td class="<?= $historico->fecha_hasta !== $historico_sig->fecha_hasta ? 'text-bold text-red' : ''; ?>"><?php echo $historico->fecha_desde === $historico->fecha_hasta ? '' : $historico->fecha_hasta; ?></td>
							<td class="<?= $historico->dias !== $historico_sig->dias ? 'text-bold text-red' : ''; ?>"><?php echo $historico->dias; ?></td>
							<td class="<?= $historico->obligaciones !== $historico_sig->obligaciones ? 'text-bold text-red' : ''; ?>"><?php echo $historico->obligaciones; ?></td>
							<td class="text-xs"><?php echo $historico->estado; ?></td>
							<td class="text-xs"><?php echo $historico->usuario; ?></td>
							<td><?php echo $historico->audi_fecha; ?></td>
							<td><?php echo $acciones[$historico->audi_accion]; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>