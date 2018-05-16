<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Detalle de suplementaria
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="suplementarias/suple/ver/<?php echo $suple_id ?>">Suplementarias</a></li>
			<li class="active"><?php echo ucfirst($metodo); ?> persona</li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_suple_persona')); ?>
					<div class="box-body">
						<?php if ($txt_btn == 'Agregar'): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="suplementarias/suple_persona/agregar">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="suplementarias/suple_persona/ver/<?php echo (!empty($suple_persona->id)) ? $suple_persona->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="suplementarias/suple_persona/editar/<?php echo (!empty($suple_persona->id)) ? $suple_persona->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="suplementarias/suple_persona/eliminar/<?php echo (!empty($suple_persona->id)) ? $suple_persona->id : ''; ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<?php if ($txt_btn == 'Agregar'): ?>
							<div class="row">
								<div class="form-group col-sm-3">
									<?php echo $fields_s['p_documento_tipo']['label']; ?>
									<?php echo $fields_s['p_documento_tipo']['form']; ?>
								</div>
								<div class="form-group col-sm-3">
									<?php echo $fields_s['p_documento']['label']; ?>
									<?php echo $fields_s['p_documento']['form']; ?>
								</div>
								<div class="form-group col-sm-3">
									<label>&nbsp;</label><br/>
									<button class="btn btn-default" id="btn-search" type="button">
										<i class="fa fa-search"></i>
									</button>
									<button class="btn btn-default" id="btn-clear" type="button">
										<i class="fa fa-times"></i>
									</button>
									<div class="alert alert-danger" role="alert" style="display: none; margin-top:10px" data-dismiss="alert">
										No se encontraron resultados.
									</div>
								</div>
							</div>
							<div class="row">							
								<div class="form-group col-sm-6">
									<?php echo $fields_s['p_apellido']['label']; ?>
									<?php echo $fields_s['p_apellido']['form']; ?>
								</div>
								<div class="form-group col-sm-6">
									<?php echo $fields_s['p_nombre']['label']; ?>
									<?php echo $fields_s['p_nombre']['form']; ?>
								</div>
							</div>

							<div style="margin-top: 1%;">
								<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="table_suples_p" style="width:100% !important">
									<thead>
										<tr style="background-color: #428bca" >
											<th style="text-align: center;" colspan="10">Suplementarias existentes</th>
										</tr>
										<tr>
											<th>Legajo</th>
											<th>Servicio</th>
											<th>Nombre</th>
											<th>Estado</th>
											<th>Periodo</th>
											<th>Importe</th>
											<th>Fecha desde</th>
											<th>Fecha hasta</th>
										</tr>
									</thead>
									<tbody name="suples_p" id="suples_p">
									</tbody>
								</table>
							</div>
							<div style="margin-top: 1%;">
								<table class="table table-hover table-bordered table-condensed table-responsive dt-responsive dataTable no-footer dtr-inline text-sm" role="grid" id="table_servicios" style="width:100% !important">
									<thead>
										<tr style="background-color: #428bca" >
											<th style="text-align: center;" colspan="10">Servicios</th>
										</tr>
										<tr>
											<th>Seleccionar</th>
											<th>Liquidación</th>
											<th>Cod.Régimen</th>
											<th>Régimen</th>
											<th>Carga horaria</th>
											<th>Escuela/Área</th>
											<th>Fecha alta</th>
											<th>Fecha baja</th>
										</tr>
									</thead>
									<tbody name="servicios" id="servicios">
									</tbody>
								</table>
							</div>
						<?php else: ?>
							<div class="row">							
								<div class="form-group col-md-12">
									<?php echo $fields['persona']['label']; ?>
									<?php echo $fields['persona']['form']; ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="row">							
							<div class="form-group col-md-3">
								<?php echo $fields['periodo']['label']; ?>
								<?php echo $fields['periodo']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['importe']['label']; ?>
								<?php echo $fields['importe']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['estado']['label']; ?>
								<?php echo $fields['estado']['form']; ?>
							</div>
						</div>
						<div class="row">							
							<div class="form-group col-md-12">
								<?php echo $fields['observaciones']['label']; ?>
								<?php echo $fields['observaciones']['form']; ?>
							</div>
						</div>
						<?php if (!empty($conceptos_form)): ?>
							<div data-step="7" data-position="top">
								<?php foreach ($conceptos_form as $tipo => $conceptos_tipo): ?>
									<div class="row" id='tipo_<?= $tipo; ?>'>
										<div class="col-sm-12">
											<div class="col-sm-12 title_tipo_concepto">
												Conceptos <?php echo $descripcion_tipos[$tipo]; ?>
											</div>
										</div>
										<?php if (!empty($conceptos_tipo['i'])): ?>
											<?php foreach ($conceptos_tipo['i'] as $concepto): ?>
												<div class="form-group col-lg-2 col-md-3 col-sm-4">
													<?php echo $concepto['label']; ?>
													<?php echo $concepto['form']; ?>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
										<?php if (!empty($conceptos_tipo['o'])): ?>
											<?php foreach ($conceptos_tipo['o'] as $concepto): ?>
												<div class="form-group col-lg-2 col-md-3 col-sm-4">
													<?php echo $concepto['label']; ?>
													<?php echo $concepto['form']; ?>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<input type="hidden" name="persona" id="persona">
						<input type="hidden" name="suple" id="suple" value=<?= (!empty($suple_id)) ? $suple_id : ''; ?>>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="suplementarias/suple/ver/<?= $suple_id ?>" title="Cancelar">Cancelar</a>
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $suple_persona->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$("#periodo").datepicker({
			format: "yyyymm",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
	});
</script>
<script>
	$('#btn-clear').click(function() {
		$('#btn-clear').attr('disabled', true);
		$('#btn-search').attr('disabled', false);
		$('#p_apellido,#p_nombre').attr('readonly', true);
		$('#p_documento').attr('readonly', false);
		$('#p_documento_tipo')[0].selectize.enable();
		$('#p_documento_tipo')[0].selectize.setValue(1);
		$('#p_apellido,#p_nombre').val('');
		$('#p_documento').select();
		$('#btn-submit').attr('disabled', true);
		$("#table_servicios tbody").empty();
		$(".alert").css('display', 'none');
	});
	$('#btn-search').click(function() {
		var documento_tipo = $('#p_documento_tipo')[0].selectize.getValue();
		var documento = $('#p_documento').val();
		$('#p_persona_id').val(null);
		if (documento_tipo !== '' && documento !== '') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_servicios_p?',
				data: {documento_tipo: documento_tipo, documento: documento},
				dataType: 'json',
				success: function(result) {
					var html = '';
					if (result === '') {
						$(".alert").css('display', 'block');
						$('#btn-clear').attr('disabled', false);
					}
					if (result.servicios.length > 0) {
						$.each(result.servicios, function(i, servicio) {
							html += '<tr>'
							html += '<td align="center"><input type="radio" name="servicio_id" id="servicio_id" value=' + servicio.id_servicio + '>' + '' + '</td>'
							html += '<td>' + (servicio.liquidacion === null ? '' : servicio.liquidacion) + '</td>'
							html += '<td>' + servicio.regimen_cod + '</td>'
							html += '<td>' + servicio.regimen + '</td>'
							html += '<td>' + ((servicio.carga_horaria === null) ? '' : servicio.carga_horaria) + '</td>'
							html += '<td>' + (servicio.area_des === null ? servicio.escuela : servicio.area_des) + '</td>'
							html += '<td>' + (servicio.fecha_alta === null ? '' : moment(servicio.fecha_alta).format('DD/MM/YYYY')) + '</td>'
							html += '<td>' + (servicio.fecha_baja === null ? '' : moment(servicio.fecha_baja).format('DD/MM/YYYY')) + '</td>'
							html += '</tr>';
						});
					}
					if (html === '')
						html = '<tr><td colspan="8" align="center">No se encuentran servicios asignados a esta persona</td></tr>'
					$("#table_servicios tbody").html(html);
					$('#p_apellido,#p_nombre').attr('readonly', true);
					$('#btn-clear').attr('disabled', false);
					$('#btn-search').attr('disabled', true);
					if (result !== '') {
						$('#p_nombre').val(result.nombre);
						$('#p_apellido').val(result.apellido);
					} else {
						$('#p_apellido,#p_nombre').prop('readonly', false);
						$('#p_documento').attr('readonly', true);
						$('#p_documento_tipo')[0].selectize.enable();
						$('#documento_tipo_id').val($('#p_documento_tipo')[0].selectize.getValue());
						$('#p_documento_tipo')[0].selectize.disable();
					}
					$('#p_documento').attr('readonly', true);
					$('#p_documento_tipo_id').val($('#p_documento_tipo')[0].selectize.getValue());
					$('#p_documento_tipo')[0].selectize.disable();
					$('#btn-submit').attr('disabled', false);
				}
			});
		}
		if (documento_tipo !== '' && documento !== '') {
			$.ajax({
				type: 'GET',
				url: 'ajax/get_suples_p?',
				data: {documento_tipo: documento_tipo, documento: documento},
				dataType: 'json',
				success: function(result) {
					var html = '';
					if (result === '') {
						$(".alert").css('display', 'block');
						$('#btn-clear').attr('disabled', false);
					}

					if (result.suples_p.length > 0) {
						$.each(result.suples_p, function(i, suple_p) {
							html += '<tr>'
							html += '<td>' + (suple_p.liquidacion === null ? '' : suple_p.liquidacion) + '</td>'
							html += '<td>' + suple_p.servicio_id + '</td>'
							html += '<td>' + suple_p.motivo + '</td>'
							html += '<td>' + suple_p.estado + '</td>'
							html += '<td>' + suple_p.periodo + '</td>'
							html += '<td>' + suple_p.importe + '</td>'
							html += '<td>' + (suple_p.fecha_desde === null ? '' : moment(suple_p.fecha_desde).format('DD/MM/YYYY')) + '</td>'
							html += '<td>' + (suple_p.fecha_hasta === null ? '' : moment(suple_p.fecha_hasta).format('DD/MM/YYYY')) + '</td>'
							html += '</tr>';
						});
					}
					if (html === '')
						html = '<tr><td colspan="8" align="center">No se encuentran suplementarias asignadas a esta persona</td></tr>'
					$("#table_suples_p tbody").html(html);
				}
			});
		}
	});
	$('.concepto').inputmask('currency', {prefix: '', groupSeparator: '.', radixPoint: ','});
	$('#importe').inputmask('currency', {prefix: '', groupSeparator: '.', radixPoint: ','});
	$("select[name='concepto_o_l[]']").on('change', function() {
		if ($(this).val() == 0) {
			$(this).parent().next().next().find("input[name='concepto_o_f[]']").val(0);
			$(this).parent().next().next().find("input[name='concepto_o_f[]']").attr('readonly', true);
		} else {
			$(this).parent().next().next().find("input[name='concepto_o_f[]']").attr('readonly', false);
			$(this).parent().next().next().find("input[name='concepto_o_f[]']").attr('tabindex', '0');
		}
	});

	$("input[name='concepto_o_f[]'],input[name='concepto_i_f[]']").on('change', actualizar_importe);
	function actualizar_importe() {
		var hr_total = 0;
		var hn_total = 0;
		var rt_total = 0;
		var importe = 0;
		$("#tipo_HR input[type='text']").each(function(index, value) {
			var valor = parseFloat($(this).inputmask('unmaskedvalue'));
			hr_total += (isNaN(valor)) ? 0 : valor;
		});
		$("#tipo_HN input[type='text']").each(function(index, value) {
			var valor = parseFloat($(this).inputmask('unmaskedvalue'));
			hn_total += (isNaN(valor)) ? 0 : valor;
		});
		$("#tipo_RT input[type='text']").each(function(index, value) {
			var valor = parseFloat($(this).inputmask('unmaskedvalue'));
			rt_total += (isNaN(valor)) ? 0 : valor;
		});
		importe = hr_total + hn_total - rt_total;
		$('#importe').val(importe);
	}
</script>
