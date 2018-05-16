<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Alumnos de <?php echo "Esc. $escuela->nombre_corto" ?> - <label class="label label-default"><?php echo $ciclo_lectivo; ?></label>
			<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="alumno/listar/<?php echo $escuela->id . '/' . date('Y'); ?>">Alumnos</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
							<i class="fa fa-search"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="alumno/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-users"></i> Alumnos
						</a>
						<?php if (ENVIRONMENT !== 'production'): ?>
							<?php if ($escuela_mes !== 1):
								?>
								<a class="btn btn-app btn-app-zetta" href="abono/abono_alumno/listar/<?php echo $escuela->id; ?>/<?php echo $escuela_mes->ames; ?>">
									<i class="fa fa-bus" id="btn-abonos"></i> Abonos
								</a>
							<?php endif; ?>	
						<?php endif; ?>	
						<div class="btn-group pull-right" role="group" style="margin-left: 5px;">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-external-link"></i> Otros accesos <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<?php if ($this->rol->codigo !== ROL_ESCUELA_ALUM): ?>
									<li><a class="dropdown-item btn-default" href="servicio/listar/<?php echo $escuela->id . '/'; ?>"><i class="fa  fa-fw fa-bookmark"></i>Servicios</a></li>
									<li><a class="dropdown-item btn-default"  href="cargo/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-users"></i> Cargos</a></li>
								<?php endif; ?>
								<li><a class="dropdown-item btn-default" href="division/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-tasks"></i> Cursos y Divisiones</a></li>
								<li><a class="dropdown-item btn-default" href="escuela_carrera/listar/<?php echo $escuela->id; ?>"><i class="fa  fa-fw fa-book"></i>Carreras</a></li>
							</ul>
						</div>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("alumno/cambiar_ciclo_lectivo/$escuela->id/$ciclo_lectivo"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Ciclo Lectivo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo "01/01/$ciclo_lectivo"; ?>"></div>
						<input type="hidden" name="ciclo_lectivo" id="ciclo_lectivo" />
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
	var alumno_table;
	function complete_alumno_table() {
		agregar_filtros('alumno_table', alumno_table, 8);
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "years",
			minViewMode: "years",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#ciclo_lectivo").val($("#datepicker").datepicker('getFormattedDate'))
		});
	});
</script>
