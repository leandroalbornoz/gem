
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"> Alumnos con apoyo de modalidad especial</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Total de alumnos que reciben apoyo:</td>
					<td><span class="badge bg-blue"><?php echo $totales_apoyo->alumnos_apoyo; ?></span></td>
				</tr>
				<tr>
					<td>Total de escuelas que brindan apoyo:</td>
					<td><span class="badge bg-green"><?php echo $totales_apoyo->escuelas_apoyo; ?></span></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="box-footer">
		<a class="btn btn-primary" href="alumno_apoyo_especial/listar">
			<i class="fa fa-cogs" id="btn-carreras"></i> Administrar
		</a>
	</div>
</div>