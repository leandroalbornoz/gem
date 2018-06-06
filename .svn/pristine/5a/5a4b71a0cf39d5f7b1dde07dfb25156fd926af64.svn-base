<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Suplementarias
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="suplementarias/<?php echo $controlador; ?>">Suplementarias</a></li>
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
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<?php if ($txt_btn === 'Agregar'): ?>
							<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="suplementarias/suple/agregar">
								<i class="fa fa-plus" id="btn-agregar"></i> Agregar
							</a>
						<?php endif; ?>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="suplementarias/suple/ver/<?php echo (!empty($suple->id)) ? $suple->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="suplementarias/suple/editar/<?php echo (!empty($suple->id)) ? $suple->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="suplementarias/suple/eliminar/<?php echo (!empty($suple->id)) ? $suple->id : ''; ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['agregar_persona']; ?> >" href="suplementarias/suple_persona/agregar/<?php echo (!empty($suple->id)) ? $suple->id : ''; ?>">
							<i class="fa fa-plus" id="btn-agregar">
							</i>Agregar persona
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['cambiar_estado']; ?> >" href="suplementarias/suple_persona/cambiar_estado/<?php echo (!empty($suple->id)) ? $suple->id : ''; ?>">
							<i class="fa fa-exchange" id="btn-cambiar_estado">
							</i>Cambiar estados
						</a>
						<div class="row">							
							<div class="form-group col-sm-3">
								<?php echo $fields['motivo']['label']; ?>
								<?php echo $fields['motivo']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields['fecha_desde']['label']; ?>
								<?php echo $fields['fecha_desde']['form']; ?>
							</div>
							<div class="form-group col-sm-3">
								<?php echo $fields['fecha_hasta']['label']; ?>
								<?php echo $fields['fecha_hasta']['form']; ?>
							</div>
							<?php if ($txt_btn != 'Agregar') { ?>
								<div class="form-group col-sm-3">
									<?php echo $fields['expediente']['label']; ?>
									<?php echo $fields['expediente']['form']; ?>
								</div>
							<?php } ?>
						</div>
						<?php if ($txt_btn == NULL) { ?> <br>

							<?php echo $js_table; ?>
							<?php echo $html_table; ?>
						<?php } ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="suplementarias/suple/listar" title="Cancelar">Cancelar</a>
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $suple->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var alumno_table;
	function complete_listar_persona_table() {
		agregar_filtros('persona_table', persona_table, 6);
	}
</script>