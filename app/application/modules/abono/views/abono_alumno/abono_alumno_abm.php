<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Abono Escuela
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Abono Escuela</a></li>
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
						<a class="btn btn-app btn-app-zetta <?php echo $class['agregar']; ?>" href="abono/abono_alumno/agregar">
							<i class="fa fa-plus" id="btn-agregar"></i> Agregar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="abono/abono_alumno/ver/<?php echo (!empty($abono_escuela->id)) ? $abono_escuela->id : ''; ?>">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['editar']; ?>" href="abono/abono_alumno/editar/<?php echo (!empty($abono_escuela->id)) ? $abono_escuela->id : ''; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta <?php echo $class['eliminar']; ?>" href="abono/abono_alumno/eliminar/<?php echo (!empty($abono_escuela->id)) ? $abono_escuela->id : ''; ?>">
							<i class="fa fa-ban" id="btn-eliminar"></i> Eliminar
						</a>
						<?php foreach ($fields as $field): ?>
							<div class="form-group">
								<?php echo $field['label']; ?>
								<?php echo $field['form']; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="abono/abono_alumno/listar" title="Cancelar">Cancelar</a>
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $abono_escuela->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>