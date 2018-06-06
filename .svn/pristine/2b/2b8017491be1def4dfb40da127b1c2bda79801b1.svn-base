<?php if (!empty($aprender_operativos)): ?>
<div class="box box-primary collapsed-box">
	<div class="box-header with-border">
		<h3 class="box-title">Operativo Aprender - 2017</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>Operativo</th>
				<th>Divisiones</th>
				<th>Aplicadores</th>
				<th>Fecha de Cierre</th>
				<th>
				</th>
			</tr>
			<?php foreach ($aprender_operativos as $operativo): ?>
			<tr>
				<td><?php echo $operativo->operativo_tipo; ?></td>
				<td><?php echo "$operativo->divisiones ($operativo->divisiones_d)"; ?></td>
				<td><?php echo isset($aprender_aplicadores[$operativo->operativo_tipo_id]) ? count($aprender_aplicadores[$operativo->operativo_tipo_id]) : 0; ?></td>
				<td><?php echo empty($operativo->fecha_cierre) ? '-- Sin cierre --' : (new DateTime($operativo->fecha_cierre))->format('d/m/Y H:i:s'); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="box-footer">
		<?php if ($administrar): ?>
		<a class="btn btn-primary" href="aprender/aprender_operativo/ver/<?php echo $escuela->id; ?>">Administrar</a>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>