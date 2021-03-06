<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Inscripción de alumnos en la cursada
		</h1>
		<ol class="breadcrumb">
			<li><a href="cursada/listar/<?php echo $division->id; ?>">Cursadas</a></li>
			<li><a href="cursada/escritorio/<?php echo $cursada->id; ?>"><i class="fa fa-desktop"></i>Escritorio</a></li>
			<li><a href=""><i class="fa fa-home"></i> Alumnos</a></li>
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
		<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
		<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_alumnos', 'name' => 'form_alumnos')); ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Alumnos de <?php echo "$division->curso$division->division"; ?>, no inscriptos en <?php echo $cursada->espacio_curricular; ?></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" id="tbl_listar_alumnos" role="grid">
							<thead>
								<tr>
									<th style="width:10%;">Documento</th>
									<th style="width:30%;">Persona</th>
									<th style="width:15%;">F.Nac</th>
									<th style="width:3%;">Sexo</th>
									<th style="width:15%;">Desde</th>
									<th style="width:15%;">Hasta</th>
									<th style="width:4%;">C.L</th>
									<th style="width:8%;"></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
							<tbody>
								<?php if (!empty($alumnos)): ?>
									<?php foreach ($alumnos as $alumno): ?>
										<tr>
											<td><?= "$alumno->documento_tipo $alumno->documento"; ?></td>
											<td><?= $alumno->persona; ?></td>
											<td><?= empty($alumno->fecha_nacimiento) ? '' : (new DateTime($alumno->fecha_nacimiento))->format('d/m/Y'); ?></td>
											<td><?= substr($alumno->sexo, 0, 1); ?></td>
											<td><?= empty($alumno->fecha_desde) ? '' : (new DateTime($alumno->fecha_desde))->format('d/m/Y'); ?></td>
											<td><?= empty($alumno->fecha_hasta) ? '' : (new DateTime($alumno->fecha_hasta))->format('d/m/Y'); ?></td>
											<td><?= $alumno->ciclo_lectivo; ?></td>
											<td align="center">
												<input type="checkbox" class="cursada_check" name="alumnos[]" value="<?php echo $alumno->id; ?>">
											</td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<input type="hidden" name="cursada_id" value="<?php echo $cursada->id; ?>">
						<a class="btn btn-default" href="cursada/escritorio/<?php echo $cursada->id; ?>" title="Cancelar">Cancelar</a>
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var table_alumnos_busqueda;
	$(document).ready(function() {
		table_alumnos_busqueda = $('#tbl_listar_alumnos').DataTable({dom: 'tp', autoWidth: false, bPaginate: false, pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 10, lengthMenu: [10], initComplete: cursada_alumno_table});
	});
	function cambiar_checkboxs(checked) {
		$('#form_alumnos input[type="checkbox"]').prop('checked', checked);
	}
	function cursada_alumno_table() {
		agregar_filtros('tbl_listar_alumnos', table_alumnos_busqueda, 7);
		$('#tbl_listar_alumnos thead tr:first-child th:last-child').append('<a class="btn btn-xs btn-primary" href="javascript:cambiar_checkboxs(true)" title="Marcar todos"><i class="fa fa-fw fa-check-square-o"></i></a> <a class="btn btn-xs btn-danger"  href="javascript:cambiar_checkboxs(false)" title="Desmarcar todos"><i class="fa fa-fw fa-square-o"></i></a>');
	}
</script>
