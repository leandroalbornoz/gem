<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Listado de personas por suplementarias
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="suplementarias/suple/ver/<?= $suple_id; ?>"><?php echo "Suplementarias"; ?></a></li>
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
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta  <?php echo $class['agregar_persona']; ?> >" href="suplementarias/suple_persona/agregar/<?php echo (!empty($suple_id)) ? $suple_id : ''; ?>">
							<i class="fa fa-plus" id="btn-agregar">
							</i>Agregar persona
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['cambiar_estado']; ?> >" href="suplementarias/suple_persona/cambiar_estado/<?php echo (!empty($suple_id)) ? $suple_id : ''; ?>">
							<i class="fa fa-exchange" id="btn-agregar">
							</i>Cambiar estados
						</a>
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
					<div class="box-footer">
						<!--<a class="btn btn-primary pull-right" href="bono/persona/agregar" title="Agregar persona">Agregar persona</a>-->
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var alumno_table;
	function complete_listar_persona_table() {
		agregar_filtros('persona_table', persona_table, 3);
	}
</script>