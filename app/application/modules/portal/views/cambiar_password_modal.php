<style>
	.form {
		position: relative;
		z-index: 1;
		background: #FFFFFF;
		max-width: 360px;
		margin: 0 auto 100px;
		padding: 30px;
		box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
	}

	.form-control {
		width: 100%;
		height: 45px;
		border: none;
		padding: 5px 7px 5px 15px;
		background: #fff;
		color: #666;
		border: 2px solid #ddd;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border-radius: 4px;
	}
	.form-control:focus, .form-control:focus + .fa {
		border-color: #3c8dbc;
	}
	.form-control:focus + .fa {
		color: #3c8dbc;
	}
	.form-group .fa {
		position: absolute;
		right: 10px;
		top: 6px;
		color: #999;
	}
	.form-group {
		margin-bottom: 20px;
	}
	.login-box-msg + .error{
		margin: 0;
		text-align: center;
		color: #a94442;
		padding: 0 20px 10px 20px;
	}
	.log-status.wrong-entry {
		-moz-animation: wrong-log 0.3s;
		-webkit-animation: wrong-log 0.3s;
		animation: wrong-log 0.3s;
	}

	.log-status.wrong-entry .form-control, .wrong-entry .form-control + .fa {
		border-color: #ed1c24;
		color: #ed1c24;
	}
	.alert{
		display:none;
		padding: 0px;
		margin-bottom: -19px;
		font-size:12px;
		color:#f00;
		text-align: center;
	}
	.alert_pass{
		display:none;
		padding: 0px;
		margin-bottom: -17px;
		font-size:12px;
		color:#f00;
		text-align: center;
	}
	.alert_igual{
		display:none;
		padding: 0px;
		margin-bottom: -17px;
		font-size:12px;
		color:#f00;
		text-align: center;
	}
	.alert_actual{
		display:none;
		padding: 0px;
		margin-bottom: -17px;
		font-size:12px;
		color:#f00;
		text-align: center;
	}
</style>
<?php echo form_open(uri_string(), array('data-toggle' => 'validator', 'id' => 'form_cambiar_password')); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group has-feedback" id="verificar_actual">
				<?php echo form_input($password); ?>
				<span class="fa fa-lock form-control-feedback"></span>
				<div class="alert_actual">El campo contraseña actual no puede estar vacío.</div>
			</div>
			<div class="form-group has-feedback" id="verificar">
				<?php echo form_input($nuevo_password); ?>
				<span class="fa fa-lock form-control-feedback"></span>
				<div class="alert">El campo contraseña nueva no puede estar vacío.</div>
				<div class="alert_igual">Las contraseñan deben coincidir.</div>
			</div>
			<div class="form-group has-feedback" id="verificar_pass">
				<?php echo form_input($repetir_nuevo_password); ?>
				<span class="fa fa-lock form-control-feedback"></span>
				<div class="alert_pass">El campo repetir contraseña no puede estar vacío.</div>
				<div class="alert_igual">Las contraseñas deben coincidir.</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" id="btn-submit" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
	<?php echo zetta_form_submit($txt_btn); ?>
	<?php echo ($txt_btn === 'Editar' || $txt_btn === 'Eliminar') ? form_hidden('id', "") : ''; ?>
</div>
<?php echo form_close(); ?>
<script>
	$(document).ready(function() {
		$('#form_cambiar_password').submit(verificar_datos);
	});
//	agregar_eventos($('#form_cambiar_password'));
	function verificar_datos(event) {
		var password = $('#password').val();
		var nuevo_password = $('#nuevo_password').val();
		var repetir_nuevo_password = $('#repetir_nuevo_password').val();
		if (nuevo_password == '' || repetir_nuevo_password == '' || password == '') {
			if (password == '') {
				$('#verificar_actual').addClass('log-status wrong-entry');
				$('.alert_actual').fadeIn(500);
				setTimeout("$('.alert_actual').fadeOut(1500);", 3000);
				setTimeout("$('#verificar_actual').removeClass('log-status wrong-entry')", 3500);
			}
			if (nuevo_password == '') {
				$('#verificar').addClass('log-status wrong-entry');
				$('.alert').fadeIn(500);
				setTimeout("$('.alert').fadeOut(1500);", 3000);
				setTimeout("$('#verificar').removeClass('log-status wrong-entry')", 3500);
			}
			if (repetir_nuevo_password == '') {
				$('#verificar_pass').addClass('log-status wrong-entry');
				$('.alert_pass').fadeIn(500);
				setTimeout("$('.alert_pass').fadeOut(1500);", 3000);
				setTimeout("$('#verificar_pass').removeClass('log-status wrong-entry')", 3500);
			}
			event.preventDefault();
			return false;
		}
		if (repetir_nuevo_password != nuevo_password) {
			$('#verificar').addClass('log-status wrong-entry');
			$('#verificar_pass').addClass('log-status wrong-entry');
			$('.alert_igual').fadeIn(500);
			setTimeout("$('.alert_igual').fadeOut(1500);", 3000);
			setTimeout("$('#verificar').removeClass('log-status wrong-entry')", 3500);
			setTimeout("$('#verificar_pass').removeClass('log-status wrong-entry')", 3500);
			event.preventDefault();
			return false;
		}
	}
</script>