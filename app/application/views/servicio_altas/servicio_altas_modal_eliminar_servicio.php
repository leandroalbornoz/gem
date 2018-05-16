<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-8">
			<?php echo $fields['novedad_tipo']['label']; ?>
			<?php echo $fields['novedad_tipo']['form']; ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $fields['fecha_desde']['label']; ?>
			<?php echo $fields['fecha_desde']['form']; ?>
		</div>
		<div class="col-sm-12">
			<?php if ($permitido): ?>
				El servicio ha sido ingresado en la planilla activa, por lo que puede eliminarlo si lo desea.
			<?php else: ?>
				El servicio ha sido ingresado en una planilla ya cerrada, por lo que no puede ser eliminado.<br/>
				Cargue una novedad de baja para dar de baja al servicio.
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="col-sm-12">
		<?php if ($permitido): ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			<a class="btn btn-primary pull-right" href="servicio/eliminar/<?php echo "$servicio->id/$servicio_novedad->id"; ?>" title="Ir a eliminar servicio">Ir a eliminar servicio</a>
		<?php else: ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		<?php endif; ?>
	</div>
</div>