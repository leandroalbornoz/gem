
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"> Alumnos con derivación Hospitalaria/Domiciliaria</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>

	<div class="box-body">
		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Total de alumnos:</td>
					<td><span class="badge bg-blue"><?php echo isset($cant_alumnos) ? "$cant_alumnos->cantidad" : ""; ?></span></td>
				</tr>
				<tr>
					<td>Alumnos con alta:</td>
					<td><span class="badge bg-green"><?php echo isset($cant_alumnos_alta) ? "$cant_alumnos_alta->cantidad" : ""; ?></span></td>
				</tr>
				<tr>
					<td>Alumnos retirados:</td>
					<td><span class="badge bg-red"><?php echo isset($cant_alumnos_baja) ? "$cant_alumnos_baja->cantidad" : ""; ?></span></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="box-footer">
		<a class="btn btn-primary" href="alumno_derivacion/listar/<?php echo $linea->id; ?>">
			<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
		</a>
	</div>
</div>
<?php if ($this->rol->entidad == 'Educación Domiciliaria y Hospitalaria'): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"> Cargos con condición Hospitalaria/Domiciliaria</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>

		<div class="box-body">
			<table class="table table-striped">
				<tbody>
					<tr>
						<td>Total de cargos:</td>
						<td><span class="badge bg-blue"><?php echo isset($cant_cargos_d_h) ? "$cant_cargos_d_h->cantidad" : ""; ?></span></td>
					</tr>
					<tr>
						<td>Total de escuelas con cargos:</td>
						<td><span class="badge bg-green"><?php echo isset($cant_escuelas_d_h) ? "$cant_escuelas_d_h->cantidad" : ""; ?></span></td>
					</tr>

			</table>
		</div>
		<div class="box-footer">
			<a class="btn btn-primary" href="reportes/cargos_d_h_listar/<?php echo $linea->id; ?>">
				<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
			</a>
		</div>
	</div>
<?php endif; ?>
