<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_listar_anteriores')); ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>

		</h1>
		<h1>
			Abonos <?php echo "Esc. $escuela->nombre_corto" ?> - <label class="label label-default"><?php echo $mes; ?></label>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="alumno/listar/<?php echo $escuela->id . '/' . date('Y'); ?>">Alumnos</a></li>
			<li><a href="abono/abono_alumno/listar/<?php echo $escuela->id; ?>/<?php echo $mes_id; ?>">Abono Escuela</a></li>
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

						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
						<input type="hidden" name="ames" value="<?php echo $mes_id; ?>">
						<input type="hidden" name="escuela_id" value="<?php echo $escuela->id; ?>">
						<input type="hidden" name="abono_alumno_estado_id" value="<?php echo 1; ?>">
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="abono/abono_alumno/listar/<?php echo $escuela->id; ?>/<?php echo $mes_id; ?>" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<button class="btn btn-primary pull-right" type="submit" id="agregar_anteriores" title="Mover alumnos">Agregar alumnos</button>

						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
					</div>
				</div>
			</div>
		</div>
</div>
<?php echo form_close(); ?>
<script>
	var abono_alumno_table;
	function complete_alumno_table() {
		$('#abono_alumno_table thead tr:first-child th:last-child').append('<a class="btn btn-xs btn-primary" href="javascript:cambiar_checkboxs(true)" title="Marcar todos"><i class="fa fa-fw fa-check-square-o"></i></a> <a class="btn btn-xs btn-danger"  href="javascript:cambiar_checkboxs(false)" title="Desmarcar todos"><i class="fa fa-fw fa-square-o"></i></a>');
	}
</script>
<script>
	function cambiar_checkboxs(checked) {
		$('#form_listar_anteriores input[type="checkbox"]').prop('checked', checked);
	}
S</script>