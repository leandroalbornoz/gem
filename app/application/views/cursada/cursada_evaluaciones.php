<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Reporte de notas de evaluaciones
		</h1>
		<ol class="breadcrumb">
			<li><a href="cursada/listar/<?= $division->id; ?>">Cursadas</a></li>
			<li><a href="cursada/escritorio/<?= $cursada->id; ?>"><i class="fa fa-book"></i> <?= "$cursada->espacio_curricular $cursada->division"; ?></a></li>
			<li class="active"><i class="fa fa-pencil"></i> Reporte de notas</li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?= $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?= $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<a class="btn btn-app btn-app-zetta" href="cursada/escritorio/<?= $cursada->id; ?>">
							<i class="fa fa-book"></i> Cursada
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="cursada/evaluaciones/<?= $cursada->id; ?>">
							<i class="fa fa-book"></i> Evaluaciones
						</a>
						<div class="dropdown pull-right">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<i class="fa fa-file"></i> Reportes
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu menu-right" aria-labelledby="dropdownMenu1">
								<li class="dropdown-header"><strong>Notas</strong></li>
								<li><a class="dropdown-item btn-warning" href="cursada/pdf_resumen_evaluaciones/<?= $cursada->id; ?>" title="Imprimir PDF" target="_blank"><i class="fa fa-file-pdf-o"></i> Exportar PDF</a></li>
								<li><a class="dropdown-item btn-success" href="cursada/excel_resumen_evaluaciones/<?= $cursada->id; ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i> Exportar Excel</a></li>
							</ul>
						</div>
					</div>
					<div class="box-body">
						<?php $array_notas = array('1' => array('R', 'red'), '2' => array('A', 'yellow'), '3' => array('V', 'green')); ?>
						<?php if (!empty($evaluaciones_periodo)): ?>
							<?php foreach ($evaluaciones_periodo as $calendario_periodo => $evaluaciones): ?>
								<h4><b><?php echo $calendario_periodo; ?></b></h4>
								<table class="table-notas table table-hover table-bordered table-condensed text-sm" role="grid" >
									<thead>
										<tr>
											<th style="text-align: center; white-space: nowrap;">Documento</th>
											<th style="text-align: center; white-space: nowrap;">Alumno</th>
											<?php foreach ($evaluaciones as $evaluacion): ?>
												<th style="width:50px;">
													<?php
													$words = explode(' ', $evaluacion->evaluacion_tipo);
													$acronym = '';
													foreach ($words as $w) {
														$acronym .= $w[0];
													}
													?>
													<?= '<span title="' . $evaluacion->evaluacion_tipo . '-' . $evaluacion->tema . '">' . (new DateTime($evaluacion->fecha))->format('d/m') . "<br>$acronym</span>"; ?>
												</th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($alumnos as $alumno): ?>
											<tr>
												<td style=" white-space: nowrap;"><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
												<td style=" white-space: nowrap;"><?= $alumno->persona; ?></td>
												<?php foreach ($evaluaciones as $evaluacion): ?>
													<?php if (isset($evaluacion->notas[$alumno->id])): ?>
														<?php if ($evaluacion->notas[$alumno->id]->asistencia === 'Ausente'): ?>
															<td align="center">
																<span class="badge bg-red">Aus</span>
															</td>
														<?php else: ?>
															<?php if ($cursada->calificacion_id === '1'): ?>
																<td class="text-right" style="text-align: center;">
																	<span class="badge bg-<?= $array_notas[round($evaluacion->notas[$alumno->id]->nota)][1]; ?>"><?= $array_notas[round($evaluacion->notas[$alumno->id]->nota)][0]; ?></span>
																</td>
															<?php else: ?>
																<td class="text-right" style="text-align: center;">
																	<span class="badge bg-green"><?= number_format($evaluacion->notas[$alumno->id]->nota, 2, ',', '.'); ?></span>
																</td>
															<?php endif; ?>
														<?php endif; ?>
													<?php else: ?>
														<td></td>
													<?php endif; ?>
												<?php endforeach; ?>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table><br>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="cursada/escritorio/<?= $cursada->id; ?>" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('.table-notas').DataTable({
			scrollY: "400px",
			scrollX: true,
			scrollCollapse: true,
			bPaginate: false,
			bSort: false,
			dom: 't',
			paging: false,
			fixedColumns: {
				leftColumns: (1, 2)
			}
		});
	});
</script>