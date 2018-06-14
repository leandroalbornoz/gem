<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo "Esc. $escuela->nombre_corto" ?> - Alumnos editar datos
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="alumno/listar/<?php echo $escuela->id . '/' . date('Y'); ?>">Alumnos</a></li>
			<li class="active">Editar datos</li>
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
							<a class="btn btn-app btn-app-zetta" href="ingreso/alumno/certificados/<?php echo $escuela->id; ?>/<?php echo $ciclo_lectivo; ?>">
								<i class="fa fa-id-card-o" aria-hidden="true"></i> Certificados
							</a>
							<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="ingreso/alumno/editar_datos/<?php echo $escuela->id; ?>/<?php echo $ciclo_lectivo; ?>">
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
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Volver'; ?></button>
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
		agregar_filtros('alumno_table', alumno_table, 8);
	}
</script>