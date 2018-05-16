<div class="content-wrapper">
	<section class="content-header">
		<h1>Reportes dinámicos</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i> Inicio</li>
			<li><a href="consultas/reportes/listar_guardados">Reportes</a></li>
			<li class="active">Agregar</li>
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
					<div class="box-body ">
						<a class="btn bg-default btn-app btn-app-zetta" href="consultas/reportes/listar_guardados">
							<i class="fa fa-file-excel-o" id="btn-agregar"></i> Reportes guardados
						</a>
						<a class="btn bg-default btn-app btn-app-zetta-active active" href="consultas/reportes/iniciar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<hr style="margin: 10px 0;">
						<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'class' => 'form-inline')); ?>
						<label for="tabla">Seleccione qué desea consultar:</label>
						<select style="width:200px;" name="tabla" class="selectize form-control" required placeholder="Seleccione tabla">
							<?php foreach ($tablas_reportes as $id => $r_tablas): ?>
								<option value="<?php echo $id; ?>"><?php echo $r_tablas['nombre']; ?></option>
							<?php endforeach; ?>
						</select>
						<button type="submit" class="btn btn-success">Iniciar</button>
						<?php echo form_close(); ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="escritorio" title="Volver">Volver</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>