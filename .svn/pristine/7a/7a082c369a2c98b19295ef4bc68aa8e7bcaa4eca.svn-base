<style>
	.input-group-sm>.form-control, .input-group-sm>.input-group-addon, .input-group-sm>.input-group-btn>.btn {
    height: 25px;
		padding: 3px 7px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Agregar Cursadas
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
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_cursadas')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="division/ver/<?php echo $division->id; ?>">
							<i class="fa fa-search"></i> Ver División
						</a>
						<a class="btn btn-app btn-app-zetta" href="cursada/listar/<?php echo $division->id; ?>">
							<i class="fa fa-book"></i> Cursadas
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>">
							<i class="fa fa-plus"></i> Agregar
						</a>
						<h5><b>Seleccione de la lista los espacios curriculares que desee agregar, cargando para cada uno la carga horaria semanal que corresponda.</b></h5>
						<div class="row">
							<div class="col-sm-12">
								<table id="espacio_curricular_table" class="table table-hover table-bordered table-condensed" role="grid">
									<thead>
										<tr>
											<th colspan="7" class="text-center bg-gray">
												<h5>
													<b>Espacios Curriculares</b>
												</h5>
											</th>
										</tr>
										<tr>
											<th style="">Materia</th>
											<th style="min-width:80px; width:120px;">Carga horaria</th>
											<?php if ($escuela->nivel_id === '7'): ?>
												<th style="">Cuatrimestre</th>
											<?php endif; ?>
											<th style="text-align: center;">Seleccionar</th>
										</tr>
									</thead>
									<tbody>
										<?php $fila = 0; ?>
										<?php if (!empty($espacios_curriculares)): ?>
											<?php foreach ($espacios_curriculares as $ec_id => $ec_materia): ?>
												<?php $fila++; ?>
												<tr>
													<td><?php echo $ec_materia; ?></td>
													<td align="center">
														<div class="input-group input-group-sm">
															<input type="number" class="form-control carga_horaria" min="0" value="<?php echo (isset($carga_horaria[$ec_id]) ? "$carga_horaria[$ec_id]" : "0"); ?>" step="1" name="carga_horaria[<?php echo $fila; ?>]" required>
														</div>
													</td>
													<?php if ($escuela->nivel_id === '7'): ?>
														<td align="center">
															<div class="btn-group btn-group-xs inasistencia" data-toggle="buttons">
																<label class="btn btn-default cursada-si <?php echo ($cuatrimestre[$ec_id] === '0') ? "active" : ""; ?>">
																	<input type="radio" name="cuatrimestre[<?php echo $fila; ?>]" value="0" <?php echo ($cuatrimestre[$ec_id] === '0') ? "checked" : ""; ?>> Anual
																</label>
																<label class="btn btn-default cursada-si <?php echo ($cuatrimestre[$ec_id] === '1') ? "active" : ""; ?>">
																	<input type="radio" name="cuatrimestre[<?php echo $fila; ?>]" value="1" <?php echo ($cuatrimestre[$ec_id] === '1') ? "checked" : ""; ?>> 1°
																</label>
																<label class="btn btn-default cursada-si <?php echo ($cuatrimestre[$ec_id] === '2') ? "active" : ""; ?>">
																	<input type="radio" name="cuatrimestre[<?php echo $fila; ?>]" value="2" <?php echo ($cuatrimestre[$ec_id] === '2') ? "checked" : ""; ?>> 2°
																</label>
															</div>
														</td>
													<?php endif; ?>
													<td align="center">
														<input type="checkbox" class="cursada_check" name="cursada[<?php echo $fila; ?>]" value="<?php echo $ec_id; ?>">
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="6" style="text-align: center;">-- No hay espacios curriculares en este curso, por favor verifique la carrera --</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
						<input type="hidden" value="<?php echo $fila; ?>" id="fila">
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="cursada/listar/<?php echo $division->id; ?>" title="Cancelar">Cancelar</a>
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $cursada->id) : ''; ?>
						<div class="row pull-right hidden" style="padding-right: 5%;"  id="cartel">
							<div class="bg-red text-bold" style="border-radius: 2px;"><h5>Ingrese una carga horaria valida.</h5></div>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var arr_cursadas = <?php echo json_encode($cursadas); ?>;
	function verificar_cursadas(event) {
		$('input[type=checkbox]:checked').each(function() {
			$(this).closest('tr').css('color', '');
		});
		if ($('input[type=checkbox]:checked').length === 0) {
			event.preventDefault();
		}
		var arr_id = new Array();
		var arr_grupo = new Array();
		$('input[type=checkbox]:checked').each(function() {
			arr_id.push($(this).val());
			arr_grupo.push($(this).closest('tr').find('.cursada_grupo').val());
		});
		for (var i = 0; i < arr_id.length; i++) {
			for (var j = i + 1; j < arr_id.length; j++) {
				if (arr_id[i] === arr_id[j]) {
					if (arr_grupo[i] === arr_grupo[j]) {
						($('[value=' + arr_id[j] + ']')).closest('tr').css('color', 'red');
						$('#cartel').removeClass('hidden');
						event.preventDefault();
					}
				}
			}
		}

		$('input[type=checkbox]:checked').each(function() {
			var id = $(this).val();
			var carga_horaria = $(this).closest('tr').find('.carga_horaria').val();
			var grupo = $(this).closest('tr').find('.cursada_grupo').val();
			if (grupo === '') {
				grupo = null;
			}
			if (carga_horaria <= 0) {
				$('#cartel').removeClass('hidden');
				$(this).closest('tr').css('color', 'red');
				event.preventDefault();
			}
		});
	}

	$(document).ready(function() {
		$('input.cursada_check').prop('checked', true);
		$('#form_cursadas').on('submit', verificar_cursadas);
	});
</script>