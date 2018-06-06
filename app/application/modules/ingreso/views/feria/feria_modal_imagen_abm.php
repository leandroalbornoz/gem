<style>
	.krajee-default.file-preview-frame .kv-file-content {
    width: 520px;
    height: 250px;
}
</style>
<?php echo form_open_multipart(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group col-md-12" style="text-align: center">
			<?php echo $fields['path']['label']; ?>
			<?php echo $fields['path']['form']; ?>
		</div>
		<div class="form-group col-md-12">
			<?php echo $fields['pie']['label']; ?>
			<?php echo $fields['pie']['form']; ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="imagen" value="" id="imagen"/>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php if($txt_btn === 'Editar'): ?>
	<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Editar'), 'Editar'); ?>
	<?php else: ?>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php endif; ?>
	<?php echo ($txt_btn === 'Eliminar') ? form_hidden('escuela_id', $escuela_id) : ''; ?>
</div>
<?php echo form_close(); ?>

<script>	
$(document).ready(function() {
	$("#path").fileinput({
		
		overwriteInitial: true,
		maxFileSize: 2048,
		showClose: false,
//		uploadUrl: '/file-upload-batch/2',
		showCaption: false,
		browseOnZoneClick: true,	
		browseLabel: '',
		removeLabel: '',
		browseIcon: 'Cargar imagen <i class="glyphicon glyphicon-folder-open"></i>', 
		removeIcon: '<i class="glyphicon glyphicon-trash"></i>',
		removeTitle: 'Cancelar cambios',
		initialPreviewAsData: true,
		elErrorContainer: '#empleado-foto-errors-2',
		msgErrorClass: 'alert alert-block alert-danger',
		defaultPreviewContent: '<img src="<?php echo (isset($ruta_imagen)) ? "$ruta_imagen" : "img/generales/logo-login.png" ?>" alt="Foto" id="foto">',
		layoutTemplates: {main2: '{preview} {remove} {browse}', footer: ''},
		allowedFileExtensions: ["jpg", "png", "gif"]
//	}).on("filebatchselected", function(event, files) {
//		var imagen = files[0].name;
//		$('#imagen').val(imagen);
//		console.log(files[0]);
//		(".file-preview-image kv-preview-data rotate-1").attr('title')
//		$('#btn_guardar').attr('disabled', false);
//		$("img").attr("title");	
	});
});
</script>