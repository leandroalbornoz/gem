<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Escuelas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
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
					<div class="box-header with-border">
						<h3 class="box-title">
								<?php echo empty($carrera) ? '' : "Carrera - $carrera->descripcion"; ?>
						</h3>
					</div>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="escuela_carrera/espacios_curriculares/<?php echo $carrera->id; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Materias
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="escuela_carrera/escuelas/<?php echo $carrera->id; ?>">
							<i class="fa fa-home" id="btn-ver"></i> Escuelas
						</a>
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href=""  title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
						<?php echo zetta_form_submit($txt_btn); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var escuela_table;
	function complete_escuela_table() {
		agregar_filtros('escuela_table', escuela_table, 11);
	}
</script>