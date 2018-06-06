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
			Evaluaciones de <?php echo "$alumno->apellido, $alumno->nombre"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="cursada/listar/<?= $division->id; ?>">Cursadas</a></li>
			<li><a href="cursada/escritorio/<?= $cursada->id; ?>"><i class="fa fa-book"></i> <?= "$cursada->espacio_curricular $cursada->division"; ?></a></li>
			<li class="active"><i class="fa fa-pencil"></i>Cargar notas</li>
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
		<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
		<?= form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_notas')); ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-weight: bold;" >Evaluaciones de <?php echo $calendario_periodo->periodo . "° " . $calendario_periodo->nombre_periodo; ?></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table id="tbl_ver_evaluaciones" class="table table-hover table-bordered table-condensed text-sm  dt-responsive" role="grid" >
							<thead>
								<tr>
									<th style="width: 15%;">Tipo de evaluación</th>
									<th style="width: 40%;">Tema</th>
									<th style="width: 10%;">Fecha</th>
									<th style="width: 15%;">Periodo</th>
									<th style="width: 10%;">Nota</th>
									<th style="width: 10%;">Asistencia</th>
								</tr>
							</thead>
							<tbody>
								<?php $index = 0; ?>
								<?php if (!empty($evaluaciones)): ?>
									<?php foreach ($evaluaciones as $evaluacion): ?>
										<?php $index++; ?>
										<tr>
											<td><?= "$evaluacion->evaluacion_tipo"; ?></td>
											<td><?= "$evaluacion->tema"; ?></td>
											<td><?= empty($evaluacion->fecha) ? '' : (new DateTime($evaluacion->fecha))->format('d/m/Y'); ?></td>
											<td><?= empty($evaluacion->nombre_periodo) ? '' : $evaluacion->nombre_periodo; ?></td>
											<td align="center">
												<div class="input-group input-group-sm">
													<?php if ($evaluacion->calificacion_id === '1'): ?>
														<select style="width: auto;text-align-last:center;" name="nota[<?= $evaluacion->id; ?>]" class="form-control notas-combo" tabindex="<?= $index; ?>" <?= ($txt_btn !== 'Editar' || (!empty($evaluacion->asistencia) && $evaluacion->asistencia === "Ausente")) ? "disabled" : ""; ?>>
															<option value="" selected="selected"></option>
															<option value="3" <?= (!empty($evaluacion->nota) && round($evaluacion->nota) == '3') ? "selected='selected'" : ''; ?>>V</option>
															<option value="2" <?= (!empty($evaluacion->nota) && round($evaluacion->nota) == '2') ? "selected='selected'" : ''; ?>>A</option>
															<option value="1" <?= (!empty($evaluacion->nota) && round($evaluacion->nota) == '1') ? "selected='selected'" : ''; ?>>R</option>
														</select>
													<?php else: ?>
														<input type="text" class="form-control notas input-notas" value="<?= !empty($evaluacion->nota) ? $evaluacion->nota : ''; ?>" name="nota[<?= $evaluacion->id; ?>]" tabindex="<?= $index; ?>" <?= ($txt_btn !== 'Editar' || (!empty($evaluacion->asistencia) && $evaluacion->asistencia === "Ausente")) ? "readonly" : ""; ?>>
													<?php endif; ?>
												</div>
											</td>
											<td>
												<div class="btn-group btn-group-xs inasistencia" data-toggle="buttons">
													<label class="btn btn-default cursada-si <?= (!empty($evaluacion->asistencia) ? ($evaluacion->asistencia === "Presente") ? "active" : "" : "active") ?>">
														<input type="radio" name="asistencia[<?= $evaluacion->id; ?>]" value="Presente" <?= (!empty($evaluacion->asistencia) ? ($evaluacion->asistencia === "Presente") ? "checked" : "" : "checked") ?>> Presente
													</label>
													<label class="btn btn-default cursada-no <?= (!empty($evaluacion->asistencia) ? ($evaluacion->asistencia === "Ausente") ? "active" : "" : "") ?>">
														<input type="radio" name="asistencia[<?= $evaluacion->id; ?>]" value="Ausente" <?= (!empty($evaluacion->asistencia) ? ($evaluacion->asistencia === "Ausente") ? "checked" : "" : "") ?>> Ausente
													</label>
												</div>
											</td>
										</tr>
										<?= form_hidden("cursada_nota_ids[$evaluacion->id]", $evaluacion->cursada_nota_id); ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="cursada/escritorio/<?= $cursada->id; ?>" title="Cancelar">Cancelar</a>
						<?= (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?= form_hidden('alumno_division_id', $alumno_division->id) ?>
					</div>
				</div>
			</div>
		</div>
		<?= form_close(); ?>
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
				var fila = $(this).closest('tr');
				fila.find('.input-notas').val('').prop('readonly', true);
			} else {
				var fila = $(this).closest('tr');
				fila.find('.input-notas').prop('readonly', false);
			}
		});
	});
</script>