<script>
	$(function() {
		$("#abono_escuela_estado").change(function() {
			if ($('select[id=abono_escuela_estado]').val() == '2') {
				$('#input_monto').hide();
				$('#monto').prop('required', false);
				$('#input_cupo_alumnos').show();
				$('#cupo_alumnos').prop('required', true);
			} else {
				$('#input_monto').show();
				$('#monto').prop('required', true);
				$('#input_cupo_alumnos').hide();
				$('#cupo_alumnos').prop('required', false);
			}
		});
	});
</script>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-sm-6">
			<?php echo $fields['escuela']['label']; ?>
			<?php echo $fields['escuela']['form']; ?>
		</div>
		<div class="form-group col-sm-6" >
			<?php echo $fields['abono_escuela_estado']['label']; ?>
			<?php echo $fields['abono_escuela_estado']['form']; ?>
		</div>
		<div class="form-group col-sm-6" id="input_monto">
			<?php echo $fields['monto']['label']; ?>
			<?php echo $fields['monto']['form']; ?>
		</div>
		<div class="form-group col-sm-6" id="input_cupo_alumnos">
			<?php echo $fields['cupo_alumnos']['label']; ?>
			<?php echo $fields['cupo_alumnos']['form']; ?>
		</div>
	</div>
</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php
	if ($txt_btn === 'Editar') {
		$btn_style = 'btn btn-warning pull-right';
	} elseif ($txt_btn === 'Eliminar') {
		$btn_style = 'btn btn-danger pull-right';
	} else {
		$btn_style = 'btn btn-primary pull-right';
	}
	?>
	<?php echo (!empty($txt_btn)) ? form_submit(array('class' => $btn_style, 'title' => $txt_btn), $txt_btn) : ''; ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $abono_escuela_monto->id) : ''; ?>
</div>
<input type="hidden" name="ames" value="<?php echo $ames; ?>" id="ames"/>
<?php echo form_close(); ?>

<script type="text/javascript">
	$(document).ready(function() {
		if ($('select[id=abono_escuela_estado]').val() == '2') {
			$('#input_monto').hide();
			$('#monto').prop('required', false);
			$('#input_cupo_alumnos').show();
			$('#cupo_alumnos').prop('required', true);
		} else {
			$('#input_monto').show();
			$('#monto').prop('required', true);
			$('#input_cupo_alumnos').hide();
			$('#cupo_alumnos').prop('required', false);
		}
		$("#datepicker").datepicker({
			format: "yyyymm",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>
