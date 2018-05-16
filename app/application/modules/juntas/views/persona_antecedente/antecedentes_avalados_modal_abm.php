<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_antecedentes_buscar_listar')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-sm-3">
			<input type="radio" name="opcion" value="2" checked=""> Búsqueda por N°Resolución.<br>
		</div>
		<div class="form-group col-sm-3">
			<input type="radio" name="opcion" value="1" > Búsqueda por Antecedente.<br>
		</div>

		<div class="form-group col-sm-3">
			<input type="radio" name="opcion" value="3"> Búsqueda por Identificador.<br>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-sm-3">
			<?php echo $fields['numero_resolucion']['label']; ?>
			<?php echo $fields['numero_resolucion']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['antecedente']['label']; ?>
			<?php echo $fields['antecedente']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<?php echo $fields['identificador']['label']; ?>
			<?php echo $fields['identificador']['form']; ?>
		</div>
		<div class="form-group col-sm-3">
			<label>&nbsp;</label><br/>
			<button class="btn btn-primary" id="btn-search" type="button">
				<i class="fa fa-search"></i>
			</button>
			<button class="btn btn-default" id="btn-clear" type="button">
				<i class="fa fa-times"></i>
			</button>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-sm-6">
			<?php echo $fields['fecha_emision']['label']; ?>
			<?php echo $fields['fecha_emision']['form']; ?>
		</div>
	</div>
	<div class="box-body ">
		<table class="table table-hover table-bordered table-condensed no-footer" role="grid" id="tbl_listar_antecedentes">
			<thead>
				<tr style="background-color: #f4f4f4" >
					<th style="text-align: center;" colspan="5">Antecedentes Avalados</th>
				</tr>
				<tr>
					<th style="min-width: 5px">Id</th>
					<th style="min-width: 100px">Institución</th>
					<th style="min-width: 100px">Antecedente</th>
					<th style="min-width: 50px">N°Resolución</th>
					<th style="min-width: 5px"></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
	<?php $data_submit = array('class' => 'btn pull-right' . ($txt_btn === 'Eliminar' ? ' btn-danger' : ' btn-primary'), 'title' => $txt_btn); ?>
	<button  class="btn btn-default pull-left" type="button" id="btn-submit" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<span class="text-danger" id="dato_invalido"></span>
</div>
<?php echo form_close(); ?>
<script>
	function validar(e) {
		var tecla = (document.all) ? e.keyCode : e.which;
		if (tecla === 13) {
			$('#btn-search').click();
		}
	}
	var table_antecedentes_busqueda;
	$(document).ready(function() {
		agregar_eventos($('#form_antecedentes_buscar_listar'));
		table_antecedentes_busqueda = $('#tbl_listar_antecedentes').DataTable({dom: 'tp', autoWidth: false, paging: 'simple', pagingType: 'simple_numbers', language: {url: 'plugins/datatables/spanish.json'}, pageLength: 5, lengthMenu: [5], columnDefs: [{orderable: false, targets: [4]}]});
		$('#tbl_listar_antecedentes').css('width', '100%');
		$('#identificador').attr('disabled', true);
		$('#antecedente').attr('disabled', true);
		$('#numero_resolucion').attr('disabled', true);
		$('#numero_resolucion').attr('disabled', false);
		$('#numero_resolucion').attr('required', true);
		$("input[name='opcion']").change(function() {
			if ($(this).val() === '1') {
				$('#antecedente').attr('disabled', false);
				$('#antecedente').attr('required', true);
				$('#numero_resolucion,#identificador').attr('disabled', true);
				$('#numero_resolucion,#identificador').val('');
				$('#antecedente').closest('.form-group').removeClass("has-error");
				$('#btn-clear').click();
			} else if ($(this).val() === '2') {
				$('#numero_resolucion').attr('disabled', false);
				$('#numero_resolucion').attr('required', true);
				$('#antecedente,#identificador').attr('disabled', true);
				$('#antecedente,#identificador').val('');
				$('#btn-clear').click();
			} else if ($(this).val() === '3') {
				$('#identificador').attr('disabled', false);
				$('#identificador').attr('required', true);
				$('#antecedente,#numero_resolucion').attr('disabled', true);
				$('#antecedente,#numero_resolucion').val('');
				$('#btn-clear').click();
			}
		});
		$('#btn-clear').attr('disabled', true);
		$('#btn-clear').click(function() {
			$('#btn-clear').attr('disabled', true);
			$('#btn-search').attr('disabled', false);
			$('#identificador').attr('readonly', false);
			$('#antecedente').attr('readonly', false);
			$('#numero_resolucion').attr('readonly', false);
			table_antecedentes_busqueda.clear();
			$('#tbl_listar_antecedentes tbody').html('');
		});
		$('#btn-search').click(function() {
			$('#antecedente').closest('.form-group').removeClass("has-error");
			$('#btn-search').attr('disabled', true);
			$('#dato_invalido').text('');
			table_antecedentes_busqueda.clear();
			var identificador = $('#identificador').val();
			var numero_resolucion = $('#numero_resolucion').val();
			var antecedente = $('#antecedente').val();
			if ((antecedente.length >= 3 || identificador !== '') || numero_resolucion !== '') {
				$('#tbl_listar_antecedentes tbody').html('');
				$('#d_persona_id').val(null);
				if ((antecedente.length >= 3 || identificador !== '') || numero_resolucion !== '') {
					$.ajax({
						type: 'GET',
						url: 'juntas/ajax/get_listar_antecedentes?',
						data: {antecedente: antecedente, numero_resolucion: numero_resolucion, identificador: identificador},
						dataType: 'json',
						success: function(result) {
							$('#identificador,#numero_resolucion,#antecedente').attr('readonly', true);
							$('#btn-clear').attr('disabled', false);
							if (result.status === 'success') {
								if (result.antecedentes_listar.length > 0) {
									for (var idx in result.antecedentes_listar) {
										var antecedentes_listar = result.antecedentes_listar[idx];
										table_antecedentes_busqueda.row.add([
											antecedentes_listar.crsid,
											antecedentes_listar.institucion,
											antecedentes_listar.antecedente,
											antecedentes_listar.numero_resolucion,
											'<button type="submit" class="btn btn-xs btn-primary" id="antecedente_id" name="antecedente_id" value="' + antecedentes_listar.id + '"<i class="fa fa-check"></i> Seleccionar</button>']);
									}
								}

								table_antecedentes_busqueda.draw();
								$('#btn-submit').attr('disabled', false);
							} else {
								table_antecedentes_busqueda.row.add([
									'Antecedente no encontrado',
									'',
									'',
									'',
									'']);
								table_antecedentes_busqueda.draw();
							}
						}
					});
				}
			} else {
				$('#btn-search').attr('disabled', false);
				if (antecedente.length < 3) {
					$('#antecedente').closest('.form-group').addClass("has-error");
				}
				$('#dato_invalido').text('Ingrese como mínimo 3 caracteres en Antecedente');
				$('#boton_guardar').attr('disabled', true);
			}
		});
	});
</script>