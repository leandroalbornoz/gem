<script>
	$(document).ready(function() {
		agregar_eventos($('#form_modal_cargo'));
		var xhr_espacio_curricular, xhr_carrera, xhr_turno;
		var select_division, $select_division;
		var select_carrera, $select_carrera;
		var select_espacio_curricular, $select_espacio_curricular;
		var select_turno, $select_turno;

		$select_division = $('#form_modal_cargo #division').selectize({
			plugins: {
				'clear_selection': {}
			},
			onChange: actualizar_carrera,
		});

		$select_turno = $('#form_modal_cargo #turno').selectize({
			plugins: {
				'clear_selection': {}
			}
		});

		$select_carrera = $('#form_modal_cargo #carrera').selectize({
			plugins: {
				'clear_selection': {}
			},
			valueField: 'id',
			labelField: 'descripcion',
			searchField: ['descripcion'],
			onChange: actualizar_espacio_curricular
		});

		$select_espacio_curricular = $('#form_modal_cargo #espacio_curricular').selectize({
			plugins: {
				'clear_selection': {}
			},
			preload: false,
			valueField: 'id',
			labelField: 'descripcion',
			searchField: ['descripcion']
		});

		select_division = $select_division[0].selectize;
		select_espacio_curricular = $select_espacio_curricular[0].selectize;
		select_carrera = $select_carrera[0].selectize;
		select_turno = $select_turno[0].selectize;

		var valor_espacio_curricular = select_espacio_curricular.getValue();
		if (select_division.getValue() !== '') {
			actualizar_carrera(select_division.getValue());
		}

		function actualizar_espacio_curricular(carrera) {
			select_espacio_curricular.disable();
			select_espacio_curricular.clearOptions();
			select_espacio_curricular.load(function(callback) {
				xhr_espacio_curricular && xhr_espacio_curricular.abort();
				var division = select_division.getValue();
				xhr_espacio_curricular = $.ajax({
					url: 'ajax/get_materias/<?php echo $escuela->nivel_id; ?>/' + (division === '' ? '0' : division) + '/' + (carrera === '' ? '0' : carrera),
					dataType: 'json',
					success: function(results) {
						select_espacio_curricular.enable();
						callback(results);
						select_espacio_curricular.setValue(valor_espacio_curricular);
					},
					error: function() {
						callback();
					}
				})
			});
		}

		function actualizar_carrera(division) {
			select_carrera.enable();
			var valor = select_carrera.getValue();
			valor_espacio_curricular = select_espacio_curricular.getValue();
			select_carrera.disable();
			select_carrera.clearOptions();
			select_carrera.load(function(callback) {
				xhr_carrera && xhr_carrera.abort();
				xhr_carrera = $.ajax({
					url: 'ajax/get_carreras/<?php echo $escuela->id; ?>/' + division,
					dataType: 'json',
					success: function(results) {
						select_carrera.enable();
						callback(results);
						if (results.length === 1 && division !== '') {
							select_carrera.setValue(results[0].id);
							select_carrera.disable();
						} else {
							select_carrera.setValue(valor);
						}
					},
					error: function() {
						callback();
					}
				});
			});

			var valor_division = select_division.getValue();
			if (valor_division !== '') {
				select_turno.load(function(callback) {
					xhr_turno && xhr_turno.abort();
					xhr_turno = $.ajax({
						url: 'ajax/get_turno/' + valor_division,
						dataType: 'json',
						success: function(results) {
							select_turno.enable();
							callback(results);
							if (results.turno_id !== '') {
								select_turno.setValue(results.turno_id);
							}
						},
						error: function() {
							callback();
						}
					});
				});
			}
		}
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id'=>'form_modal_cargo')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-6">
			<?php echo $fields['division']['label']; ?>
			<?php echo $fields['division']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['carrera']['label']; ?>
			<?php echo $fields['carrera']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['espacio_curricular']['label']; ?>
			<?php echo $fields['espacio_curricular']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['turno']['label']; ?>
			<?php echo $fields['turno']['form']; ?>
		</div>
		<div class="form-group col-md-6">
			<?php echo $fields['fecha_hasta']['label']; ?>
			<?php echo $fields['fecha_hasta']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['observaciones']['label']; ?>
			<?php echo $fields['observaciones']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Guardar'), 'Guardar'); ?>
	<?php echo form_hidden('id', $cargo->id); ?>
</div>
<?php echo form_close(); ?>