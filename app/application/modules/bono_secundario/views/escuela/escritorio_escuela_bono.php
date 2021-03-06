<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Escuela <?= $escuela->numero . " - " . $escuela->nombre ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>"><?php echo "Esc. " . $escuela->numero . "-" . $escuela->nombre ?></a></li>
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
					<?php $data_submit = array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn); ?>
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="escritorio">
							<i class="fa fa-search" id="btn-ver"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['pendientes']; ?> >" href="bono_secundario/recepcion/pendientes/<?= $escuela->id ?>">
							<i class="fa fa-envelope"></i> Pendientes de recepción<span class="badge"><?= $escuela->pendientes ?></span>
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['recibidos']; ?> >" href="bono_secundario/recepcion/recibidos/<?= $escuela->id ?>">
							<i class="fa fa-check"></i> Recibidos<span class="badge"><?= $escuela->recibidos ?></span>
						</a>
						<a class="btn btn-app btn-app-zetta  <?php echo $class['reclamos']; ?> >" href="bono_secundario/recepcion/reclamos/<?= $escuela->id ?>">
							<i class="fa fa-check"></i> Reclamos<span class="badge"><?= $escuela->reclamos ?></span>
						</a>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields['numero']['label']; ?>
								<?php echo $fields['numero']['form']; ?>
							</div>
							<div class="form-group col-md-9">
								<?php echo $fields['nombre']['label']; ?>
								<?php echo $fields['nombre']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['vacantes']['label']; ?>
								<?php echo $fields['vacantes']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['vacantes_disponibles']['label']; ?>
								<?php echo $fields['vacantes_disponibles']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['recibidos']['label']; ?>
								<?php echo $fields['recibidos']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields['pendientes']['label']; ?>
								<?php echo $fields['pendientes']['form']; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<?php echo (!empty($txt_btn)) ? form_submit($data_submit, $txt_btn) : ''; ?>
						<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', $escuela->id) : ''; ?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

