<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<?php echo $fields['path']['label']; ?>
		<?php echo $fields['path']['form']; ?>
	</div>
	<div class="form-group">
		<?php echo $fields['pie']['label']; ?>
		<?php echo $fields['pie']['form']; ?>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" name="video_tipo" id="video_tipo"/>
	<input type="hidden" name="video_id" id="video_id"/>
<!--<span class="label label-danger pull-left" style=" display: none;" id="message_error">
		La URL ingresada no se corresponde con una dirección de video de Youtube o Vimeo.
	</span>-->
	<span class="badge bg-red" style=" display: none;" id="message_error">URL no corresponde con una dirección de video de Youtube o Vimeo</span>
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
		$('#path').change(function() {
			$('#message_error').css('display', 'none');
			$('.btn-primary').attr('disabled', false);
			var url = $('#path').val();
			var media = testUrlForMedia(url);
			if (media) {
				$('#video_tipo').val(media['type']);
				$('#video_id').val(media['id']);
			} else {
				$('#message_error').css('display', '');
				$('.btn-primary').attr('disabled', true);
				$('#video_tipo').val('');
				$('#video_id').val('');
			}
		});
	});
	function testUrlForMedia(pastedData) {
		var success = false;
		var media = {};
		var youtube_id = '';
		var vimeo_id = '';
		if (pastedData.match('http://(www.)?youtube|youtu\.be') || pastedData.match('https://(www.)?youtube|youtu\.be')) {
			if (pastedData.match('embed')) {
				youtube_id = pastedData.split(/embed\//)[1].split('"')[0];
			} else {
				youtube_id = pastedData.split(/v\/|v=|youtu\.be\//)[1].split(/[?&]/)[0];
			}
			media.type = "youtube";
			media.id = youtube_id;
			success = true;
		} else if (pastedData.match('http://(player.)?vimeo\.com') || pastedData.match('https://(player.)?vimeo\.com')) {
			vimeo_id = pastedData.split(/video\/|http:\/\/vimeo\.com\//)[1].split(/[?&]/)[0];
			media.type = "vimeo";
			media.id = vimeo_id;
			success = true;
		}
		if (success) {
			return media;
		} else {
			return false;
		}
		return false;
	}
</script>