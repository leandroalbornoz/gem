<style>
	.btn-group {
    display: inline-table;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Cursadas <?php echo "$division->curso $division->division"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?= $escuela->id ?>"><?= "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="division/listar/<?= $escuela->id ?>">Cursos y Divisiones</a></li>
			<?php if (!empty($division)): ?>
				<li><a href="division/ver/<?= $division->id ?>"><?php echo "$division->curso $division->division"; ?></a></li>
			<?php endif; ?>
			<li><a href="<?php echo $controlador; ?>/listar/<?php echo $division->id; ?>">Cursadas</a></li>
			<li class="active"><?php echo ucfirst($metodo); ?></li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<?php if ($edicion === TRUE): ?>
							<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo $division->id; ?>">
								<i class="fa fa-search"></i> Ver División
							</a>	
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="cursada/listar/<?php echo $division->id; ?>">
							<i class="fa fa-book"></i> Cursadas
						</a>
						<?php if ($edicion === TRUE): ?>
							<?php if (empty($cursadas_division)): ?>
								<a class="btn bg-blue btn-app btn-app-zetta" href="cursada/agregar/<?php echo $division->id; ?>">
									<i class="fa fa-plus"></i> Agregar
								</a>
							<?php else: ?>
								<a class="btn bg-blue btn-app btn-app-zetta"  data-remote="false" data-toggle="modal" data-target="#remote_modal" href="cursada/modal_agregar/<?php echo $division->id; ?>">
									<i class="fa fa-plus"></i> Agregar
								</a>
							<?php endif; ?>
						<?php endif; ?>
						<div class="box-tools pull-right">
							<a class="btn btn-danger" title="Manual cursadas" href="uploads/ayuda/cursadas/manual.pdf" target="_blank" style=""><i class="fa fa-file-pdf-o"></i> Manual cursadas</a>
						</div>
						<table id="tbl_ver_cargos" class="table table-hover table-bordered table-condensed text-sm  dt-responsive" role="grid" >
							<thead>
								<tr>
									<th colspan="11" class="text-center bg-gray">Cursadas de la división</th>
								</tr>
								<tr>
									<th style="width: 3%;" data-orderable="false"></th>
									<th style="width: 5%;">División</th>
									<th style="width: 19%;text-align: center;">Materia</th>
									<th style="width: 2%;">Carga horaria</th>
									<th style="width: 2%;text-align: center;">Alumnos</th>
									<th style="width: 15%;text-align: center;">Grupo</th>
									<th style="width: 5%;text-align: center;">Desde</th>
									<th style="width: 20%;text-align: center;">Cargo/s</th>
									<th style="width: 8%;text-align: center;">Hs cubiertas</th>
									<?php if ($escuela->nivel_id === '7'): ?>
										<th style="width: 2%;text-align: center;">Cuatr.</th>
									<?php endif; ?>
									<th style="width: <?php echo ($escuela->nivel_id === '7') ? "14%" : "10%"; ?>; text-align: center;"></th>
									<th class="none"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($cursadas_division as $ec_id => $cursada): ?>
									<tr>
										<td><span style="display:none;"><?php echo $cursada->id; ?></span></td>
										<td><?php echo $cursada->division; ?></td>
										<td><?php echo $cursada->espacio_curricular; ?></td>
										<td><?php echo $cursada->carga_horaria; ?></td>
										<td><?php echo $cursada->alumnos; ?></td>
										<td><?php echo $cursada->grupo; ?></td>
										<td><?php echo (new DateTime($cursada->fecha_desde))->format('d/m/Y') ?></td>
										<?php if ($edicion === TRUE): ?>
											<td style="text-align: center;">
												<?php if (empty($cursada->cargo_cursada)): ?>
													<?php if (empty($cursada->carga_horaria)): ?>
														<div class="bg-red text-bold" style="border-radius: 2px; text-align: center;"><h5>Sin carga horaria<br>Edite la cursada</h5></div>
													<?php else: ?>
														<a href="cursada/modal_agregar_cargo/<?php echo $cursada->id; ?>" class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Agregar cargo"><i class="fa fa-plus"></i>&nbsp;Agregar cargo</a>
													<?php endif; ?>
												<?php else: ?>
													<?php echo $cursada->personas_cargo; ?><br>
													<?php if ($cursada->carga_horaria_cargo < $cursada->carga_horaria): ?>
														<a href="cursada/modal_agregar_cargo/<?php echo $cursada->id; ?>" class="btn btn-xs btn-success" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Agregar cargo"><i class="fa fa-plus"></i></a>
													<?php endif; ?>
												<?php endif; ?>
											</td>
										<?php else: ?>
											<td>
												<?php echo $cursada->personas_cargo; ?><br>
											</td>
										<?php endif; ?>
										<td style="text-align: center;">
											<?php if (empty($cursada->cargo_cursada)): ?>
												Sin horas cubiertas
											<?php else: ?>
												<?php echo $cursada->carga_horaria_cargo; ?>
											<?php endif; ?>
										</td>
										<?php if ($escuela->nivel_id === '7'): ?>
											<td style="text-align: center;">
												<?php echo ($cursada->cuatrimestre === '0') ? "Anual" : (($cursada->cuatrimestre === '1') ? "1°" : "2°"); ?>
											</td>
										<?php endif; ?>
										<td style="text-align: center;">
											<div class="btn-group" role="group">
												<a class="btn btn-xs btn-primary" href="cursada/escritorio/<?php echo $cursada->id; ?>" title="Escritorio">&nbsp;<i class="fa fa-desktop"></i>&nbsp;</a>
												<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li><a class="dropdown-item btn-default" href="cursada/modal_grupos/<?php echo $cursada->id; ?>" title="Dividir en Grupos" data-remote="false" data-toggle="modal" data-target="#remote_modal">&nbsp;<i class="fa fa-fw fa-clone"></i> Dividir en Grupos</a></li>
													<li><a class="dropdown-item btn-default" href="cursada/modal_ver/<?php echo $cursada->id; ?>" title="Ver" data-remote="false" data-toggle="modal" data-target="#remote_modal">&nbsp;<i class="fa fa-fw fa-search"></i> Ver</a></li>
													<li><a class="dropdown-item btn-warning" href="cursada/modal_editar/<?php echo $cursada->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar"><i class="fa fa-fw fa-pencil"></i> Editar</a></li>
													<li><a class="dropdown-item btn-danger" href="cursada/modal_eliminar/<?php echo $cursada->id; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Eliminar"><i class="fa fa-fw fa-remove"></i> Eliminar</a></li>
												</ul>
											</div>
										</td>
										<td></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var table_cargos_busqueda;
	$(document).ready(function() {
		table_cargos_busqueda = $('#tbl_ver_cargos').DataTable({dom: 't', autoWidth: false, paging: false, language: {url: 'plugins/datatables/spanish.json'}, order: [[2, 'asc'], [5, 'asc']], responsive: {details: {renderer: cargo_table_detalles}}});
	});
	function cargo_table_detalles(api, rowIdx, columns) {
		var html = $.map(columns, function(col, i) {
			return col.hidden ?
							'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
							'<td>' + col.data + '</td>' +
							'</tr>' :
							'';
		}).join('');
		var cursada_id = $(api.row(rowIdx).data()[0]).text();
		html = $('<table class="table table-condensed table-bordered table-hover"/>').append(html).prop('outerHTML');
		html += '<div id="cargo_table_detalle_' + rowIdx + '"><i class="fa fa-spin fa-refresh"></i>Cargando cargos...</div>';
		$.ajax({
			type: 'GET',
			url: 'ajax/get_cargos_cursada?',
			data: 'cursada_id=' + cursada_id,
			dataType: 'json',
			success: function(result) {
				$('#cargo_table_detalle_' + rowIdx).html(format(cursada_id, result.cargos));
			}
		});
		return html;
	}
	function format(cursada_id, cargos) {
		if (cargos === undefined) {
			return "No hay cargos asignados a la cursada";
		}
		var len = cargos.length;
		var html = '<table class="table table-condensed table-bordered table-hover" style="margin-bottom: 0;">\n\
<thead>\n\<tr class="bg-gray">\n\<th class="text-center" colspan="8">Cargos de esta cursada</th>\n\</tr>\n\<tr>\n\<th></th>\n\<th>Condición</th>\n\<th>Curso</th>\n\<th>Div</th>\n\<th>Regimen/materia</th>\n\<th>Persona</th>\n\<th>Hs Cubiertas</th>\n\</thead>\n\<tbody>';
		for (var i = 0; i < len; i++) {
			html += '<tr><td style="text-align: center;"><a href="cargo/ver/' + cargos[i].cargo_id + '" target="_blank"><i class="fa fa-search"></i></a></td>' +
							'<td>' + (!cargos[i].condicion_cargo ? '' : cargos[i].condicion_cargo) + '</td>' +
							'<td>' + (!cargos[i].curso ? '' : cargos[i].curso) + '</td>' +
							'<td>' + (!cargos[i].division ? '' : cargos[i].division) + '</td>' +
							'<td>' + (!cargos[i].regimen_materia ? '' : cargos[i].regimen_materia) + '</td>' +
							'<td>' + (!cargos[i].persona ? '' : cargos[i].persona) + '</td>' +
							'<td>' + (!cargos[i].carga_horaria ? '' : cargos[i].carga_horaria) + '</td>' +
							'<td><a href="cursada/modal_editar_cargo/' + cargos[i].id + '" class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Editar carga horaria"><i class="fa fa-edit"></i></a>&nbsp;<a href="cursada/modal_eliminar_cargo/' + cargos[i].id + '" class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" title="Eliminar cargo"><i class="fa fa-remove"></i></a></td>\n\</tr>';
		}
		return html + '</tbody></table>';
	}
</script>