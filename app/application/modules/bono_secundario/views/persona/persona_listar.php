<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Lista de personas
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo "escritorio" ?>"><?php echo "Esc. " . $escuela->numero . "-" . $escuela->nombre ?></a></li>
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
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body text-sm">
						<a class="btn btn-app btn-app-zetta  <?php echo $class['escuelas']; ?> >" href="escritorio">
							<i class="fa fa-search"></i>Ver
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['pendientes']; ?> >" href="bono_secundario/recepcion/pendientes/<?= $escuela->id ?>">
							<i class="fa fa-envelope"></i> Pendientes de recepción<span class="badge"><?= $cantidad_bonos['precepcion'] ?></span>
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['recibidos']; ?> >" href="bono_secundario/recepcion/recibidos/<?= $escuela->id ?>">
							<i class="fa fa-check"></i> Recibidos<span class="badge"><?= $cantidad_bonos['recibidos'] ?></span>
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['reclamos']; ?> >" href="bono_secundario/recepcion/reclamos/<?= $escuela->id ?>">
							<i class="fa fa-check"></i> Reclamos<span class="badge"><?= $cantidad_bonos['reclamos'] ?></span>
						</a>
						<a class="btn btn-app btn-app-zetta" href="bono_secundario/recepcion/excel/<?= "$escuela->id"; ?>">
							<i class="fa fa-file-excel-o"></i> Exportar
						</a>
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
					<div class="box-footer">
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var persona_table;
	function complete_persona_table() {
		agregar_filtros('persona_table', persona_table, 9);
	}
</script>