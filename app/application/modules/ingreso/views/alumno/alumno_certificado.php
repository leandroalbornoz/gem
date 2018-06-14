<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
	.promedio-status-ok,.promedio-status-loading,.promedio-status-error{
		display:none;
	}
	.promedio-status.ok>.promedio-status-ok{
		display:inline-block;
	}
	.promedio-status.loading>.promedio-status-loading{
		display:inline-block;
	}
	.promedio-status.error>.promedio-status-error{
		display:inline-block;
	}

	.abaderado-status-ok,.abaderado-status-loading,.abaderado-status-error{
		display:none;
	}
	.abaderado-status.ok>.abaderado-status-ok{
		display:inline-block;
	}
	.abaderado-status.loading>.abaderado-status-loading{
		display:inline-block;
	}
	.abaderado-status.error>.abaderado-status-error{
		display:inline-block;
	}

	.participa-status-ok,.participa-status-loading,.participa-status-error{
		display:none;
	}
	.participa-status.ok>.participa-status-ok{
		display:inline-block;
	}
	.participa-status.loading>.participa-status-loading{
		display:inline-block;
	}
	.participa-status.error>.participa-status-error{
		display:inline-block;
	}

	.abanderado.active {
    color: green;
	}
	.abanderado.active {
    font-weight: bold;
	}
	.abanderado_no.active {
    font-weight: bold;
	}
	#form_certificado_alumno .has-error .help-block {
		display: block;
		border: none;
		color: #737373;
		padding-top: 5px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo "Esc. $escuela->nombre_corto" ?> - Alumnos certificados
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
					<form action="<?php echo current_url(); ?>" method="post" name="form_certificado_alumno" id="form_certificado_alumno">
						<div class="box-body">
							<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
								<i class="fa fa-search"></i> Ver escuela
							</a>
							<a class="btn btn-app btn-app-zetta" href="ingreso/alumno/listar/<?php echo $escuela->id; ?>/<?php echo $ciclo_lectivo; ?>">
								<i class="fa fa-users"></i> Alumnos
							</a>
							<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="ingreso/alumno/certificados/<?php echo $escuela->id; ?>/<?php echo $ciclo_lectivo; ?>">
								<i class="fa fa-id-card-o" aria-hidden="true"></i> Certificados
							</a>
							<a class="btn btn-app btn-app-zetta" href="ingreso/alumno/editar_datos/<?php echo $escuela->id; ?>/<?php echo $ciclo_lectivo; ?>">
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Docimilio y datos
							</a>
							<?php if ($abanderados_baja != NULL): ?>
								<a class="btn btn-app bg-yellow btn-app-zetta" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="ingreso/alumno/abanderados_baja/<?php echo $escuela->id; ?>/<?php echo $ciclo_lectivo; ?>">
									<i class="fa fa-flag" aria-hidden="true"></i> Abanderados baja <span class="badge bg-red" style="float: left; margin-top: -5%; font-size: 17px;"><?php echo count($abanderados_baja); ?></span>
								</a>
							<?php endif; ?>
							<hr style="margin: 10px 0;">
							<?php echo $js_table; ?>
							<?php echo $html_table; ?>
						</div>
						<br>
						<div class="box-footer">
							<button class="btn btn-success pull-right" type="submit" title="Imprimir" formtarget="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Imprimir</button>
							<div class="row pull-right hidden" style="padding-right: 5%; margin-bottom: -8px;"  id="cartel">
								<div class="bg-red text-bold" style="border-radius: 2px; padding-bottom: 0px;"><h4>Debe seleccionar al menos un alumno.</h4></div>
							</div>
						</div>
					</form>
				</div>	
			</div>
		</div>
	</section>
</div>
<script>
	var alumno_table;
	function complete_alumno_table() {
		agregar_filtros('alumno_table', alumno_table, 7);
		$('#alumno_table thead tr:first-child th:last-child').append('<a class="btn btn-xs btn-primary" href="javascript:cambiar_checkboxs(true)" title="Marcar todos"><i class="fa fa-fw fa-check-square-o"></i></a> <a class="btn btn-xs btn-danger"  href="javascript:cambiar_checkboxs(false)" title="Desmarcar todos"><i class="fa fa-fw fa-square-o"></i></a>');
	}

	function cambiar_checkboxs(checked) {
		$('#form_certificado_alumno input[type="checkbox"').prop('checked', checked);
	}

	$(document).ready(function() {
		$('#form_certificado_alumno').validate({
			rules: {
				'alumno_division[]': {
					required: true,
					minlength: 1
				}
			},

			highlight: function(element) {
				$('#cartel').removeClass('hidden');
			},
			unhighlight: function(element) {
				$('#cartel').addClass('hidden');
			},
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if (element.parent('.form-group').length) {
					error.insertAfter(element.parent());
				}
			}
		});
	});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
