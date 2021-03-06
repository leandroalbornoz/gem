<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo empty($title) ? TITLE : $title; ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="description" content="">
		<link rel="apple-touch-icon" href="apple-touch-icon.png">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="plugins/bootstrap-3.3.6/css/bootstrap.min.css">
		<!-- Font Awesome Icons -->
		<link rel="stylesheet" href="plugins/font-awesome-4.7/css/font-awesome.min.css" />
		<!-- Theme style -->
		<link rel="stylesheet" href="css/AdminLTE.min.css" />
		<!-- App style -->
		<link rel="stylesheet" href="css/skins/skin-zetta.css" />
		<!-- iCheck -->
		<link rel="stylesheet" href="plugins/iCheck/square/blue.css" />

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
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
			.btn {
				width: 100%;
				font-size: 16px;
				height: 40px;
				border-radius: 4px;
			}
			.btn.btn-flat {
				border-radius: 4px;
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
			.form-group {
				margin-bottom: 20px;
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
			.alert_mail{
				display:none;
				padding: 0px;
				margin-bottom: -17px;
				font-size:12px;
				color:#f00;
				text-align: center;
			}
			.alert_term_cond{
				display:none;
				padding: 0px;
				margin-bottom: -17px;
				font-size:12px;
				color:#f00;
				text-align: center;
			}
		</style>
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="img/generales/logo-login.png" alt="<?php echo TITLE; ?>" />
			</div>
			<div class="form">
				<p class="login-box-msg"><?php echo $aviso; ?></p>
				<?php if (!empty($error)) : ?>
					<p class="login-box-msg error">
						<?php echo $error; ?>
					</p>
				<?php endif; ?>
				<?php if (!empty($message)) : ?>
					<p class="login-box-msg message">
						<?php echo $message; ?>
					</p>
				<?php endif; ?>
				<?php echo form_open(current_url(), array('id' => 'form_login')); ?>
				<div class="form-group has-feedback">
					<p><strong><u>Datos personales del alumno: </u></strong></p>
					<p>Apellido y nombre: <?php echo $datos->apellido; ?>, <?php echo $datos->nombre; ?></p>
					<p>Documento: <?php echo $datos->documento; ?></p>
					<?php if ((!empty($datos->padre_cuil))): ?>
						<p>Cuil: <?php echo $datos->padre_cuil; ?></p>
					<?php endif; ?>
					<?php if ((!empty($usuario))): ?>
						<p>Usuario: <?php echo $usuario->usuario; ?></p>
					<?php endif; ?>
				</div>
				<div class="form-group has-feedback">
					<p><strong><u>Datos de la escuela: </u></strong></p>
					<p><?php echo $datos->nombre_escuela; ?></p>
				</div>
				<?php if (!isset($usuario_creado)): ?>
					<?php if (isset($cuil_alumno)): ?> 
						<div class="form-group has-feedback" id="verificar">
							<?php echo form_input($cuil_alumno); ?>
							<span class="fa fa-user form-control-feedback"></span>
							<div class="alert">El campo CUIL es obligatorio.</div>
						</div>
					<?php endif; ?>
					<?php if (isset($mail_alumno)): ?> 
						<div class="form-group has-feedback" id="verificar_mail">
							<?php echo form_input($mail_alumno); ?>
							<span class="fa fa-at form-control-feedback"></span>
							<div class="alert_mail">El campo Mail es obligatorio.</div>
						</div>
					<?php endif; ?>
					<div class="row">
						<div class="col-xs-12">
							<?php if (isset($usuario)): ?>
								<a class="btn btn-success btn-block btn-flat" href="portal/auth/agregar_rol/<?php echo $usuario->usuario_id; ?>">Asignar rol y continuar</a>
							<?php else: ?>
								<?php echo form_hidden('dni_alumno', $dni_alumno); ?>
								<?php echo form_hidden('clave_division', $clave_division); ?>
								<?php echo form_hidden('num_escuela', $num_escuela); ?>
								<div class="checkbox" style="margin-top: 4px; font-size: 12px; margin-bottom: 25px;">
									<label style="padding-left: 0px;">
										<input type="checkbox" id="term_cond"> 
									</label><a href="#terminos_condiciones" data-toggle="modal"> He leído y acepto los <strong>términos y condiciones</strong> de uso.</a>
									<div class="alert_term_cond">Debe aceptar los <strong>términos y condiciones</strong></div>
								</div>
								<?php echo form_submit('submit', $btn_text, 'class="btn btn-success btn-block btn-flat"'); ?>
								<label style="font-size: 13px; text-align: right; width: 100%; margin-top: 20px;">
									<a class="text-center need-help" href="portal/auth/verificar_usuario_alumno/">Volver</a>
								</label>
							<?php endif; ?>
						</div>
					</div>
					<?php echo form_close(); ?>
				<?php else: ?>
					<a class="btn btn-success btn-block btn-flat" href="portal/auth/login">Continuar</a>
				<?php endif; ?>
			</div>
		</div>
			<div class="modal" id="terminos_condiciones" tabindex="-1" role="dialog" aria-labelledby="Modal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h3 class="modal-title" id="myModalLabel">Términos y condiciones</h3>
					</div>
					<div class="modal-body">
						<h4><strong>Capacidad para aceptar estos Términos y Condiciones</strong></h4>
						<p>La dirección de correo electrónico que proporciona la persona (Docente/No Docente, Alumno, Padre, Tutor) para los fines de ser notificado, no es un dato que la D.G.E. recopila por mandato legal, sino que es aportado voluntariamente por éste para el trámite solicitado.</p>
						<p>Las notificaciones que se efectúen a través del correo electrónico, tienen igual validez que las que se realicen por los otros medios legales y le son oponibles a la persona en los casos y oportunidades establecidas en la ley.</p>
						<p>Cualquier circunstancia ajena a la D.G.E. por la que la persona no reciba el correo electrónico, no anula la notificación. En efecto, es carga del titular la vigencia y correcta operación de su correo electrónico, y la DGE queda eximida de responsabilidad en casos tales como que éste haya caducado, presente fallas o no tenga suficiente espacio de almacenamiento para recibir los documentos.</p>
					</div>
					<div class="modal-footer">
						<button  class="btn btn-default pull-right" type="button" id="btn-submit" data-dismiss="modal" style="width: 12%; font-size: 16px;"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
					</div>
				</div>
			</div>
		</div>

		<!-- jQuery 2.1.4 -->
		<script src="js/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="plugins/bootstrap-3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- iCheck -->
		<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
		<script src="plugins/inputmask/jquery.inputmask.bundle.js" type="text/javascript"></script>
		<script>
			$(function() {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
			$(document).ready(function() {
				$("#cuil_alumno").inputmask("99-99999999-9", {placeholder: "_"});
			});
			function validar(event2) {
				var terminos_condiciones = document.getElementById('term_cond').checked;
				if(terminos_condiciones == false){
					if(terminos_condiciones == false){
						$('.alert_term_cond').fadeIn(500);
						setTimeout("$('.alert_term_cond').fadeOut(1500);", 3000);
					}
					event2.preventDefault();
					return false;
				}
			}
			function verificar_datos(event) {
				var cuil = $('#cuil_alumno').val();
				var mail = $('#mail_alumno').val();
				if (cuil == '' || mail == '') {
					if (cuil == '') {
						$('#verificar').addClass('log-status wrong-entry');
						$('.alert').fadeIn(500);
						setTimeout("$('.alert').fadeOut(1500);", 3000);
						setTimeout("$('#verificar').removeClass('log-status wrong-entry')", 3500);
					}
					if (mail == '') {
						$('#verificar_mail').addClass('log-status wrong-entry');
						$('.alert_mail').fadeIn(500);
						setTimeout("$('.alert_mail').fadeOut(1500);", 3000);
						setTimeout("$('#verificar_mail').removeClass('log-status wrong-entry')", 3500);
					}
					event.preventDefault();
					return false;
				}
			}
			$(document).ready(function() {
				$('#form_login').submit(verificar_datos);
				$('#form_login').submit(validar);
			});
		</script>
	</body>
</html>
