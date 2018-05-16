<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<h4>¿Está seguro que desea reabrir este cargo?</h4>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => $txt_btn, 'id' => 'btn_submit_editar'), $txt_btn); ?>
	<?php echo form_hidden('id', $cargo_id); ?>
</div>
<?php echo form_close(); ?>