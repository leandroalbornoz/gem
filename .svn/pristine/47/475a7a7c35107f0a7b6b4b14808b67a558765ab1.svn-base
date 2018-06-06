<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<?php echo $fields['encabezado']['label']; ?>
		<?php echo $fields['encabezado']['form']; ?>
	</div>
	<div class="form-group">
		<?php echo $fields['texto']['label']; ?>
		<?php echo $fields['texto']['form']; ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php if ($txt_btn === 'Editar'): ?>
		<?php echo form_submit(array('class' => 'btn btn-warning pull-right', 'title' => 'Editar'), 'Editar'); ?>
	<?php else: ?>
		<?php echo zetta_form_submit($txt_btn); ?>
	<?php endif; ?>
	<?php echo ($txt_btn === 'Eliminar') ? form_hidden('escuela_id', $escuela_id) : ''; ?>
</div>
<?php echo form_close(); ?>

<script>	
$(document).ready(function() {
//	$('#texto').wysihtml5();
//	$('#some-textarea').wysihtml5({someOption: 23, ...});

	$('#texto').wysihtml5({
	"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
	"emphasis": true, //Italics, bold, etc. Default true
	"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
	"html": false, //Button which allows you to edit the generated HTML. Default false
	"link": false, //Button to insert a link. Default true
	"image": false, //Button to insert an image. Default true,
	"color": false, //Button to change color of font  
	"blockquote": true, //Blockquote  
	});
});
</script>