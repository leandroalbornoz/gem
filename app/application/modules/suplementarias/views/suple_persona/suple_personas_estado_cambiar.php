<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Listado de personas por suplementarias
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="bono/<?php echo $controlador; ?>"><?php echo ucfirst($controlador); ?></a></li>
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
						<h4>Cambiar personas seleccionados al siguiente estado:</h4>
						<form action="<?php echo current_url(); ?>" method="post" name="f1" id="f1">
							<div class="row">
								<div class="form-group col-md-3">
									<?php echo $fields['nuevo_estado']['label']; ?>
									<?php echo $fields['nuevo_estado']['form']; ?>
								</div>
							</div>
							<?php echo $js_table; ?>
							<?php echo $html_table; ?>
							<button class="btn btn-primary pull-right" type="submit">Cambiar estados</button>
						</form>		
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
	function complete_estado_table() {
		$('#cambiar_estado_table thead tr:first-child th:last-child').append('<a class="btn btn-xs btn-primary" href="javascript:cambiar_checkboxs(true)" title="Marcar todos"><i class="fa fa-fw fa-check-square-o"></i></a> <a class="btn btn-xs btn-danger"  href="javascript:cambiar_checkboxs(false)" title="Desmarcar todos"><i class="fa fa-fw fa-square-o"></i></a>');
	}
</script>
<script>
	var cambiar_estado_table;
	$(document).ready(function() {
		$('#nuevo_estado').val(cambiar_estado_table.column(2).search());
		$('#nuevo_estado').change(cambiar_estado);
	});
	function cambiar_estado() {
		cambiar_estado_table.column(2).search($(this).val()).draw();
	}
</script>
<script>
		function cambiar_checkboxs(checked) {
		$('#f1 input[type="checkbox"]').prop('checked', checked);
	}
</script>

