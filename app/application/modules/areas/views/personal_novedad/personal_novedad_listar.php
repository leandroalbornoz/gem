<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Novedades de <?php echo "$area->codigo $area->descripcion" ?> - <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="areas/area/ver/<?php echo $area->id; ?>"><?php echo "$area->codigo - $area->descripcion"; ?></a></li>
			<li><a href="areas/personal_novedad/listar/<?php echo "$area->id/$mes_id"; ?>">Novedades</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="areas/personal/listar/<?php echo "$area->id/$mes_id"; ?>">
							<i class="fa fa-users"></i> Personal
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="areas/personal_novedad/listar/<?php echo "$area->id/$mes_id"; ?>">
							<i class="fa fa-calendar"></i> Novedades
						</a>
						<a class="btn btn-app btn-app-zetta" href="areas/personal_novedad/listar/<?php echo "$area->id/$mes_id/1"; ?>">
							<i class="fa fa-calendar"></i> Novedades a confirmar
						</a>
						<a class="btn btn-app btn-app-zetta pull-right" href="areas/asisnov/index/<?php echo "$area->id/$mes_id"; ?>">
							<i class="fa fa-print"></i> Asis. nov
						</a>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
						<hr style="margin: 10px 0;">
						<h4 class="text-center">Novedades de otros servicios que cumplen función en el área</h4>
						<?php echo $js_table_o; ?>
						<?php echo $html_table_o; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("areas/personal_novedad/cambiar_mes/$area->id/$mes_id/0"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo $fecha; ?>"></div>
						<input type="hidden" name="mes" id="mes" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar'), 'Seleccionar'); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script>
	var personal_novedad_table;
	var personal_novedad_f_table;
	function complete_personal_novedad_table() {
		agregar_filtros('personal_novedad_table', personal_novedad_table, 11);
	}
	function complete_personal_novedad_f_table() {
		agregar_filtros('personal_novedad_f_table', personal_novedad_f_table, 11);
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'));
		});
	});
</script>