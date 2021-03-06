<style>
	.btn-group {
    display: inline-table;
	}
	.cursada-si.active{
		color: green;
	}
	.cursada-no.active{
		color: red;
	}
	#tbl_notas .active{
		font-weight: bold;
	}
	.checkbox label:after, 
	.radio label:after {
    content: '';
    display: table;
    clear: both;
	}

	.checkbox .cr,
	.radio .cr {
    position: relative;
    display: inline-block;
    border: 1px solid #a9a9a9;
    border-radius: .25em;
    width: 1.3em;
    height: 1.3em;
    float: left;
    margin-right: .5em;
	}

	.radio .cr {
    border-radius: 50%;
	}

	.checkbox .cr .cr-icon,
	.radio .cr .cr-icon {
    position: absolute;
    font-size: .8em;
    line-height: 0;
    top: 50%;
    left: 20%;
	}

	.radio .cr .cr-icon {
    margin-left: 0.04em;
	}

	.checkbox label input[type="checkbox"],
	.radio label input[type="radio"] {
    display: none;
	}

	.checkbox label input[type="checkbox"] + .cr > .cr-icon,
	.radio label input[type="radio"] + .cr > .cr-icon {
    transform: scale(3) rotateZ(-20deg);
    opacity: 0;
    transition: all .1s ease-in;
	}

	.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
	.radio label input[type="radio"]:checked + .cr > .cr-icon {
    transform: scale(1.2) rotateZ(0deg);
    opacity: 1;
	}

	.checkbox label input[type="checkbox"]:disabled + .cr,
	.radio label input[type="radio"]:disabled + .cr {
    opacity: .5;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Evaluación
		</h1>
		<ol class="breadcrumb">
			<li><a href="cursada/listar/<?= $division->id; ?>">Cursadas</a></li>
			<li><a href="cursada/escritorio/<?= $cursada->id; ?>"><i class="fa fa-book"></i> <?= "$cursada->espacio_curricular $cursada->division"; ?></a></li>
			<li class="active"><i class="fa fa-pencil"></i> <?= $evaluacion->tema; ?></li>
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
		<?php if ($txt_btn != 'Ver'): ?>
			<?php if ($txt_btn === 'Eliminar'): ?>
				<?php $data_submit = array('class' => 'btn btn-danger pull-right', 'title' => $txt_btn); ?>
			<?php else: ?>
				<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
			<?php endif; ?>
			<?= form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_notas')); ?>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="cursada/escritorio/<?= $cursada->id; ?>">
							<i class="fa fa-book"></i> Cursada
						</a>
						<a class="btn btn-app btn-app-zetta <?= $class['ver']; ?>" href="evaluacion/ver/<?= $evaluacion->id; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?= $class['editar']; ?>" href="evaluacion/editar/<?= $evaluacion->id; ?>">
							<i class="fa fa-edit"></i> Cargar notas
						</a>
						<a class="btn btn-app btn-app-zetta <?= $class['eliminar']; ?>" href="evaluacion/eliminar/<?= $evaluacion->id; ?>">
							<i class="fa fa-ban"></i> Eliminar
						</a>
						<div class="dropdown pull-right">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<i class="fa fa-file"></i> Reportes
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu menu-right" aria-labelledby="dropdownMenu1">
								<li class="dropdown-header"><strong>Notas</strong></li>
								<li><a class="dropdown-item btn-warning" href="cursada/pdf_evaluacion/<?= "$evaluacion->id"; ?>" title="Imprimir PDF" target="_blank"><i class="fa fa-file-pdf-o"></i> Exportar PDF</a></li>
								<li><a class="dropdown-item btn-success" href="cursada/excel_evaluacion/<?= "$evaluacion->id"; ?>" title="Exportar excel"><i class="fa fa-file-excel-o"></i> Exportar Excel</a></li>
							</ul>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="form-group col-md-2">
								<?php echo $fields['ciclo_lectivo']['label']; ?>
								<?php echo $fields['ciclo_lectivo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['periodo']['label']; ?>
								<?php echo $fields['periodo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['evaluacion_tipo']['label']; ?>
								<?php echo $fields['evaluacion_tipo']['form']; ?>
							</div>
							<div class="form-group col-md-2">
								<?php echo $fields['fecha']['label']; ?>
								<?php echo $fields['fecha']['form']; ?>
							</div>
							<div class="form-group col-md-4">
								<?php echo $fields['tema']['label']; ?>
								<?php echo $fields['tema']['form']; ?>
							</div>
						</div>
						<table id="tbl_notas" class="table table-hover table-bordered table-condensed text-sm  dt-responsive" role="grid" >
							<thead>
								<tr>
									<th colspan="6" style="text-align: center;background-color: #d4d4d4;">
										Notas
									</th>
								</tr>
								<tr>
									<th style="width: 15%;text-align: center;">Documento</th>
									<th style="width: 30%;text-align: center;">Nombre del alumno</th>
									<th style="width: 10%;text-align: center;">Fecha desde</th>
									<th style="width: 10%;text-align: center;">Fecha hasta</th>
									<th style="width: 15%;text-align: center;">Nota</th>
									<th style="width: 20%;text-align: center;">Asistencia</th>
								</tr>
							</thead>
							<tbody>
								<?php $index = 0; ?>
								<?php if (!empty($alumnos_notas)): ?>
									<?php foreach ($alumnos_notas as $alumno): ?>
										<?php $index++; ?>
										<tr>
											<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
											<td><?= $alumno->persona; ?></td>
											<td><?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?></td>
											<td><?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?></td>
											<td align="center">
												<div class="input-group input-group-sm">
													<?php if ($alumno->calificacion_id === '1'): ?>
														<select style="width: auto;text-align-last:center;" name="nota[<?= (empty($alumno->id)) ? $alumno->alumno_division_id : $alumno->id; ?>]" class="form-control notas-combo" tabindex="<?= $index; ?>" <?= ($txt_btn !== 'Editar' || (!empty($alumno->asistencia) && $alumno->asistencia === "Ausente")) ? "disabled" : ""; ?>>
															<option value="" selected="selected"></option>
															<option value="3" <?= (!empty($alumno->nota_evaluacion) && round($alumno->nota_evaluacion) == '3') ? "selected='selected'" : ''; ?>>V</option>
															<option value="2" <?= (!empty($alumno->nota_evaluacion) && round($alumno->nota_evaluacion) == '2') ? "selected='selected'" : ''; ?>>A</option>
															<option value="1" <?= (!empty($alumno->nota_evaluacion) && round($alumno->nota_evaluacion) == '1') ? "selected='selected'" : ''; ?>>R</option>
														</select>
													<?php else: ?>
														<input type="text" class="form-control notas input-notas" value="<?= !empty($alumno->nota_evaluacion) ? $alumno->nota_evaluacion : ''; ?>" name="nota[<?= (empty($alumno->id)) ? $alumno->alumno_division_id : $alumno->id; ?>]" tabindex="<?= $index; ?>" <?= ($txt_btn !== 'Editar' || (!empty($alumno->asistencia) && $alumno->asistencia === "Ausente")) ? "readonly" : ""; ?>>
													<?php endif; ?>
												</div>
											</td>
											<td style="text-align: center;">
												<?php if ($txt_btn === 'Editar'): ?>
													<div class="btn-group btn-group-xs inasistencia" data-toggle="buttons">
														<label class="btn btn-default cursada-si <?= (!empty($alumno->asistencia) ? ($alumno->asistencia === "Presente") ? "active" : "" : "active") ?>">
															<input type="radio" name="asistencia[<?= (empty($alumno->id)) ? $alumno->alumno_division_id : $alumno->id; ?>]" value="Presente" <?= (!empty($alumno->asistencia) ? ($alumno->asistencia === "Presente") ? "checked" : "" : "checked") ?>> Presente
														</label>
														<label class="btn btn-default cursada-no <?= (!empty($alumno->asistencia) ? ($alumno->asistencia === "Ausente") ? "active" : "" : "") ?>">
															<input type="radio" name="asistencia[<?= (empty($alumno->id)) ? $alumno->alumno_division_id : $alumno->id; ?>]" value="Ausente" <?= (!empty($alumno->asistencia) ? ($alumno->asistencia === "Ausente") ? "checked" : "" : "") ?>> Ausente
														</label>
													</div>
												<?php else: ?>	
													<b><?= $alumno->asistencia; ?></b>
												<?php endif; ?>
											</td>
										</tr>
										<?php if ($txt_btn != 'Ver'): ?>
											<?php if (empty($alumno->id)): ?>
												<?= form_hidden("cursada_nota_ids[$alumno->alumno_division_id]", $alumno->cursada_nota_id); ?>
											<?php else: ?>
												<?= form_hidden("cursada_nota_ids[$alumno->id]", $alumno->cursada_nota_id); ?>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<?php if ($txt_btn != 'Ver'): ?>
							<a class="btn btn-default" href="cursada/escritorio/<?= $cursada->id; ?>" title="Cancelar">Cancelar</a>
							<?= (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
							<?= form_hidden('evaluacion_id', $evaluacion->id); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php if ($txt_btn != 'Ver'): ?>
			<?= form_close(); ?>
		<?php endif; ?>
	</section>
</div>
<script>
	Inputmask.extendAliases({
		'nota': {
			alias: "numeric",
			placeholder: '',
			allowPlus: false,
			allowMinus: false,
			insertMode: false,
			radixPoint: ',',
			digits: 2,
			autoUnmask: true,
			removeMaskOnSubmit: true,
			onUnMask: function(value) {
				return value.replace('.', '').replace(',', '.');
			}
		}
	}
	);
	$(document).ready(function() {
		$('input.input-notas').change(function() {
			var input = parseFloat($(this).val());
			if (input > 10) {
				$(this).val(10);
			}
			if (input < 1) {
				$(this).val(1);
			}
		});
		$('input.input-notas').inputmask('nota');
		$('input[type="radio"]').change(function() {
			var estado = $(this).val();
			if (estado === 'Ausente') {
				if ($(this).closest('tr').find('select').hasClass("notas-combo")) {
					$(this).closest('tr').find('select').val('');
					$(this).closest('tr').find('select').prop('disabled', true);
				} else {
					var fila = $(this).closest('tr');
					fila.find('.input-notas').val('').prop('readonly', true);
				}
			} else {
				if ($(this).closest('tr').find('select').hasClass("notas-combo")) {
					$(this).closest('tr').find('select').prop('disabled', false);
				} else {
					var fila = $(this).closest('tr');
					fila.find('.input-notas').prop('readonly', false);
				}
			}
		});
	});
</script>